<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class EditedNewCreatedOffenses extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'edited_new_created_offenses_tbl';
    protected $fillable = [
        'eNew_from_eOld_id',
        'eNew_crOffense_category',
        'eNew_crOffense_type',
        'eNew_crOffense_details'
    ];
    public $primaryKey = 'eNew_id';
    public $timestamps = false;
}
