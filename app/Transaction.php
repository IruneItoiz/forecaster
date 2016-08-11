<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * Mass assignable attributes
     */
	protected $fillable = ['description', 'amount', 'datedon', 'type', 'status'];
	
	/**
	 * Get the user that owns the task.
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}

}
