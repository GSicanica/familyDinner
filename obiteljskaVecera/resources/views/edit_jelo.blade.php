<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uredi Jelo</title>
</head>
<body>
    <div class="container">
        <h1>Uredi Jelo</h1>
        @if($jelo)
            <form action="{{ route('jela.update', ['jelo' => $jelo->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="naziv">Naziv Jela:</label>
                    <input type="text" name="naziv" id="naziv" value="{{ $jelo->naziv }}" required>
                </div>
                <div class="form-group">
                    <button type="submit">Spremi promjene</button>
                </div>
            </form>
        @else
            <p>Jelo nije pronađeno.</p>
        @endif
    </div>
</body>
</html>
