<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Useractivites extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users_activity_tbl';
    protected $fillable = [
        'act_respo_user_id',
        'act_respo_users_lname',
        'act_respo_users_fname',
        'act_type',
        'act_details',
        'act_affected_id',
        'act_affected_sanct_ids' => 'array',
        'act_deleted_viola_ids' => 'array',
        'act_perm_deleted_viola_ids' => 'array',
        'act_recovered_viola_ids' => 'array'
    ];
    protected $casts = [
        'act_affected_sanct_ids' => 'array',
        'act_deleted_viola_ids'  => 'array',
        'act_perm_deleted_viola_ids' => 'array',
        'act_recovered_viola_ids' => 'array'
    ];
    public $primaryKey = 'act_id';
    public $timestamps = false;
}
