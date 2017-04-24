<?php namespace App\Http\Controllers\System;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Models\Setting;
use App\Models\Language;
use App\Models\Country;
use App\Models\Zone;
use App\Models\Currency;
use App\Http\Controllers\Common\FilemanagerController;

use Illuminate\Http\Request;

class SettingsController extends Controller {

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
		$this->website = new Website();
		$this->setting = new Setting();
		$this->language = new Language();
		$this->currency = new Currency();
		$this->country = new Country();
		$this->zone = new Zone();
		$this->filemanager = new FilemanagerController();
		$this->data->web_title = 'Settings';
		$this->data->breadcrumbs = [
			'home'	=> ['text' => 'Home', 'href' => url('home')],
			'setting'	=> ['text' => 'Settings', 'href' => url('settings')]
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
		$this->data->actionlist = url('/settings/list');
		$this->data->add_website = url('/settings/create');
		return view('system.settings.index', ['data' => $this->data]);
	}

	public function getList() {
		$request = \Request::all();
		$this->data->edit_website = url('/settings/edit');

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

		$this->data->websites = $this->website->getWebsites($filter_data)->paginate(10)->setPath(url('/settings'))->appends($paginate_url);

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
		$this->data->sort_url = '?sort=url'.$url;
		// define column
		$this->data->column_name = "Website Name";
		$this->data->column_url = "Website URL";
		$this->data->column_action = "Action";

		return view('system.settings.list', ['data' => $this->data]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
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
	public function getEdit($website_id)
	{
		$this->data->settings = $this->setting->getSettings($website_id);
		$datas = [
			'action' => url('/settings/update/'.$website_id),
			'titlelist'	=> 'Edit Setting',
			'settings' => $this->data->settings
		];
		echo $this->getSettingForm($datas);
		exit();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($website_id)
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

	public function getSettingForm($datas=[]) {
		$this->data->go_back = url('/settings');

		$this->data->languages = $this->language->getLanguages(['sort' => 'name', 'order' => 'asc'])->lists('name', 'code');
		$this->data->currencies = $this->currency->getCurrencies(['sort' => 'code', 'order' => 'asc'])->lists('title', 'code');
		$this->data->countries = $this->country->getCountries(['sort' => 'name', 'order' => 'asc'])->lists('name', 'country_id');
		$this->data->zones = $this->zone->getZones(['sort' => 'name', 'order' => 'asc'])->lists('name', 'zone_id');

		// define tab
		$this->data->tap_general = 'General';
		$this->data->tap_website = 'Website';
		$this->data->tap_localisation = 'Localisation';
		$this->data->tap_option = 'Option';
		$this->data->tap_image = 'Image';

		// define entry
		$this->data->entry_meta_title = 'Meta Title';
		$this->data->entry_meta_tag_description = 'Meta Tag Description';
		$this->data->entry_meta_tag_keywords = 'Meta Tag Keywords';

		$this->data->entry_website_name = 'Website Name';
		$this->data->entry_website_owner = 'Website Owner';
		$this->data->entry_address = 'Address';
		$this->data->entry_geocode = 'Geocode';
		$this->data->entry_email = 'E-Mail';
		$this->data->entry_telephone = 'Telephone';
		$this->data->entry_fax = 'Fax';
		$this->data->entry_image = 'Image';
		$this->data->entry_opening_times = 'Opening Times';
		$this->data->entry_comment = 'Comment';

		$this->data->entry_country = 'Country';
		$this->data->entry_region_state = 'Region / State';
		$this->data->entry_language = 'Language';
		$this->data->entry_administration_language = 'Administration Language';
		$this->data->entry_currency = 'Currency';

		$this->data->entry_default_lists_per_page_admin = 'Default Lists Per Page (Admin)';

		$this->data->entry_website_logo = 'Website Logo';
		$this->data->entry_website_icon = 'Website Icon';

		$this->data->text_select = '--please select--';
		$this->data->text_none = '-- None --';

		// define input title
		$this->data->title_password = 'Must be enter at least 6 characters, Ex:@As!02';
		$this->data->title_geocode = 'Please enter your store location geocode manually.';
		$this->data->title_opening_times = 'Fill in your store\'s opening times.';
		$this->data->title_comment = 'This field is for any special notes you would like to tell the customer i.e. Store does not accept cheques.';
		$this->data->title_currency ='Change the default currency. Clear your browser cache to see the change and reset your existing cookie.';
		$this->data->title_default_lists_per_page_admin = 'Determines how many admin items are shown per page (orders, customers, etc).';

		$this->data->title_website_icon = 'The icon should be a PNG that is 16px x 16px.';

		// define fieldset
		$this->data->fieldset_list = 'Lists';

		if(isset($datas['settings'])) {
			foreach ($datas['settings'] as $key => $value) {
				$this->data->{$value->key} = $value->value;
			}
		}else {
			$this->data->config_icon = '';
			$this->data->config_logo = '';
			$this->data->config_limit_admin = '';
			$this->data->config_currency = '';
			$this->data->config_admin_language = '';
			$this->data->config_language = '';
			$this->data->config_zone_id = '';
			$this->data->config_country_id = '';
			$this->data->config_comment = '';
			$this->data->config_open = '';
			$this->data->config_image = '';
			$this->data->config_fax = '';
			$this->data->config_telephone = '';
			$this->data->config_geocode = '';
			$this->data->config_email = '';
			$this->data->config_address = '';
			$this->data->config_owner = '';
			$this->data->config_name = '';
			$this->data->config_meta_description = '';
			$this->data->config_meta_keyword = '';
			$this->data->config_meta_title = '';
		}

		if ($this->data->config_image && is_file($this->data->dir_image . $this->data->config_image)) {
			$this->data->thumb = $this->filemanager->resize($this->data->config_image, 100, 100);
		} else {
			$this->data->thumb = $this->filemanager->resize('no_image.png', 100, 100);
		}

		$this->data->placeholder = $this->filemanager->resize('no_image.png', 100, 100);

		if ($this->data->config_logo && is_file($this->data->dir_image . $this->data->config_logo)) {
			$this->data->logo = $this->filemanager->resize($this->data->config_logo, 100, 100);
		} else {
			$this->data->logo = $this->filemanager->resize('no_image.png', 100, 100);
		}

		if ($this->data->config_icon && is_file($this->data->dir_image . $this->data->config_icon)) {
			$this->data->icon = $this->filemanager->resize($this->data->config_icon, 100, 100);
		} else {
			$this->data->icon = $this->filemanager->resize('no_image.png', 100, 100);
		}
		
		$this->data->action = (($datas['action'])? $datas['action']:'');
		$this->data->titlelist = (($datas['titlelist'])? $datas['titlelist']:'');

		return view('system.settings.form', ['data' => $this->data]);
	}

	public function getCountry($country_id) {
		$json = array();

		$country_info = $this->country->getCountry($country_id);

		if ($country_info) {

			$json = array(
				'country_id'        => $country_info->country_id,
				'name'              => $country_info->name,
				'iso_code_2'        => $country_info->iso_code_2,
				'iso_code_3'        => $country_info->iso_code_3,
				'address_format'    => $country_info->address_format,
				'postcode_required' => $country_info->postcode_required,
				'zone'              => $this->zone->getArrayZonesByContryID($country_id),
				'status'            => $country_info->status
			);
		}

		return json_encode($json);
	}

}
