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
		$this->data->actionlist = url('/users/list');
		$this->data->add_user = url('/users/create');
		return view('system.users.user.index', ['data' => $this->data]);
	}

	public function getList() {
		$request = \Request::all();
		$this->data->edit_user = url('/users/edit');
		$this->data->change_password_user = url('/users/change-password');

		// define data filter
		if (isset($request['sort'])) {
			$sort = $request['sort'];
		} else {
			$sort = 'created_at';
		}

		if (isset($request['order'])) {
			$order = $request['order'];
		} else {
			$order = 'desc';
		}

		// define filter data
		$filter_data = array(
			'sort'	=> $sort,
			'order'	=> $order
		);

		// define paginate url
		$paginate_url = [];
		if (isset($request['sort'])) {
			$paginate_url['sort'] = $request['sort'];
		}

		if (isset($request['order'])) {
			$paginate_url['order'] = $request['order'];
		}

		$this->data->users = $this->user->getUsers($filter_data)->paginate(2)->setPath(url('/users'))->appends($paginate_url);

		// define data
		$this->data->sort = $sort;
		$this->data->order = $order;

		// define column sort
		$url = '';
		if ($order == 'asc') {
			$url .= '&order=desc';
		} else {
			$url .= '&order=asc';
		}

		if (isset($request['page'])) {
			$url .= '&page='.$request['page'];
		}

		$this->data->sort_name = '?sort=name'.$url;
		$this->data->sort_status = '?sort=status'.$url;
		$this->data->sort_created_at = '?sort=created_at'.$url;

		// define column
		$this->data->column_name = "Username";
		$this->data->column_status = "Status";
		$this->data->column_date_added = "Date Added";
		$this->data->column_action = "Action";

		return view('system.users.user.list', ['data' => $this->data]);
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
	public function getEdit($user_id)
	{
		$this->data->user = $this->user->getUser($user_id);
		$datas = [
			'action' => url('/users/update/'.$user_id),
			'titlelist'	=> 'Edit User',
			'user_group' => $this->data->user
		];
		echo $this->getUserForm($datas);
		exit();
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
