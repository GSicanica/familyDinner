<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezultat Glasanja</title>
</head>
<body>
    <div class="container">
        <h1>Rezultat Glasanja</h1>
        <ul>
            @foreach($rezultati as $jelo => $brojGlasova)
                <li>{{ $jelo }}: {{ $brojGlasova }} glasova</li>
            @endforeach
        </ul>
        <form action="{{ route('prijedlozi.resetiraj') }}" method="POST">
            @csrf
            <button type="submit">Resetiraj Glasanje</button>
        </form>
    </div>
</body>
</html>
