<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;
    protected $fillable = [
        'name', 'email', 'password', 'type', 'active', 'activation_token'
    ];

    protected $dates = ['deleted_at'];
    protected $hidden = [
        'password', 'remember_token', 'activation_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function employee($value = '')
    {
        return $this->hasOne('App\Employee');
    }
    public function student($value = '')
    {
        return $this->hasOne('App\Student');
    }
    public function father($value = '')
    {
        return $this->hasOne('App\Father');
    }
    public function mother($value = '')
    {
        return $this->hasOne('App\Mother');
    }
    public function user_role($value = '')
    {
        return $this->hasOne('App\User_Role');
    }
    public function permission_employee($value = '')
    {
        return $this->hasOne('App\Permission_Employee');
    }
    public function permission_invoice($value = '')
    {
        return $this->hasOne('App\PermissionInvoice');
    }
    public function permission_user($value = '')
    {
        return $this->hasOne('App\Permission_User');
    }
    public function permission_student($value = '')
    {
        return $this->hasOne('App\Permission_Student');
    }
    public function mails($value = '')
    {
        return $this->hasMany('App\EMail');
    }
    public function cc($value = '')
    {
        return $this->hasMany('App\MailCC');
    }
    public function todoCate($value = '')
    {
        return $this->hasMany('App\TodoCategory');
    }
    public function mailCate($value = '')
    {
        return $this->hasMany('App\MailCategory');
    }
    public function calendarCate($value = '')
    {
        return $this->hasMany('App\CalendarCategory');
    }
    public function bcc($value = '')
    {
        return $this->hasMany('App\MailBCC');
    }
    public function to($value = '')
    {
        return $this->hasMany('App\mailTo');
    }
}
