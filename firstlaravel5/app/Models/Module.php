<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model {

	public $timestamps = false;

	protected $table = 'module';
	protected $fillable = ['name', 'code', 'setting'];

	public function getLayouts($filter_data=[]) {
		$db = Layout::orderBy($filter_data['sort'], $filter_data['order']);
		return $db;
	}

}
