<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = ['nome'];

    // Relacionamento: uma categoria tem muitos produtos
    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }
}
