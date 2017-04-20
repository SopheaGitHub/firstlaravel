<?php namespace App\Http\Controllers\System\Localisation;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Language;

use Illuminate\Http\Request;
use DB;

class LanguagesController extends Controller {

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
		$this->language = new Language();
		$this->data->web_title = 'Languages';
		$this->data->breadcrumbs = [
			'home'	=> ['text' => 'Home', 'href' => url('home')],
			'language'	=> ['text' => 'Languages', 'href' => url('languages')]
		];
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$this->data->actionlist = url('/languages/list');
		$this->data->add_language = url('/languages/create');
		return view('system.localisation.language.index', ['data' => $this->data]);
	}

	public function getList() {
		$request = \Request::all();
		$this->data->edit_language = url('/languages/edit');

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

		$this->data->languages = $this->language->getLanguages($filter_data)->paginate(10)->setPath(url('/languages'))->appends($paginate_url);

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
		$this->data->sort_code = '?sort=code'.$url;
		$this->data->sort_sort_order = '?sort=sort_order'.$url;

		// define column
		$this->data->column_name = "Languages Name";
		$this->data->column_code = "Code";
		$this->data->column_sort_order = "Sort Order";
		$this->data->column_action = "Action";

		return view('system.localisation.language.list', ['data' => $this->data]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getCreate()
	{
		$datas = [
			'action' => url('/languages/store'),
			'titlelist'	=> 'Add Language'
		];
		echo $this->getLanguageForm($datas);
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
		$validationError = $this->language->validationForm(['request'=>$request]);
		if($validationError) {
			return \Response::json($validationError);
		}

		DB::beginTransaction();
		try {
			$languageDatas = [
				'name'			=> $request['name'],
				'code'			=> $request['code'],
				'locale'		=> $request['locale'],
				'image'			=> $request['image'],
				'directory'		=> $request['directory'],
				'sort_order'	=> $request['sort_order'],
				'status'		=> $request['status']
			];
			$language = $this->language->create($languageDatas);
			DB::commit();
			$return = ['error'=>'0','success'=>'1','action'=>'create','msg'=>'Success : save language successfully!'];
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
	public function getEdit($language_id)
	{
		$this->data->language = $this->language->getLanguage($language_id);
		$datas = [
			'action' => url('/languages/update/'.$language_id),
			'titlelist'	=> 'Edit Language',
			'language' => $this->data->language
		];
		echo $this->getLanguageForm($datas);
		exit();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function postUpdate($language_id)
	{
		$request = \Request::all();
		$validationError = $this->language->validationForm(['request'=>$request]);
		if($validationError) {
			return \Response::json($validationError);
		}

		DB::beginTransaction();
		try {
			$languageDatas = [
				'name'			=> $request['name'],
				'code'			=> $request['code'],
				'locale'		=> $request['locale'],
				'image'			=> $request['image'],
				'directory'		=> $request['directory'],
				'sort_order'	=> $request['sort_order'],
				'status'		=> $request['status']
			];
			$language = $this->language->where('language_id', '=', $language_id)->update($languageDatas);
			DB::commit();
			$return = ['error'=>'0','success'=>'1','action'=>'edit','msg'=>'Success : save language successfully!'];
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

	public function getLanguageForm($datas=[]) {
		$this->data->go_back = url('/languages');

		$this->data->status = [
			'1' => 'Enabled',
			'0'	=> 'Disabled'
		];

		// define entry
		$this->data->entry_name = 'Language Name';
		$this->data->entry_code = 'Code';
		$this->data->entry_locale = 'Locale';
		$this->data->entry_image = 'Image';
		$this->data->entry_directory = 'Directory';
		$this->data->entry_status = 'Status';
		$this->data->entry_sort_order = 'Sort Order';

		// define input title
		$this->data->title_code = 'Example: en. Do not change if this is your default language.';
		$this->data->title_locale = 'Example: en_US.UTF-8,en_US,en-gb,en_gb,english';
		$this->data->title_image = 'Example: gb.png';
		$this->data->title_directory = 'Name of the language directory (case-sensitive)';
		$this->data->title_filename = 'Main language filename without extension';

		if(isset($datas['language'])) {
			$this->data->name = $datas['language']->name;
			$this->data->code = $datas['language']->code;
			$this->data->locale = $datas['language']->locale;
			$this->data->image = $datas['language']->image;
			$this->data->directory = $datas['language']->directory;
			$this->data->language_status = $datas['language']->status;
			$this->data->sort_order = $datas['language']->sort_order;
		}else {
			$this->data->name = '';
			$this->data->code = '';
			$this->data->locale = '';
			$this->data->image = '';
			$this->data->directory = '';
			$this->data->language_status = 1;
			$this->data->sort_order = 0;
		}

		$this->data->action = (($datas['action'])? $datas['action']:'');
		$this->data->titlelist = (($datas['titlelist'])? $datas['titlelist']:'');

		return view('system.localisation.language.form', ['data' => $this->data]);
	}

}
