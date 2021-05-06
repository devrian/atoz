<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PrepaidBalance
 *
 * @property int $id
 * @property string $phone_number
 * @property float $amount
 * @property int $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property User $user
 *
 * @package App\Models
 */
class PrepaidBalance extends Model
{
	use SoftDeletes;
	protected $table = 'prepaid_balances';

	protected $casts = [
		'amount' => 'float',
		'created_by' => 'int'
	];

	protected $fillable = [
		'phone_number',
		'amount',
		'created_by'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'created_by');
	}

    protected static function boot()
    {
        parent::boot();
        $user_id = app('request')->user_id;

        static::creating(function($model) use ($user_id) {
            $model->created_by = $user_id;
		});

		static::saving(function ($model) {
            $model->updated_at = now();
        });

        static::deleting(function($model) {
            $model->deleted_at = now();
            $model->save();
        });
    }
}
