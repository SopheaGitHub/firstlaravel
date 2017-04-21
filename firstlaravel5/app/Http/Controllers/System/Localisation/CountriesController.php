<?php namespace App\Http\Controllers\System\Localisation;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Country;

use Illuminate\Http\Request;
use DB;

class CountriesController extends Controller {

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
		$this->country = new Country();
		$this->data->web_title = 'Countries';
		$this->data->breadcrumbs = [
			'home'	=> ['text' => 'Home', 'href' => url('home')],
			'country'	=> ['text' => 'Countries', 'href' => url('countries')]
		];
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$this->data->actionlist = url('/countries/list');
		$this->data->add_country = url('/countries/create');
		return view('system.localisation.country.index', ['data' => $this->data]);
	}

	public function getList() {
		$request = \Request::all();
		$this->data->edit_country = url('/countries/edit');

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

		$this->data->countries = $this->country->getCountries($filter_data)->paginate(10)->setPath(url('/countries'))->appends($paginate_url);

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
		$this->data->sort_iso_code_2 = '?sort=iso_code_2'.$url;
		$this->data->sort_iso_code_3 = '?sort=iso_code_3'.$url;

		// define column
		$this->data->column_name = "Country Name";
		$this->data->column_iso_code_2 = "ISO Code (2)";
		$this->data->column_iso_code_3 = "ISO Code (3)";
		$this->data->column_action = "Action";

		return view('system.localisation.country.list', ['data' => $this->data]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getCreate()
	{
		$datas = [
			'action' => url('/countries/store'),
			'titlelist'	=> 'Add Country'
		];
		echo $this->getCountryForm($datas);
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
		$validationError = $this->country->validationForm(['request'=>$request]);
		if($validationError) {
			return \Response::json($validationError);
		}

		DB::beginTransaction();
		try {
			$countryDatas = [
				'name'				=> $request['name'],
				'iso_code_2'		=> $request['iso_code_2'],
				'iso_code_3'		=> $request['iso_code_3'],
				'address_format'	=> $request['address_format'],
				'postcode_required'	=> $request['postcode_required'],
				'status'			=> $request['status']
			];
			$country = $this->country->create($countryDatas);
			DB::commit();
			$return = ['error'=>'0','success'=>'1','action'=>'create','msg'=>'Success : save currency successfully!'];
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
	public function getEdit($country_id)
	{
		$this->data->country = $this->country->getCountry($country_id);
		$datas = [
			'action' => url('/countries/update/'.$country_id),
			'titlelist'	=> 'Edit Country',
			'country' => $this->data->country
		];
		echo $this->getCountryForm($datas);
		exit();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function postUpdate($country_id)
	{
		$request = \Request::all();
		$validationError = $this->country->validationForm(['request'=>$request]);
		if($validationError) {
			return \Response::json($validationError);
		}

		DB::beginTransaction();
		try {
			$countryDatas = [
				'name'				=> $request['name'],
				'iso_code_2'		=> $request['iso_code_2'],
				'iso_code_3'		=> $request['iso_code_3'],
				'address_format'	=> $request['address_format'],
				'postcode_required'	=> $request['postcode_required'],
				'status'			=> $request['status']
			];
			$country = $this->country->where('country_id', '=', $country_id)->update($countryDatas);
			DB::commit();
			$return = ['error'=>'0','success'=>'1','action'=>'edit','msg'=>'Success : save country successfully!'];
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

	public function getCountryForm($datas=[]) {
		$this->data->go_back = url('/countries');

		$this->data->status = [
			'1' => 'Enabled',
			'0'	=> 'Disabled'
		];

		// define entry
		$this->data->entry_name = 'Country Name';
		$this->data->entry_iso_code_2 = 'ISO Code (2)';
		$this->data->entry_iso_code_3 = 'ISO Code (3)';
		$this->data->entry_address_format = 'Address Format';
		$this->data->entry_postcode_required = 'Postcode Required';
		$this->data->entry_status = 'Status';

		// define input title
		$this->data->title_address_format = 'First Name = {firstname}&lt;br /&gt;Last Name = {lastname}&lt;br /&gt;Company = {company}&lt;br /&gt;Address 1 = {address_1}&lt;br /&gt;Address 2 = {address_2}&lt;br /&gt;City = {city}&lt;br /&gt;Postcode = {postcode}&lt;br /&gt;Zone = {zone}&lt;br /&gt;Zone Code = {zone_code}&lt;br /&gt;Country = {country}';

		if(isset($datas['country'])) {
			$this->data->name = $datas['country']->name;
			$this->data->iso_code_2 = $datas['country']->iso_code_2;
			$this->data->iso_code_3 = $datas['country']->iso_code_3;
			$this->data->address_format = $datas['country']->address_format;
			$this->data->postcode_required = $datas['country']->postcode_required;
			$this->data->country_status = $datas['country']->status;
		}else {
			$this->data->name = '';
			$this->data->iso_code_2 = '';
			$this->data->iso_code_3 = '';
			$this->data->address_format = '';
			$this->data->postcode_required = 0;
			$this->data->country_status = 1;
		}

		$this->data->action = (($datas['action'])? $datas['action']:'');
		$this->data->titlelist = (($datas['titlelist'])? $datas['titlelist']:'');

		return view('system.localisation.country.form', ['data' => $this->data]);
	}

}
