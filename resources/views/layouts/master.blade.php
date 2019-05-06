<!DOCTYPE html>
<html lang='en'>
<head>
    <title>Project 4</title>
    <meta charset='utf-8'/>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
          crossorigin="anonymous">
    <link href='/css/styles.css' rel='stylesheet'>
</head>

<body class='mt-4'>
    <section id='main'>
        <header>
            <h1 id='title'>
                Currency Converter
            </h1>
            <hr>
            <p id='info'>
                Currency conversion rates are current as of:
                <br>
                {{ $ratesTimeStamp }}
                <br>
            </p>
        </header>
        @yield('content')
    </section>
</body>
</html>
