<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Obiteljska Večera - Članovi</title>
</head>
<body>
    <h1>Popis Članova</h1>
    <ul>
        @foreach($clanovi as $clan)
            <li>{{ $clan->ime }}</li>
        @endforeach
    </ul>
    <div class="add-member">
        @if ($clanovi->count() < 4)
            <a href="{{ url('/clanovi/create') }}">Dodaj Novog Člana</a>
        @else
            <p style="color: red;">Maksimalni broj članova je postignut. Možete započeti glasanje.</p>
            <a href="{{ url('/resetiraj-glasanje') }}" class="button">Započni Glasanje</a>
        @endif
    </div>
</body>
</html>
