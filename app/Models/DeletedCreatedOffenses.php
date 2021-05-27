<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class DeletedCreatedOffenses extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'deleted_created_offenses_tbl';
    protected $fillable = [
        'del_crOffense_details',
        'del_Status',
        'deleted_by',
        'perm_deleted_by'
    ];
    public $primaryKey = 'del_id';
    public $timestamps = false;
}
