<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>LiCSSs</title>
    </head>
    <body>
        <header>
            LiCSSs
        </header>
        <main>
            @isset($name)
                <div>
                    {{$name}}
                    @isset($email)
                        ({{$email}})
                    @endisset
                    様
                </div>
            @endisset
            <div>
                @yield('content')
            </div>
        </main>
        <footer>
            &copy; LiCSSs.
        </footer>
    </body>
</html>