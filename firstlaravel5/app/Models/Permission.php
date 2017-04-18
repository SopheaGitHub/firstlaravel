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

        $messages = [
        	'name.required' => 'The <b>User Group Name</b> field is required.'
        ];

		$validator = \Validator::make($datas['request'], $ruless, $messages);
		if ($validator->fails()) {
			$error = ['error'=>'1','success'=>'0','msg'=>'Warning : save user group unsuccessfully!','validatormsg'=>$validator->messages()];
        }
		return $error;
	}

}
