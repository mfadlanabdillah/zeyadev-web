@extends('errors::layout')

@section('title', 'Terlalu Banyak Permintaan')
@section('code', '429')
@section('icon')
    <svg class="w-20 h-20 mx-auto text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
@endsection
@section('message', 'Terlalu banyak permintaan dalam waktu singkat. Silakan tunggu beberapa saat sebelum mencoba lagi.')
