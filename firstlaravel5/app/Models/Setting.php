<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model {

	public $timestamps = false;

	protected $table = 'setting';
	protected $fillable = ['website_id', 'code', 'key', 'value'];

	public function getSettings($website_id) {
		$db = Setting::where('website_id', '=', $website_id)->get();
		return $db;
	}
	//
	

}
