<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Api extends Model
{
    protected $table = 'history';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'value',
        'created_at',
        'updated_at',
    ];
}
