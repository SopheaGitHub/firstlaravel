<?php namespace App\Http\Controllers\Website\Design;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Banner;

use Illuminate\Http\Request;

class BannersController extends Controller {

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
		$this->data->web_title = 'Banners';
		$this->data->breadcrumbs = [
			'home'	=> ['text' => 'Home', 'href' => url('home')],
			'banner'	=> ['text' => 'Banners', 'href' => url('banners')]
		];
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$this->data->actionlist = url('/banners/list');
		$this->data->add_user = url('/banners/create');
		return view('website.design.banner.index', ['data' => $this->data]);
	}

	public function getList() {
		$request = \Request::all();
		$this->data->edit_banner = url('/banners/edit');

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

		$this->data->banners = $this->banner->getBanners($filter_data)->paginate(10)->setPath(url('/banners'))->appends($paginate_url);

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

		// define column
		$this->data->column_name = "Banner Name";
		$this->data->column_status = "Status";
		$this->data->column_action = "Action";

		return view('website.design.banner.list', ['data' => $this->data]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getCreate()
	{
		//
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

}
