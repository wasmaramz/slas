<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

	public $table = "students";

	public $primaryKey = "stud_id";

	protected $fillable = [
		"user_id",
		"stud_fullname",
		"stud_nomat",
		"stud_nokp",
		"prog_id",
		"created_by",
		"updated_by",
		"created_at",
		"updated_at",
	];
}
