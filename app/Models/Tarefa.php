<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarefa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'titulo', 
        'descricao', 
        'data_vencimento', 
        'status'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
