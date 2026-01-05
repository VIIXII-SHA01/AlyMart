 $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#loginForm').on('submit', function (e) {
            e.preventDefault();

            const submitBtn = $('.btn-login');
            const originalText = submitBtn.html();

            submitBtn
                .html('<i class="fas fa-spinner fa-spin me-2"></i>Signing in...')
                .prop('disabled', true);

            $.ajax({
                url: '/login',
                type: 'POST',
                dataType: 'json',
                data: {
                    email: $('#email').val(),
                    password: $('#password').val(),
                    remember: $('#rememberMe').is(':checked'),
                    _token: $('input[name="_token"]').val()
                }
            })
            .done(function (response) {
                alert(response.message);

                window.location.href = '/admin-dashboard';
            })
            .fail(function (xhr) {
                let message = 'Invalid email or password';

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    // Validation error
                    message = Object.values(xhr.responseJSON.errors)[0][0];
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                alert(message);
            })
            .always(function () {
                // 🔥 THIS GUARANTEES SPINNER STOPS
                submitBtn.html(originalText).prop('disabled', false);
            });
        });