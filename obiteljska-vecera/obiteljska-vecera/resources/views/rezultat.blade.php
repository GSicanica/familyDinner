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
        <table>
            <thead>
                <tr>
                    <th>Jelo</th>
                    <th>Broj Glasova</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rezultati as $jelo)
                    <tr>
                        <td>{{ $jelo->naziv }}</td>
                        <td>{{ $jelo->prijedlozi_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ url('/') }}" class="button">Povratak na početnu stranicu</a>
    </div>
</body>
</html>
