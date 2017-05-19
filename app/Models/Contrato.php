<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Contrato
 * @package App\Models
 * @version May 18, 2017, 6:06 pm BRT
 */
class Contrato extends Model
{
    use SoftDeletes;

    public $table = 'contratos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'contrato_status_id',
        'obra_id',
        'quadro_de_concorrencia_id',
        'fornecedor_id',
        'valor_total',
        'contrato_template_id',
        'arquivo'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'contrato_status_id' => 'integer',
        'obra_id' => 'integer',
        'quadro_de_concorrencia_id' => 'integer',
        'fornecedor_id' => 'integer',
        'contrato_template_id' => 'integer',
        'arquivo' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function contratoStatus()
    {
        return $this->belongsTo(\App\Models\ContratoStatus::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function contratoTemplate()
    {
        return $this->belongsTo(\App\Models\ContratoTemplate::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function fornecedore()
    {
        return $this->belongsTo(\App\Models\Fornecedore::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function obra()
    {
        return $this->belongsTo(\App\Models\Obra::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function quadroDeConcorrencia()
    {
        return $this->belongsTo(\App\Models\QuadroDeConcorrencia::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function contratoItens()
    {
        return $this->hasMany(\App\Models\ContratoIten::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function contratoStatusLogs()
    {
        return $this->hasMany(\App\Models\ContratoStatusLog::class);
    }
}