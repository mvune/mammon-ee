@extends('layouts.app')

@section('content')
<div id="app"></div>
@endsection

@push('scripts')
<script src="{{ asset(mix('js/main.js')) }}"></script>
@endpush
