<?php

namespace App\Models\Payments\Base;

use App\Models\Payments\Base\PaymentInterface;

abstract class PaymentAbstract implements PaymentInterface
{

    protected $_request;

    public function __construct($request){
        $this->_request = $request;
    }

}
