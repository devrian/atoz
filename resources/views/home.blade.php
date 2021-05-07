@extends('layouts.app')

@section('title', 'Home')

@section('content')

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">

            @include('components.alert-info')

            @include('components.alert-error')

            <h3 class="text-center">
                Welcome To Atoz<br/>
                Happy Fun {{ Auth::user()->name }}
            </h3>

        </div>
    </div>
</div>

@endsection
