<?php namespace App\Http\Controllers\System\Localisation;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Zone;
use App\Models\GeoZone;

use Illuminate\Http\Request;
use DB;

class GeoZonesController extends Controller {

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
		$this->geo_zone = new GeoZone();
		$this->data->web_title = 'Geo-Zones';
		$this->data->breadcrumbs = [
			'home'	=> ['text' => 'Home', 'href' => url('home')],
			'geo-zone'	=> ['text' => 'Geo-Zones', 'href' => url('geo-zones')]
		];
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$this->data->actionlist = url('/geo-zones/list');
		$this->data->add_geo_zone = url('/geo-zones/create');
		return view('system.localisation.geo_zone.index', ['data' => $this->data]);
	}

	public function getList() {
		$request = \Request::all();
		$this->data->edit_geo_zone = url('/geo-zones/edit');

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

		$this->data->geo_zones = $this->geo_zone->getGeoZones($filter_data)->paginate(10)->setPath(url('/geo-zones'))->appends($paginate_url);

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
		$this->data->sort_description = '?sort=description'.$url;

		// define column
		$this->data->column_name = "Geo Zone Name";
		$this->data->column_description = "Description";
		$this->data->column_action = "Action";

		return view('system.localisation.geo_zone.list', ['data' => $this->data]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getCreate()
	{
		$datas = [
			'action' => url('/geo-zones/store'),
			'titlelist'	=> 'Add Geo-Zone'
		];
		echo $this->getGeoZoneForm($datas);
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
		$validationError = $this->geo_zone->validationForm(['request'=>$request]);
		if($validationError) {
			return \Response::json($validationError);
		}

		DB::beginTransaction();
		try {
			$geo_zoneDatas = [
				'name'			=> $request['name'],
				'description'	=> $request['description']
			];
			$geo_zone = $this->geo_zone->create($geo_zoneDatas);

			$zone_to_geo_zoneDatas = [
				'geo_zone_id'		=> $geo_zone->id,
				'zone_to_geo_zone_datas'	=> $request['zone_to_geo_zone']
			];

			$zone_to_geo_zone = $this->geo_zone->insertZoneToGeoZone($zone_to_geo_zoneDatas);

			DB::commit();
			$return = ['error'=>'0','success'=>'1','action'=>'create','msg'=>'Success : save geo-zone successfully!', 'post'=>$geo_zone];
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
	public function getEdit($geo_zone_id)
	{
		$this->data->geo_zone = $this->geo_zone->getGeoZone($geo_zone_id);
		$this->data->zone_to_geo_zones = $this->geo_zone->getZoneToGeoZones($geo_zone_id);
		$datas = [
			'action' => url('/geo-zones/update/'.$geo_zone_id),
			'titlelist'	=> 'Edit Zone',
			'geo_zone' => $this->data->geo_zone,
			'zone_to_geo_zones'	=> $this->data->zone_to_geo_zones
		];
		echo $this->getGeoZoneForm($datas);
		exit();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function postUpdate($geo_zone_id)
	{
		$request = \Request::all();
		$validationError = $this->geo_zone->validationForm(['request'=>$request]);
		if($validationError) {
			return \Response::json($validationError);
		}

		DB::beginTransaction();
		try {
			$geo_zoneDatas = [
				'name'			=> $request['name'],
				'description'	=> $request['description']
			];
			$geo_zone = $this->geo_zone->where('geo_zone_id', '=', $geo_zone_id)->update($geo_zoneDatas);

			$clear_zone_to_geo_zone = $this->geo_zone->deletedZoneToGeoZone($geo_zone_id);

			$zone_to_geo_zoneDatas = [
				'geo_zone_id'		=> $geo_zone_id,
				'zone_to_geo_zone_datas'	=> $request['zone_to_geo_zone']
			];

			$zone_to_geo_zone = $this->geo_zone->insertZoneToGeoZone($zone_to_geo_zoneDatas);

			DB::commit();
			$return = ['error'=>'0','success'=>'1','action'=>'edit','msg'=>'Success : save geo-zone successfully!', 'post'=>$geo_zone];
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

	public function getGeoZoneForm($datas=[]) {
		$this->data->go_back = url('/geo-zones');

		$this->data->countries = $this->country->getCountries(['sort'=>'name','order'=>'asc'])->lists('name', 'country_id');

		$this->data->status = [
			'1' => 'Enabled',
			'0'	=> 'Disabled'
		];

		// define entry
		$this->data->entry_name = 'Geo Zone Name';
		$this->data->entry_description = 'Description';
		$this->data->entry_country = 'Country';
		$this->data->entry_zone = 'Zone';

		if(isset($datas['geo_zone'])) {
			$this->data->name = $datas['geo_zone']->name;
			$this->data->description = $datas['geo_zone']->description;
		}else {
			$this->data->name = '';
			$this->data->description = '';
		}

		if(isset($datas['zone_to_geo_zones'])) {
			$this->data->zone_to_geo_zones = $datas['zone_to_geo_zones'];
		}else {
			$this->data->zone_to_geo_zones = [];
		}

		$this->data->action = (($datas['action'])? $datas['action']:'');
		$this->data->titlelist = (($datas['titlelist'])? $datas['titlelist']:'');
		$this->data->load_zone_action = url('geo-zones/zone');

		return view('system.localisation.geo_zone.form', ['data' => $this->data]);
	}

	public function getZone($country_id, $zone_id) {
		$this->data->country_zones = $this->zone->getZonesByContry($country_id);
		$this->data->zone_id = $zone_id;
		return view('system.localisation.geo_zone.zone_form', ['data' => $this->data]);
	}

}
