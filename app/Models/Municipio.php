<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Municipio extends Model
{
    use HasFactory;

    protected $table = 'tb_municipio';
    protected $primaryKey = 'muni_codi';
    public $timestamps = false;

    protected $fillable = ['muni_nomb', 'depa_codi', 'muni_pobl'];

    protected function casts(): array
    {
        return [
            'muni_pobl' => 'integer',
        ];
    }

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'depa_codi', 'depa_codi');
    }

    public function comunas(): HasMany
    {
        return $this->hasMany(Comuna::class, 'muni_codi', 'muni_codi');
    }

    public function scopeBuscar(Builder $query, ?string $termino): Builder
    {
        return $query->when($termino, fn ($q) => $q
            ->where('muni_nomb', 'like', "%{$termino}%")
            ->orWhereHas('departamento', fn ($d) => $d->where('depa_nomb', 'like', "%{$termino}%")));
    }
}
