<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class printers extends Model
{
    protected $table="printers";
    protected $fillable = [
        'id',
        'ubicacion',
        'printer',
        'ruta'
    ];
}
