<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Deletedoldviolations extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'deleted_old_violations_tbl';
    protected $fillable = [
        'del_status',
        'from_del_id',
        'dOld_offense_count',
        'dOld_minor_off' => 'array',
        'dOld_less_serious_off' => 'array',
        'dOld_other_off' => 'array',
    ];
    protected $casts = [
        'dOld_minor_off' => 'array',
        'dOld_less_serious_off' => 'array',
        'dOld_other_off' => 'array'
    ];
    public $primaryKey = 'dOldViola_id';
    public $timestamps = false;
}
