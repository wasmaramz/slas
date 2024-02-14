<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormType extends Model
{
    use HasFactory;

	public $table = "form_types";

	public $primaryKey = "ftype_id";

	protected $fillable = [
		'ftype_name',
		'ftype_desc',
		'created_at',
		'updated_at',
	];
}
