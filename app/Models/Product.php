<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Product
 *
 * @property int $id
 * @property string $name
 * @property float $amount
 * @property string $shipping_address
 * @property string|null $shipping_code
 * @property int $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property User $user
 *
 * @package App\Models
 */
class Product extends Model
{
	use SoftDeletes;
	protected $table = 'products';

	protected $casts = [
		'amount' => 'float',
		'created_by' => 'int'
	];

	protected $fillable = [
		'name',
		'amount',
		'shipping_address',
		'shipping_code',
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
