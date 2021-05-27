<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class EditedOldCreatedOffenses extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'edited_old_created_offenses_tbl';
    protected $fillable = [
        'eOld_from_crOffense_id',
        'eOld_crOffense_details',
        'edited_by'
    ];
    public $primaryKey = 'eOld_id';
    public $timestamps = false;
}
