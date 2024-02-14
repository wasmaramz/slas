<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

	public $table = 'levels';

	public $primaryKey = "level_id";

	public $incrementing = false;

	protected $fillable = [
		"level_name",
		"created_at",
		"updated_at",
	];
}
