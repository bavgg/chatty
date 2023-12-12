<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

use App\Models\Status;
use App\Models\Like;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'first_name',
        'last_name',
        'location',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function statuses() : HasMany
    {
        return $this->hasMany(Status::class, 'user_id');
    }




    public function getName(){
        if($this->firt_name && $this->last_name){
            return "{$this->firt_name} {$this->last_name}";
        }

        if($this->first_name){
            return $this->first_name;
        }

        return null;
    }

    public function getNameOrUsername(){
        return $this->getName() ?: $this->username;
    }

    public function getFirstnameOrUsername(){
        return $this->first_name ?: $this->username;
    }

    public function getAvatarUrl(){
        return "https://www.gravatar.com/avatar/{{md5($this->email)}}?d=mm&s=50";
    }

        /**
     *
     * Relationships with FRIENDS table
     *
     */
    public function friendsByReceiving() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friends', 'receiver_id', 'sender_id');
    }

    public function friendsBySending() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friends', 'sender_id', 'receiver_id');
    }

    public function friends()
    {
        $friendsList =
            $this->friendsByReceiving()->wherePivot('status', 'accepted')->get()
                ->merge( $this->friendsBySending()->wherePivot('status', 'accepted' )->get() );
        //dd($friendsList);
        return $friendsList;
    }

    /**
     * FRIEND REQUESTS handling
     */
    public function friendRequests()
    {
        return $this->friendsByReceiving()->wherePivot('status', 'pending')->get();
    }

    public function friendRequestsSent()
    {
        return $this->friendsBySending()->wherePivot('status', 'pending')->get();
    }

    public function friendRequestSentTo(User $user)
    {
        return (bool) $this->friendRequestsSent()->where('id', $user->id)->count();
    }

    public function friendRequestReceivedFrom(User $user)
    {
        return (bool) $this->friendRequests()->where('id', $user->id)->count();
    }

    public function addFriend(User $user)
    {
        return $this->friendsBySending()->attach($user->id);
    }

    public function acceptFriendRequest(User $user)
    {
        return $this->friendRequests()->where('id', $user->id)->first()
                ->pivot->update(['status' => 'accepted']);
    }

    public function isFriendsWith(User $user)
    {
        return (bool) $this->friends()->where('id', $user->id)->count();
    }

    public function hasLikedStatus(Status $status)
    {
        return (bool) $status->likes
            ->where('likeable_id', $status->id)
            ->where('likeable_type', get_class($status))
            ->where('user_id', $this->id)
            ->count();
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }


}
