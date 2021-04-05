<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Userupdatesstatus extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'user_status_updates_tbl';
    protected $fillable = [
        'from_user_id',
        'updated_status',
        'reason_update',
    ];
    public $primaryKey = 'uStatUpdate_id';
    public $timestamps = false;
}
