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
                    <h1 class="display-4">Settings</h1>
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
