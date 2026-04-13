@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-5">
                <h3 class="text-center mb-4 fw-bold text-white">
                    <i class="fa-solid fa-boxes-stacked text-primary"></i> LOGIN
                </h3>
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-light">Email address</label>
                        <input type="email" name="email" class="form-control form-control-lg bg-dark text-white border-secondary" value="{{ old('email') }}" required autofocus>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-light">Password</label>
                        <input type="password" name="password" class="form-control form-control-lg bg-dark text-white border-secondary" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 btn-lg shadow-sm">Enter</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
