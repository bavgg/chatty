<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;


use App\Models\User;

class Like extends Model
{
    use HasFactory;
    protected $table = 'likeable';


    // provide a polymorphic relationship
    public function likeable(  ): MorphTo
     {
    	return $this->morphTo();
    }


    // get user of a like (normal relationship)
    // this means the local 'user_id' field is the key (id) to the foreign table 'User'
    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }
}
