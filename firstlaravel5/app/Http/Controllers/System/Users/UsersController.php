<?php namespace App\Http\Controllers\System\Users;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Usergroup;
use Illuminate\Http\Request;
use DB;
use Hash;

class UsersController extends Controller {

	protected $data = null;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');

		$this->data = new \stdClass();
		$this->user = new User();
		$this->usergroup = new Usergroup();
		$this->data->title = 'Users';
		$this->data->breadcrumbs = [
			'home'	=> ['text' => 'Home', 'href' => url('home')],
			'user'	=> ['text' => 'Users', 'href' => url('users')]
		];
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$this->data->add_user = url('/users/create');
		return view('system.users.user.index', ['data' => $this->data]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getCreate()
	{
		$datas = [
			'action' => url('/users/store'),
			'titlelist'	=> 'Add User'
		];
		echo $this->getUserForm($datas);
		exit();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function postStore()
	{
		$request = \Request::all();
		$validationError = $this->user->validationForm(['request'=>$request]);
		if($validationError) {
			return \Response::json($validationError);
		}

		DB::beginTransaction();
		try {
			$userDatas = [
				'name'			=> $request['name'],
				'user_group_id'	=> $request['user_group_id'],
				'email'			=> $request['email'],
				'password'		=> Hash::make($request['password']),
				'status'		=> $request['status']
			];
			$user = $this->user->create($userDatas);
			DB::commit();
			$return = ['error'=>'0','success'=>'1','action'=>'create','msg'=>'Success : save user successfully!'];
			return \Response::json($return);
		} catch (Exception $e) {
			DB::rollback();
			echo $e->getMessage();
			exit();
		}
		exit();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	public function getUserForm($datas=[]) {
		$this->data->go_back = url('/users');
		$this->data->usergroups = $this->usergroup->orderBy('name', 'asc')->lists('name', 'user_group_id');
		$this->data->status = [
			'1' => 'Enabled',
			'0'	=> 'Disabled'
		];

		// define entry
		$this->data->entry_name = 'Name';
		$this->data->entry_email_address = 'E-Mail Address';
		$this->data->entry_password = 'Password';
		$this->data->entry_confirm_password = 'Confirm Password';
		$this->data->entry_user_group_name = 'User Group Name';
		$this->data->entry_status = 'Status';

		$this->data->action = (($datas['action'])? $datas['action']:'');
		$this->data->titlelist = (($datas['titlelist'])? $datas['titlelist']:'');

		return view('system.users.user.form', ['data' => $this->data]);
	}

}
