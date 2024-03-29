<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Users extends Model
{
    use HasFactory, Notifiable;

    /**
     * Get the user's information accociated with the user
     */
    public function user_employee()
    {
        return $this->hasMany(Useremployees::class,'user_sdca_id','uEmp_id');
    }
    public function user_student()
    {
        return $this->hasMany(Userstudents::class,'user_sdca_id','uStud_num');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users_tbl';
    protected $fillable = [
        'email',
        'password',
        'user_role',
        'user_status',
        'user_role_status',
        'user_type',
        'user_sdca_id',
        'user_image',
        'user_lname',
        'user_fname',
        'user_gender',
        'registered_by',
    ];
    public $primaryKey = 'id';
    public $timestamps = false;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
