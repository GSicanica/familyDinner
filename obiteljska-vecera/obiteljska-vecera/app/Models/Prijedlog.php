<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prijedlog extends Model
{
    use HasFactory;

    protected $fillable = ['clan_id', 'jelo_id'];

    public function clan()
    {
        return $this->belongsTo(Clan::class);
    }

    public function jelo()
    {
        return $this->belongsTo(Jelo::class);
    }
}
