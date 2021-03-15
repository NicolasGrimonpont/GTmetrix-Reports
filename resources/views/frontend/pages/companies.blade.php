{{-- Extends layout --}}
@extends('frontend.layouts.template')

@section('title', 'Companies')

    {{-- Content --}}
@section('content')

    {{-- Header --}}
    <header class="header pb-0">
        <div class="container">
            <h1 class="display-4">Companies</h1>
            <p>Companies can be used to group several URLs from different or from the same websites.<br>
                You will be able to see a report of all the sites you have added to the same company.</p>
            <p><a href="{{ route('company.create') }}">Add a new company</a></p>
        </div>
    </header>
    {{-- /.header --}}


    {{-- Main Content --}}
    <main class="main-content">

        <section class="section pt-6">
            <div class="container">

                <div class="row">
                    <div class="col-lg-12 mx-auto">

                        <table class="table table-striped" data-provide="datatables">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Companies</th>
                                    <th>API keys</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($companies)
                                    @foreach ($companies as $company)
                                        <tr>
                                            <td scope="row">{{ $company->id }}</td>
                                            <td>{{ $company->name }}</td>
                                            <td>
                                                @if ($company->gt_email && $company->gt_api)
                                                    <span class="badge badge-dot badge-success"></span>
                                                @else
                                                    <span class="badge badge-dot badge-danger"></span>
                                                @endif
                                            </td>
                                            <td class="w-10 text-center">
                                                <a href="{{ route('websites.edit', $company->id) }}" class="mr-3"
                                                    data-toggle="tooltip" data-placement="top" title="Websites">
                                                    <i class="fa fa-sliders text-muted"></i>
                                                </a>
                                                <a href="{{ route('company.edit', $company->id) }}" class="mr-3"
                                                    data-toggle="tooltip" data-placement="top" title="Edit">
                                                    <i class="fa fa-pencil text-muted"></i>
                                                </a>
                                                <a href="{{ route('company.delete', $company->id) }}"
                                                    data-toggle="tooltip" data-placement="top"
                                                    title="Delete company and all datas related !!">
                                                    <i class="fa fa-remove text-muted"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
        </section>

    </main>
    {{-- ./Main Content --}}


@endsection
{{-- ./Content --}}
