<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\Language;
use App\Models\Layout;
use App\Models\UrlAlias;
use App\Http\Controllers\Common\FilemanagerController;
use App\Http\Controllers\ConfigController;

use Illuminate\Http\Request;
use DB;

class DocumentationController extends Controller {

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
		$this->documentation = new Documentation();
		$this->language = new Language();
		$this->layout = new Layout();
		$this->url_alias = new UrlAlias();
		$this->filemanager = new FilemanagerController();
		$this->config = new ConfigController();
		$this->data->web_title = 'Documentation';
		$this->data->breadcrumbs = [
			'home'	=> ['text' => 'Home', 'href' => url('home')],
			'documentation'	=> ['text' => 'Documentation', 'href' => url('documentation')]
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
		$this->data->actionlist = url('/documentation/list');
		$this->data->add_documentation = url('/documentation/create');
		return view('website.documentation.index', ['data' => $this->data]);
	}

	public function getList() {
		$request = \Request::all();
		$this->data->edit_documentation = url('/documentation/edit');
		$this->data->action_delete = url('/documentation/destroy');

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

		$this->data->documentation = $this->documentation->getAllDocumentation($filter_data)->paginate(10)->setPath(url('/documentation'))->appends($paginate_url);

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
		$this->data->column_name = "Documentation Name";
		$this->data->column_sort_order = "Sort Order";
		$this->data->column_action = "Action";

		return view('website.documentation.list', ['data' => $this->data]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getCreate()
	{
		$datas = [
			'action' => url('/documentation/store'),
			'titlelist'	=> 'Add Documentation'
		];
		echo $this->getDocumentationForm($datas);
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
		$validationError = $this->documentation->validationForm(['request'=>$request]);
		if($validationError) {
			return \Response::json($validationError);
		}

		DB::beginTransaction();
		try {
			$documentationDatas = [
				'image'		=> $request['image'],
				'parent_id'	=> $request['parent_id'],
				'top'		=> ((isset($request['top']))? $request['top']:''),
				'column'	=> $request['column'],
				'sort_order'=> $request['sort_order'],
				'status'	=> $request['status']
			];
			$documentation = $this->documentation->create($documentationDatas);

			$documentation_descriptionDatas = [
				'documentation_id'		=> $documentation->id,
				'documentation_description_datas'	=> $request['documentation_description']
			];

			$documentation_description = $this->documentation->insertDocumentationDescription($documentation_descriptionDatas);

			$url_aliasDatas = [
				'query'		=> 'documentation_id='.$documentation->id,
				'keyword'	=> $request['keyword']
			];
			$url_alias = $this->url_alias->create($url_aliasDatas);

			$documentation_to_layoutDatas['documentation_to_layout_datas'][] = [
				'documentation_id'		=> $documentation->id,
				'website_id'			=> '1',
				'layout_id'	=> $request['documentation_layout'][0]
			];

			$documentation_to_layout = $this->documentation->insertDocumentationToLayout($documentation_to_layoutDatas);

			$documentation_pathDatas = [
				'documentation_id' => $documentation->id,
				'parent_id'	=> $request['parent_id']
			];
			$documentation_path = $this->documentation->insertDocumentationPath($documentation_pathDatas);

			DB::commit();
			$return = ['error'=>'0','success'=>'1','action'=>'create','msg'=>'Success : save documentation successfully!'];
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
	public function getEdit($documentation_id)
	{
		$this->data->documentation = $this->documentation->getDocumentation($documentation_id);
		$this->data->documentation_descriptions = $this->documentation->getDocumentationDescriptions($documentation_id);
		$this->data->documentation_to_layouts = $this->documentation->getDocumentationToLayouts($documentation_id);
		$datas = [
			'action' => url('/documentation/update/'.$documentation_id),
			'titlelist'	=> 'Edit Documentation',
			'documentation' => $this->data->documentation,
			'documentation_descriptions' => $this->data->documentation_descriptions,
			'documentation_to_layouts' => $this->data->documentation_to_layouts
		];
		echo $this->getDocumentationForm($datas);
		exit();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function postUpdate($documentation_id)
	{
		$request = \Request::all();
		$validationError = $this->documentation->validationForm(['request'=>$request]);
		if($validationError) {
			return \Response::json($validationError);
		}

		DB::beginTransaction();
		try {
			$documentationDatas = [
				'image'		=> $request['image'],
				'parent_id'	=> $request['parent_id'],
				'top'		=> ((isset($request['top']))? $request['top']:''),
				'column'	=> $request['column'],
				'sort_order'=> $request['sort_order'],
				'status'	=> $request['status']
			];
			$documentation = $this->documentation->where('documentation_id', '=', $documentation_id)->update($documentationDatas);

			$clear_documentation_description = $this->documentation->deletedDocumentationDescription($documentation_id);
			$documentation_descriptionDatas = [
				'documentation_id'		=> $documentation_id,
				'documentation_description_datas'	=> $request['documentation_description']
			];

			$documentation_description = $this->documentation->insertDocumentationDescription($documentation_descriptionDatas);

			$clear_url_alias = $this->documentation->deletedUrlAlias($documentation_id);
			$url_aliasDatas = [
				'query'		=> 'documentation_id='.$documentation_id,
				'keyword'	=> $request['keyword']
			];
			$url_alias = $this->url_alias->create($url_aliasDatas);

			$clear_documentation_to_layout = $this->documentation->deletedDocumentationToLayout($documentation_id);
			$documentation_to_layoutDatas['documentation_to_layout_datas'][] = [
				'documentation_id'		=> $documentation_id,
				'website_id'			=> '1',
				'layout_id'	=> $request['documentation_layout'][0]
			];

			$documentation_to_layout = $this->documentation->insertDocumentationToLayout($documentation_to_layoutDatas);

			$clear_documentation_path = $this->documentation->deletedDocumentationPath($documentation_id);
			$documentation_pathDatas = [
				'documentation_id' => $documentation_id,
				'parent_id'	=> $request['parent_id']
			];
			$documentation_path = $this->documentation->insertDocumentationPath($documentation_pathDatas);

			DB::commit();
			$return = ['error'=>'0','success'=>'1','action'=>'edit','msg'=>'Success : save documentation successfully!'];
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
	 * @param  int $id
	 * @return Response
	 */
	public function postDestroy()
	{
		$request = \Request::all();
		if(isset($request['selected'])) {
			DB::beginTransaction();
			try {
				$arraydocumentationID = $request['selected'];
				$this->documentation->destroyDocumentation($arraydocumentationID);
				DB::commit();
				return Redirect('/documentation')->with('success', 'Success: delete documentation successfully!');
			} catch (Exception $e) {
				DB::rollback();
				return Redirect('/documentation')->with('error', 'Error: delete documentation successfully!'.$e->getMessage());
				exit();
			}
		}else {
			return Redirect('/documentation')->with('warning', 'Warning: there is no documentation selected!');
		}
		exit();		
	}

	public function getDocumentationForm($datas=[]) {
		$this->data->go_autocomplete = url('/documentation/autocomplete');
		$this->data->go_back = url('/documentation');
		$this->data->languages = $this->language->getLanguages(['sort'=>'name', 'order'=>'asc'])->get();
		$this->data->layouts = $this->layout->orderBy('name', 'asc')->lists('name', 'layout_id');
		$this->data->status = $this->config->status;

		// define tap
		$data['tab_general'] = 'General';
		$data['tab_data'] = 'Data';
		$data['tab_design'] = 'Design';

		// define entry
		$this->data->entry_name = 'Documentation Name';
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
		$this->data->title_top = 'Display in the top menu bar. Only works for the top parent documentation.';
		$this->data->title_column = 'Number of columns to use for the bottom 3 documentation. Only works for the top parent documentation.';

		$this->data->text_none = '-- None --';

		if(isset($datas['documentation'])) {
			$this->data->parent_id = $datas['documentation']->parent_id;
			$this->data->path = $datas['documentation']->path;
			$this->data->image = $datas['documentation']->image;
			$this->data->top = $datas['documentation']->top;
			$this->data->column = $datas['documentation']->column;
			$this->data->sort_order = $datas['documentation']->sort_order;
			$this->data->documentation_status = $datas['documentation']->status;
			$this->data->keyword = $datas['documentation']->keyword;
		}else {
			$this->data->parent_id = '';
			$this->data->path = '';
			$this->data->image = '';
			$this->data->top = '';
			$this->data->column = '1';
			$this->data->sort_order = '0';
			$this->data->documentation_status = '1';
			$this->data->keyword = '';
		}

		if(isset($datas['documentation_descriptions'])) {
			foreach ($datas['documentation_descriptions'] as $description) {
				$this->data->documentation_description[$description->language_id]['name'] = $description->name;
				$this->data->documentation_description[$description->language_id]['description'] = $description->description;
				$this->data->documentation_description[$description->language_id]['meta_title'] = $description->meta_title;
				$this->data->documentation_description[$description->language_id]['meta_description'] = $description->meta_description;
				$this->data->documentation_description[$description->language_id]['meta_keyword'] = $description->meta_keyword;
			}
		}else {
			foreach ($this->data->languages as $language) {
				$this->data->documentation_description[$language->language_id]['name'] = '';
				$this->data->documentation_description[$language->language_id]['description'] = '';
				$this->data->documentation_description[$language->language_id]['meta_title'] = '';
				$this->data->documentation_description[$language->language_id]['meta_description'] = '';
				$this->data->documentation_description[$language->language_id]['meta_keyword'] = '';
			}
		}

		if(isset($datas['documentation_to_layouts'])) {
			$this->data->documentation_layout = $datas['documentation_to_layouts'];
		}else {
			$this->data->documentation_layout = [];
		}

		if ($this->data->image && is_file($this->data->dir_image . $this->data->image)) {
			$this->data->thumb = $this->filemanager->resize($this->data->image, 100, 100);
		} else {
			$this->data->thumb = $this->filemanager->resize('no_image.png', 100, 100);
		}

		$this->data->placeholder = $this->filemanager->resize('no_image.png', 100, 100);

		$this->data->action = (($datas['action'])? $datas['action']:'');
		$this->data->titlelist = (($datas['titlelist'])? $datas['titlelist']:'');

		return view('website.documentation.form', ['data' => $this->data]);
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

			$results = $this->documentation->getAutocompleteDocumentation($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'documentation_id' => $result->documentation_id,
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
