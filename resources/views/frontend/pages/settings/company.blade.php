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

        <section class="section pt-6">
            <div class="container">

                @if (!isset($user->company_id))

                    <div class="row">
                        <div class="col mx-auto">

                            <div class="alert alert-warning" role="alert">
                                <h4 class="alert-heading">Welcome {{ $user->name ?? '' }} !</h4>
                                <p>You have not configured your company yet, please complete the form bellow to create your
                                    company.</p>
                            </div>

                        </div>
                    </div>

                @endif


                @if (Session::has('success'))
                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ Session::get('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="post">

                    @csrf

                    <div class="row pt-7">

                        <div class="col-3 ml-auto">
                            <h5>Your company</h5>
                        </div>

                        <div class="col-5 mr-auto">

                            <div class="form-group">
                                <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                    placeholder="Company name" name="company_name"
                                    value="{{ old('company_name', $company->name ?? '') }}">
                                @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-7">

                        </div>
                    </div>

                    <div class="row">

                        <div class="col-3 ml-auto">
                            <h5>GTmetrix account</h5>
                        </div>


                        <div class="col-5 mr-auto">

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

                                    @if (isset($company->gt_config['locations']))
                                        @foreach ($company->gt_config['locations'] as $id)
                                            <option value="{{ $id }}">{{ $id }}</option>
                                        @endforeach
                                    @endif

                                </select>
                                @error('gt_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-7">

                            <input class="btn btn-primary btn-lg btn-block" type="submit">

                        </div>

                    </div>
                </form>

            </div>
        </section>

    </main>
    {{-- ./Main Content --}}


@endsection
{{-- ./Content --}}
