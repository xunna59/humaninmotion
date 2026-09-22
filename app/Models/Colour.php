<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colour extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'hex'];

    public function swatchStyle(): string
    {
        return $this->hex ? 'background-color:' . $this->hex : 'background-color:#000';
    }
}