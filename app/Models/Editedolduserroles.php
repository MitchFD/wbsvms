<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Editedolduserroles extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'edited_old_user_roles_tbl';
    protected $fillable = [
        'from_uRole_id',
        'old_uRole_status',
        'old_uRole_type',
        'old_uRole',
        'old_uRole_access' => 'array',
        'respo_user_id',
    ];
    protected $casts = [
        'old_uRole_access' => 'array'
    ];
    public $primaryKey = 'eOld_uRole_id';
    public $timestamps = false;
}
