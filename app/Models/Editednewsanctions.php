<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Editednewsanctions extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'edited_new_sanctions_tbl';
    protected $fillable = [
        'edi_from_eOld_id',
        'eNew_sanct_status',
        'eNew_sanct_details',
    ];
    public $primaryKey = 'eNew_id';
    public $timestamps = false;
}
