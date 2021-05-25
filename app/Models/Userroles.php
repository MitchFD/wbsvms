<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Userroles extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'user_roles_tbl';
    protected $fillable = [
        'uRole_status',
        'uRole_type',
        'uRole',
        'uRole_access' => 'array',
        'assUsers_count',
        'created_by',
    ];
    protected $casts = [
        'uRole_access' => 'array'
    ];
    public $primaryKey = 'uRole_id';
    public $timestamps = false;
}
