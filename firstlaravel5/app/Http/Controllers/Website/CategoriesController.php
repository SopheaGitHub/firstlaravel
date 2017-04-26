<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Language;

use Illuminate\Http\Request;

class CategoriesController extends Controller {

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
		$this->category = new Category();
		$this->data->web_title = 'Categories';
		$this->language = new Language();
		$this->data->breadcrumbs = [
			'home'	=> ['text' => 'Home', 'href' => url('home')],
			'category'	=> ['text' => 'Categories', 'href' => url('categories')]
		];
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$this->data->actionlist = url('/categories/list');
		$this->data->add_category = url('/categories/create');
		return view('website.category.index', ['data' => $this->data]);
	}

	public function getList() {
		$request = \Request::all();
		$this->data->edit_category = url('/categories/edit');

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

		$this->data->categories = $this->category->getCategories($filter_data)->paginate(10)->setPath(url('/categories'))->appends($paginate_url);

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

		$this->data->sort_name = '?sort=cd.name'.$url;
		$this->data->sort_sort_order = '?sort=c.sort_order'.$url;

		// define column
		$this->data->column_name = "Category Name";
		$this->data->column_sort_order = "Sort Order";
		$this->data->column_action = "Action";

		return view('website.category.list', ['data' => $this->data]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getCreate()
	{
		$datas = [
			'action' => url('/categories/store'),
			'titlelist'	=> 'Add Category'
		];
		echo $this->getCategoryForm($datas);
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

	public function getCategoryForm($datas=[]) {
		$this->data->go_back = url('/categories');
		$this->data->languages = $this->language->getLanguages(['sort'=>'name', 'order'=>'asc'])->get();
		$this->data->status = [
			'1' => 'Enabled',
			'0'	=> 'Disabled'
		];

		// define tap
		$data['tab_general'] = 'General';
		$data['tab_data'] = 'Data';
		$data['tab_design'] = 'Design';

		// define entry
		$this->data->entry_name = 'Category Name';
		$this->data->entry_description = 'Description';
		$this->data->entry_meta_title = 'Meta Tag Title';
		$this->data->entry_meta_description = 'Meta Tag Description';
		$this->data->entry_meta_keyword = 'Meta Tag Keywords';

		$this->data->entry_keyword = 'SEO Keyword';
		$this->data->entry_bottom = 'Bottom';
		$this->data->entry_status = 'Status';
		$this->data->entry_sort_order = 'Sort Order';

		$this->data->entry_layout = 'Layout';

		// define input title
		$this->data->title_password = 'Must be enter at least 6 characters, Ex:@As!02';

		$this->data->action = (($datas['action'])? $datas['action']:'');
		$this->data->titlelist = (($datas['titlelist'])? $datas['titlelist']:'');

		return view('website.category.form', ['data' => $this->data]);
	}

}
