<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class CreatedSanctions extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'created_sanctions_tbl';
    protected $fillable = [
        'crSanct_details',
        'respo_user_id',
    ];
    public $primaryKey = 'crSanct_id';
    public $timestamps = false;
}
