<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uredi Člana</title>
</head>
<body>
    <div class="container">
        <h1>Uredi Člana</h1>
        @if($clan)
            <form action="{{ route('clanovi.update', ['clan' => $clan->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="ime">Ime Člana:</label>
                    <input type="text" name="ime" id="ime" value="{{ $clan->ime }}" required>
                </div>
                <div class="form-group">
                    <button type="submit">Spremi promjene</button>
                </div>
            </form>
        @else
            <p>Član nije pronađen.</p>
        @endif
    </div>
</body>
</html>
