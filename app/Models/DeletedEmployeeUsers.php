<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class DeletedEmployeeUsers extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'deleted_employee_users_tbl';
    protected $fillable = [
        'del_Status',
        'del_user_role',
        'del_user_type',
        'del_user_image',
        'del_user_lname',
        'del_user_fname',
        'del_user_gender',
        'del_user_email',
        'del_user_sdca_id',
        'del_uEmp_job_desc',
        'del_uEmp_dept',
        'del_uEmp_phnum',
        'del_created_by',
        'reason_deletion',
        'respo_user_id',
        'perm_deleted_by'
    ];
    public $primaryKey = 'del_id';
    public $timestamps = false;
}
