<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'post',
        'device_date',
        'salary',
        'parent_id',
        'urlImage'
    ];


    public function  children()
    {
        return $this->hasMany(Worker::class,'parent_id');

    }

}
