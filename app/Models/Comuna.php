<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comuna extends Model
{
    use HasFactory;

    protected $table = 'tb_comuna';
    protected $primaryKey = 'comu_codi';
    public $timestamps = false;

    protected $fillable = ['comu_nomb', 'muni_codi'];

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'muni_codi', 'muni_codi');
    }

    public function scopeBuscar(Builder $query, ?string $termino): Builder
    {
        return $query->when($termino, fn ($q) => $q
            ->where('comu_nomb', 'like', "%{$termino}%")
            ->orWhereHas('municipio', fn ($m) => $m->where('muni_nomb', 'like', "%{$termino}%")));
    }
}
