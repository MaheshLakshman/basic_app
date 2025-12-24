@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Create Permission</h1>
    <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary">Back to List</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        @include('permissions.form')
    </div>
</div>
@endsection
