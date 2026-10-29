<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Pais extends Model
{
    use HasFactory;

    protected $table = 'tb_pais';
    protected $primaryKey = 'pais_codi';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['pais_codi', 'pais_nomb', 'pais_capi'];

    protected function casts(): array
    {
        return [
            'pais_codi' => 'string',
            'pais_nomb' => 'string',
            'pais_capi' => 'integer',
        ];
    }

    public function capital(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'pais_capi', 'muni_codi');
    }

    public function departamentos(): HasMany
    {
        return $this->hasMany(Departamento::class, 'pais_codi', 'pais_codi');
    }

    public function municipios(): HasManyThrough
    {
        return $this->hasManyThrough(Municipio::class, Departamento::class, 'pais_codi', 'depa_codi', 'pais_codi', 'depa_codi');
    }

    public function scopeBuscar(Builder $query, ?string $termino): Builder
    {
        return $query->when($termino, fn ($q) => $q
            ->where('pais_nomb', 'like', "%{$termino}%")
            ->orWhere('pais_codi', 'like', "%{$termino}%"));
    }
}
