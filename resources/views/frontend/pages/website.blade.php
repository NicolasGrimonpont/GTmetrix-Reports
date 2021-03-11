{{-- Extends layout --}}
@extends('frontend.layouts.template')

@section('title', 'Upload')

    {{-- Content --}}
@section('content')

    {{-- Header --}}
    <header class="header pb-0">
        <div class="container">
            <h1 class="display-4">Website</h1>
            <p class="lead-2">Configuration of the website</p>
        </div>
    </header>
    {{-- /.header --}}


    {{-- Main Content --}}
    <main class="main-content">

        <section class="section pt-6">
            <div class="container">

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

                        <div class="col-3">
                            <h5>Website</h5>
                        </div>

                        <div class="col-7 mr-auto">

                            <div class="form-group">
                                <input type="text" class="form-control @error('site') is-invalid @enderror"
                                    placeholder="Website URL" name="site"
                                    value="{{ old('site', isset($website->site) ? $website->site : '') }}">
                                @error('site')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control @error('kind') is-invalid @enderror"
                                    placeholder="Type (optional)" name="kind"
                                    value="{{ old('kind', isset($website->kind) ? $website->kind : '') }}">
                                @error('kind')
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
