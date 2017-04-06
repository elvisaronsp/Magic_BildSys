<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Insumo
 * @package App\Models
 * @version April 5, 2017, 6:36 pm BRT
 */
class Insumo extends Model
{
    use SoftDeletes;

    public $table = 'insumos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'nome',
        'unidade_sigla',
        'codigo'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nome' => 'string',
        'unidade_sigla' => 'string',
        'codigo' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nome' => 'required',
        'unidade_sigla' => 'required',
        'codigo' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function unidade()
    {
        return $this->belongsTo(Unidade::class);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function insumoServicos()
    {
        return $this->hasMany(InsumoServico::class);
    }

//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\HasMany
//     **/
//    public function contratoInsumos()
//    {
//        return $this->hasMany(ContratoInsumo::class);
//    }
//
//
//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\HasMany
//     **/
//    public function orcamentos()
//    {
//        return $this->hasMany(Orcamento::class);
//    }
//
//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\HasMany
//     **/
//    public function ordemDeCompraItens()
//    {
//        return $this->hasMany(OrdemDeCompraIten::class);
//    }
}