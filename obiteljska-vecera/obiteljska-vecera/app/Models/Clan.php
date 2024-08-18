<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clan extends Model
{
    use HasFactory;

    protected $fillable = ['ime'];

    public function prijedlozi()
    {
        return $this->hasMany(Prijedlog::class);
    }
}
