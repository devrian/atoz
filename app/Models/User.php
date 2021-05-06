<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Order[] $orders
 * @property Collection|PrepaidBalance[] $prepaid_balances
 * @property Collection|Product[] $products
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    use Notifiable;

	protected $table = 'users';

	protected $dates = [
		'email_verified_at'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'email',
		'email_verified_at',
		'password',
		'remember_token'
	];

	public function orders()
	{
		return $this->hasMany(Order::class, 'created_by');
	}

	public function prepaid_balances()
	{
		return $this->hasMany(PrepaidBalance::class, 'created_by');
	}

	public function products()
	{
		return $this->hasMany(Product::class, 'created_by');
	}
}
