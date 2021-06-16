<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Violations extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'violations_tbl';
    protected $fillable = [
        'violation_status',
        'offense_count',
        'major_off' => 'array',
        'minor_off' => 'array',
        'less_serious_off' => 'array',
        'other_off' => 'array',
        'stud_num',
        'has_sanction',
        'has_sanct_count',
        'respo_user_id',
        'notified'
    ];
    protected $casts = [
        'major_off' => 'array',
        'minor_off' => 'array',
        'less_serious_off' => 'array',
        'other_off' => 'array'
    ];
    public $primaryKey = 'viola_id';
    public $timestamps = false;
}
