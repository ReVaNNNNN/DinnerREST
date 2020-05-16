<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 * @property int id
 * @property string email
 * @property Carbon email_verified_at
 * @property string password
 * @property int role_id
 */

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany('App\Order');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() : array
    {
        return [];
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail() : string
    {
        return $this->email;
    }

    /**
     * @return Carbon
     */
    public function getEmailVerifiedAt() : ?Carbon
    {
        return $this->email_verified_at;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email) : User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password) : User
    {
        $this->password = bcrypt($password);
        return $this;
    }


    /**
     * @param Carbon $emailVerifiedAt
     * @return User
     */
    public function setEmailVerifiedAt(Carbon $emailVerifiedAt) : User
    {
        $this->email_verified_at = $emailVerifiedAt;
        return $this;
    }

    /**
     * @return bool
     */
    public function checkUserIsVerified() : bool
    {
        return $this->email_verified_at ? true : false;
    }

    /**
     * @return bool
     */
    public function isAdmin() : bool
    {
        return $this->role_id === Role::ADMIN;
    }
}
