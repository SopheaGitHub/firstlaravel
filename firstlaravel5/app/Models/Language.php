<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model {

	protected $table = 'language';
	protected $fillable = ['name', 'code', 'locale', 'image', 'directory', 'sort_order', 'status'];

	public function getLanguages($filter_data=[]) {
		$db = Language::orderBy($filter_data['sort'], $filter_data['order']);
		return $db;
	}

}
