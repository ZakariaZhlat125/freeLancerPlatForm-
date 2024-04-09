
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ __('static.reset_password_desc1') }}
                    </div>
                    <div class="card-body">
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                        {{ __('static.reset_password_desc2') }}
                            </div>
                        @endif
                        <a href="http://127.0.0.1:8000/reset-password/{{ $token }}">
                        {{ __('static.reset_password_desc3') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

