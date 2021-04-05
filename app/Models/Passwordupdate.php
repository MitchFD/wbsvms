<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Passwordupdate extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'password_updates_tbl';
    protected $fillable = [
        'sel_user_id',
        'upd_by_user_id',
        'reason_update',
    ];
    public $primaryKey = 'pass_upd_id';
    public $timestamps = false;
}
