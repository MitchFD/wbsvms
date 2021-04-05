<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Deleteduserroles extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'deleted_user_roles_tbl';
    protected $fillable = [
        'del_uRole_status',
        'del_uRole_type',
        'del_uRole',
        'del_uRole_access' => 'array',
        'del_created_at',
        'reason_deletion',
        'respo_user_id',
    ];
    protected $casts = [
        'del_uRole_access' => 'array'
    ];
    public $primaryKey = 'del_uRole_id';
    public $timestamps = false;
}
