@extends('errors::layout')

@section('title', 'Pembayaran Diperlukan')
@section('code', '402')
@section('icon')
    <svg class="w-20 h-20 mx-auto text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
    </svg>
@endsection
@section('message', 'Halaman ini memerlukan pembayaran untuk dapat diakses.')
