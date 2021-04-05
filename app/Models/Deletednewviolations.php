<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Deletednewviolations extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'deleted_new_violations_tbl';
    protected $fillable = [
        'from_dOldViola_id',
        'dNew_offense_count',
        'dNew_minor_off' => 'array',
        'dNew_less_serious_off' => 'array',
        'dNew_other_off' => 'array',
    ];
    protected $casts = [
        'dNew_minor_off' => 'array',
        'dNew_less_serious_off' => 'array',
        'dNew_other_off' => 'array'
    ];
    public $primaryKey = 'dNewViola_id';
    public $timestamps = false;
}
