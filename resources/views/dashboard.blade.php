@extends('layouts.app')

@section('content')
<div id="app"></div>
@endsection

@push('scripts')
<script src="{{ asset(mix('js/app.js')) }}"></script>
@endpush
