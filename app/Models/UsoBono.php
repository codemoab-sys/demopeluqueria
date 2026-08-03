<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsoBono extends Model
{
    use HasFactory;

    protected $table = 'uso_bonos';

    protected $fillable = ['bono_id', 'cita_id', 'empleado_id', 'fecha', 'notas'];

    protected $casts = ['fecha' => 'date'];

    public function bono()
    {
        return $this->belongsTo(Bono::class);
    }
}
