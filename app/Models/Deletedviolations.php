<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Deletedviolations extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'deleted_violations_tbl';
    protected $fillable = [
        'del_status',
        'from_viola_id',
        'del_violation_status',
        'del_offense_count',
        'del_minor_off' => 'array',
        'del_less_serious_off' => 'array',
        'del_other_off' => 'array',
        'del_stud_num',
        'del_has_sanction', 
        'del_has_sanct_count',
        'del_respo_user_id',
        'reason_deletion',
        'respo_user_id',
        'perm_deleted_by'
    ];
    protected $casts = [
        'del_minor_off' => 'array',
        'del_less_serious_off' => 'array',
        'del_other_off' => 'array'
    ];
    public $primaryKey = 'del_id';
    public $timestamps = false;
}
