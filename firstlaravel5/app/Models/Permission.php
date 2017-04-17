<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model {

	protected $table = 'permission';
	protected $fillable = ['text'];

	public function validationForm($datas=[]) {
		$error = false;
		$ruless = [
            'name' => 'required'
        ];
		$validator = \Validator::make($datas['request'],$ruless);
		if ($validator->fails()) {
			$error = ['error'=>'1','success'=>'0','msg'=>'Warning : add new user group unsuccessfully!','validatormsg'=>$validator->messages()];
        }
		return $error;
	}

}
