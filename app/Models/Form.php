<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

	public $table = "forms";

	public $primaryKey = "form_id";

	protected $fillable = [
		'stud_id',
		'ftype_id',
		'fstud_fullname',
		'fstud_nomat',
		'fstud_nokp',
		'fprog_name',
		'fprog_code',
		'count_leave',
		'start_leave',
		'end_leave',
		'form_reason',
		'form_date',
		'form_flag',
		'created_by',
		'updated_by',
	];
}
