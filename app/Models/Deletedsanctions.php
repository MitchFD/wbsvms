<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Deletedsanctions extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'deleted_sanctions_tbl';
    protected $fillable = [
        'del_from_sanct_id',
        'del_by_user_id',
        'reason_deletion',
        'del_stud_num',
        'del_sanct_status',
        'del_sanct_details',
        'del_sel_viola_ids' => 'array',
        'del_respo_user_id',
    ];
    protected $casts = [
        'del_sel_viola_ids' => 'array'
    ];
    public $primaryKey = 'del_id';
    public $timestamps = false;
}
