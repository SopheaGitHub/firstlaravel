<?php namespace App\Http\Controllers\Website\Design;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Layout;
use App\Models\Website;

use Illuminate\Http\Request;

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

	public function getLayoutForm($datas=[]) {
		$this->data->go_back = url('/banners');
		$this->data->websites = $this->website->getWebsites(['sort'=>'name', 'order'=>'asc'])->get()->toArray();

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

		return view('website.design.layout.form', ['data' => $this->data]);
	}

}
