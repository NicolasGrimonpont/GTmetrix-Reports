{{-- Extends layout --}}
@extends('frontend.layouts.template')

@section('title', 'Homepage')

    {{-- Content --}}
@section('content')

    {{-- Main Content --}}
    <main class="main-content">

        <section class="section">
            <div class="container">

                <div class="row gap-y">

                    @if ($companies)

                        @foreach ($companies as $company)

                            <div class="col-md-4">
                                <div class="card border hover-shadow-4">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $company->name }}</h5>
                                        <p>{{ $company->description }}</p>
                                        <a class="fs-12 fw-600" href="{{ route('report', $company->id) }}">Show reports <i
                                                class="fa fa-angle-right pl-1"></i></a>
                                    </div>
                                </div>
                            </div>

                        @endforeach

                    @endif

                </div>

            </div>
        </section>

    </main>
    {{-- ./Main Content --}}

@endsection
{{-- ./Content --}}
