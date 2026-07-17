@extends('layouts.top')
@section('title', 'Lamaran Saya')
@section('content')
  @php $status = auth()->user()->status_lamaran ?? 'menunggu'; @endphp
  <h1 style="margin:0 0 12px;font-size:28px">Status Lamaran</h1>
  <div class="card">
    Status saat ini: <strong>{{ strtoupper(str_replace('_',' ', $status)) }}</strong>
  </div>
@endsection

