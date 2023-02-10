@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('User Edit') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Name</label>
                                <input type="text" class="form-control" value="{{ $user->name }}">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Email address</label>
                                <input type="email" class="form-control" value="{{ $user->email }}">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Password</label>
                                <input type="password" class="form-control" id="exampleInputPassword1">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        var eventRefreshUrl = '{{ route('updateEventStatus', $user->event) }}';
        var lastActiveTime = new Date();
        var confirmationAsked = false;
        var confirmed = false;
        window.onclick = function() {
            lastActiveTime = new Date();
        };
        window.onmousemove = function() {
            lastActiveTime = new Date();
        };
        window.onkeypress = function() {
            lastActiveTime = new Date();
        };
        window.onscroll = function() {
            lastActiveTime = new Date();
        };


        function updateEventStatus() {
            if (CheckIdleTime() > 10) {
                if (!confirmationAsked) {

                    if (confirm(
                            'You are inactive for more than 30 sec. Do you want to unblock the form to let others have the access?'
                        )) {
                        confirmed = true;
                        alert('unblocked')
                        const interval_id = window.setInterval(function() {}, Number.MAX_SAFE_INTEGER);

                        // Clear any timeout/interval up to that id
                        for (let i = 1; i < interval_id; i++) {
                            window.clearInterval(i);
                        }
                        clearMyEvent()
                    }
                }
            }

            fetch(eventRefreshUrl)
                .then(response => {
                    if (response.status == 500) {
                        response.json()
                            .then(data => {
                                alert(data.error)
                            })
                    }

                })
        }

        const clearMyEvent = () => {
            fetch(eventRefreshUrl + `?clear=1`)
                .then(response => {
                    alert('you are now only on viewing mode.')

                })
        }

        function CheckIdleTime() {
            let dateNowTime = new Date().getTime();
            let lActiveTime = new Date(lastActiveTime).getTime();
            return Math.floor((dateNowTime - lActiveTime) / 1000);
        }

        setInterval(function() {
            updateEventStatus()
        }, 8000);
    </script>
@endpush
