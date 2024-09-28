<?php

namespace D4T\UnFxSdk;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
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
            'base_uri' => $this->endpoint,
            'headers' => [
                'MID' => "{$this->mid}",
                'Key' => "{$this->token}",
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

    public function ping(): bool
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

    const PermittedNumbers = '0123456789';
    const PermittedChars = 'abcdefghijklmnopqrstuvwxyz';
    const PermittedUppers = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const PermittedSymbols = '*!@';

    public static function generatePassword()
    {
        return str_shuffle(
            self::generateString(self::PermittedNumbers, 2).
            self::generateString(self::PermittedChars, 2).
            self::generateString(self::PermittedUppers, 2).
            self::generateString(self::PermittedSymbols, 2)
        );
    }

    private static function generateString($input, $strength = 16)
    {

        $input_length = strlen($input);

        $random_string = '';

        for ($i = 0; $i < $strength; $i++) {

            $random_character = $input[mt_rand(0, $input_length - 1)];

            $random_string .= $random_character;
        }

        return $random_string;
    }
}
