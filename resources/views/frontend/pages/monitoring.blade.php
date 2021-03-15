{{-- Extends layout --}}
@extends('frontend.layouts.template')

@section('title', 'Monitoring')

    {{-- Content --}}
@section('content')

    {{-- Main Content --}}
    <main class="main-content">

        <section class="section">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-12 mx-auto">

                        <table class="table table-striped" data-provide="datatables-full">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Site</th>
                                    <th class="d-none"></th>
                                    <th>state</th>
                                    <th>Pagespeed</th>
                                    <th>Yslow</th>
                                    <th>HTML bytes</th>
                                    <th>HTML load time</th>
                                    <th>Page bytes</th>
                                    <th>Page load time</th>
                                    <th>Page elements</th>
                                    <th>Redirection duration</th>
                                    <th>Connect duration</th>
                                    <th>Backend duration</th>
                                    <th>First paint time</th>
                                    <th>DOM interactive time</th>
                                    <th>Dom content loaded time</th>
                                    <th>Dom content loaded duration</th>
                                    <th>Onload time</th>
                                    <th>Onload duration</th>
                                    <th>Fully loaded time</th>
                                    <th>Rum speed index</th>
                                    <th>GT report</th>
                                    <th>Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($domains as $domain)
                                    <tr>
                                        <th scope="row">{{ $loop->index }}</th>
                                        <td>
                                            {{ $domain->site }}
                                            <a href="{{ $domain->site }}" target="_blank" class="ml-3 text-muted vertical-align">
                                                <i class="fa fa-external-link" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                        <td class="d-none"></td>
                                        <td data-toggle="tooltip" data-placement="right" title="{{ $domain->error }}">{{ $domain->state }}</td>
                                        <td>{{ $domain->pagespeed_score . ' / 100' }}</td>
                                        <td>{{ $domain->yslow_score . ' / 100' }}</td>
                                        <td>{{ $domain->html_bytes }}</td>
                                        <td>{{ $domain->html_load_time / 1000 }} s</td>
                                        <td>{{ $domain->page_bytes }}</td>
                                        <td>{{ $domain->page_load_time / 1000 }} s</td>
                                        <td>{{ $domain->page_elements }}</td>
                                        <td>{{ $domain->redirect_duration / 1000 }} s</td>
                                        <td>{{ $domain->connect_duration / 1000 }} s</td>
                                        <td>{{ $domain->backend_duration / 1000 }} s</td>
                                        <td>{{ $domain->first_paint_time / 1000 }} s</td>
                                        <td>{{ $domain->dom_interactive_time / 1000 }} s</td>
                                        <td>{{ $domain->dom_content_loaded_time / 1000 }} s</td>
                                        <td>{{ $domain->dom_content_loaded_duration / 1000 }} s</td>
                                        <td>{{ $domain->onload_time / 1000 }} s</td>
                                        <td>{{ $domain->onload_duration / 1000 }} s</td>
                                        <td>{{ $domain->fully_loaded_time / 1000 }} s</td>
                                        <td>{{ $domain->rum_speed_index }} s</td>
                                        <td>
                                            <a href="{{ $domain->report_url }}" target="_blank" class="text-muted vertical-align">
                                                <i class="fa fa-external-link" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                        <td>{{ date('M j, Y', strtotime($domain->updated_at)) }}</td>
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
