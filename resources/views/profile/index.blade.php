@extends('layouts.app')
@section('title', 'My Profile')
@section('content')
<div class="card w-50 shadow-sm border-0">
    <div class="card-body p-4">
        <h4 class="mb-4"><i class="fa-solid fa-user-circle text-primary"></i> My Profile</h4>
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required value="{{ old('name', $user->name) }}">
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required value="{{ old('email', $user->email) }}">
            </div>
            <div class="mb-4">
                <label>New Password</label>
                <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
            </div>
            
            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-save"></i> Update Profile</button>
        </form>
    </div>
</div>
@endsection
