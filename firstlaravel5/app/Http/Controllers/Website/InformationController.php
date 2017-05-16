<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Information;
use App\Models\Language;
use App\Models\Layout;
use App\Models\UrlAlias;
use App\Http\Controllers\ConfigController;

use Illuminate\Http\Request;
use DB;

class InformationController extends Controller {

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
		$this->information = new Information();
		$this->language = new Language();
		$this->layout = new Layout();
		$this->url_alias = new UrlAlias();
		$this->config = new ConfigController();
		$this->data->web_title = 'Information';
		$this->data->breadcrumbs = [
			'home'	=> ['text' => 'Home', 'href' => url('home')],
			'information'	=> ['text' => 'Information', 'href' => url('information')]
		];
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$this->data->actionlist = url('/information/list');
		$this->data->add_information = url('/information/create');
		return view('website.information.index', ['data' => $this->data]);
	}

	public function getList() {
		$request = \Request::all();
		$this->data->edit_information = url('/information/edit');
		$this->data->action_delete = url('/information/destroy');

		// define data filter
		if (isset($request['sort'])) {
			$sort = $request['sort'];
		} else {
			$sort = 'title';
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

		$this->data->all_information = $this->information->getAllInformation($filter_data)->paginate(10)->setPath(url('/information'))->appends($paginate_url);

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

		$this->data->sort_title = '?sort=title'.$url;
		$this->data->sort_sort_order = '?sort=sort_order'.$url;

		// define column
		$this->data->column_title = "Information Title";
		$this->data->column_sort_order = "Sort Order";
		$this->data->column_action = "Action";

		return view('website.information.list', ['data' => $this->data]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getCreate()
	{
		$datas = [
			'action' => url('/information/store'),
			'titlelist'	=> 'Add Information'
		];
		echo $this->getInformationForm($datas);
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
		$validationError = $this->information->validationForm(['request'=>$request]);
		if($validationError) {
			return \Response::json($validationError);
		}

		DB::beginTransaction();
		try {
			$informationDatas = [
				'bottom'		=> ((isset($request['bottom']))? $request['bottom']:''),
				'sort_order'	=> $request['sort_order'],
				'status'		=> $request['status']
			];
			$information = $this->information->create($informationDatas);

			$information_descriptionDatas = [
				'information_id'		=> $information->id,
				'information_description_datas'	=> $request['information_description']
			];

			$information_description = $this->information->insertInformationDescription($information_descriptionDatas);

			$url_aliasDatas = [
				'query'		=> 'information_id='.$information->id,
				'keyword'	=> $request['keyword']
			];
			$url_alias = $this->url_alias->create($url_aliasDatas);

			$information_to_layoutDatas['information_to_layout_datas'][] = [
				'information_id'		=> $information->id,
				'website_id'			=> '1',
				'layout_id'	=> $request['information_layout'][0]
			];

			$information_to_layout = $this->information->insertInformationToLayout($information_to_layoutDatas);
			DB::commit();
			$return = ['error'=>'0','success'=>'1','action'=>'create','msg'=>'Success : save information successfully!'];
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
	public function getEdit($information_id)
	{
		$this->data->information = $this->information->getInformation($information_id);
		$this->data->information_descriptions = $this->information->getInformationDescriptions($information_id);
		$this->data->information_to_layouts = $this->information->getInformationToLayouts($information_id);
		$datas = [
			'action' => url('/information/update/'.$information_id),
			'titlelist'	=> 'Edit Information',
			'information' => $this->data->information,
			'information_descriptions' => $this->data->information_descriptions,
			'information_to_layouts' => $this->data->information_to_layouts
		];
		echo $this->getInformationForm($datas);
		exit();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function postUpdate($information_id)
	{
		$request = \Request::all();
		$validationError = $this->information->validationForm(['request'=>$request]);
		if($validationError) {
			return \Response::json($validationError);
		}

		DB::beginTransaction();
		try {
			$informationDatas = [
				'bottom'		=> ((isset($request['bottom']))? $request['bottom']:''),
				'sort_order'	=> $request['sort_order'],
				'status'		=> $request['status']
			];
			$information = $this->information->where('information_id', '=', $information_id)->update($informationDatas);

			$clear_information_description = $this->information->deletedInformationDescription($information_id);
			$information_descriptionDatas = [
				'information_id'		=> $information_id,
				'information_description_datas'	=> $request['information_description']
			];

			$information_description = $this->information->insertInformationDescription($information_descriptionDatas);

			$clear_url_alias = $this->information->deletedUrlAlias($information_id);
			$url_aliasDatas = [
				'query'		=> 'information_id='.$information_id,
				'keyword'	=> $request['keyword']
			];
			$url_alias = $this->url_alias->create($url_aliasDatas);

			$clear_information_to_layout = $this->information->deletedInformationToLayout($information_id);
			$information_to_layoutDatas['information_to_layout_datas'][] = [
				'information_id'		=> $information_id,
				'website_id'			=> '1',
				'layout_id'	=> $request['information_layout'][0]
			];

			$information_to_layout = $this->information->insertInformationToLayout($information_to_layoutDatas);
			DB::commit();
			$return = ['error'=>'0','success'=>'1','action'=>'edit','msg'=>'Success : save information successfully!'];
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
				$arrayInformationID = $request['selected'];
				$this->information->destroyInformation($arrayInformationID);
				DB::commit();
				return Redirect('/information')->with('success', 'Success: delete information successfully!');
			} catch (Exception $e) {
				DB::rollback();
				return Redirect('/information')->with('error', 'Error: delete information successfully!'.$e->getMessage());
				exit();
			}
		}else {
			return Redirect('/information')->with('warning', 'Warning: there is no information selected!');
		}
		exit();		
	}

	public function getInformationForm($datas=[]) {
		$this->data->go_back = url('/information');
		$this->data->languages = $this->language->getLanguages(['sort'=>'name', 'order'=>'asc'])->get();
		$this->data->layouts = $this->layout->orderBy('name', 'asc')->lists('name', 'layout_id');
		$this->data->status = $this->config->status;

		// define tap
		$data['tab_general'] = 'General';
		$data['tab_data'] = 'Data';
		$data['tab_design'] = 'Design';

		// define entry
		$this->data->entry_title = 'Information Title';
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
		$this->data->title_keyword = 'Do not use spaces, instead replace spaces with - and make sure the keyword is globally unique.';
		$this->data->title_bottom = 'Display in the bottom footer.';

		if(isset($datas['information'])) {
			$this->data->bottom = $datas['information']->bottom;
			$this->data->sort_order = $datas['information']->sort_order;
			$this->data->information_status = $datas['information']->status;
			$this->data->keyword = $datas['information']->keyword;
		}else {
			$this->data->bottom = '';
			$this->data->sort_order = '0';
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

		$this->data->action = (($datas['action'])? $datas['action']:'');
		$this->data->titlelist = (($datas['titlelist'])? $datas['titlelist']:'');

		return view('website.information.form', ['data' => $this->data]);
	}

}
