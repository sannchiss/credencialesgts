<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
    //

    protected $table = 'usuarios';

    protected $fillable  = ['name','user','id_company','email','password','modality','acountTXA','acountGTS','id_ejecutivo','remember_token'];
 


}
