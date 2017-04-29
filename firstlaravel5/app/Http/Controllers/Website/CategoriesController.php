<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Language;
use App\Models\Layout;
use App\Models\UrlAlias;
use App\Http\Controllers\Common\FilemanagerController;

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
		$this->language = new Language();
		$this->layout = new Layout();
		$this->url_alias = new UrlAlias();
		$this->filemanager = new FilemanagerController();
		$this->data->web_title = 'Categories';
		$this->data->breadcrumbs = [
			'home'	=> ['text' => 'Home', 'href' => url('home')],
			'category'	=> ['text' => 'Categories', 'href' => url('categories')]
		];
		$this->data->dir_image = 'C:/xampp/htdocs/projects/firstlaravel/firstlaravel5/public/images/';
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
	public function postStore()
	{
		$request = \Request::all();
		// $validationError = $this->information->validationForm(['request'=>$request]);
		// if($validationError) {
		// 	return \Response::json($validationError);
		// }

		// DB::beginTransaction();
		// try {
		// 	$informationDatas = [
		// 		'bottom'		=> ((isset($request['bottom']))? $request['bottom']:''),
		// 		'sort_order'	=> $request['sort_order'],
		// 		'status'		=> $request['status']
		// 	];
		// 	$information = $this->information->create($informationDatas);

		// 	$information_descriptionDatas = [
		// 		'information_id'		=> $information->id,
		// 		'information_description_datas'	=> $request['information_description']
		// 	];

		// 	$information_description = $this->information->insertInformationDescription($information_descriptionDatas);

		// 	$url_aliasDatas = [
		// 		'query'		=> 'information_id='.$information->id,
		// 		'keyword'	=> $request['keyword']
		// 	];
		// 	$url_alias = $this->url_alias->create($url_aliasDatas);

		// 	$information_to_layoutDatas['information_to_layout_datas'][] = [
		// 		'information_id'		=> $information->id,
		// 		'website_id'			=> '1',
		// 		'layout_id'	=> $request['information_layout'][0]
		// 	];

		// 	$information_to_layout = $this->information->insertInformationToLayout($information_to_layoutDatas);
		// 	DB::commit();
			$return = ['error'=>'0','success'=>'1','action'=>'create','msg'=>'Success : save information successfully!', 'post'=>$request];
			return \Response::json($return);
		// } catch (Exception $e) {
		// 	DB::rollback();
		// 	echo $e->getMessage();
		// 	exit();
		// }
		// exit();
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
		$this->data->go_back = url('/information');
		$this->data->languages = $this->language->getLanguages(['sort'=>'name', 'order'=>'asc'])->get();
		$this->data->layouts = $this->layout->orderBy('name', 'asc')->lists('name', 'layout_id');
		$this->data->status = [
			'1' => 'Enabled',
			'0'	=> 'Disabled'
		];

		// define tap
		$data['tab_general'] = 'General';
		$data['tab_data'] = 'Data';
		$data['tab_design'] = 'Design';

		// define entry
		$this->data->entry_title = 'Category Title';
		$this->data->entry_description = 'Description';
		$this->data->entry_meta_title = 'Meta Tag Title';
		$this->data->entry_meta_description = 'Meta Tag Description';
		$this->data->entry_meta_keyword = 'Meta Tag Keywords';

		$this->data->entry_parent = 'Parent';
		$this->data->entry_keyword = 'SEO Keyword';
		$this->data->entry_image = 'Image';
		$this->data->entry_top = 'Top';
		$this->data->entry_column = 'Column';
		$this->data->entry_status = 'Status';
		$this->data->entry_sort_order = 'Sort Order';

		$this->data->entry_layout = 'Layout';

		// define input title
		$this->data->title_keyword = 'Do not use spaces, instead replace spaces with - and make sure the keyword is globally unique.';
		$this->data->title_top = 'Display in the top menu bar. Only works for the top parent categories.';
		$this->data->title_column = 'Number of columns to use for the bottom 3 categories. Only works for the top parent categories.';

		if(isset($datas['information'])) {
			$this->data->parent_id = $datas['information']->parent_id;
			$this->data->path = $datas['information']->path;
			$this->data->image = $datas['information']->image;
			$this->data->top = $datas['information']->top;
			$this->data->column = $datas['information']->column;
			$this->data->sort_order = $datas['information']->sort_order;
			$this->data->information_status = $datas['information']->status;
			$this->data->keyword = $datas['information']->keyword;
		}else {
			$this->data->parent_id = '0';
			$this->data->path = '';
			$this->data->image = '';
			$this->data->top = '';
			$this->data->column = '1';
			$this->data->sort_order = '';
			$this->data->information_status = '1';
			$this->data->keyword = '';
		}

		if(isset($datas['information_descriptions'])) {
			foreach ($datas['information_descriptions'] as $description) {
				$this->data->information_description[$description->language_id]['title'] = $description->title;
				$this->data->information_description[$description->language_id]['description'] = $description->description;
				$this->data->information_description[$description->language_id]['meta_title'] = $description->meta_title;
				$this->data->information_description[$description->language_id]['meta_description'] = $description->meta_description;
				$this->data->information_description[$description->language_id]['meta_keyword'] = $description->meta_keyword;
			}
		}else {
			foreach ($this->data->languages as $language) {
				$this->data->information_description[$language->language_id]['title'] = '';
				$this->data->information_description[$language->language_id]['description'] = '';
				$this->data->information_description[$language->language_id]['meta_title'] = '';
				$this->data->information_description[$language->language_id]['meta_description'] = '';
				$this->data->information_description[$language->language_id]['meta_keyword'] = '';
			}
		}

		if(isset($datas['information_to_layouts'])) {
			$this->data->information_layout = $datas['information_to_layouts'];
		}else {
			$this->data->information_layout = [];
		}

		if ($this->data->image && is_file($this->data->dir_image . $this->data->image)) {
			$this->data->thumb = $this->filemanager->resize($this->data->image, 100, 100);
		} else {
			$this->data->thumb = $this->filemanager->resize('no_image.png', 100, 100);
		}

		$this->data->placeholder = $this->filemanager->resize('no_image.png', 100, 100);

		$this->data->action = (($datas['action'])? $datas['action']:'');
		$this->data->titlelist = (($datas['titlelist'])? $datas['titlelist']:'');

		return view('website.category.form', ['data' => $this->data]);
	}

}
