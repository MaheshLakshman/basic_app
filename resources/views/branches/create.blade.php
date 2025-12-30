@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Create Branch</h5>
                </div>
                <div class="card-body">
                    @include('branches.form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
