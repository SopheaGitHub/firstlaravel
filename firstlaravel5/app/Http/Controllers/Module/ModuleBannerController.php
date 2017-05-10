<?php namespace App\Http\Controllers\Module;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ModuleBannerController extends Controller {

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
		$this->data->web_title = 'Banner';
		$this->data->breadcrumbs = [
			'home'	=> ['text' => 'Home', 'href' => url('home')],
			'module'	=> ['text' => 'Modules', 'href' => url('modules')],
			'banner'	=> ['text' => 'Banner', 'href' => url('module/banner/create')]
		];
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
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
			'action' => url('/layouts/store'),
			'titlelist'	=> 'Add Banner Module'
		];
		echo $this->getModuleBannerForm($datas);
		exit();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
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
	public function getEdit($banner_id)
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

	public function getModuleBannerForm($datas=[]) {
		$this->data->go_back = url('/modules');

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

		// if(isset($datas['banner'])) {
		// 	$this->data->name = $datas['banner']->name;
		// 	$this->data->banner_status = $datas['banner']->status;
		// } else {
		// 	$this->data->name = '';
		// 	$this->data->banner_status = '1';
		// }

		if(isset($datas['layout_routes'])) {
			
		} else {
			$this->data->layout_routes = [];
		}

		if(isset($datas['layout_modules'])) {
			
		} else {
			$this->data->layout_modules = [];
		}

		$this->data->action = (($datas['action'])? $datas['action']:'');
		$this->data->titlelist = (($datas['titlelist'])? $datas['titlelist']:'');

		return view('module.banner.form', ['data' => $this->data]);
	}

}
