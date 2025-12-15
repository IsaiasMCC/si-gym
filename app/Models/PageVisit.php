<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageVisit extends Model
{
    protected $table = 'contador_paginas';
    protected $fillable = ['pagina', 'contador'];
}
