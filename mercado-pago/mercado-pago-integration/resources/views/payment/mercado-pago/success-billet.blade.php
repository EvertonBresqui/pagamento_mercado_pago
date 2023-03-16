@extends('layouts.app')

@section('content')
<h3>Pagamento Realizado com sucesso!</h3>

<a href="{{$billet_url}}" target="_blank">Link do boleto</a>
@stop