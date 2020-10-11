<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Editednewuseremployees extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'edited_new_emp_users_tbl';
    protected $fillable = [
        'from_eOldEmp_id',
        'eNew_email',
        // 'eNew_password',
        'eNew_uRole',
        // 'eNew_user_status',
        // 'eNew_uRole_status',
        'eNew_user_type',
        'eNew_user_image',
        'eNew_user_lname',
        'eNew_user_fname',
        'eNew_sdca_id',
        'eNew_job_desc',
        'eNew_dept',
        'eNew_phnum',
        'respo_user_id',
    ];
    public $primaryKey = 'eNewEmp_id';
    public $timestamps = false;
}
