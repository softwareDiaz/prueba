@extends('layout')
@section('module')
Servicios
@stop
@section('base_url')
<base href="{{URL::to('/')}}/services"/>
@stop
@section('css-customize')
@stop
@section('content')
<section ng-app="services">
    <div ng-view>

    </div>
</section>

@section('js-customize')
	<script src="/js/app/services/app.js"></script>
    <script src="/js/app/services/controllers.js"></script>
    <script src="/js/app/services/servicesglobalServices.js"></script>
@stop

@stop