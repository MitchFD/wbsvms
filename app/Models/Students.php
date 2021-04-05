<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Students extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'registered_students_tbl';
    protected $fillable = [
        'stud_lname',
        'stud_fname',
        'stud_image',
        'stud_course',
        'stud_yearlvl',
        'stud_section',
        'stud_school',
        'stud_age',
        'stud_sex',
        'stud_email',
        'stud_phnum',
    ];
    public $primaryKey = 'stud_num';
    public $timestamps = false;
}
