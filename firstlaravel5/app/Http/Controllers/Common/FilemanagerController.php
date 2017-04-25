<?php namespace App\Http\Controllers\Common;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Pagination;

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
		$this->data->dir_image = 'C:/wamp/www/firstlaravel/firstlaravel5/public/images/';
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
					'path'  => substr($image, strlen($this->data->dir_image)),
					'href'  => url('filemanager?directory=' . urlencode(substr($image, strlen($this->data->dir_image . 'catalog/'))).$url)
				);
			} elseif (is_file($image)) {
				// Find which protocol to use to pass the full image link back
				// if ($this->request->server['HTTPS']) {
				// 	$server = $this->data->https_catalog;
				// } else {
				// 	$server = $this->data->http_catalog;
				// }
				$server = $this->data->http_catalog;

				$data['images'][] = array(
					'thumb' => $this->resize(substr($image, strlen($this->data->dir_image)), 100, 100),
					'name'  => implode(' ', $name),
					'type'  => 'image',
					'path'  => substr($image, strlen($this->data->dir_image)),
					'href'  => $server . '/images/' . substr($image, strlen($this->data->dir_image))
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

		$data['parent'] = url('/filemanager?loading=yes'.$url);

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

		$data['refresh'] = url('/filemanager?loading=yes'.$url);

		$url = '';

		if (isset($request['directory'])) {
			$url .= '&directory=' . urlencode(html_entity_decode($request['directory'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($request['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($request['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($request['target'])) {
			$url .= '&target=' . $request['target'];
		}

		if (isset($request['thumb'])) {
			$url .= '&thumb=' . $request['thumb'];
		}

		$pagination = new Pagination();
		$pagination->total = $image_total;
		$pagination->page = $page;
		$pagination->limit = 16;
		$pagination->url = url('filemanager?loading=yes' . $url . '&page={page}');

		$data['pagination'] = $pagination->render();

		return view('common.filemanager.index', compact('data'));
	}

	public function postUpload() {
		$request = \Request::all();
		$json = array();

		// Make sure we have the correct directory
		if (isset($request['directory'])) {
			$directory = rtrim($this->data->dir_image . 'catalog/' . str_replace(array('../', '..\\', '..'), '', $request['directory']), '/');
		} else {
			$directory = $this->data->dir_image . 'catalog';
		}

		// Check its a directory
		if (!is_dir($directory)) {
			$json['error'] = $directory.'Warning: Directory does not exist!';
		}

		if (!$json) {
			if (!empty($_FILES['file']['name']) && is_file($_FILES['file']['tmp_name'])) {
				// Sanitize the filename
				$filename = basename(html_entity_decode($_FILES['file']['name'], ENT_QUOTES, 'UTF-8'));

				// Validate the filename length
				if ((strlen($filename) < 3) || (strlen($filename) > 255)) {
					$json['error'] = 'Warning: Filename must be a between 3 and 255!';
				}

				// Allowed file extension types
				$allowed = array(
					'jpg',
					'jpeg',
					'gif',
					'png'
				);

				if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
					$json['error'] = 'Warning: Incorrect file type!';
				}

				// Allowed file mime types
				$allowed = array(
					'image/jpeg',
					'image/pjpeg',
					'image/png',
					'image/x-png',
					'image/gif'
				);

				if (!in_array($_FILES['file']['type'], $allowed)) {
					$json['error'] = 'Warning: Incorrect file type!';
				}

				// Check to see if any PHP files are trying to be uploaded
				$content = file_get_contents($_FILES['file']['tmp_name']);

				if (preg_match('/\<\?php/i', $content)) {
					$json['error'] = 'Warning: Incorrect file type!';
				}

				// Return any upload error
				if ($_FILES['file']['error'] != UPLOAD_ERR_OK) {
					$json['error'] = 'Error: error_upload_' . $_FILES['file']['error'];
				}
			} else {
				$json['error'] = 'Warning: File could not be uploaded for an unknown reason!';
			}
		}

		if (!$json) {
			move_uploaded_file($_FILES['file']['tmp_name'], $directory . '/' . $filename);

			$json['success'] = 'Success: Your file has been uploaded!';
		}

		return json_encode($json);
	}

	public function resize($filename, $width, $height) {
		if (!is_file($this->data->dir_image . $filename)) {
			return;
		}

		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		$old_image = $filename;
		$new_image = 'cache/' . substr($filename, 0, strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;

		if (!is_file($this->data->dir_image . $new_image) || (filectime($this->data->dir_image . $old_image) > filectime($this->data->dir_image . $new_image))) {
			$path = '';

			$directories = explode('/', dirname(str_replace('../', '', $new_image)));

			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;

				if (!is_dir($this->data->dir_image . $path)) {
					@mkdir($this->data->dir_image . $path, 0777);
				}
			}

			list($width_orig, $height_orig) = getimagesize($this->data->dir_image . $old_image);

			if ($width_orig != $width || $height_orig != $height) {
				$image = new Image($this->data->dir_image . $old_image);
				$image->resize($width, $height);
				$image->save($this->data->dir_image . $new_image);
			} else {
				copy($this->data->dir_image . $old_image, $this->data->dir_image . $new_image);
			}
		}

		// if ($this->request->server['HTTPS']) {
		// 	return HTTPS_CATALOG . 'image/' . $new_image;
		// } else {
		// 	return HTTP_CATALOG . 'image/' . $new_image;
		// }

		return $this->data->http_catalog. '/images/' . $new_image;
	}

}
