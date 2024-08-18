<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ime'];

    /**
     * Get the votes (prijedlozi) associated with the clan.
     */
    public function prijedlozi()
    {
        return $this->hasMany(Prijedlog::class);
    }
}
