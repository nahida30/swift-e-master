@extends('shared.layout')

@section('title', 'PDF Viewer')

@push('styles')
<style>

</style>
@endpush

@section('content')

<iframe src="https://docs.google.com/gview?url=https://swift-e.azurewebsites.net/getfile/1718736985-BRET Report.pdf&embedded=true" style="width:600px; height:500px;" frameborder="0"></iframe>

@endsection


@push('scripts')
<script>

</script>
@endpush