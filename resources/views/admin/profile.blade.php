@extends('layouts.layoutMaster')

@section('title', __('My Profile'))

@section('content')
<div class="container-xxl">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">{{ __('My Profile') }}</h5>
      <span class="text-body-secondary small">{{ __('Update your personal information') }}</span>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('admin.profile') }}">
        @csrf
        <div class="row g-3">
          <div class="col-12 col-md-6">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ auth()->user()->name ?? '' }}" />
          </div>
          <div class="col-12 col-md-6">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ auth()->user()->email ?? '' }}" />
          </div>
          <div class="col-12">
            <hr/>
          </div>
          <div class="col-12 col-md-6">
            <label for="current_password" class="form-label">{{ __('Current Password') }}</label>
            <input type="password" id="current_password" name="current_password" class="form-control" autocomplete="current-password" />
            <small class="text-body-secondary">{{ __('Required to change your password') }}</small>
          </div>
          <div class="col-12 col-md-6">
            <label for="password" class="form-label">{{ __('New Password') }}</label>
            <input type="password" id="password" name="password" class="form-control" />
          </div>
          <div class="col-12 col-md-6">
            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" />
          </div>
        </div>
        <div class="mt-4 d-flex gap-2">
          <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
          <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
