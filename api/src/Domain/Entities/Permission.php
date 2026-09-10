<?php

namespace Domain\Entities;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    
    protected $fillable = [
        'name',
        'code',
        'module_id',
        'description',
    ];
}