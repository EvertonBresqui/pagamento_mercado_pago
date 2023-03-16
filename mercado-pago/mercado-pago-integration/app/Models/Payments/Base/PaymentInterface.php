<?php

namespace App\Models\Payments\Base;

interface PaymentInterface
{

    public function initClient();

    public function create();

    public function get();
}
