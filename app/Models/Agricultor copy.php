<?php

namespace App\Models;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agricultor extends Model
{
    use HasFactory;
    protected $table = 'agricultor';
    protected $fillable = ['id_agricultor','nombre','direccion','telefono','dpi'];
    public $timestamps = false;
}
