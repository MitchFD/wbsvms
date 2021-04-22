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
    protected $table = 'students_tbl';
    protected $fillable = [
        'First_Name',
        'Middle_Name',
        'Last_Name',
        'Gender',
        'Age',
        'Email',
        'School_Name',
        'Course',
        'YearLevel',
        'Student_Image',
        'Status',
    ];
    public $primaryKey = 'Student_Number';
    public $timestamps = false;
}
