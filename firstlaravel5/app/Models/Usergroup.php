<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usergroup extends Model {

	protected $table = 'user_group';
	protected $fillable = ['name', 'permission', 'default'];

	public function getUsergroup($user_group_id) {
		$result = Usergroup::where('user_group_id', '=', $user_group_id)->first();
		return $result;
	}

}
