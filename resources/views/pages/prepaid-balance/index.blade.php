@php
    $title = 'Prepaid Balance';
@endphp

@extends('layouts.app')

@section('title', $title)

@section('content')

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-6">

            @include('components.title', ['title' => $title])

            @include('components.alert-info')

            @include('components.alert-error')

            <form method="POST" action="{{ route('prepaid-balance.store') }}">

                {{ csrf_field() }}
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                <div class="form-group">
                    <input type="text"
                        required
                        class="form-control"
                        placeholder="Mobile Number"
                        name="phone_number"
                        value="{{ old('phone_number') }}"
                        onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                    >
                </div>
                <div class="form-group">
                    <select class="form-control" name="amount" required>
                        <option value="" disabled selected>Value</option>
                        <option value="10000">Rp {{ number_format(10000) }}</option>
                        <option value="50000">Rp {{ number_format(50000) }}</option>
                        <option value="100000">Rp {{ number_format(100000) }}</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-block">
                    Submit
                </button>
            </form>

        </div>
    </div>
</div>

@endsection
