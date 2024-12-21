<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    /**
     * Get the identifier that will be stored in the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }


    /**
     * User Email Verification
     * Return true if email is verified
     */
    public function PasswordResetNotification()
    {
        $otp = rand(1000, 9999);
        $this->notify(new EmailVerificationNotification($otp));
        return $otp;
    }


    //define relactionship wishlists
    public function wishlists()
    {
        return $this->hasMany(WishList::class);
    }

    // Relationship for users that this user is following
    public function following()
    {
        return $this->belongsToMany(User::class, 'friendships', 'follower_id', 'followed_id');
    }

    // Relationship for users that are following this user
    public function followers()
    {
        return $this->belongsToMany(User::class, 'friendships', 'followed_id', 'follower_id');
    }

    // Relationship: User has many FirebaseTokens
    public function firebaseTokens()
    {
        return $this->hasMany(FirebaseTokens::class);
    }
}
