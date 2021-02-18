{{-- Extends layout --}}
@extends('frontend.layouts.template')

@section('title', 'Upload')

    {{-- Content --}}
@section('content')

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
                                    <th>Monitoring</th>
                                    <th>State</th>
                                    <th>Last update</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($domains as $domain)
                                    <tr>
                                        <td>
                                            <a href="{{ url('monitoring', $domain->id) }}">{{ $domain->site }}</a>
                                            <a href="{{ $domain->site }}" target="_blank"
                                                class="ml-3 text-muted vertical-align">
                                                <i class="fa fa-external-link" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <span class="switch">
                                                <input type="checkbox" class="switch-input"
                                                    {{ $domain->monitoring ? 'checked' : '' }}
                                                    data-id="{{ $domain->id }}">
                                                <label
                                                    class="switch-label">{{ $domain->monitoring ? 'On' : 'Off' }}</label>
                                            </span>
                                        </td>
                                        <td data-toggle="tooltip" data-placement="right" title="{{ $domain->error }}">
                                            {{ $domain->state }}</td>
                                        <td>{{ date('M j, Y', strtotime($domain->updated_at)) }}</td>
                                        <td>
                                            <a href="{{ url('monitoring/delete', $domain->id) }}" data-toggle="tooltip"
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

{{-- Scripts --}}
@section('scripts')
    <script>
        $(document).ready(function($) {
            $('.switch-label').click(function() {
                $.post("/settings/monitoring", {
                        id: $(this).prev().data('id'),
                        state: $(this).prev().is(":checked")
                    })
                    .done(function(msg) {
                        if (msg.data.state === "true") {
                            debugger
                            $('[data-id=' + msg.data.id + ']').prop('checked', false);
                            $('[data-id=' + msg.data.id + ']').next().text('Off');
                        } else {
                            debugger
                            $('[data-id=' + msg.data.id + ']').prop('checked', true);
                            $('[data-id=' + msg.data.id + ']').next().text('On');
                        }
                    });
            });
        });

    </script>
@endsection
