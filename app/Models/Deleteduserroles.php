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
        'del_status',
        'reason_deletion',
        'del_uRole_status',
        'del_uRole_type',
        'del_uRole',
        'del_uRole_access' => 'array',
        'del_assUsers_count',
        'del_created_by',
        'deleted_by',
        'perm_deleted_by'
    ];
    protected $casts = [
        'del_uRole_access' => 'array'
    ];
    public $primaryKey = 'del_uRole_id';
    public $timestamps = false;
}
