{{-- Extends layout --}}
@extends('frontend.layouts.template')

@section('title', 'Upload')

    {{-- Content --}}
@section('content')

    {{-- Header --}}
    <header class="header pb-0">
        <div class="container">
            <h1 class="display-4">Company</h1>
            <p class="lead-2">Configuration of the company</p>
        </div>
    </header>
    {{-- /.header --}}


    {{-- Main Content --}}
    <main class="main-content">

        <section class="section">
            <div class="container">

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

                                    @foreach ($company->gt_config['locations'] as $id)
                                        <option value="{{ $id }}">{{ $id }}</option>
                                    @endforeach

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
