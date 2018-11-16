<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
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

    const ADMIN_ROLE = 1;

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
     */
    public function setEmail(string $email) : void
    {
        $this->email = $email;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password) : void
    {
        $this->password = bcrypt($password);
    }

    /**
     * @param Carbon $emailVerifiedAt
     */
    public function setEmailVerifiedAt(Carbon $emailVerifiedAt) : void
    {
        $this->email_verified_at = $emailVerifiedAt;
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
        return $this->role_id === User::ADMIN_ROLE;
    }

}
