@extends('shared.layout')

@section('title', 'PDF to TIFF Converter')

@push('styles')
<style>

</style>
@endpush

@section('content')
@include('shared.header2')

<div id="pdf-viewer">
  <canvas id="pdf-canvas"></canvas>
</div>

@include('shared.footer2')
@endsection


@push('scripts')
<script>

</script>
@endpush