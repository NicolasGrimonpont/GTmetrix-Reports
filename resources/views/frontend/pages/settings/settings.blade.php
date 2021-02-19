{{-- Extends layout --}}
@extends('frontend.layouts.template')

@section('title', 'Settings')

    {{-- Content --}}
@section('content')

    {{-- Main Content --}}
    <main class="main-content">

        <section class="section">
            <div class="container">
                <header class="section-header">
                    <h2>Settings</h2>
                    <hr>
                    <p class="lead">Configure your account</p>
                </header>


                <div class="row">
                    <div class="col-md-4 mx-auto">

                        <form class="input-round" method="post">

                            @csrf

                            @if (Session::has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ Session::get('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                            @endif

                            <h5 class="mt-5 mb-3">Personal account</h5>

                            <div class="form-group">
                                <input class="form-control @error('name') is-invalid @enderror')" type="text"
                                    placeholder="Username" name="name" value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control bg-light" placeholder="Email address"
                                    value="{{ $user->email }}" disabled>
                            </div>

                            <h5 class="mt-5 mb-3">GTmetrix account</h5>

                            <div class="form-group">
                                <input type="text" class="form-control @error('gt_email') is-invalid @enderror"
                                    placeholder="Email address" name="gt_email"
                                    value="{{ old('gt_email', $company->gt_email) }}">
                                @error('gt_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control @error('gt_api') is-invalid @enderror"
                                    placeholder="GTmetrix API key" name="gt_api"
                                    value="{{ old('gt_api', $company->gt_api) }}">
                                @error('gt_api')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <select class="form-control  @error('gt_location') is-invalid @enderror"
                                    placeholder="Location" name="gt_location">
                                    <option value="0"
                                        {{ old('gt_location', $company->gt_location) === '0' ? 'selected' : '' }}>
                                        Select a default location
                                    </option>
                                    <option value="1"
                                        {{ old('gt_location', $company->gt_location) === '1' ? 'selected' : '' }}>
                                        Canada
                                    </option>
                                    <option value="2"
                                        {{ old('gt_location', $company->gt_location) === '2' ? 'selected' : '' }}>
                                        US West</option>
                                    <option value="3"
                                        {{ old('gt_location', $company->gt_location) === '3' ? 'selected' : '' }}>
                                        US Est</option>
                                </select>
                                @error('gt_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <input class="btn btn-primary btn-round btn-lg btn-block" type="submit">

                        </form>

                    </div>
                </div>


            </div>
        </section>

    </main>
    {{-- ./Main Content --}}

@endsection
{{-- ./Content --}}
