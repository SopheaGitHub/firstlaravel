<?php namespace App\Http\Controllers\Module;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Post;
use App\Http\Controllers\ConfigController;

use Illuminate\Http\Request;
use DB;

class ModuleFeaturedController extends Controller {

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
		$this->module = new Module();
		$this->post = new Post();
		$this->config = new ConfigController();
		$this->data->web_title = 'Featured';
		$this->data->breadcrumbs = [
			'home'	=> ['text' => 'Home', 'href' => url('home')],
			'module'=> ['text' => 'Modules', 'href' => url('modules')],
			'featured'=> ['text' => 'Featured', 'href' => url('module/featured/create')]
		];
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getCreate()
	{
		$datas = [
			'action' => url('/module/featured/store'),
			'titlelist'	=> 'Add Featured Module'
		];
		echo $this->getModuleFeaturedForm($datas);
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
		$validationError = $this->validationForm(['request'=>$request]);
		if($validationError) {
			return \Response::json($validationError);
		}

		DB::beginTransaction();
		try {
			$moduleDatas = [
				'name'		=> $request['name'],
				'code'		=> 'featured',
				'setting'	=> json_encode(\Request::except('_token')),
			];
			$module = $this->module->create($moduleDatas);

			DB::commit();
			$return = ['error'=>'0','success'=>'1','action'=>'create','msg'=>'Success : save featured module successfully!'];
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
	public function getEdit($module_id)
	{
		$this->data->module = $this->module->getModule($module_id);	
		$datas = [
			'action' => url('/module/featured/update/'.$module_id),
			'titlelist'	=> 'Edit Featured Module',
			'module' => $this->data->module
		];
		echo $this->getModuleFeaturedForm($datas);
		exit();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function postUpdate($module_id)
	{
		$request = \Request::all();
		$validationError = $this->validationForm(['request'=>$request]);
		if($validationError) {
			return \Response::json($validationError);
		}

		DB::beginTransaction();
		try {

			$moduleDatas = [
				'name'		=> $request['name'],
				'setting'	=> json_encode(\Request::except('_token')),
			];
			$module = $this->module->where('module_id', '=', $module_id)->update($moduleDatas);

			DB::commit();
			$return = ['error'=>'0','success'=>'1','action'=>'edit','msg'=>'Success : save featured module successfully!'];
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

	public function getModuleFeaturedForm($datas=[]) {
		$this->data->go_back = url('/modules');
		$this->data->go_autocomplete = url('/posts/autocomplete');

		// define entry
		$this->data->entry_name = 'Module Name';
		$this->data->entry_post = 'Posts';
		$this->data->entry_limit = 'Limit';
		$this->data->entry_width = 'Width';
		$this->data->entry_height = 'Height';
		$this->data->entry_status = 'Status';

		if(isset($datas['module'])) {
			$this->data->name = $datas['module']['name'];
			$posts = $datas['module']['post'];

			foreach ($posts as $post_id) {
				$post_info = $this->post->getPost($post_id);

				if ($post_info) {
					$this->data->posts[] = [
						'post_id' => $post_info->post_id,
						'title'   => $post_info->title
					];
				}
			}

			$this->data->limit = $datas['module']['limit'];
			$this->data->width = $datas['module']['width'];
			$this->data->height = $datas['module']['height'];
			$this->data->module_status = $datas['module']['status'];
		}else {
			$this->data->name = '';
			$this->data->posts = [];
			$this->data->limit = 5;
			$this->data->width = 200;
			$this->data->height = 200;
			$this->data->module_status = 0;
		}

		$this->data->status = $this->config->status;

		$this->data->action = (($datas['action'])? $datas['action']:'');
		$this->data->titlelist = (($datas['titlelist'])? $datas['titlelist']:'');

		return view('module.featured.form', ['data' => $this->data]);
	}

	public function validationForm($datas=[]) {
		$error = false;
		$rules = [
            'name'	=> 'required|between:3,64',
            'width'	=> 'required|integer',
            'height'=> 'required|integer'
        ];

        $messages = [
        	'name.required' => 'The <b>Module Name</b> field is required.',
        	'name.between' => 'The <b>Module Name</b> must be between 3 and 64 characters.',
        	'width.required' => 'The <b>Width</b> field is required.',
        	'width.integer' => 'The <b>Width</b> must be an integer.',
        	'height.required' => 'The <b>Height</b> field is required.',
        	'height.integer' => 'The <b>Height</b> must be an integer.'
        ];

		$validator = \Validator::make($datas['request'], $rules, $messages);
		if ($validator->fails()) {
			$error = ['error'=>'1','success'=>'0','msg'=>'Warning : save featured module unsuccessfully!','validatormsg'=>$validator->messages()];
        }
		return $error;
	}

}
