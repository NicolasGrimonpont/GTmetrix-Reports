{{-- Extends layout --}}
@extends('frontend.layouts.template')

@section('title', 'Upload')

    {{-- Content --}}
@section('content')

    {{-- Header --}}
    <header class="header pb-0">
        <div class="container">
            <h1 class="display-4">Websites for {{ $company->name }}</h1>
            <p>Add new websites or different urls of the same website in this company.<br>
                This will generate a specific report for all these urls.</p>
            <p><a href="{{ route('website.add', $company->id) }}">Add a new website</a></p>
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
                                    <th>Site</th>
                                    <th>Type</th>
                                    <th>Monitoring</th>
                                    <th>State</th>
                                    <th>Last update</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($domains)
                                    @foreach ($domains as $domain)
                                        <tr>
                                            <td>
                                                <span title="{{ $domain->site }}">
                                                    {{ mb_strimwidth($domain->site, 0, 65, '...') }}
                                                </span>
                                                <a href="{{ $domain->site }}" target="_blank"
                                                    class="ml-3 text-muted vertical-align">
                                                    <i class="fa fa-external-link" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                            <td>{{ $domain->kind }}</td>
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
                                            <td class="w-10 text-center">
                                                <a href="{{ url('monitoring', $domain->id) }}" class="mr-3"
                                                    data-toggle="tooltip" data-placement="top" title="Monitoring report">
                                                    <i class="fa fa-line-chart text-muted"></i>
                                                </a>
                                                <a href="{{ route('website.edit', $domain->id) }}" class="mr-3"
                                                    data-toggle="tooltip" data-placement="top" title="Edit">
                                                    <i class="fa fa-pencil text-muted"></i>
                                                </a>
                                                <a href="{{ route('website.delete', $domain->id) }}"
                                                    data-toggle="tooltip" data-placement="top"
                                                    title="Delete website and all datas related !!">
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


    {{-- Modal --}}
    <div class="modal fade" id="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-body">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <form id="upload-form" method="POST" enctype="multipart/form-data">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <h4 class="fw-200 text-center">File Upload</h4>
                        <p class="text-center">Upload a .txt file containing a list of domains (1 by line)</p>
                        <hr class="w-10">

                        <div class="alert alert-danger" role="alert" style="display:none;"></div>

                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="file" name="file" accept=".txt">
                            <label class="custom-file-label" for="file">Choose file</label>
                        </div>

                        <button class="btn btn-lg btn-block btn-primary mt-6" type="submit">Send</button>

                    </form>

                </div>

            </div>
        </div>

    </div>
    {{-- ./Modal --}}


@endsection
{{-- ./Content --}}


{{-- Scripts --}}
@section('scripts')
    <script>
        // Upload form
        $('#upload-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: location.href,
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function(msg) {
                    $('.modal-body .close').click();
                    $('#message').text('Checking domains.. Please wait !!').show();
                },
                success: function(response) {
                    $('.alert-danger').hide();
                    $('input[type="file"]').val('');
                    if (response.error) {
                        $('#message').text(response.error).show();
                    } else {
                        $('#message').text(response).show();
                    }
                },
                error: function(response) {
                    $('#message').text('Error').show();
                    console.log(response.responseJSON.message);
                }
            });
        });

        // Monitoring form
        $(document).ready(function($) {

            window.loadSwitch = function loadSwitch() {

                $('.switch-label').click(function() {
                    $.post("/websites/update/monitoring", {
                            id: $(this).prev().data('id'),
                            state: $(this).prev().is(":checked")
                        })
                        .done(function(msg) {
                            if (msg.data.state === "true") {
                                $('[data-id=' + msg.data.id + ']').prop('checked', false);
                                $('[data-id=' + msg.data.id + ']').next().text('Off');
                            } else {
                                $('[data-id=' + msg.data.id + ']').prop('checked', true);
                                $('[data-id=' + msg.data.id + ']').next().text('On');
                            }
                        });
                });
            }

            loadSwitch();
        });

    </script>
@endsection
{{-- ./Scripts --}}
