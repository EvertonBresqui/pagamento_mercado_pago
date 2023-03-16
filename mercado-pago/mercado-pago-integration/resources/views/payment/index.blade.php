@extends('layouts.app')

@section('content')
<h1>Pagamento</h1>
<div class="form-group row">
  <input type="hidden" name="gateway" value="MercadoPago" />
  <div class="form-check col-2">
    <input class="form-check-input" type="radio" name="method" id="ck-method-card" value="CreditCard" checked>
    <label class="form-check-label" for="ck-method-card">
      Cartão de crédito
    </label>
  </div>
  <div class="form-check col-2">
    <input class="form-check-input" type="radio" name="method" id="ck-method-billet" value="Billet">
    <label class="form-check-label" for="ck-method-billet">
      Boleto
    </label>
  </div>
</div>
<br />
<style>
  #div-billet {
    display: none;
  }
</style>
<!-- <form id="form-checkout"> -->
<div id="payment-content">
  <div id="div-creditcard">
    <form action="{{ route('payment.store') }}" method="post" id="form-checkout">
      <input type="hidden" name="gateway" value="MercadoPago" />
      <input type="hidden" name="method"value="CreditCard" />
      @csrf
      <h3>Cartão de Crédito</h3><br />
      <div class="form-group row">
        <div class="externo col-4">
          <label for="form-checkout__cardNumber" class="col-sm-12 col-form-label col-form-label-lg">Número do Cartão</label>
          <div class="form-control" id="form-checkout__cardNumber"></div>
        </div>
        <div class="externo col-4">
          <label for="form-checkout__expirationDate" class="col-sm-12 col-form-label col-form-label-lg">Data de Expiração</label>
          <div class="form-control" id="form-checkout__expirationDate"></div>
        </div>
        <div class="externo col-4">
          <label for="form-checkout__securityCode" class="col-sm-12 col-form-label col-form-label-lg">Código de segurança</label>
          <div class="form-control" id="form-checkout__securityCode"></div>
        </div>
      </div>
      <div class="form-group row">
        <div class="col-4">
          <label for="form-checkout__cardholderName" class="col-sm-12 col-form-label col-form-label-lg">Nome no cartão</label>
          <input class="form-control" type="text" id="form-checkout__cardholderName" />
        </div>
        <div class="col-4">
          <label for="form-checkout__issuer" class="col-sm-12 col-form-label col-form-label-lg">Issuer</label>
          <select class="form-select" id="form-checkout__issuer"></select>
        </div>
        <div class="col-4">
          <label for="form-checkout__installments" class="col-sm-12 col-form-label col-form-label-lg">Parcelas</label>
          <select class="form-select" id="form-checkout__installments"></select>
        </div>
      </div>
      <div class="form-group row">
        <div class="col-3">
          <label for="form-checkout__identificationType" class="col-sm-12 col-form-label col-form-label-lg">Documento</label>
          <select class="form-select" id="form-checkout__identificationType"></select>
        </div>
        <div class="col-4">
          <label for="form-checkout__identificationNumber" class="col-sm-12 col-form-label col-form-label-lg">Número do documento</label>
          <input type="text" class="form-control" id="form-checkout__identificationNumber" />
        </div>
        <div class="col-5">
          <label for="form-checkout__cardholderEmail" class="col-sm-12 col-form-label col-form-label-lg">Email</label>
          <input type="email" class="form-control" id="form-checkout__cardholderEmail" />
        </div>
      </div>
      <progress value="0" class="progress-bar">Carregando...</progress>
      <br />
      <div class="form-group row">
        <div class="col-3">
          <button type="submit" id="form-checkout__submit" name="btn-payment-submit" class="btn btn-primary">Finalizar Pagamento</button>
        </div>
        <div class="col-9"></div>
      </div>
    </form>

  </div>
  <div id="div-billet">
    <form action="{{ route('payment.store') }}" method="post">
      <input type="hidden" name="gateway" value="MercadoPago" />
      <input type="hidden" name="method" value="Billet" />
      @csrf
      <h3>Boleto</h3><br />
      <div class="form-group row">
        <div class="col-3">
          <label for="name" class="col-sm-12 col-form-label col-form-label-lg">Nome completo</label>
          <input type="text" class="form-control" id="name" />
        </div>
        <div class="col-2">
          <label for="doc-type" class="col-sm-12 col-form-label col-form-label-lg">Documento</label>
          <select class="form-select" id="doc-type">
            <option>CPF</option>
            <option>CNPJ</option>
          </select>
        </div>
        <div class="col-3">
          <label for="doc-number" class="col-sm-12 col-form-label col-form-label-lg">Número do documento</label>
          <input type="text" class="form-control" id="doc-number" />
        </div>
        <div class="col-4">
          <label for="email" class="col-sm-12 col-form-label col-form-label-lg">Email</label>
          <input type="email" class="form-control" id="email" />
        </div>
      </div>
      <br />
      <div class="form-group row">
        <div class="col-3">
          <button type="button" id="btn-payment-submit-billet" class="btn btn-primary">Finalizar Pagamento</button>
        </div>
        <div class="col-9"></div>
      </div>
    </form>
  </div>
</div>


<script src="https://sdk.mercadopago.com/js/v2"></script>
@stop