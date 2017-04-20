<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\User;

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

	public function getUser($user_id) {
		$result = User::where('id', '=', $user_id)->first();
		return $result;
	}

	public function getUsers($filter_data=[]) {
		$db = User::orderBy($filter_data['sort'], $filter_data['order']);
		return $db;
	}

	public function validationForm($datas=[]) {
		$error = false;
		$rules = [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|in:'.$datas['request']['password']
        ];

        $emailexist = User::where('email', '=', $datas['request']['email'])->count();
        if($emailexist > 0){
			$rules['emailexist'] = 'required';
		}

        $messages = [
        	'name.required' => 'The <b>Name</b> field is required.',
        	'email.required' => 'The <b>E-Mail Address</b> field is required.',
        	'email.email' => 'The <b>E-Mail Address</b> be a valid email address.',
        	'emailexist.required' => 'The <b>E-Mail Address</b> is already exist.',
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

	public function validationEditForm($datas=[]) {
		$error = false;
		$rules = [
            'name' => 'required',
            'email' => 'required|email',
        ];

        $emailexist = User::where('email', '=', $datas['request']['email'])->where('id', '!=', $datas['request']['user_id'])->count();
        if($emailexist > 0){
			$rules['emailexist'] = 'required';
		}

        $messages = [
        	'name.required' => 'The <b>Name</b> field is required.',
        	'email.required' => 'The <b>E-Mail Address</b> field is required.',
        	'email.email' => 'The <b>E-Mail Address</b> be a valid email address.',
        	'emailexist.required' => 'The <b>E-Mail Address</b> is already exist.'
        ];

		$validator = \Validator::make($datas['request'], $rules, $messages);
		if ($validator->fails()) {
			$error = ['error'=>'1','success'=>'0','msg'=>'Warning : save user unsuccessfully!','validatormsg'=>$validator->messages()];
        }
		return $error;
	}

	public function validationChangePasswordForm($datas=[]) {
		$error = false;
		$rules = [
            'new_password' => 'required|min:6',
            'new_password_confirmation' => 'required|in:'.$datas['request']['new_password']
        ];

        $messages = [
        	'new_password.required' => 'The <b>New Password</b> field is required.',
        	'new_password.min' => 'The <b>New Password</b> must be at least 6 characters.',
        	'new_password_confirmation.required' => 'The <b>Confirm New Password</b> field is required.',
        	'new_password_confirmation.in' => 'The selected <b>Confirm New Password</b> is invalid.'
        ];

		$validator = \Validator::make($datas['request'], $rules, $messages);
		if ($validator->fails()) {
			$error = ['error'=>'1','success'=>'0','msg'=>'Warning : change password user unsuccessfully!','validatormsg'=>$validator->messages()];
        }
		return $error;
	}

}
