@extends('errors::layout')

@section('title', 'Halaman Tidak Ditemukan')
@section('code', '404')
@section('icon')
    <svg class="w-20 h-20 mx-auto text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
@endsection
@section('message', 'Maaf, halaman yang Anda cari tidak ditemukan atau telah dipindahkan.')
