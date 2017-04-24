<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Information extends Model {

	protected $table = 'information';
	protected $fillable = ['bottom', 'sort_order', 'status'];

	// public function getZone($zone_id) {
	// 	$result = Zone::where('zone_id', '=', $zone_id)->first();
	// 	return $result;
	// }

	public function getAllInformation($filter_data=[]) {
		$db = DB::table('information as i')
		->select('i.information_id as information_id', 'i.sort_order as sort_order', 'id.title as title')
		->join('information_description as id', 'id.information_id', '=', 'i.information_id')
		->where('id.language_id', '=', '1')
		->orderBy($filter_data['sort'], $filter_data['order']);
		return $db;
	}

	// public function getZonesByContry($country_id) {
	// 	$result = Zone::where('country_id', '=', $country_id)->orderBy('name', 'asc')->lists('name', 'zone_id');
	// 	return $result;
	// }

	// public function validationForm($datas=[]) {
	// 	$error = false;
	// 	$rules = [
 //            'name'			=> 'required',
 //            'code'			=> 'max:32',
 //        ];

 //        $messages = [
 //        	'name.required' => 'The <b>Country Name</b> field is required.',
 //        	'code.max' => 'The <b>Code</b> may not be greater than 32 characters.'
 //        ];

	// 	$validator = \Validator::make($datas['request'], $rules, $messages);
	// 	if ($validator->fails()) {
	// 		$error = ['error'=>'1','success'=>'0','msg'=>'Warning : save zone unsuccessfully!','validatormsg'=>$validator->messages()];
 //        }
	// 	return $error;
	// }

}
