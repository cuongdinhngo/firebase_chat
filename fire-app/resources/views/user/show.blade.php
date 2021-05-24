@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('User Profile') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <span class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</span>

                            <div class="col-md-6 col-form-label ">
                                <span>{{$user->name}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <span class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</span>

                            <div class="col-md-6 col-form-label">
                                <span>{{$user->email}}</span>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-3 offset-md-4">
                                <div class="alert alert-primary" role="alert">
                                    @if (false === empty($friend) && $friend['status'] != -1)
                                        @if ($friend['status'] == 1)
                                            <a href="{{route('rooms.enter', ['receiver_id' => request()->id])}}" class="alert-link"><i class="fas fa-comments"></i> Chat</a>
                                        @else
                                            <a href="#" class="alert-link"><i class="fas fa-comments"></i> Confirming</a>
                                        @endif
                                    @else
                                        <a href="{{route("users.connect", ["id" => $user->id])}}" class="alert-link"><i class="fas fa-user-plus"></i> Connect</a>
                                    @endif
                                    
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
