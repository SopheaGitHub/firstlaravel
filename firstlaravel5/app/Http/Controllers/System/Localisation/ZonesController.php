<?php namespace App\Http\Controllers\System\Localisation;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Zone;
use App\Http\Controllers\ConfigController;

use Illuminate\Http\Request;
use DB;

class ZonesController extends Controller {

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
		$this->zone = new Zone();
		$this->config = new ConfigController();
		$this->data->web_title = 'Zones';
		$this->data->breadcrumbs = [
			'home'	=> ['text' => 'Home', 'href' => url('home')],
			'zone'	=> ['text' => 'Zones', 'href' => url('zones')]
		];
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$this->data->actionlist = url('/zones/list');
		$this->data->add_zone = url('/zones/create');
		return view('system.localisation.zone.index', ['data' => $this->data]);
	}

	public function getList() {
		$request = \Request::all();
		$this->data->edit_zone = url('/zones/edit');
		$this->data->action_delete = url('/zones/destroy');

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

		$this->data->zones = $this->zone->getZones($filter_data)->paginate(10)->setPath(url('/zones'))->appends($paginate_url);

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
		$this->data->sort_country_id = '?sort=country_id'.$url;

		// define column
		$this->data->column_name = "Zone Name";
		$this->data->column_code = "Zone Code";
		$this->data->column_country_id = "Country";
		$this->data->column_action = "Action";

		return view('system.localisation.zone.list', ['data' => $this->data]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getCreate()
	{
		$datas = [
			'action' => url('/zones/store'),
			'titlelist'	=> 'Add Zone'
		];
		echo $this->getZoneForm($datas);
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
		$validationError = $this->zone->validationForm(['request'=>$request]);
		if($validationError) {
			return \Response::json($validationError);
		}

		DB::beginTransaction();
		try {
			$zoneDatas = [
				'country_id'	=> $request['country_id'],
				'name'			=> $request['name'],
				'code'			=> $request['code'],
				'status'		=> $request['status']
			];
			$zone = $this->zone->create($zoneDatas);
			DB::commit();
			$return = ['error'=>'0','success'=>'1','action'=>'create','msg'=>'Success : save zone successfully!'];
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
	public function getEdit($zone_id)
	{
		$this->data->zone = $this->zone->getZone($zone_id);
		$datas = [
			'action' => url('/zones/update/'.$zone_id),
			'titlelist'	=> 'Edit Zone',
			'zone' => $this->data->zone
		];
		echo $this->getZoneForm($datas);
		exit();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function postUpdate($zone_id)
	{
		$request = \Request::all();
		$validationError = $this->zone->validationForm(['request'=>$request]);
		if($validationError) {
			return \Response::json($validationError);
		}

		DB::beginTransaction();
		try {
			$zoneDatas = [
				'country_id'	=> $request['country_id'],
				'name'			=> $request['name'],
				'code'			=> $request['code'],
				'status'		=> $request['status']
			];
			$zone = $this->zone->where('zone_id', '=', $zone_id)->update($zoneDatas);
			DB::commit();
			$return = ['error'=>'0','success'=>'1','action'=>'edit','msg'=>'Success : save zone successfully!'];
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
				$arrayZoneID = $request['selected'];
				$this->zone->destroyZones($arrayZoneID);
				DB::commit();
				return Redirect('/zones')->with('success', 'Success: delete zone successfully!');
			} catch (Exception $e) {
				DB::rollback();
				return Redirect('/zones')->with('error', 'Error: delete zone successfully!'.$e->getMessage());
				exit();
			}
		}else {
			return Redirect('/zones')->with('warning', 'Warning: there is no zone selected!');
		}
		exit();		
	}

	public function getZoneForm($datas=[]) {
		$this->data->go_back = url('/zones');
		$this->data->countries = $this->country->orderBy('name', 'asc')->lists('name', 'country_id');
		$this->data->status = $this->config->status;

		// define entry
		$this->data->entry_name = 'Zone Name';
		$this->data->entry_code = 'Code';
		$this->data->entry_country = 'Country';
		$this->data->entry_status = 'Status';

		if(isset($datas['zone'])) {
			$this->data->name = $datas['zone']->name;
			$this->data->code = $datas['zone']->code;
			$this->data->country_id = $datas['zone']->country_id;
			$this->data->zone_status = $datas['zone']->status;
		}else {
			$this->data->name = '';
			$this->data->code = '';
			$this->data->country_id = '';
			$this->data->zone_status = 1;
		}

		$this->data->action = (($datas['action'])? $datas['action']:'');
		$this->data->titlelist = (($datas['titlelist'])? $datas['titlelist']:'');

		return view('system.localisation.zone.form', ['data' => $this->data]);
	}

}
