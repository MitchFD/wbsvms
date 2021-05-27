<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class OffensesCategories extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'offenses_categories_tbl';
    protected $fillable = [
        'offCategory',
        'created_by',
    ];
    public $primaryKey = 'offCat_id';
    public $timestamps = false;
}
