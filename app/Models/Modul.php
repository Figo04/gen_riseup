<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
    protected $table = 'modul';

    protected $fillable = ['nama', 'slug', 'urutan', 'cover_image'];

    public function subBagian()
    {
        return $this->hasMany(SubBagian::class)->orderBy('urutan');
    }
}
