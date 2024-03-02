<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStatus extends Model
{
    use HasFactory;

	public $table = "user_statuses";

	public $primaryKey = "ustat_id";

	public $incrementing = false;

	protected $fillable = [
		'ustat_id',
		'ustat_name',
	];
}
