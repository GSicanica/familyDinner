<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj Člana</title>
</head>
<body>
    <div class="container">
        <h1>Dodaj novog člana</h1>
        <form action="{{ route('clanovi.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="ime">Ime Člana:</label>
                <input type="text" name="ime" id="ime" required>
            </div>
            <div class="form-group">
                <button type="submit">Dodaj Člana</button>
            </div>
        </form>
    </div>
</body>
</html>
