<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

	public $table = "programs";

	public $primaryKey = "prog_id";

	protected $fillable = [
		"prog_code",
		"prog_name",
	];
}
