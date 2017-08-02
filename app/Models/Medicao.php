<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Medicao
 * @package App\Models
 * @version July 11, 2017, 2:13 pm BRT
 */
class Medicao extends Model
{
    public $table = 'medicoes';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'mc_medicao_previsao_id',
        'qtd',
        'medicao_servico_id',
        'user_id',
        'aprovado',
        'obs'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mc_medicao_previsao_id' => 'integer',
        'medicao_servico_id' => 'integer',
        'user_id' => 'integer',
        'obs' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'mc_medicao_previsao_id'=>'required',
        'qtd'=>'required',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mcMedicaoPrevisao()
    {
        return $this->belongsTo(McMedicaoPrevisao::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function medicaoImagens()
    {
        return $this->hasMany(MedicaoImagem::class,'medicao_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function medicaoServico()
    {
        return $this->belongsTo(MedicaoServico::class,'medicao_servico_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // -------- APROVAÇÕES

    public function aprovacoes()
    {
        return $this->morphMany(WorkflowAprovacao::class, 'aprovavel');
    }

    public function irmaosIds()
    {
        return $this->medicaoServico->medicoes()->pluck('medicoes.id', 'medicoes.id')->toArray();
    }

    public function paiEmAprovacao()
    {
        if(!$this->medicao_servico_id){
            return true;
        }
        if (!$this->medicaoServico->finalizado) {
            $this->medicaoServico->update(['finalizado' => 3]);
        }
    }

    public function confereAprovacaoGeral()
    {
        if(!$this->medicao_servico_id){
            return true;
        }
        $qtd_itens = $this->medicaoServico->medicoes()->count();
        $qtd_itens_aprovados = $this->medicaoServico->medicoes()->where('aprovado', '1')->count();
        $qtd_itens_sem_voto = $this->medicaoServico->medicoes()->whereNull('aprovado')->count();

        // Verifica se todos foram aprovados
        if ($qtd_itens === $qtd_itens_aprovados) {

            $this->medicaoServico->update(['aprovado' => 1]);
        }

        // Verifica se algum foi reprovado e todos foram votados
        if ($qtd_itens !== $qtd_itens_aprovados && $qtd_itens_sem_voto===0) {
            $this->medicaoServico->update(['aprovado'=>0]);
        }
    }

    public function qualObra()
    {
        return $this->mcMedicaoPrevisao->contratoItem->contrato->obra_id;
    }

    public function aprova($valor)
    {
        $this->timestamps = false;
        $this->attributes['aprovado'] = $valor;
        $this->save();
    }

    public static $workflow_tipo_id = WorkflowTipo::MEDICAO;

    public function workflowNotification()
    {
        if($this->medicao_servico_id){
            return [
                'message' => 'Você tem uma medição para aprovar',
                'link' => route('medicaoServicos.show', $this->medicao_servico_id)
            ];
        }else{
            return [
                'message' => 'Você tem uma medição para aprovar',
                'link' => route('medicoes.show', $this->id)
            ];
        }

    }
}