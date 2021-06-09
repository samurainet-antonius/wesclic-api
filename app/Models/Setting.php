<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{

    protected $table = 'setting';

    // const tgl_input,update,delete
    const CREATED_AT = 'tgl_input';
    const UPDATED_AT = 'tgl_update';
    const DELETED_AT = 'tgl_delete';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'type',
        'isi',
        'flag_aktif',
        'tgl_input',
        'tgl_update',
        'uuid',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'id',
    ];


    public static function detail($kolom,$value){
      return (new static)->where($kolom, $value)->first();
    }
}
