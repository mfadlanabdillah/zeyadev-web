@extends('errors::layout')

@section('title', 'Sesi Berakhir')
@section('code', '419')
@section('icon')
    <svg class="w-20 h-20 mx-auto text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
@endsection
@section('message', 'Sesi Anda telah berakhir. Silakan muat ulang halaman dan login kembali.')
