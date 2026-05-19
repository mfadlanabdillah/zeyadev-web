@extends('errors::layout')

@section('title', 'Kesalahan Server')
@section('code', '500')
@section('icon')
    <svg class="w-20 h-20 mx-auto text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
@endsection
@section('message', 'Terjadi kesalahan pada server. Silakan coba beberapa saat lagi.')
