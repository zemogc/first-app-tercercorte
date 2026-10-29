<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departamento extends Model
{
    use HasFactory;

    protected $table = 'tb_departamento';
    protected $primaryKey = 'depa_codi';
    public $timestamps = false;

    protected $fillable = ['depa_nomb', 'pais_codi'];

    public function pais(): BelongsTo
    {
        return $this->belongsTo(Pais::class, 'pais_codi', 'pais_codi');
    }

    public function municipios(): HasMany
    {
        return $this->hasMany(Municipio::class, 'depa_codi', 'depa_codi');
    }

    public function scopeBuscar(Builder $query, ?string $termino): Builder
    {
        return $query->when($termino, fn ($q) => $q
            ->where('depa_nomb', 'like', "%{$termino}%")
            ->orWhereHas('pais', fn ($p) => $p->where('pais_nomb', 'like', "%{$termino}%")));
    }
}
