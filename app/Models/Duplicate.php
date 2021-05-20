<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Duplicate extends Model
{
    use HasFactory;

	/**
	 * @var string[] $guarded
	 */
    protected $guarded = ['id'];

	/**
	 *
	 * @return BelongsTo
	 */
    public function ads(): BelongsTo
	{
        return $this->belongsTo(Ad::class, 'ad_id');
    }
}
