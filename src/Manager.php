<?php

namespace D4T\UnFxSdk;

use GuzzleHttp\Client;
use Illuminate\Support\Str;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\Facades\Http;
use D4T\UnFxSdk\Resources\AccountCredentails;
use D4T\UnFxSdk\Requests\AccountCreateRequest;

class Manager
{
    use MakesHttpRequests;

    public function __construct(
        public string $endpoint,
        public string $token,
        public string $mid,
        public ?ClientInterface $client = null
    ) {
        $this->client ??= new Client([
            'http_errors' => false,
            'base_uri' => $this->endpoint.'/mt5/api/',
            'headers' => [
                'MID' => "MID: {$this->mid}",
                'Key' => "Key: {$this->token}",
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function Token(): string
    {
        return $this->token;
    }

    public function Endpoint(): string
    {
        return $this->endpoint;
    }

    public function MID(): string
    {
        return $this->mid;
    }

    public function ping() : bool
    {
        $this->get("ping");

        return true;
    }

    public function createAccount(AccountCreateRequest $request): AccountCredentails
    {
        $request->MainPass = self::generatePassword();
        $request->InvestorPass = self::generatePassword();

        $attributes = $this->post('user/create', (array)$request);

        return new AccountCredentails($attributes, $this);
    }

    public static function generatePassword() {
        return Str::password(8);
    }

}
