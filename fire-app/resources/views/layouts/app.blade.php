
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'RealtimeChat') }}</title>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="{{ asset('css/fontawesome/all.css') }}" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ route("messages.index") }}">
                    <i class="fas fa-comments"></i> {{ config('app.name', 'RealtimeChat') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        @if (Auth::user())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-users fa-lg"></i></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('users.list-notifications') }}"><i class="fas fa-bell fa-lg"></i></a>
                                <span id="notify" ></span>
                            </li>
                        @endif
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ Auth::user()->name }} <i class="fas fa-sign-out-alt"></i>
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script src="https://www.gstatic.com/firebasejs/8.6.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.6.1/firebase-messaging.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.6.1/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.6.1/firebase-database.js"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    @if ($currentUser = Auth::user())
        <script>
            $(document).ready(function() {
                getFriends();
            });

            var currentUser = {!! json_encode($currentUser, JSON_FORCE_OBJECT) !!};
            var uid;
            var userStatusDatabaseRef;

            var isOfflineForDatabase = {
                state: 'offline',
                last_changed: firebase.database.ServerValue.TIMESTAMP,
            };

            var isOnlineForDatabase = {
                state: 'online',
                last_changed: firebase.database.ServerValue.TIMESTAMP,
            };

            auth.signInWithCustomToken(currentUser.custom_token)
                .then((userCredential) => {
                    console.log('SIGN IN ...');
                    var user = userCredential.user;
                    uid = user.uid;

                    console.log('CURRENT UID = ' + uid);
                    
                    userStatusDatabaseRef = database.ref('/status/' + uid);

                    database.ref('.info/connected').on('value', function(snapshot) {

                        if (snapshot.val() == false) {
                            return;
                        };

                        userStatusDatabaseRef
                            .onDisconnect()
                            .set(isOfflineForDatabase)
                            .then(function() {
                                userStatusDatabaseRef.set(isOnlineForDatabase);
                            })
                            .catch((error) => {
                                console.log('ERROR ....');
                                console.log(error.code);
                                console.log(error.message);
                            });
                    });
                })
                .catch((error) => {
                    console.log('ERROR ....');
                    console.log(error.code);
                    console.log(error.message);
                });

        </script>
    @endif
    @yield('extra_script')
</body>
</html>
