<?php namespace App\Http\Controllers\System\Users;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Usergroup;
use Illuminate\Http\Request;
use DB;

class UsergroupsController extends Controller {

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
		$this->permission = new Permission();
		$this->usergroup = new Usergroup();
		$this->data->title = 'User Groups';
		$this->data->breadcrumbs = [
			'home'	=> ['text' => 'Home', 'href' => url('home')],
			'user'	=> ['text' => 'User Groups', 'href' => url('user-groups')]
		];
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$this->data->actionlist = url('/user-groups/list');
		$this->data->add_user_group = url('/user-groups/create');
		return view('system.users.user_group.index', ['data' => $this->data]);
	}

	public function getList() {
		$this->data->edit_user_group = url('/user-groups/edit');
		$this->data->user_groups = $this->usergroup->paginate(10)->setPath(url('/user-groups'));
		return view('system.users.user_group.list', ['data' => $this->data]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getCreate()
	{
		$datas = [
			'action' => url('/user-groups/store'),
			'titlelist'	=> 'Add User Group'
		];
		echo $this->getUserGroupForm($datas);
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
		$validationError = $this->permission->validationForm(['request'=>$request]);
		if($validationError) {
			return \Response::json($validationError);
		}

		DB::beginTransaction();
		try {
			$userGruopDatas = [
				'name'			=> $request['name'],
				'permission'	=> ((isset($request['permission']))? json_encode($request['permission']):'')
			];
			$usergroup = $this->usergroup->create($userGruopDatas);
			DB::commit();
			$return = ['error'=>'0','success'=>'1','action'=>'create','msg'=>'Success : save user group successfully!'];
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
	public function getEdit($user_group_id)
	{
		$this->data->usergroup = $this->usergroup->getUsergroup($user_group_id);
		$datas = [
			'action' => url('/user-groups/update/'.$user_group_id),
			'titlelist'	=> 'Edit User Group',
			'user_group' => $this->data->usergroup
		];
		echo $this->getUserGroupForm($datas);
		exit();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function postUpdate($user_group_id)
	{
		$request = \Request::all();
		$validationError = $this->permission->validationForm(['request'=>$request]);
		if($validationError) {
			return \Response::json($validationError);
		}

		DB::beginTransaction();
		try {
			$userGruopDatas = [
				'name'			=> $request['name'],
				'permission'	=> ((isset($request['permission']))? json_encode($request['permission']):'')
			];
			$usergroup = $this->usergroup->where('user_group_id', '=', $user_group_id)->update($userGruopDatas);
			DB::commit();
			$return = ['error'=>'0','success'=>'1','action'=>'edit','msg'=>'Success : save user group successfully!'];
			return \Response::json($return);
		} catch (Exception $e) {
			DB::rollback();
			echo $e->getMessage();
			exit();
		}
		exit();
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

	public function getUserGroupForm($datas=[]) {

		$this->data->go_back = url('/user-groups');
		$this->data->permissions = $this->permission->orderBy('text', 'asc')->lists('text', 'text');
		// define entry
		$this->data->entry_name = 'User Group Name';
		$this->data->entry_access = 'Access Permission';

		// define text
		$this->data->text_select_all = 'Select All';
		$this->data->text_unselect_all = 'Unselect All';

		if(isset($datas['user_group'])) {
			$this->data->access = json_decode($datas['user_group']->permission)->access;
			$this->data->name = $datas['user_group']->name;
		}else {
			$this->data->access = [];
			$this->data->name = '';
		}

		$this->data->action = (($datas['action'])? $datas['action']:'');
		$this->data->titlelist = (($datas['titlelist'])? $datas['titlelist']:'');

		return view('system.users.user_group.form', ['data' => $this->data]);
	}

}
