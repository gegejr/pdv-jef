<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assinatura extends Model
{
    use HasFactory;

    protected $table = 'assinaturas';

    protected $fillable = [
        'cliente_id',
        'data_vencimento',
        'status',
    ];

    // Relacionamento com Cliente (supondo que você tenha um model Cliente)
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Método para verificar se está ativa
    public function isAtiva()
    {
        return $this->status === 'pago' && $this->data_vencimento >= now()->startOfDay();
    }
}
