<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj Novo Jelo</title>
</head>
<body>
    <div class="container">
        <h1>Dodaj Novo Jelo</h1>
        <form action="{{ route('jela.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="naziv">Naziv Jela:</label>
                <input type="text" name="naziv" id="naziv" required>
            </div>
            <div class="form-group">
                <button type="submit">Dodaj Jelo</button>
            </div>
        </form>
    </div>
</body>
</html>
