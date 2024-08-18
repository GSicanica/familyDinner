<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jelo extends Model
{
    use HasFactory;

    protected $fillable = ['naziv'];

    public function prijedlozi()
    {
        return $this->hasMany(Prijedlog::class);
    }
}
