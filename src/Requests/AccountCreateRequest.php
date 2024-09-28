<?php

namespace D4T\UnFxSdk\Requests;

class AccountCreateRequest {

    public function __construct(
        public string $FirstName,
        public string $LastName,
        public string $Email,
        public string $Phone,
        public int $Leverage,
        public string $Group,
        public float $Balance,
        public ?string $MainPass = null,
        public ?string $InvestorPass = null,
    ) {

    }

}
