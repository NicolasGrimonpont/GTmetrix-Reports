{{-- Extends layout --}}
@extends('frontend.layouts.template')

@section('title', 'Upload')

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
                                    <th>Screenshot</th>
                                    <th>Site</th>
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
                                        <td><img src="{{ $domain->screenshot }}"></td>
                                        <td>
                                            <a href="{{ url('monitoring', $domain->id) }}">{{ $domain->site }}</a>
                                            <a href="{{ $domain->site }}" target="_blank"
                                                class="ml-3 text-muted vertical-align">
                                                <i class="fa fa-external-link" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                        <td data-toggle="tooltip" data-placement="right" title="{{ $domain->error }}">
                                            {{ $domain->state }}</td>
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
                                            <a href="{{ $domain->report_url }}" target="_blank"
                                                class="text-muted vertical-align">
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

    {{-- Notification --}}
    {{-- <div id="popup-slide-up" class="popup col-6 col-md-4" data-animation="slide-up" data-autoshow="1000"
        data-position="bottom-right">
        <button type="button" class="close" data-dismiss="popup" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <div class="media">
            <img class="avatar mr-5" src="{{ asset('storage/avatar/1.jpg') }}" alt="avatar">
            <div class="media-body">
                <h5>Welcome {{ Auth::user()->name }} !</h5>
                <p>We'd like to welcome you to our website and hope you have a great time using our services.</p>
                <p>First of all you should be sure you confirm your email in order to use all our features. If this has not
                    been done, please check your mailbox and click the link provided in the mail we sent you.</p>
                <p>If you are ready to start, click on the welcome menu on the top of the page and beggin
                    creating or joining a company to share your datas with all your team.</p>
                <p class="mb-0">Finally and if you are the owner of the company, think to link this account to GTmetrix using your API
                    key. You don't know how to do that ? <a href="https://gtmetrix.com/api/" target="_blank">Click here</a> !</p>
            </div>
        </div>
    </div> --}}
    {{-- ./Notification --}}

@endsection
{{-- ./Content --}}

{{-- Scripts --}}
@section('scripts')
    <script>
        $('#upload-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: location.href,
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    $('.alert-danger').hide();
                    $('.modal-body .close').click();
                    $('input[type="file"]').val('');
                },
                error: function(response) {
                    var error = response.responseJSON.errors;
                    if (error.file) {
                        $('.alert-danger').text(error.file).show();
                    }
                }
            });
        });

    </script>
@endsection
{{-- ./Scripts --}}
