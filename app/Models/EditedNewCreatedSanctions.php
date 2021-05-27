<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class EditedNewCreatedSanctions extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'edited_new_created_sanctions_tbl';
    protected $fillable = [
        'eNew_from_eOld_id',
        'eNew_crSanct_details'
    ];
    public $primaryKey = 'eNew_id';
    public $timestamps = false;
}
