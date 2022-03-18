<?php

namespace App\Http\Controllers;

use App\Models\PagoPlan;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;
use App\Http\Procesos\planes\Handler as HandlerPlan;

class PaymentController extends Controller
{
    private $apiContext;

    private $payPalConfig;

    public function __construct()
    {
        $this->payPalConfig = Config::get('paypal');
        $payPalConfig = $this->payPalConfig['sandbox'];
        //dd($payPalConfig);
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                $payPalConfig['client_id'],
                $payPalConfig['client_secret']
            )
        );

        $this->apiContext->setConfig($this->payPalConfig);
    }

    // ...

    public function payWithPayPal(Request $request)
    {

        $user = Auth::user();

        $datos = $request->validate([
            'plan_id' => ['required','integer', 'exists:planes,id'],
            'metodo_pago' => ['required','string', 'exists:planes,id'],
        ]);

        $plan = Plan::find($datos['plan_id']);

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $amount = new Amount();
        $amount->setTotal($plan->valor);
        $amount->setCurrency('USD');

        $transaction = new Transaction();
        $transaction->setAmount($amount);
        // $transaction->setDescription('See your IQ results');

        $callbackUrl = $this->payPalConfig['frontend_contactar'] . '/paypal/status';//url('/paypal/status');

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($callbackUrl)
            ->setCancelUrl($callbackUrl);

        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions(array($transaction))
            ->setRedirectUrls($redirectUrls);

        try {
            $payment->create($this->apiContext);
            
            DB::beginTransaction();
            $pago = PagoPlan::create([
                'idpay'=> $payment->getId(),
                'token'=> $payment->getToken(),
                'state'=> $payment->getState(),
                'link'=> $payment->getApprovalLink(),
                'iduser'=> $user->id,
                'idplan'=> $datos['plan_id']
            ]);


            DB::commit();

            return response()->json([
                'status' => 201,
                'pay' => $payment->getApprovalLink(),
            ], 200);
        } catch (PayPalConnectionException $ex) {
            //echo $ex->getData();
            DB::rollBack();
            $code = is_numeric($ex->getCode()) ? $ex->getCode() : 500;
            return response()->json([
                'status' => $code,
                'message' => $ex->getData()
            ], 500);
        }
    }

    public function payPalStatus(Request $request)
    {
        $paymentId = $request->input('paymentId');
        $payerId = $request->input('PayerID');
        $token = $request->input('token');
        $pagoPlan = new PagoPlan();

        $datos = $request->validate([
            'paymentId' => ['required', 'string'],
            'PayerID' =>['required','string'],
            'token' =>['required','string']
        ]);


        try {
            DB::beginTransaction();

            if (!$paymentId || !$payerId || !$token) {
                $status = 'Lo sentimos! El pago a través de PayPal no se pudo realizar.';
                $pagoPlan->updateState('failed');
                return redirect('/paypal/failed')->with(compact('status'));
            }
    
            $payment = Payment::get($paymentId, $this->apiContext);
    
            $execution = new PaymentExecution();
            $execution->setPayerId($payerId);
    
            /** Execute the payment **/
            $result = $payment->execute($execution, $this->apiContext);
    
            if ($result->getState() === 'approved') {
                $pagoPlan->updateState($result->getState());
                $handler = HandlerPlan::init();
                $newPlan = $handler->active($pagoPlan);
                $status = 'Gracias! El pago a través de PayPal se ha ralizado correctamente.';
                return redirect('/results')->with(compact('status'));
            }
    
            $status = 'Lo sentimos! El pago a través de PayPal no se pudo realizar.';
            return redirect('/results')->with(compact('status'));
        } catch (\Throwable $th) {
            //throw $th;
        }

        
    }
}
