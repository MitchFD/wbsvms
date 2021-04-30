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
        'minor_off' => 'array',
        'less_serious_off' => 'array',
        'other_off' => 'array',
        'stud_num',
        'has_sanction',
        'respo_user_id',
    ];
    protected $casts = [
        'minor_off' => 'array',
        'less_serious_off' => 'array',
        'other_off' => 'array'
    ];
    public $primaryKey = 'viola_id';
    public $timestamps = false;
}
