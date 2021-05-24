@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="users-online">
                <button type="button" class="btn btn-primary">
                    Users: <span class="badge badge-light" id="userOnline"></span>
                </button>
            </div>
            <div class="online-users">
                <div class="d-flex flex-column mb-3 available-users">
                    @foreach ($data['listOfUsers'] as $user)
                        <div class="p-2"><a href="/users/{{ $user->id }}">{{ $user->name }}</a></div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="users-online">
                <button type="button" class="btn btn-primary">
                    Your friends
                </button>
            </div>
            <div class="user-rooms">
                <div class="d-flex flex-column mb-3 available-rooms">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
