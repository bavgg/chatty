<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\User;
use App\Models\Like;


class Status extends Model
{
    use HasFactory;

    protected $fillable = [
    	'body', 'parent_id'
    ];

    public function user() : BelongsTo
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function likes() : MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    // create a scope that limits the results to 'status updates'
    // (A scope allows us to use the Query Builder to filter out anything we do not want in the result)
    public function scopeNotReply( $query ) {
    	return $query->whereNull('parent_id');
    }


    // create a relationship between statuses (parent_id = NULL) and replies, using the parent_id as foreign key
    public function replies() : HasMany
    {
        return $this->hasMany(Status::class, 'parent_id');
    }


    // method to create various likes


}
