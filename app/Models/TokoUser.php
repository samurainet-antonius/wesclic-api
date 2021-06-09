<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TokoUser extends Model
{

    const CREATED_AT = 'tgl_input';
    const UPDATED_AT = 'tgl_update';
    const DELETED_AT = 'tgl_delete';

    protected $table = 'toko_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number_unique',
        'user_id',
        'nama',
        'kategori',
        'jenis',
        'email',
        'nohp',
        'tagline',
        'provinsi',
        'kota',
        'kecamatan',
        'alamat',
        'logo',
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
        'user_id',
    ];
}
