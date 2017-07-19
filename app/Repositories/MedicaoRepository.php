<?php

namespace App\Repositories;

use App\Models\Medicao;
use InfyOm\Generator\Common\BaseRepository;

class MedicaoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'mc_medicao_previsao_id',
        'qtd',
        'periodo_inicio',
        'periodo_termino',
        'user_id',
        'aprovado',
        'obs'
    ];

    public function create(array $attributes)
    {
        $attributes['user_id'] = auth()->id();
        if(isset($attributes['medicaoImagens'])){
            $imagens = [];
            foreach ($attributes['medicaoImagens'] as &$medicaoImagem){
                $imagens[]['imagem'] = CodeRepository::saveFile($medicaoImagem, 'medicao/'.$attributes['mc_medicao_previsao_id']);
            }
            $attributes['medicaoImagens'] = $imagens;
        }
        $model = parent::create($attributes);

        return $model;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Medicao::class;
    }
}
