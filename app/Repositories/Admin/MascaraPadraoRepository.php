<?php

namespace App\Repositories\Admin;

use App\Models\MascaraPadrao;
use InfyOm\Generator\Common\BaseRepository;

class MascaraPadraoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nome',
		'orcamento_tipo_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MascaraPadrao::class;
    }
	
}
