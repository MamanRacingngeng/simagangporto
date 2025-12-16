@extends('layouts.app')

@section('title', 'Laporan Mingguan')
@section('page-title', 'Unggah Laporan Mingguan')

@section('content')
<div class="max-w-2xl">
    <h2 class="text-xl font-semibold mb-4">Unggah Laporan Kegiatan Mingguan</h2>
    <form method="POST" action="{{ route('laporan.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="file" name="laporan" class="block w-full mb-4 border border-gray-300 rounded-lg p-2">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Unggah Sekarang
        </button>
    </form>
</div>
@endsection
