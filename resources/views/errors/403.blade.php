@extends('errors::layout')

@section('title', 'Akses Ditolak')
@section('code', '403')
@section('icon')
    <svg class="w-20 h-20 mx-auto text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m0 0v2m0-2h2m-2 0H10m9.364-7.364A9 9 0 1112 3a9 9 0 017.364 4.636z"/>
    </svg>
@endsection
@section('message', 'Anda tidak memiliki izin untuk mengakses halaman ini. Silakan hubungi administrator.')
