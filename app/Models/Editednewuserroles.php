<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Editednewuserroles extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'edited_new_user_roles_tbl';
    protected $fillable = [
        'from_eOld_uRole_id',
        'new_uRole_status',
        'new_uRole_type',
        'new_uRole',
        'new_uRole_access' => 'array',
    ];
    protected $casts = [
        'new_uRole_access' => 'array'
    ];
    public $primaryKey = 'eNew_uRole_id';
    public $timestamps = false;
}
