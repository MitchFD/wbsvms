<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Editedoldsanctions extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'edited_old_sanctions_tbl';
    protected $fillable = [
        'edi_from_sanct_id',
        'edi_by_user_id',
        'eOld_sel_violation_ids' => 'array',
        'eOld_sanct_status',
        'eOld_sanct_details',
    ];
    protected $casts = [
        'eOld_sel_violation_ids' => 'array'
    ];
    public $primaryKey = 'eOld_id';
    public $timestamps = false;
}
