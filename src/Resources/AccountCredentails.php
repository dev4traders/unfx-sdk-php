<?php

namespace D4T\UnFxSdk\Resources;

class AccountCredentails extends ApiResource
{
    public string $Login;
    public string $FirstName;
    public string $LastName;
    public string $Email;
    public string $Phone;
    public int $Leverage;
    public string $Group;
    public string $MainPass;
    public string $InvestorPass;
    public float $Balance;
}
