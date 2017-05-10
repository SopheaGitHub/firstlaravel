<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Module;

class Extension extends Model {

	
	public $timestamps = false;

	protected $table = 'extension';
	protected $fillable = ['type', 'code'];

	public function getInstalled($type) {
		$extension_data = [];
		$result = Extension::where('type', '=', $type)->orderBy('code', 'asc')->get()->toArray();
		foreach ($result as $key => $value) {
			$extension_data[] = $value['code'];
		}

		return $extension_data;
	}

	public function getModulesByCode($code) {
		$result = Module::where('code', '=', $code)->orderBy('name', 'asc')->get()->toArray();
		return $result;
	}

}
