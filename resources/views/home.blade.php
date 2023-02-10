@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (session('actor'))
                            <div class="alert alert-danger" role="alert">
                                The record you are try to edit is being actively edited by {{ session('actor') }}
                            </div>
                        @endif

                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Updated at</th>
                                    <th scope="col">Handle</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <th scope="row">{{ $loop->index + 1 }}</th>
                                        <td>
                                            <div>{{ $user->email }}
                                            </div>
                                            @if ($user->event)
                                                <span class="badge text-bg-info">Editing By</span>
                                                <small>
                                                    {{ $user->event->actor->name }}
                                                </small>
                                            @endif

                                        </td>
                                        <td>{{ $user->updated_at->diffForHumans() }}</td>
                                        <td><a href="{{ route('user.edit', $user) }}" class="btn btn-sm btn-info">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
