<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Sanctions extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'sanctions_tbl';
    protected $fillable = [
        'stud_num',
        'sel_violation_ids' => 'array',
        'sanct_status',
        'sanct_details',
        'respo_user_id',
    ];
    protected $casts = [
        'sel_violation_ids' => 'array'
    ];
    public $primaryKey = 'sanct_id';
    public $timestamps = false;
}