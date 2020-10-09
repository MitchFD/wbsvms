<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Userstudents extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'user_students_tbl';
    protected $fillable = [
        'uStud_num',
        'uStud_school',
        'uStud_program',
        'uStud_yearlvl',
        'uStud_section',
        'uStud_phnum',
    ];
    public $primaryKey = 'uStud_num';
    public $timestamps = false;
}
