{{-- Extends layout --}}
@extends('frontend.layout.template')

{{-- Content --}}
@section('content')

    {{-- Main Content --}}
    <main class="main-content">


    </main>
    {{-- ./Main Content --}}

@endsection
{{-- ./Content --}}

{{-- Scripts --}}
@section('scripts')
    <script>
        $('#testimonial-form button').click(function() {
            $.ajax({
                type: "POST",
                url: location.href,
                data: $('#testimonial-form').serialize(),
                success: function(response) {
                    $('.alert-success').text(response.success).show();
                    $('#title-group, #testimonial-group, #author-group').children().removeClass(
                        'is-invalid').val('').next().text('');
                    setTimeout(() => {
                        $('.modal-body .close').click();
                    }, 2000);
                },
                error: function(response) {
                    var error = response.responseJSON.errors;
                    if (error.title) {
                        $('#title-group').children().addClass('is-invalid').next().text(error.title);
                    } else {
                        $('#title-group').children().removeClass('is-invalid').next().text('');
                    }
                    if (error.testimonial) {
                        $('#testimonial-group').children().addClass('is-invalid').next().text(error
                            .testimonial);
                    } else {
                        $('#testimonial-group').children().removeClass('is-invalid').next().text('');
                    }
                    if (error.author) {
                        $('#author-group').children().addClass('is-invalid').next().text(error.author);
                    } else {
                        $('#author-group').children().removeClass('is-invalid').next().text('');
                    }
                }
            });
        });

    </script>
@endsection
