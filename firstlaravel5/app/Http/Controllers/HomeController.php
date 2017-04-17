<?php namespace App\Http\Controllers;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

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
		$this->data->title = 'Home';
		$this->data->breadcrumbs = [
			'home'	=> ['text' => 'Home', 'href' => url('home')]
		];
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('home', ['data' => $this->data]);
	}

	public function template() {
		$template = 'oc_template';
		return view('templates.'.$template);
	}

}
