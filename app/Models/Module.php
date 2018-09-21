<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
	use SoftDeletes;

	protected $fillable = [ 
		'parent_id',
		'name',
		'url',
		'order',
		'active'
	];
	
	protected $dates = ['deleted_at'];
}
