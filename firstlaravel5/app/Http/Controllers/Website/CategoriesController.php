<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Language;
use App\Models\Layout;
use App\Models\UrlAlias;
use App\Http\Controllers\Common\FilemanagerController;
use App\Http\Controllers\ConfigController;

use Illuminate\Http\Request;
use DB;

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
		$this->config = new ConfigController();
		$this->data->web_title = 'Categories';
		$this->data->breadcrumbs = [
			'home'	=> ['text' => 'Home', 'href' => url('home')],
			'category'	=> ['text' => 'Categories', 'href' => url('categories')]
		];
		$this->data->dir_image = $this->config->dir_image;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		echo $this->data->dir_image;
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
			$sort = 'name';
		}

		if (isset($request['order'])) {
			$order = $request['order'];
		} else {
			$order = 'asc';
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

		$this->data->sort_name = '?sort=name'.$url;
		$this->data->sort_sort_order = '?sort=sort_order'.$url;

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
		$validationError = $this->category->validationForm(['request'=>$request]);
		if($validationError) {
			return \Response::json($validationError);
		}

		DB::beginTransaction();
		try {
			$categoryDatas = [
				'image'		=> $request['image'],
				'parent_id'	=> $request['parent_id'],
				'top'		=> ((isset($request['top']))? $request['top']:''),
				'column'	=> $request['column'],
				'sort_order'=> $request['sort_order'],
				'status'	=> $request['status']
			];
			$category = $this->category->create($categoryDatas);

			$category_descriptionDatas = [
				'category_id'		=> $category->id,
				'category_description_datas'	=> $request['category_description']
			];

			$category_description = $this->category->insertCategoryDescription($category_descriptionDatas);

			$url_aliasDatas = [
				'query'		=> 'category_id='.$category->id,
				'keyword'	=> $request['keyword']
			];
			$url_alias = $this->url_alias->create($url_aliasDatas);

			$category_to_layoutDatas['category_to_layout_datas'][] = [
				'category_id'		=> $category->id,
				'website_id'			=> '1',
				'layout_id'	=> $request['category_layout'][0]
			];

			$category_to_layout = $this->category->insertCategoryToLayout($category_to_layoutDatas);

			$category_pathDatas = [
				'category_id' => $category->id,
				'parent_id'	=> $request['parent_id']
			];
			$category_path = $this->category->insertCategoryPath($category_pathDatas);

			DB::commit();
			$return = ['error'=>'0','success'=>'1','action'=>'create','msg'=>'Success : save category successfully!'];
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
	public function getEdit($category_id)
	{
		$this->data->category = $this->category->getCategory($category_id);
		$this->data->category_descriptions = $this->category->getCategoryDescriptions($category_id);
		$this->data->category_to_layouts = $this->category->getCategoryToLayouts($category_id);
		$datas = [
			'action' => url('/categories/update/'.$category_id),
			'titlelist'	=> 'Edit category',
			'category' => $this->data->category,
			'category_descriptions' => $this->data->category_descriptions,
			'category_to_layouts' => $this->data->category_to_layouts
		];
		echo $this->getCategoryForm($datas);
		exit();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function postUpdate($category_id)
	{
		$request = \Request::all();
		$validationError = $this->category->validationForm(['request'=>$request]);
		if($validationError) {
			return \Response::json($validationError);
		}

		DB::beginTransaction();
		try {
			$categoryDatas = [
				'image'		=> $request['image'],
				'parent_id'	=> $request['parent_id'],
				'top'		=> ((isset($request['top']))? $request['top']:''),
				'column'	=> $request['column'],
				'sort_order'=> $request['sort_order'],
				'status'	=> $request['status']
			];
			$category = $this->category->where('category_id', '=', $category_id)->update($categoryDatas);

			$clear_category_description = $this->category->deletedCategoryDescription($category_id);
			$category_descriptionDatas = [
				'category_id'		=> $category_id,
				'category_description_datas'	=> $request['category_description']
			];

			$category_description = $this->category->insertCategoryDescription($category_descriptionDatas);

			$clear_url_alias = $this->category->deletedUrlAlias($category_id);
			$url_aliasDatas = [
				'query'		=> 'category_id='.$category_id,
				'keyword'	=> $request['keyword']
			];
			$url_alias = $this->url_alias->create($url_aliasDatas);

			$clear_category_to_layout = $this->category->deletedCategoryToLayout($category_id);
			$category_to_layoutDatas['category_to_layout_datas'][] = [
				'category_id'		=> $category_id,
				'website_id'			=> '1',
				'layout_id'	=> $request['category_layout'][0]
			];

			$category_to_layout = $this->category->insertCategoryToLayout($category_to_layoutDatas);

			$clear_category_path = $this->category->deletedCategoryPath($category_id);
			$category_pathDatas = [
				'category_id' => $category_id,
				'parent_id'	=> $request['parent_id']
			];
			$category_path = $this->category->insertCategoryPath($category_pathDatas);

			DB::commit();
			$return = ['error'=>'0','success'=>'1','action'=>'edit','msg'=>'Success : save category successfully!'];
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

	public function getCategoryForm($datas=[]) {
		$this->data->go_autocomplete = url('/categories/autocomplete');
		$this->data->go_back = url('/categories');
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
		$this->data->entry_name = 'Category Name';
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

		$this->data->text_none = '-- None --';

		if(isset($datas['category'])) {
			$this->data->parent_id = $datas['category']->parent_id;
			$this->data->path = $datas['category']->path;
			$this->data->image = $datas['category']->image;
			$this->data->top = $datas['category']->top;
			$this->data->column = $datas['category']->column;
			$this->data->sort_order = $datas['category']->sort_order;
			$this->data->category_status = $datas['category']->status;
			$this->data->keyword = $datas['category']->keyword;
		}else {
			$this->data->parent_id = '';
			$this->data->path = '';
			$this->data->image = '';
			$this->data->top = '';
			$this->data->column = '1';
			$this->data->sort_order = '0';
			$this->data->category_status = '1';
			$this->data->keyword = '';
		}

		if(isset($datas['category_descriptions'])) {
			foreach ($datas['category_descriptions'] as $description) {
				$this->data->category_description[$description->language_id]['name'] = $description->name;
				$this->data->category_description[$description->language_id]['description'] = $description->description;
				$this->data->category_description[$description->language_id]['meta_title'] = $description->meta_title;
				$this->data->category_description[$description->language_id]['meta_description'] = $description->meta_description;
				$this->data->category_description[$description->language_id]['meta_keyword'] = $description->meta_keyword;
			}
		}else {
			foreach ($this->data->languages as $language) {
				$this->data->category_description[$language->language_id]['name'] = '';
				$this->data->category_description[$language->language_id]['description'] = '';
				$this->data->category_description[$language->language_id]['meta_title'] = '';
				$this->data->category_description[$language->language_id]['meta_description'] = '';
				$this->data->category_description[$language->language_id]['meta_keyword'] = '';
			}
		}

		if(isset($datas['category_to_layouts'])) {
			$this->data->category_layout = $datas['category_to_layouts'];
		}else {
			$this->data->category_layout = [];
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

	public function getAutocomplete() {
		$request = \Request::all();
		$json = array();

		if (isset($request['filter_name'])) {

			$filter_data = array(
				'filter_name' => $request['filter_name'],
				'sort'        => 'name',
				'order'       => 'ASC',
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->category->getAutocompleteCategories($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'category_id' => $result->category_id,
					'name'        => strip_tags(html_entity_decode($result->name, ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		return json_encode($json);
	}

}
