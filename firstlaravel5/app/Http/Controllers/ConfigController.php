<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ConfigController extends Controller {

	// image path
	public $dir_image = "C:/xampp/htdocs/projects/firstlaravel/firstlaravel5/public/images/";

	// application path
	public $dir_application_http = "C:/xampp/htdocs/projects/firstlaravel/firstlaravel5/app/Http/";

	// http best path
	public $http_best_path = "http://localhost/projects/firstlaravel/firstlaravel5/public/";

	// https best path
	public $https_best_path = "https://localhost/projects/firstlaravel/firstlaravel5/public/";

	// system status
	public $status = ['1' => 'Enabled', '0'	=> 'Disabled'];

}
