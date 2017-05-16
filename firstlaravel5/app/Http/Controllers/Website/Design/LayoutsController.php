<?php namespace App\Http\Controllers\Website\Design;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Layout;
use App\Models\Website;
use App\Models\Extension;

use Illuminate\Http\Request;
use DB;

class LayoutsController extends Controller {

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
		$this->layout = new Layout();
		$this->website = new Website();
		$this->extension = new Extension();
		$this->data->web_title = 'Layouts';
		$this->data->breadcrumbs = [
			'home'	=> ['text' => 'Home', 'href' => url('home')],
			'layout'	=> ['text' => 'Layouts', 'href' => url('layouts')]
		];
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$this->data->actionlist = url('/layouts/list');
		$this->data->add_layout = url('/layouts/create');
		return view('website.design.layout.index', ['data' => $this->data]);
	}

	public function getList() {
		$request = \Request::all();
		$this->data->edit_layout = url('/layouts/edit');
		$this->data->action_delete = url('/layouts/destroy');

		// define data filter
		if (isset($request['sort'])) {
			$sort = $request['sort'];
		} else {
			$sort = 'name';
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

		$this->data->layouts = $this->layout->getLayouts($filter_data)->paginate(10)->setPath(url('/layouts'))->appends($paginate_url);

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

		// define column
		$this->data->column_name = "Layout Name";
		$this->data->column_action = "Action";

		return view('website.design.layout.list', ['data' => $this->data]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getCreate()
	{
		$datas = [
			'action' => url('/layouts/store'),
			'titlelist'	=> 'Add Layout'
		];
		echo $this->getLayoutForm($datas);
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
		$validationError = $this->layout->validationForm(['request'=>$request]);
		if($validationError) {
			return \Response::json($validationError);
		}

		DB::beginTransaction();
		try {
			$layoutDatas = [
				'name'			=> $request['name']
			];
			$layout = $this->layout->create($layoutDatas);

			$layout_routeDatas = [
				'layout_id'		=> $layout->id,
				'layout_route' 	=> ((isset($request['layout_route']))? $request['layout_route']:[])
			];

			$layout_route = $this->layout->insertLayoutRoute($layout_routeDatas);

			$layout_moduleDatas = [
				'layout_id'		=> $layout->id,
				'layout_module' 	=> ((isset($request['layout_module']))? $request['layout_module']:[])
			];

			$layout_route = $this->layout->insertLayoutModule($layout_moduleDatas);

			DB::commit();
			$return = ['error'=>'0','success'=>'1','action'=>'create','msg'=>'Success : save layout successfully!', 'post'=>$request];
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
	public function getEdit($layout_id)
	{
		$this->data->layout = $this->layout->getLayout($layout_id);
		$this->data->layout_routes = $this->layout->getLayoutRoutes($layout_id);
		$this->data->layout_modules = $this->layout->getLayoutModules($layout_id);
		
		$datas = [
			'action' => url('/layouts/update/'.$layout_id),
			'titlelist'	=> 'Edit Layout',
			'layout' => $this->data->layout,
			'layout_routes'	=> $this->data->layout_routes,
			'layout_modules' => $this->data->layout_modules
		];
		echo $this->getLayoutForm($datas);
		exit();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function postUpdate($layout_id)
	{
		$request = \Request::all();
		$validationError = $this->layout->validationForm(['request'=>$request]);
		if($validationError) {
			return \Response::json($validationError);
		}

		DB::beginTransaction();
		try {

			$layoutDatas = [
				'name'			=> $request['name']
			];
			$layout = $this->layout->where('layout_id', '=', $layout_id)->update($layoutDatas);

			$clear_layout_route = $this->layout->deletedLayoutRoute($layout_id);
			$layout_routeDatas = [
				'layout_id'		=> $layout_id,
				'layout_route' 	=> ((isset($request['layout_route']))? $request['layout_route']:[])
			];

			$layout_route = $this->layout->insertLayoutRoute($layout_routeDatas);

			$clear_layout_module = $this->layout->deletedLayoutModule($layout_id);
			$layout_moduleDatas = [
				'layout_id'		=> $layout_id,
				'layout_module' 	=> ((isset($request['layout_module']))? $request['layout_module']:[])
			];

			$layout_route = $this->layout->insertLayoutModule($layout_moduleDatas);

			DB::commit();
			$return = ['error'=>'0','success'=>'1','action'=>'edit','msg'=>'Success : save layout successfully!'];
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
	public function postDestroy()
	{
		$request = \Request::all();
		if(isset($request['selected'])) {
			DB::beginTransaction();
			try {
				$arrayLayoutID = $request['selected'];
				$this->layout->destroyLayouts($arrayLayoutID);
				DB::commit();
				return Redirect('/layouts')->with('success', 'Success: delete layout successfully!');
			} catch (Exception $e) {
				DB::rollback();
				return Redirect('/layouts')->with('error', 'Error: delete layout successfully!'.$e->getMessage());
				exit();
			}
		}else {
			return Redirect('/layouts')->with('warning', 'Warning: there is no layout selected!');
		}
		exit();		
	}

	public function getLayoutForm($datas=[]) {
		$this->data->go_back = url('/layouts');
		$this->data->websites = $this->website->getWebsites(['sort'=>'name', 'order'=>'asc'])->get()->toArray();
		$this->data->extensions = [];
		// Get a list of installed modules
		$extensions = $this->extension->getInstalled('module');

		// Add all the modules which have multiple settings for each module
		foreach ($extensions as $code) {

			$module_data = [];

			$modules = $this->extension->getModulesByCode($code);

			foreach ($modules as $module) {
				$module_data[] = [
					'name' => ucfirst($code) . ' &gt; ' . $module['name'],
					'code' => $code . '.' .  $module['module_id']
				];
			}

			if ($module_data) {
				$this->data->extensions[] = [
					'name'   => ucfirst($code),
					'code'   => $code,
					'module' => $module_data
				];
			}
		}

		// define entry
		$this->data->entry_name = 'Layout Name';
		$this->data->entry_module = 'Module';
		$this->data->entry_website = 'Website';
		$this->data->entry_route = 'Route';
		$this->data->entry_position = 'Position';
		$this->data->entry_sort_order = 'Sort Order';

		$this->data->button_route_add = 'Add Route';
		$this->data->button_module_add = 'Add Module';
		$this->data->button_remove = 'Remove';

		$this->data->text_default = 'Default';
		$this->data->text_content_top = 'Content Top';
		$this->data->text_content_bottom = 'Content Bottom';
		$this->data->text_column_left = 'Content Left';
		$this->data->text_column_right = 'Content Right';

		if(isset($datas['layout'])) {
			$this->data->name = $datas['layout']->name;
		} else {
			$this->data->name = '';
		}

		if(isset($datas['layout_routes'])) {
			$this->data->layout_routes = $datas['layout_routes'];
		} else {
			$this->data->layout_routes = [];
		}

		if(isset($datas['layout_modules'])) {
			$this->data->layout_modules = $datas['layout_modules'];
		} else {
			$this->data->layout_modules = [];
		}
		

		$this->data->action = (($datas['action'])? $datas['action']:'');
		$this->data->titlelist = (($datas['titlelist'])? $datas['titlelist']:'');

		return view('website.design.layout.form', ['data' => $this->data]);
	}

}
