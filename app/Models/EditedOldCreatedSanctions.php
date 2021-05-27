<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class EditedOldCreatedSanctions extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'edited_old_created_sanctions_tbl';
    protected $fillable = [
        'eOld_from_crSanct_id',
        'eOld_crSanct_details',
        'edited_by'
    ];
    public $primaryKey = 'eOld_id';
    public $timestamps = false;
}
