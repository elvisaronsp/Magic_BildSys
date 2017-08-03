<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Carteira
 * @package App\Models
 * @version April 25, 2017, 2:16 pm BRT
 */
class Carteira extends Model
{
    use SoftDeletes;

    public $table = 'carteiras';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'nome',
    ];

    public static $campos = [
        'nome',        
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nome' => 'string',        
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nome' => 'required'
    ];
	
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function carteiraUsers()
    {
        return $this->belongsToMany(CarteiraUser::class, 'carteira_users', 'carteira_id', 'user_id')->withPivot('deleted_at')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function users()
    {
        return $this->belongsToMany(User::class, 'carteira_users', 'carteira_id', 'user_id')
            ->withPivot('deleted_at')
            ->withTimestamps();
    }
}