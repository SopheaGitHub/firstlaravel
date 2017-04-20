<?php namespace App\Http\Controllers\System\Localisation;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Currency;

use Illuminate\Http\Request;
use DB;

class CurrenciesController extends Controller {

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
		$this->currency = new Currency();
		$this->data->web_title = 'Currency';
		$this->data->breadcrumbs = [
			'home'	=> ['text' => 'Home', 'href' => url('home')],
			'currency'	=> ['text' => 'Currencies', 'href' => url('currencies')]
		];
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$this->data->actionlist = url('/currencies/list');
		$this->data->add_currency = url('/currencies/create');
		return view('system.localisation.currency.index', ['data' => $this->data]);
	}

	public function getList() {
		$request = \Request::all();
		$this->data->edit_currency = url('/currencies/edit');

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

		$this->data->currencies = $this->currency->getCurrencies($filter_data)->paginate(1)->setPath(url('/currencies'))->appends($paginate_url);

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
		$this->data->sort_code = '?sort=code'.$url;
		$this->data->sort_value = '?sort=value'.$url;
		$this->data->sort_updated_at = '?sort=updated_at'.$url;

		// define column
		$this->data->column_title = "Currency Title";
		$this->data->column_code = "Code";
		$this->data->column_value = "Value";
		$this->data->column_updated_at = "Last Updated";
		$this->data->column_action = "Action";

		return view('system.localisation.currency.list', ['data' => $this->data]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getCreate()
	{
		$datas = [
			'action' => url('/currencies/store'),
			'titlelist'	=> 'Add Currency'
		];
		echo $this->getCurrencyForm($datas);
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
		$validationError = $this->currency->validationForm(['request'=>$request]);
		if($validationError) {
			return \Response::json($validationError);
		}

		DB::beginTransaction();
		try {
			$currencyDatas = [
				'title'			=> $request['title'],
				'code'			=> $request['code'],
				'symbol_left'	=> $request['symbol_left'],
				'symbol_right'	=> $request['symbol_right'],
				'decimal_place'	=> $request['decimal_place'],
				'value'			=> $request['value'],
				'status'		=> $request['status']
			];
			$currency = $this->currency->create($currencyDatas);
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
	public function getEdit($currency_id)
	{
		$this->data->currency = $this->currency->getCurrency($currency_id);
		$datas = [
			'action' => url('/currencies/update/'.$currency_id),
			'titlelist'	=> 'Edit Currency',
			'currency' => $this->data->currency
		];
		echo $this->getCurrencyForm($datas);
		exit();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function postUpdate($currency_id)
	{
		$request = \Request::all();
		$validationError = $this->currency->validationForm(['request'=>$request]);
		if($validationError) {
			return \Response::json($validationError);
		}

		DB::beginTransaction();
		try {
			$currencyDatas = [
				'title'			=> $request['title'],
				'code'			=> $request['code'],
				'symbol_left'	=> $request['symbol_left'],
				'symbol_right'	=> $request['symbol_right'],
				'decimal_place'	=> $request['decimal_place'],
				'value'			=> $request['value'],
				'status'		=> $request['status']
			];
			$currency = $this->currency->where('currency_id', '=', $currency_id)->update($currencyDatas);
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

	public function getCurrencyForm($datas=[]) {
		$this->data->go_back = url('/currencies');

		$this->data->status = [
			'1' => 'Enabled',
			'0'	=> 'Disabled'
		];

		// define entry
		$this->data->entry_title = 'Currency Title';
		$this->data->entry_code = 'Code';
		$this->data->entry_symbol_left = 'Symbol Left';
		$this->data->entry_symbol_right = 'Symbol Right';
		$this->data->entry_decimal_places = 'Decimal Places';
		$this->data->entry_value = 'Value';
		$this->data->entry_status = 'Status';

		// define input title
		$this->data->title_code = 'Do not change if this is your default currency. Must be valid &lt;a href=&quot;http://www.xe.com/iso4217.php&quot; target=&quot;_blank&quot;&gt;ISO code&lt;/a&gt;.';
		$this->data->title_value = 'Set to 1.00000 if this is your default currency.';

		if(isset($datas['currency'])) {
			$this->data->title = $datas['currency']->title;
			$this->data->code = $datas['currency']->code;
			$this->data->symbol_left = $datas['currency']->symbol_left;
			$this->data->symbol_right = $datas['currency']->symbol_right;
			$this->data->decimal_place = $datas['currency']->decimal_place;
			$this->data->value = $datas['currency']->value;
			$this->data->currency_status = $datas['currency']->status;
		}else {
			$this->data->title = '';
			$this->data->code = '';
			$this->data->symbol_left = '';
			$this->data->symbol_right = '';
			$this->data->decimal_place = '';
			$this->data->value = '';
			$this->data->currency_status = 1;
		}

		$this->data->action = (($datas['action'])? $datas['action']:'');
		$this->data->titlelist = (($datas['titlelist'])? $datas['titlelist']:'');

		return view('system.localisation.currency.form', ['data' => $this->data]);
	}

}
