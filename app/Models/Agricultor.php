<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agricultor extends Model
{
    use HasFactory;
    protected $table = 'agricultor';
    protected $fillable = ['nombre','direccion','telefono','dpi'];
}
