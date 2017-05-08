<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model {

	protected $table = 'banner';
	protected $fillable = ['name', 'status'];

	public function getBanners($filter_data=[]) {
		$db = Banner::orderBy($filter_data['sort'], $filter_data['order']);
		return $db;
	}

}
