<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PaymentRequest;

class PaymentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('payment.index');
    }

    public function successCreditcard(){
        return view('payment.mercado-pago.success-card');
    }

    public function successBillet(Request $request){
        return view('payment.mercado-pago.success-billet', ['billet_url' => base64_decode($request->billet_url)]);
    }

    public function store(PaymentRequest $request){
        $class = 'App\Models\Payments\\'. $request->gateway . '\Payment\\' . $request->method;
        
        $paymentObj = new $class($request);
        $paymentObj->initClient();
        $response = $paymentObj->create();

        return response()->json($response['data'], $response['status']);
    }
}
