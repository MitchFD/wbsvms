<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Editednewstudentusers extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'edited_new_stud_users_tbl';
    protected $fillable = [
        'from_eOldStud_id',
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
        'eNew_school',
        'eNew_program',
        'eNew_yearlvl',
        'eNew_section',
        'eNew_phnum',
    ];
    public $primaryKey = 'eNewStud_id';
    public $timestamps = false;
}
