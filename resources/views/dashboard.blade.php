@extends('layouts.main')

@section('content')
<div class="container-fluid px-4 pt-2">
    <h1 class="mt-2">User Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="row">
        <!-- User dashboard content here -->
        <p>Welcome, {{ auth()->user()->fullname }}!</p>
    </div>
</div>
@endsection
