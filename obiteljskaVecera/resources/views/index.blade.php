<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Početna stranica</title>
</head>
<body>
    <div class="container">
        <h1>Obiteljska večera</h1>

        @if(session('success'))
            <p>{{ session('success') }}</p>
        @endif

        @if(session('error'))
            <p>{{ session('error') }}</p>
        @endif

        <h2>Članovi</h2>
        <ul>
            @foreach($clanovi as $clan)
                <li>{{ $clan->ime }} <a href="{{ route('clanovi.edit', $clan->id) }}">Uredi</a></li>
            @endforeach
        </ul>

        <h2>Jela</h2>
        <ul>
            @foreach($jela as $jelo)
                <li>{{ $jelo->naziv }} <a href="{{ route('jela.edit', $jelo->id) }}">Uredi</a></li>
            @endforeach
        </ul>

        <a href="{{ route('clanovi.create') }}">Dodaj novog člana</a> |
        <a href="{{ route('jela.create') }}">Dodaj novo jelo</a> |
        <a href="{{ route('prijedlozi.glasanje') }}">Započni glasanje</a>
    </div>
</body>
</html>
