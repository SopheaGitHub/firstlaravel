<?php namespace App\Http\Controllers\Common;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class FilemanagerController extends Controller {

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

		// create define
		$this->data->dir_image = url('/images/');
		$this->data->https_catalog = url('/');
		$this->data->http_catalog = url('/');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$request = \Request::all();
		$data = [];

		if (isset($request['filter_name'])) {
			$filter_name = rtrim(str_replace(array('../', '..\\', '..', '*'), '', $request['filter_name']), '/');
		} else {
			$filter_name = null;
		}

		// Make sure we have the correct directory
		if (isset($request['directory'])) {
			$directory = rtrim($this->data->dir_image . 'catalog/' . str_replace(array('../', '..\\', '..'), '', $request['directory']), '/');
		} else {
			$directory = $this->data->dir_image . 'catalog';
		}

		if (isset($request['page'])) {
			$page = $request['page'];
		} else {
			$page = 1;
		}

		$data['images'] = array();

		// Get directories
		$directories = glob($directory . '/' . $filter_name . '*', GLOB_ONLYDIR);

		if (!$directories) {
			$directories = array();
		}

		// Get files
		$files = glob($directory . '/' . $filter_name . '*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}', GLOB_BRACE);

		if (!$files) {
			$files = array();
		}

		// Merge directories and files
		$images = array_merge($directories, $files);

		// Get total number of files and directories
		$image_total = count($images);

		// Split the array based on current page number and max number of items per page of 10
		$images = array_splice($images, ($page - 1) * 16, 16);

		foreach ($images as $image) {
			$name = str_split(basename($image), 14);

			if (is_dir($image)) {
				$url = '';

				if (isset($request['target'])) {
					$url .= '&target=' . $request['target'];
				}

				if (isset($request['thumb'])) {
					$url .= '&thumb=' . $request['thumb'];
				}

				$data['images'][] = array(
					'thumb' => '',
					'name'  => implode(' ', $name),
					'type'  => 'directory',
					'path'  => utf8_substr($image, utf8_strlen($this->data->dir_image)),
					'href'  => url('filemanager?directory=' . urlencode(utf8_substr($image, utf8_strlen($this->data->dir_image . 'catalog/'))).$url)
				);
			} elseif (is_file($image)) {
				// Find which protocol to use to pass the full image link back
				if ($this->request->server['HTTPS']) {
					$server = $this->data->https_catalog;
				} else {
					$server = $this->data->http_catalog;
				}

				$data['images'][] = array(
					'thumb' => 'ss',
					'name'  => implode(' ', $name),
					'type'  => 'image',
					'path'  => utf8_substr($image, utf8_strlen($this->data->dir_image)),
					'href'  => $server . 'image/' . utf8_substr($image, utf8_strlen($this->data->dir_image))
				);
			}
		}

		$data['heading_title'] = 'Image Manager';

		$data['text_no_results'] = 'No results!';
		$data['text_confirm'] = 'Are you sure?';

		$data['entry_search'] = 'Search..';
		$data['entry_folder'] = 'Folder Name';

		$data['button_parent'] = 'Parent';
		$data['button_refresh'] = 'Refresh';
		$data['button_upload'] = 'Upload';
		$data['button_folder'] = 'New Folder';
		$data['button_delete'] = 'Delete';
		$data['button_search'] = 'Search';

		if (isset($request['directory'])) {
			$data['directory'] = urlencode($request['directory']);
		} else {
			$data['directory'] = '';
		}

		if (isset($request['filter_name'])) {
			$data['filter_name'] = $request['filter_name'];
		} else {
			$data['filter_name'] = '';
		}

		// Return the target ID for the file manager to set the value
		if (isset($request['target'])) {
			$data['target'] = $request['target'];
		} else {
			$data['target'] = '';
		}

		// Return the thumbnail for the file manager to show a thumbnail
		if (isset($request['thumb'])) {
			$data['thumb'] = $request['thumb'];
		} else {
			$data['thumb'] = '';
		}

		// Parent
		$url = '';

		if (isset($request['directory'])) {
			$pos = strrpos($request['directory'], '/');

			if ($pos) {
				$url .= '&directory=' . urlencode(substr($request['directory'], 0, $pos));
			}
		}

		if (isset($request['target'])) {
			$url .= '&target=' . $request['target'];
		}

		if (isset($request['thumb'])) {
			$url .= '&thumb=' . $request['thumb'];
		}

		$data['parent'] = url('/filemanager?filter=yes'.$url);

		// Refresh
		$url = '';

		if (isset($request['directory'])) {
			$url .= '&directory=' . urlencode($request['directory']);
		}

		if (isset($request['target'])) {
			$url .= '&target=' . $request['target'];
		}

		if (isset($request['thumb'])) {
			$url .= '&thumb=' . $request['thumb'];
		}

		$data['refresh'] = url('/filemanager?filter=yes'.$url);

		return view('common.filemanager.index', compact('data'));
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
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
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

}
