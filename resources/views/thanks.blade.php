@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Nueva Orden'])
<div class="container text-center mt-5">
    <p>Tu orden ha sido registrada exitosamente.</p>
</div>

@endsection