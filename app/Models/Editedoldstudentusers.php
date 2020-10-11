<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Editedoldstudentusers extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'edited_old_stud_users_tbl';
    protected $fillable = [
        'from_user_id',
        'eOld_email',
        // 'eOld_password',
        'eOld_uRole',
        // 'eOld_user_status',
        // 'eOld_uRole_status',
        'eOld_user_type',
        'eOld_user_image',
        'eOld_user_lname',
        'eOld_user_fname',
        'eOld_sdca_id',
        'eOld_school',
        'eOld_program',
        'eOld_yearlvl',
        'eOld_section',
        'eOld_phnum',
        'respo_user_id',
    ];
    public $primaryKey = 'eOldStud_id';
    public $timestamps = false;
}
