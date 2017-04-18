<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'user_group_id', 'email', 'password', 'status'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	public function validationForm($datas=[]) {
		$error = false;
		$rules = [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|in:'.$datas['request']['password']
        ];

        $messages = [
        	'name.required' => 'The <b>Name</b> field is required.',
        	'email.required' => 'The <b>E-Mail Address</b> field is required.',
        	'email.email' => 'The <b>E-Mail Address</b> be a valid email address.',
        	'password.required' => 'The <b>Password</b> field is required.',
        	'password.min' => 'The <b>Password</b> must be at least 6 characters.',
        	'password_confirmation.required' => 'The <b>Confirm Password</b> field is required.',
        	'password_confirmation.in' => 'The selected <b>Confirm Password</b> is invalid.'
        ];

		$validator = \Validator::make($datas['request'], $rules, $messages);
		if ($validator->fails()) {
			$error = ['error'=>'1','success'=>'0','msg'=>'Warning : save user unsuccessfully!','validatormsg'=>$validator->messages()];
        }
		return $error;
	}

}
