@extends('form::layouts.input')

@section('input')
    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
@endsection