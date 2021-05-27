<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class CreatedOffenses extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'created_offenses_tbl';
    protected $fillable = [
        'crOffense_details',
        'respo_user_id',
    ];
    public $primaryKey = 'crOffense_id';
    public $timestamps = false;
}
