<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Books extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Name', 'Author', 'CategoryId', 'UserId', 'Status','PublicationDate'
    ];



    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        '',
    ];
}