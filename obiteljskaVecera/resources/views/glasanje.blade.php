<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glasanje</title>
</head>
<body>
    <div class="container">
        <h1>Glasanje za Jelo</h1>
        <form action="{{ route('prijedlozi.glasaj') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="jelo">Odaberi Jelo:</label>
                <select name="jelo_id" id="jelo" required>
                    @foreach($jela as $jelo)
                        <option value="{{ $jelo->id }}">{{ $jelo->naziv }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="submit">Glasaj</button>
            </div>
        </form>
    </div>
</body>
</html>
