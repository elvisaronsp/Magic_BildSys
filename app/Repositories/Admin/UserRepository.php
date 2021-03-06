<?php

namespace App\Repositories\Admin;

use App\Models\User;
use InfyOm\Generator\Common\BaseRepository;

class UserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'email',
        'password',
        'active',
        'remember_token'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return User::class;
    }

    public function create(array $attributes)
    {
        $attributes['password'] = bcrypt($attributes['password']);
        $attributes['active'] = isset($attributes['active'])?$attributes['active']:0;
        $attributes['admin'] = isset($attributes['admin'])?$attributes['admin']:0;

        $model = parent::create($attributes);

        return $model;
    }

    public function update(array $attributes, $id)
    {
        if(isset($attributes['password'])&& strlen($attributes['password']) ){
            $attributes['password'] = bcrypt($attributes['password']);
        }else{
            unset($attributes['password']);
        }
        $attributes['active'] = isset($attributes['active'])?$attributes['active']:0;
        $attributes['admin'] = isset($attributes['admin'])?$attributes['admin']:0;

        $model = parent::update($attributes, $id);

        return $model;
    }

    public function usuariosDaObra($obraId)
    {
        return $this->model->whereHas('obras', function($q) use ($obraId) {
            $q->where('obra_id', $obraId);
        })->where('active', true)->get();
    }
	
	public function usuariosDaCarteira($carteiraId)
    {
        return $this->model->whereHas('carteiras', function($q) use ($carteiraId) {
            $q->where('carteira_id', $carteiraId);
        })->where('active', true)->get();
    }


    public function getUsersByType($type) {

        $user = User::select([
            'id',
            'name'
        ]);

        $user->join('role_user','users.id', '=', 'role_user.user_id')->where('role_user.role_id',$type)->where('active', true);

        return $user->get();
    }

}
