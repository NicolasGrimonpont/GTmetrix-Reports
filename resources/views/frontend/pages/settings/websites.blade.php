{{-- Extends layout --}}
@extends('frontend.layouts.template')

@section('title', 'Upload')

{{-- Content --}}
@section('content')

    {{-- Header --}}
    <header class="header pb-0">
        <div class="container">
            <h1 class="display-4">Websites</h1>
            <p class="lead-2">Configuration of websites of the company</p>
        </div>
    </header>
    {{-- /.header --}}


    {{-- Main Content --}}
    <main class="main-content">

        <section class="section">
            <div class="container">

                <div class="row">
                    <div class="col-lg-12 mx-auto">

                        <table class="table table-striped" data-provide="datatables">
                            <thead>
                                <tr>
                                    <th>Site</th>
                                    <th>State</th>
                                    <th>Last update</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($domains as $domain)
                                    <tr>
                                        <td>
                                            {{ $domain->site }}
                                            <a href="{{ $domain->site }}" target="_blank"
                                                class="ml-3 text-muted vertical-align">
                                                <i class="fa fa-external-link" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                        <td data-toggle="tooltip" data-placement="right" title="{{ $domain->error }}">
                                            {{ $domain->state }}</td>
                                        <td>{{ date('M j, Y', strtotime($domain->updated_at)) }}</td>
                                        <td>
                                            <a href="{{ url('settings/company/delete', $domain->id) }}" data-toggle="tooltip"
                                                data-placement="top" title="Delete websites and all datas related !!">
                                                <i class="fa fa-remove text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
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
