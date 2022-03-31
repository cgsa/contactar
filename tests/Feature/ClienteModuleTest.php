<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ClienteModuleTest extends TestCase
{

    use RefreshDatabase, InteractsWithDatabase;

    /**
     * @method createUser
     */
    private function createUser()
    {
        $user = User::create([
            'first_name' => 'Name',
            'last_name' => 'Lastname',
            'email' => 'test@gmail.com',
            'password' => bcrypt('test000')
        ]);

        $user->cliente()->create([
            'nombrefantasia' => $user->first_name." ".$user->last_name,
            'mail' => $user->email,
            'idestado'=>1
        ]);

        return $user;
    }


    /**
     * @test
     */
    function the_user_can_create_a_client_from_application()
    {

        $user = $this->createUser();

        $response = $this->actingAs($user)->post('oauth/clients',[
            'name'      =>'Test',
            'redirect'  =>'http://localhost',
        ]);



        $this->assertDatabaseHas('oauth_clients',[
            'name'      =>'Test',
            'redirect'  =>'http://localhost',
        ]);


        $response->assertStatus(201);
    }


    /**
     * @test
     */
    function the_client_can_get_a_access_token()
    {

        $this->artisan('migrate:fresh --seed');
        $user = $this->createUser();

        $login = $this->actingAs($user)->post('oauth/clients',[
            'name'=>'Test',
            'redirect'=>'http://localhost'
        ]);

        $response = $this->post('oauth/token',[
            'grant_type' => 'client_credentials',
            'client_id' => $login->getData()->id,
            'client_secret' => $login->getData()->secret,
            'scope' => ''
        ]);

        $data = json_decode($response->getContent(),true);

        $response->assertStatus(200);
        $this->assertArrayHasKey('access_token',$data);
    }


    /**
     * @test
     */
    function it_can_validate_a_phone_number()
    {
        
        $this->artisan('migrate:fresh --seed');

        $user = $this->createUser();

        DB::select('ALTER DATABASE db_contactar_test CHARACTER SET utf8 COLLATE utf8_general_ci');
        DB::select('ALTER TABLE db_contactar_test.argentina CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci');


        $login = $this->actingAs($user)->post('oauth/clients',[
            'name'=>'Test',
            'redirect'=>'http://localhost'
        ]);

        $client = $this->post('oauth/token',[
            'grant_type' => 'client_credentials',
            'client_id' => $login->getData()->id,
            'client_secret' => $login->getData()->secret,
            'scope' => ''
        ]);

        $token = json_decode($client->getContent());     
        
        $response = $this->withHeaders([
            'Authorization' =>'Bearer '.$token->access_token,
            'Content-Type'  =>'multipart/form-data'
        ])->post('api/v1/validate',[
            'cod-pai'=>'AR',
            'telefono'=>'023024824'
        ]);


        $response->assertStatus(200);
    }


    /**
     * @test
     */
    function the_number_phone_must_be_lenght_7_character_or_more()
    {
        
        $this->artisan('migrate:fresh --seed');

        $user = $this->createUser();

        DB::select('ALTER DATABASE db_contactar_test CHARACTER SET utf8 COLLATE utf8_general_ci');
        DB::select('ALTER TABLE db_contactar_test.argentina CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci');


        $login = $this->actingAs($user)->post('oauth/clients',[
            'name'=>'Test',
            'redirect'=>'http://localhost'
        ]);

        $client = $this->post('oauth/token',[
            'grant_type' => 'client_credentials',
            'client_id' => $login->getData()->id,
            'client_secret' => $login->getData()->secret,
            'scope' => ''
        ]);

        $token = json_decode($client->getContent());     
        
        $response = $this->withHeaders([
            'Authorization' =>'Bearer '.$token->access_token,
            'Content-Type'  =>'multipart/form-data'
        ])->post('api/v1/validate',[
            'cod-pai'=>'AR',
            'telefono'=>'0234'
        ]);

        
        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            "telefono"=>[
                "The telefono must be between 7 and 14 digits."
            ]
        ]);
    }

    /**
     * @test
     */
    function can_process_phone_file()
    {
        
        $this->artisan('migrate:fresh --seed');
        $user = $this->createUser();
        $path = dirname(__DIR__) . '/../database/data/telefono.xls';
        $file = new UploadedFile($path, 'telefono.xls', 'application/vnd.ms-excel', null, true);

        DB::select('ALTER DATABASE db_contactar_test CHARACTER SET utf8 COLLATE utf8_general_ci');
        DB::select('ALTER TABLE db_contactar_test.argentina CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci');


        $login = $this->actingAs($user)->post('oauth/clients',[
            'name'=>'Test',
            'redirect'=>'http://localhost'
        ]);

        $client = $this->post('oauth/token',[
            'grant_type' => 'client_credentials',
            'client_id' => $login->getData()->id,
            'client_secret' => $login->getData()->secret,
            'scope' => ''
        ]);

        $token = json_decode($client->getContent());     
        
        $response = $this->withHeaders([
            'Authorization' =>'Bearer '.$token->access_token,
            'Content-Type'  =>'multipart/form-data'
        ])->post('api/v1/validate-file',[
            'cod-pai'=>'AR',
            'telefonos'=>$file
        ]);  

        $response->assertStatus(201);
        
    }
}
