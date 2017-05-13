<?php namespace App\Http\Controllers\Website\Design;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Language;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\Common\FilemanagerController;

use Illuminate\Http\Request;
use DB;

class BannersController extends Controller {

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
		$this->banner = new Banner();
		$this->language = new Language();
		$this->config = new ConfigController();
		$this->filemanager = new FilemanagerController();
		$this->data->web_title = 'Banners';
		$this->data->breadcrumbs = [
			'home'	=> ['text' => 'Home', 'href' => url('home')],
			'banner'	=> ['text' => 'Banners', 'href' => url('banners')]
		];
		$this->data->dir_image = $this->config->dir_image;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$this->data->actionlist = url('/banners/list');
		$this->data->add_banner = url('/banners/create');
		return view('website.design.banner.index', ['data' => $this->data]);
	}

	public function getList() {
		$request = \Request::all();
		$this->data->edit_banner = url('/banners/edit');

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

		$this->data->banners = $this->banner->getBanners($filter_data)->paginate(10)->setPath(url('/banners'))->appends($paginate_url);

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
		$this->data->sort_status = '?sort=status'.$url;

		// define column
		$this->data->column_name = "Banner Name";
		$this->data->column_status = "Status";
		$this->data->column_action = "Action";

		return view('website.design.banner.list', ['data' => $this->data]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getCreate()
	{
		$datas = [
			'action' => url('/banners/store'),
			'titlelist'	=> 'Add Banner'
		];
		echo $this->getBannerForm($datas);
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
		$validationError = $this->banner->validationForm(['request'=>$request]);
		if($validationError) {
			return \Response::json($validationError);
		}

		DB::beginTransaction();
		try {
			$bannerDatas = [
				'name'			=> $request['name'],
				'status'		=> $request['status']
			];
			$banner = $this->banner->create($bannerDatas);

			$banner_imageDatas = [
				'banner_id'		=> $banner->id,
				'banner_image' 	=> ((isset($request['banner_image']))? $request['banner_image']:[])
			];

			$banner_image = $this->banner->insertBannerImage($banner_imageDatas);

			DB::commit();
			$return = ['error'=>'0','success'=>'1','action'=>'create','msg'=>'Success : save banner successfully!', 'post'=>$request];
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
	public function getEdit($banne_id)
	{
		$this->data->banner = $this->banner->getBanner($banne_id);
		$banner_images = $this->banner->getBannerImages($banne_id);

		$data['banner_images'] = [];

		foreach ($banner_images as $banner_image) {
			if (is_file($this->data->dir_image . $banner_image['image'])) {
				$image = $banner_image['image'];
				$thumb = $banner_image['image'];
			} else {
				$image = '';
				$thumb = 'no_image.png';
			}

			$data['banner_images'][] = [
				'banner_image_description' => $banner_image['banner_image_description'],
				'link'                     => $banner_image['link'],
				'image'                    => $image,
				'thumb'                    => $this->filemanager->resize($thumb, 100, 100),
				'sort_order'               => $banner_image['sort_order']
			];
		}

		$this->data->banner_images = $data['banner_images'];
		
		$datas = [
			'action' => url('/banners/update/'.$banne_id),
			'titlelist'	=> 'Edit Banner',
			'banner' => $this->data->banner,
			'banner_images'	=> $this->data->banner_images
		];
		echo $this->getBannerForm($datas);
		exit();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function postUpdate($banner_id)
	{
		$request = \Request::all();
		$validationError = $this->banner->validationForm(['request'=>$request]);
		if($validationError) {
			return \Response::json($validationError);
		}

		DB::beginTransaction();
		try {

			$bannerDatas = [
				'name'			=> $request['name'],
				'status'		=> $request['status']
			];
			$banner = $this->banner->where('banner_id', '=', $banner_id)->update($bannerDatas);

			$clear_banner_image = $this->banner->deletedBannerImage($banner_id);
			$clear_banner_image_description = $this->banner->deletedBannerImageDescription($banner_id);
			$banner_imageDatas = [
				'banner_id'		=> $banner_id,
				'banner_image' 	=> $request['banner_image']
			];

			$banner_image = $this->banner->insertBannerImage($banner_imageDatas);

			DB::commit();
			$return = ['error'=>'0','success'=>'1','action'=>'edit','msg'=>'Success : save banner successfully!'];
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

	public function getBannerForm($datas=[]) {
		$this->data->go_back = url('/banners');
		$this->data->status = $this->config->status;
		$this->data->languages = $this->language->getLanguages(['sort'=>'name', 'order'=>'asc'])->get();

		// define entry
		$this->data->entry_name = 'Banner Name';
		$this->data->entry_status = 'Status';
		$this->data->entry_title = 'Title';
		$this->data->entry_link = 'Link';
		$this->data->entry_image = 'Image';
		$this->data->entry_sort_order = 'Sort Order';

		$this->data->button_banner_add = 'Add Banner';
		$this->data->button_remove = 'Remove';

		if(isset($datas['banner'])) {
			$this->data->name = $datas['banner']->name;
			$this->data->banner_status = $datas['banner']->status;
		} else {
			$this->data->name = '';
			$this->data->banner_status = '1';
		}

		if(isset($datas['banner_images'])) {
			$this->data->banner_images = $datas['banner_images'];
		} else {
			$this->data->banner_images = [];
		}

		$this->data->placeholder = $this->filemanager->resize('no_image.png', 100, 100);

		$this->data->action = (($datas['action'])? $datas['action']:'');
		$this->data->titlelist = (($datas['titlelist'])? $datas['titlelist']:'');

		return view('website.design.banner.form', ['data' => $this->data]);
	}

}
