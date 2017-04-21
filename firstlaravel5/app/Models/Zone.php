<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Zone extends Model {

	protected $table = 'zone';
	protected $fillable = ['country_id', 'name', 'code', 'status'];

	public function getZone($zone_id) {
		$result = Zone::where('zone_id', '=', $zone_id)->first();
		return $result;
	}

	public function getZones($filter_data=[]) {
		$db = DB::table('zone as z')
		->select('z.*', 'c.name as country_name')
		->join('country as c', 'c.country_id', '=', 'z.country_id')
		->orderBy('z.'.$filter_data['sort'], $filter_data['order']);
		return $db;
	}

	public function validationForm($datas=[]) {
		$error = false;
		$rules = [
            'name'			=> 'required',
            'code'			=> 'max:32',
        ];

        $messages = [
        	'name.required' => 'The <b>Country Name</b> field is required.',
        	'code.max' => 'The <b>Code</b> may not be greater than 32 characters.'
        ];

		$validator = \Validator::make($datas['request'], $rules, $messages);
		if ($validator->fails()) {
			$error = ['error'=>'1','success'=>'0','msg'=>'Warning : save zone unsuccessfully!','validatormsg'=>$validator->messages()];
        }
		return $error;
	}

}
