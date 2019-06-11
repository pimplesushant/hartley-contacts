<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 'email', 'photo', 'primary_phone', 'secondary_phone', 'created_by'
    ];

    protected $default = [
        'id', 'first_name', 'middle_name', 'last_name', 'email', 'photo', 'primary_phone', 'secondary_phone', 'created_by'
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsToMany(User::class);
    }

}
