<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class DeletedCreatedSanctions extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'deleted_created_sanctions_tbl';
    protected $fillable = [
        'del_crSanct_details',
        'del_Status',
    ];
    public $primaryKey = 'del_id';
    public $timestamps = false;
}
