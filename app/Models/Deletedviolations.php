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
        'from_violation_id',
        'del_violation_status',
        'del_offense_count',
        'del_minor_off' => 'array',
        'del_less_serious_off' => 'array',
        'del_other_off' => 'array',
        'del_stud_num',
        'del_from_sanct_id',
        'del_respo_user_id',
        'reason_deletion',
        'count_selected_off',
        'respo_user_id',
    ];
    protected $casts = [
        'del_minor_off' => 'array',
        'del_less_serious_off' => 'array',
        'del_other_off' => 'array'
    ];
    public $primaryKey = 'del_id';
    public $timestamps = false;
}
