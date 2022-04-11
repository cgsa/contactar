<?php

namespace App\Models;

use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\Client;
use Laravel\Passport\Token;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\DataSet;

class OAuthClient extends Client
{
    public static function findByRequest(?Request $request = null) : ?OAuthClient
    {
        $bearerToken = $request !== null ? $request->bearerToken() : RequestFacade::bearerToken();        
        $parser = new Parser(new JoseEncoder);
        $parsedJwt = $parser->parse($bearerToken);
        $claims = $parsedJwt->claims();
        $tokenId = $claims->get('jti');

        $clientId = Token::find($tokenId)->client->id;

        return (new static)->findOrFail($clientId);
    }


    public static function deleteTokenIdByRequest(?Request $request = null) : ?string
    {
        $bearerToken = $request !== null ? $request->bearerToken() : RequestFacade::bearerToken();        
        $parser = new Parser(new JoseEncoder);
        $parsedJwt = $parser->parse($bearerToken);
        $claims = $parsedJwt->claims();
        $tokenId = $claims->get('jti');
        Token::find($tokenId)->delete;

        return true;
    }
}