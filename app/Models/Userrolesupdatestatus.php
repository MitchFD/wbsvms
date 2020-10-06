<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Userrolesupdatestatus extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'user_role_status_updates_tbl';
    protected $fillable = [
        'from_uRole_id',
        'updated_status',
        'reason_update',
        'updated_by',
    ];
    public $primaryKey = 'uRoleStatUp_id';
    public $timestamps = false;
}
