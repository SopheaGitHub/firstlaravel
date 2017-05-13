<?php namespace App\Http\Controllers\Module;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Module;
use App\Http\Controllers\ConfigController;

use Illuminate\Http\Request;
use DB;

class ModuleCarouselController extends Controller {

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
		$this->banner = new Banner();
		$this->module = new Module();
		$this->config = new ConfigController();
		$this->data->web_title = 'Carousel';
		$this->data->breadcrumbs = [
			'home'	=> ['text' => 'Home', 'href' => url('home')],
			'module'=> ['text' => 'Modules', 'href' => url('modules')],
			'carousel'=> ['text' => 'Carousel', 'href' => url('module/carousel/create')]
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
			'action' => url('/module/carousel/store'),
			'titlelist'	=> 'Add Carousel Module'
		];
		echo $this->getModuleCarouselForm($datas);
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
				'code'		=> 'carousel',
				'setting'	=> json_encode(\Request::except('_token')),
			];
			$module = $this->module->create($moduleDatas);

			DB::commit();
			$return = ['error'=>'0','success'=>'1','action'=>'create','msg'=>'Success : save carousel module successfully!'];
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
			'action' => url('/module/carousel/update/'.$module_id),
			'titlelist'	=> 'Edit Carousel Module',
			'module' => $this->data->module
		];
		echo $this->getModuleCarouselForm($datas);
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
			$return = ['error'=>'0','success'=>'1','action'=>'edit','msg'=>'Success : save carousel module successfully!'];
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

	public function getModuleCarouselForm($datas=[]) {
		$this->data->go_back = url('/modules');
		$this->data->banners = $this->banner->getBanners(['sort'=>'name', 'order'=>'asc'])->get()->toArray();

		// define entry
		$this->data->entry_name = 'Module Name';
		$this->data->entry_banner = 'Banner';
		$this->data->entry_width = 'Width';
		$this->data->entry_height = 'Height';
		$this->data->entry_status = 'Status';

		if(isset($datas['module'])) {
			$this->data->name = $datas['module']['name'];
			$this->data->banner_id = $datas['module']['banner_id'];
			$this->data->width = $datas['module']['width'];
			$this->data->height = $datas['module']['height'];
			$this->data->module_status = $datas['module']['status'];
		}else {
			$this->data->name = '';
			$this->data->banner_id = '';
			$this->data->width = 130;
			$this->data->height = 100;
			$this->data->module_status = 0;
		}

		$this->data->status = $this->config->status;

		$this->data->action = (($datas['action'])? $datas['action']:'');
		$this->data->titlelist = (($datas['titlelist'])? $datas['titlelist']:'');

		return view('module.carousel.form', ['data' => $this->data]);
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
			$error = ['error'=>'1','success'=>'0','msg'=>'Warning : save carousel module unsuccessfully!','validatormsg'=>$validator->messages()];
        }
		return $error;
	}

}
