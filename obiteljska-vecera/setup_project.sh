#!/bin/bash

# Naziv projekta
PROJECT_NAME="obiteljska-vecera"

# Provjera je li Composer instaliran
if ! command -v composer &> /dev/null
then
    echo "Composer nije instaliran. Molimo instalirajte Composer i pokušajte ponovno."
    exit
fi

# Kreiranje novog Laravel projekta koristeći Composer
echo "Kreiranje Laravel projekta: $PROJECT_NAME"
composer create-project --prefer-dist laravel/laravel $PROJECT_NAME

# Provjera je li projekt uspješno kreiran
if [ ! -d "$PROJECT_NAME" ]; then
  echo "Neuspješno kreiranje projekta. Provjerite imate li potrebne dozvole i pokušajte ponovno."
  exit
fi

cd $PROJECT_NAME || exit

# Postavljanje .env datoteke
echo "Postavljanje .env datoteke"
sed -i 's/DB_DATABASE=laravel/DB_DATABASE=obiteljska_vecera/' .env
sed -i 's/DB_USERNAME=root/DB_USERNAME=vaš_korisnik/' .env
sed -i 's/DB_PASSWORD=/DB_PASSWORD=vaša_lozinka/' .env

# Kreiranje migracije za članove
echo "Kreiranje migracije za članove"
php artisan make:model Clan -m

# Definiranje migracije za clanove
echo "Definiranje migracije za clanove"
cat <<EOT > database/migrations/*_create_clans_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClansTable extends Migration
{
    public function up()
    {
        Schema::create('clans', function (Blueprint \$table) {
            \$table->id();
            \$table->string('ime');
            \$table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clans');
    }
}
EOT

# Kreiranje migracije za jela
echo "Kreiranje migracije za jela"
php artisan make:migration create_jelos_table --create=jelos

# Definiranje migracije za jela
echo "Definiranje migracije za jela"
cat <<EOT > database/migrations/*_create_jelos_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJelosTable extends Migration
{
    public function up()
    {
        Schema::create('jelos', function (Blueprint \$table) {
            \$table->id();
            \$table->string('naziv');
            \$table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jelos');
    }
}
EOT

# Kreiranje modela i migracije za Prijedlog
echo "Kreiranje modela i migracije za Prijedlog"
php artisan make:model Prijedlog -m

# Definiranje migracije za prijedloge
echo "Definiranje migracije za prijedloge"
cat <<EOT > database/migrations/*_create_prijedlogs_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrijedlogsTable extends Migration
{
    public function up()
    {
        Schema::create('prijedlogs', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('clan_id')->constrained('clans')->onDelete('cascade');
            \$table->foreignId('jelo_id')->constrained('jelos')->onDelete('cascade');
            \$table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prijedlogs');
    }
}
EOT

# Dodavanje odnosa u modele
echo "Dodavanje odnosa u modele"

cat <<EOT > app/Models/Clan.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clan extends Model
{
    use HasFactory;

    protected \$fillable = ['ime'];

    public function prijedlozi()
    {
        return \$this->hasMany(Prijedlog::class);
    }
}
EOT

cat <<EOT > app/Models/Jelo.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jelo extends Model
{
    use HasFactory;

    protected \$fillable = ['naziv'];

    public function prijedlozi()
    {
        return \$this->hasMany(Prijedlog::class);
    }
}
EOT

cat <<EOT > app/Models/Prijedlog.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prijedlog extends Model
{
    use HasFactory;

    protected \$fillable = ['clan_id', 'jelo_id'];

    public function clan()
    {
        return \$this->belongsTo(Clan::class);
    }

    public function jelo()
    {
        return \$this->belongsTo(Jelo::class);
    }
}
EOT

# Kreiranje kontrolera za ClanController
echo "Kreiranje ClanController-a"
php artisan make:controller ClanController

cat <<EOT > app/Http/Controllers/ClanController.php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clan;

class ClanController extends Controller
{
    public function index()
    {
        \$clanovi = Clan::all();
        return view('index', ['clanovi' => \$clanovi]);
    }

    public function create()
    {
        if (Clan::count() >= 4) {
            return redirect('/resetiraj-glasanje')->with('error', 'Maksimalan broj članova je postignut.');
        }

        return view('create');
    }

    public function store(Request \$request)
    {
        \$validated = \$request->validate([
            'ime' => 'required|max:255',
        ]);

        Clan::create(\$validated);

        if (Clan::count() >= 4) {
            return redirect('/resetiraj-glasanje')->with('success', 'Svi članovi su uspješno dodani!');
        }

        return redirect('/clanovi')->with('success', 'Član uspješno dodan!');
    }
}
EOT

# Kreiranje kontrolera za PrijedlogController
echo "Kreiranje PrijedlogController-a"
php artisan make:controller PrijedlogController

cat <<EOT > app/Http/Controllers/PrijedlogController.php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clan;
use App\Models\Jelo;
use App\Models\Prijedlog;

class PrijedlogController extends Controller
{
    public function glasanje(Request \$request)
    {
        \$jela = Jelo::all();
        \$clanovi = Clan::all();
        \$trenutni_clan_id = \$request->session()->get('trenutni_clan_id', \$clanovi->first()->id);

        return view('glasanje', [
            'jela' => \$jela,
            'clanovi' => \$clanovi,
            'trenutni_clan_id' => \$trenutni_clan_id,
        ]);
    }

    public function glasaj(Request \$request)
    {
        \$validated = \$request->validate([
            'clan_id' => 'required|exists:clans,id',
            'jelo_id' => 'required|exists:jelos,id',
        ]);

        Prijedlog::create(\$validated);

        // Prebacivanje na sljedećeg člana
        \$clanovi = Clan::all();
        \$currentClanIndex = \$clanovi->pluck('id')->search(\$request->clan_id);
        \$nextClanIndex = (\$currentClanIndex + 1) % \$clanovi->count();

        // Ako su svi članovi glasali, preusmjerava na rezultat
        if (\$nextClanIndex == 0) {
            return redirect('/rezultat');
        }

        \$request->session()->put('trenutni_clan_id', \$clanovi[\$nextClanIndex]->id);

        return redirect('/glasanje')->with('success', 'Glas uspješno dodan!');
    }

    public function rezultat()
    {
        \$rezultati = Jelo::withCount('prijedlozi')->orderBy('prijedlozi_count', 'desc')->get();

        return view('rezultat', ['rezultati' => \$rezultati]);
    }

    public function resetirajGlasanje()
    {
        // Briše sve prethodne prijedloge
        Prijedlog::truncate();

        // Resetiranje sesije za trenutnog člana
        session()->forget('trenutni_clan_id');

        return redirect('/glasanje')->with('success', 'Glasanje je resetirano. Možete započeti ponovno.');
    }
}
EOT

# Dodavanje ruta u web.php
echo "Dodavanje ruta u web.php"
cat <<EOT > routes/web.php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClanController;
use App\Http\Controllers\PrijedlogController;

Route::get('/', [ClanController::class, 'index']);
Route::resource('clanovi', ClanController::class);
Route::get('/glasanje', [PrijedlogController::class, 'glasanje']);
Route::post('/glasanje', [PrijedlogController::class, 'glasaj']);
Route::get('/rezultat', [PrijedlogController::class, 'rezultat']);
Route::get('/resetiraj-glasanje', [PrijedlogController::class, 'resetirajGlasanje']);
EOT

# Kreiranje početnog pogleda index.blade.php
echo "Kreiranje početnog pogleda index.blade.php"
mkdir -p resources/views
cat <<EOT > resources/views/index.blade.php
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
        @foreach(\$clanovi as \$clan)
            <li>{{ \$clan->ime }}</li>
        @endforeach
    </ul>
    <div class="add-member">
        @if (\$clanovi->count() < 4)
            <a href="{{ url('/clanovi/create') }}">Dodaj Novog Člana</a>
        @else
            <p style="color: red;">Maksimalni broj članova je postignut. Možete započeti glasanje.</p>
            <a href="{{ url('/resetiraj-glasanje') }}" class="button">Započni Glasanje</a>
        @endif
    </div>
</body>
</html>
EOT

# Kreiranje create.blade.php pogleda
echo "Kreiranje create.blade.php pogleda"
cat <<EOT > resources/views/create.blade.php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj Novog Člana</title>
</head>
<body>
    <div class="container">
        <h1>Dodaj Novog Člana</h1>
        <form action="{{ url('/clanovi') }}" method="POST">
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
EOT

# Kreiranje glasanje.blade.php pogleda
echo "Kreiranje glasanje.blade.php pogleda"
cat <<EOT > resources/views/glasanje.blade.php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glasanje za Jelo</title>
</head>
<body>
    <div class="container">
        <h1>Glasanje za Jelo</h1>
        @if (session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif
        <form action="{{ url('/glasanje') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="clan_id">Član koji glasa:</label>
                <select name="clan_id" id="clan_id" required>
                    @foreach(\$clanovi as \$clan)
                        <option value="{{ \$clan->id }}" {{ \$clan->id == \$trenutni_clan_id ? 'selected' : '' }}>
                            {{ \$clan->ime }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="jelo_id">Odaberi Jelo:</label>
                <select name="jelo_id" id="jelo_id" required>
                    @foreach(\$jela as \$jelo)
                        <option value="{{ \$jelo->id }}">{{ \$jelo->naziv }}</option>
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
EOT

# Kreiranje rezultat.blade.php pogleda
echo "Kreiranje rezultat.blade.php pogleda"
cat <<EOT > resources/views/rezultat.blade.php
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
                @foreach(\$rezultati as \$jelo)
                    <tr>
                        <td>{{ \$jelo->naziv }}</td>
                        <td>{{ \$jelo->prijedlozi_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ url('/') }}" class="button">Povratak na početnu stranicu</a>
    </div>
</body>
</html>
EOT

# Kreiranje JeloSeeder-a
echo "Kreiranje JeloSeeder-a"
cat <<EOT > database/seeders/JeloSeeder.php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jelo;

class JeloSeeder extends Seeder
{
    public function run()
    {
        \$jela = [
            ['naziv' => 'Pizza'],
            ['naziv' => 'Pasta'],
            ['naziv' => 'Salata'],
            ['naziv' => 'Gulaš'],
        ];

        foreach (\$jela as \$jelo) {
            Jelo::create(\$jelo);
        }
    }
}
EOT

# Uređivanje DatabaseSeeder-a za pokretanje JeloSeeder-a
echo "Uređivanje DatabaseSeeder-a"
cat <<EOT > database/seeders/DatabaseSeeder.php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        \$this->call(JeloSeeder::class);
    }
}
EOT

# Pokretanje migracija i seedera
echo "Pokretanje migracija i seedera"
php artisan migrate:fresh --seed

# Pokretanje aplikacije
echo "Laravel projekt je uspješno postavljen i pokreće se!"
php artisan serve
