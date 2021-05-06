<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Order
 *
 * @property int $id
 * @property string $order_no
 * @property int $transaction_id
 * @property float $amount
 * @property string $model_type
 * @property int $order_status
 * @property int $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property User $user
 *
 * @package App\Models
 */
class Order extends Model
{
	use SoftDeletes;

    const PAGINATE = 5;
    const STATUS_NEW = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_FAIL = 3;
    const STATUS_CANCEL = 4;

	protected $table = 'orders';

	protected $casts = [
        'transaction_id' => 'int',
		'order_status' => 'int',
		'created_by' => 'int',
        'amount' => 'float'
	];

	protected $fillable = [
		'order_no',
		'transaction_id',
        'amount',
		'model_type',
		'order_status',
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
