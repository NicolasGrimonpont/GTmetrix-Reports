{{-- Extends layout --}}
@extends('frontend.layouts.template')

@section('title', 'Upload')

{{-- Content --}}
@section('content')

    {{-- Header --}}
    {{-- <header class="header text-center pb-0">
        <div class="container-fluid">
            <h1 class="display-4">Upload</h1>
            <p class="lead-2">Upload a .txt file containing a list of domains (1 by line).</p>
        </div>
    </header> --}}
    {{-- /.header --}}



    {{-- Main Content --}}
    <main class="main-content">

        <section class="section">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-12 mx-auto text-center">

                        Welcome...

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
