<?php

namespace App\Models\Payments\MercadoPago\Payment;

use Illuminate\Support\Facades\Auth;
use App\Models\Payments\Base\PaymentAbstract;

class CreditCard extends PaymentAbstract
{
    public function __construct($request)
    {
        parent::__construct($request);
    }

    public function initClient()
    {
        \MercadoPago\SDK::setAccessToken(env('MERCADO_PAGO_ACCESS_TOKEN'));
    }

    public function create()
    {
        $response = array(
            'status' => 500,
            'data' => array()
        );

        $response['data']['errors'] = $this->_validate();
        if (count($response['data']['errors']) == 0) {
            $payment = new \MercadoPago\Payment();
            $payment->payer = array(
                'type' => 'customer',
                'email' => $this->_request->payer['email']
            );
            $payment->token = $this->_request->token;
            $payment->installments = $this->_request->installments;
            $payment->transaction_amount = $this->_request->transaction_amount;

            $result = $payment->save();

            if ($result == true) {
                $response['status'] = 200;
                $response['data']['params'] = array(
                    'redirect' => 'payment-success-creditcard'
                );
            }
        }

        return $response;
    }

    public function get()
    {
    }

    protected function _validate()
    {
        $errors = array();
        if (!isset($this->_request->payer['email']) || !filter_var($this->_request->payer['email'], FILTER_VALIDATE_EMAIL))
            $errors[] = 'Email inválido';
        if (!isset($this->_request->token) || strlen(trim($this->_request->token)) == 0)
            $errors[] = 'Informe o token do cartão de crédito';
        if (!isset($this->_request->installments) || !is_numeric($this->_request->installments) || $this->_request->installments < 0)
            $errors[] = 'Informe a quantidade de parcelas do pagamento';
        if (!isset($this->_request->transaction_amount) || !is_numeric($this->_request->installments) || $this->_request->installments < 0)
            $errors[] = 'Informe o valor total do pagamento';

        return $errors;
    }
}
