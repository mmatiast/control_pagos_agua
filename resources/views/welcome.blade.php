<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{config('app.name')}}</title>
    <link rel="stylesheet" href="/css/app.css">
    <link rel="icon" type="image/png" href="/favicon.png">
    <link href="/css/cover.css?v=1" rel="stylesheet">
</head>
<body class="text-center bg-white">
    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
        <header class="masthead mb-auto">
        </header>
        <main role="main" class="inner cover mb-auto">
            <img class="cover-heading" src="/images/LOGO.jpg" alt="logo">
            <p class="text-lg mt-3">
                Sistema para la gestión de registros de consumos y pagos de servicio de agua potable en la <b>Asociación de Vecinos, El Manzano, La Libertad, Santa Catarina Pinula</b>
            </p>
            @if (Route::has('login'))
            <p>
                @auth
                <a class="btn btn-lg btn-primary" href="{{ url('/home') }}">{{__('Home')}}</a>
                @else
                <a class="btn btn-lg btn-primary" href="{{ route('login') }}">{{__('Login')}}</a>
                @endauth
            </p>
            @endif
        </main>
    </div>
</body>
</html>
