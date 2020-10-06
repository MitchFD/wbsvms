<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Useremployees extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'user_employees_tbl';
    protected $fillable = [
        'uEmp_id',
        'uEmp_job_desc',
        'uEmp_dept',
        'uEmp_phnum',
    ];
    public $primaryKey = 'uEmp_id';
    public $timestamps = false;
}
