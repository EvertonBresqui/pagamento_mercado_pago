<?php

namespace App\Models\Payments\MercadoPago\Payment;

//use Illuminate\Support\Facades\Auth;
use App\Models\Payments\Base\PaymentAbstract;

class Billet extends PaymentAbstract
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
            $payment->payment_method_id = 'bolbradesco';
            $payment->transaction_amount = $this->_request->transaction_amount;
            $nameArray = explode(' ', $this->_request->payer['name']);
            $payment->payer = array(
                'type' => 'customer',
                'email' => $this->_request->payer['email'],
                "first_name" => $nameArray[0],
                "last_name" => $nameArray[count($nameArray) - 1],
                "identification" => array(
                    "type" => $this->_request->payer['identification']['type'],
                    "number" => $this->_request->payer['identification']['number']
                )
            );

            $result = $payment->save();
            if ($result == true) {
                $res = $payment->toArray();
                if (isset($res['transaction_details']->external_resource_url)) {
                    $response['status'] = 200;
                    $response['data']['params'] = array(
                        'redirect' => 'payment-success-billet',
                        'billet_url' => base64_encode($res['transaction_details']->external_resource_url)
                    );
                }
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
        if (!isset($this->_request->payer['name']) || trim($this->_request->payer['name']) == '')
            $errors[] = 'Informe o nome';
        if (!isset($this->_request->payer['identification']['type']) || ($this->_request->payer['identification']['type'] != 'CPF' && $this->_request->payer['identification']['type'] != 'CNPJ'))
            $errors[] = 'Informe o tipo do documento';
        if (!isset($this->_request->payer['identification']['number']))
            $errors[] = 'Informe o número do documento';

        return $errors;
    }
}
