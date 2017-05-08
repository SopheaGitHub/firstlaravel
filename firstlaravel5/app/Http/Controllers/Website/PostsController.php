<?php namespace App\Http\Controllers\Website;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Language;
use App\Models\Layout;
use App\Models\UrlAlias;
use App\Models\Category;
use App\Http\Controllers\Common\FilemanagerController;

use Illuminate\Http\Request;
use DB;
use Auth;

class PostsController extends Controller {

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
		$this->post = New Post();
		$this->language = new Language();
		$this->layout = new Layout();
		$this->url_alias = new UrlAlias();
		$this->category = new Category();
		$this->filemanager = new FilemanagerController();
		$this->data->web_title = 'Posts';
		$this->data->breadcrumbs = [
			'home'	=> ['text' => 'Home', 'href' => url('home')],
			'post'	=> ['text' => 'Posts', 'href' => url('posts')]
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
		$this->data->actionlist = url('/posts/list');
		$this->data->add_post = url('/posts/create');
		return view('website.post.index', ['data' => $this->data]);
	}

	public function getList() {
		$request = \Request::all();
		$this->data->edit_post = url('/posts/edit');

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

		$this->data->posts = $this->post->getPosts($filter_data)->paginate(10)->setPath(url('/posts'))->appends($paginate_url);

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
		$this->data->sort_author_name = '?sort=author_name'.$url;
		$this->data->sort_status = '?sort=status'.$url;

		// define column
		$this->data->column_title = "Post Title";
		$this->data->column_categories = "Categories";
		$this->data->column_author_name = "Author Name";
		$this->data->column_status = "Status";
		$this->data->column_action = "Action";

		return view('website.post.list', ['data' => $this->data]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getCreate()
	{
		$datas = [
			'action' => url('/posts/store'),
			'titlelist'	=> 'Add Post'
		];
		echo $this->getPostForm($datas);
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
		$validationError = $this->post->validationForm(['request'=>$request]);
		if($validationError) {
			return \Response::json($validationError);
		}

		$author_id = ((Auth::check())? Auth::user()->id:'0');
		DB::beginTransaction();
		try {
			$postDatas = [
				'author_id'		=> $author_id,
				'image'			=> $request['image'],
				'sort_order'	=> $request['sort_order'],
				'status'		=> $request['status']
			];
			$post = $this->post->create($postDatas);

			$post_descriptionDatas = [
				'post_id'		=> $post->id,
				'post_description_datas'	=> $request['post_description']
			];

			$post_description = $this->post->insertPostDescription($post_descriptionDatas);

			$url_aliasDatas = [
				'query'		=> 'post_id='.$post->id,
				'keyword'	=> $request['keyword']
			];
			$url_alias = $this->url_alias->create($url_aliasDatas);

			$post_to_layoutDatas['post_to_layout_datas'][] = [
				'post_id'		=> $post->id,
				'website_id'	=> '1',
				'layout_id'		=> $request['post_layout'][0]
			];

			$post_to_layout = $this->post->insertPostToLayout($post_to_layoutDatas);

			$post_to_categoryDatas = [
				'post_id'		=> $post->id,
				'post_category_datas'	=> $request['post_category']
			];

			$post_category = $this->post->insertPostCategory($post_to_categoryDatas);

			DB::commit();
			$return = ['error'=>'0','success'=>'1','action'=>'create','msg'=>'Success : save post successfully!'];
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
	public function getEdit($post_id)
	{
		$this->data->post = $this->post->getPost($post_id);
		$this->data->post_descriptions = $this->post->getPostDescriptions($post_id);
		$this->data->post_to_layouts = $this->post->getPostToLayouts($post_id);

		$post_to_categories = $this->post->getPostCategories($post_id);
		$data['post_categories'] = [];

		foreach ($post_to_categories as $post_to_category) {
			$category_info = $this->category->getCategory($post_to_category->category_id);

			if ($category_info) {
				$data['post_categories'][] = [
					'category_id' => $category_info->category_id,
					'name' => ($category_info->path) ? $category_info->path . ' &gt; ' . $category_info->name : $category_info->name
				];
			}
		}

		$datas = [
			'action' => url('/posts/update/'.$post_id),
			'titlelist'	=> 'Edit Post',
			'post' => $this->data->post,
			'post_descriptions' => $this->data->post_descriptions,
			'post_to_layouts' => $this->data->post_to_layouts,
			'post_to_categories' => $data['post_categories']
		];
		echo $this->getPostForm($datas);
		exit();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function postUpdate($post_id)
	{
		$request = \Request::all();
		$validationError = $this->post->validationForm(['request'=>$request]);
		if($validationError) {
			return \Response::json($validationError);
		}

		$author_id = ((Auth::check())? Auth::user()->id:'0');
		DB::beginTransaction();
		try {
			$postDatas = [
				'updated_by_author_id'		=> $author_id,
				'image'			=> $request['image'],
				'sort_order'	=> $request['sort_order'],
				'status'		=> $request['status']
			];
			$post = $this->post->where('post_id', '=', $post_id)->update($postDatas);

			$clear_post_description = $this->post->deletedPostDescription($post_id);
			$post_descriptionDatas = [
				'post_id'		=> $post_id,
				'post_description_datas'	=> $request['post_description']
			];

			$post_description = $this->post->insertPostDescription($post_descriptionDatas);

			$clear_url_alias = $this->post->deletedUrlAlias($post_id);
			$url_aliasDatas = [
				'query'		=> 'post_id='.$post_id,
				'keyword'	=> $request['keyword']
			];
			$url_alias = $this->url_alias->create($url_aliasDatas);

			$clear_post_to_layout = $this->post->deletedPostToLayout($post_id);
			$post_to_layoutDatas['post_to_layout_datas'][] = [
				'post_id'		=> $post_id,
				'website_id'	=> '1',
				'layout_id'		=> $request['post_layout'][0]
			];

			$post_to_layout = $this->post->insertPostToLayout($post_to_layoutDatas);

			$clear_post_to_layout = $this->post->deletedPostToCategory($post_id);
			$post_to_categoryDatas = [
				'post_id'		=> $post_id,
				'post_category_datas'	=> $request['post_category']
			];

			$post_category = $this->post->insertPostCategory($post_to_categoryDatas);

			DB::commit();
			$return = ['error'=>'0','success'=>'1','action'=>'edit','msg'=>'Success : save post successfully!'];
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

	public function getPostForm($datas=[]) {
		$this->data->go_autocomplete = url('/categories/autocomplete');
		$this->data->go_back = url('/posts');
		$this->data->languages = $this->language->getLanguages(['sort'=>'name', 'order'=>'asc'])->get();
		$this->data->layouts = $this->layout->orderBy('name', 'asc')->lists('name', 'layout_id');
		$this->data->status = [
			'1' => 'Enabled',
			'0'	=> 'Disabled'
		];

		// define tap
		$this->data->tab_general = 'General';
		$this->data->tab_data = 'Data';
		$this->data->tab_links = 'Links';
		$this->data->tab_design = 'Design';

		// define entry
		$this->data->entry_title = 'Post Title';
		$this->data->entry_description = 'Description';
		$this->data->entry_meta_title = 'Meta Tag Title';
		$this->data->entry_meta_description = 'Meta Tag Description';
		$this->data->entry_meta_keyword = 'Meta Tag Keywords';
		$this->data->entry_tag = 'Post Tags';

		$this->data->entry_keyword = 'SEO URL';
		$this->data->entry_image = 'Image';
		$this->data->entry_status = 'Status';
		$this->data->entry_sort_order = 'Sort Order';

		$this->data->entry_category = 'Categories';

		$this->data->entry_layout = 'Layout';

		// define input title
		$this->data->title_keyword = 'Do not use spaces, instead replace spaces with - and make sure the keyword is globally unique.';
		$this->data->title_category = '(Autocomplete)';

		$this->data->text_none = '-- None --';

		if(isset($datas['post'])) {
			$this->data->image = $datas['post']->image;
			$this->data->sort_order = $datas['post']->sort_order;
			$this->data->post_status = $datas['post']->status;
			$this->data->keyword = $datas['post']->keyword;
		}else {
			$this->data->image = '';
			$this->data->sort_order = '0';
			$this->data->post_status = '1';
			$this->data->keyword = '';
		}

		if(isset($datas['post_descriptions'])) {
			foreach ($datas['post_descriptions'] as $description) {
				$this->data->post_description[$description->language_id]['title'] = $description->title;
				$this->data->post_description[$description->language_id]['description'] = $description->description;
				$this->data->post_description[$description->language_id]['meta_title'] = $description->meta_title;
				$this->data->post_description[$description->language_id]['meta_description'] = $description->meta_description;
				$this->data->post_description[$description->language_id]['meta_keyword'] = $description->meta_keyword;
				$this->data->post_description[$description->language_id]['tag'] = $description->tag;
			}
		}else {
			foreach ($this->data->languages as $language) {
				$this->data->post_description[$language->language_id]['title'] = '';
				$this->data->post_description[$language->language_id]['description'] = '';
				$this->data->post_description[$language->language_id]['meta_title'] = '';
				$this->data->post_description[$language->language_id]['meta_description'] = '';
				$this->data->post_description[$language->language_id]['meta_keyword'] = '';
				$this->data->post_description[$language->language_id]['tag'] = '';
			}
		}

		if(isset($datas['post_to_layouts'])) {
			$this->data->post_layout = $datas['post_to_layouts'];
		}else {
			$this->data->post_layout = [];
		}

		if(isset($datas['post_to_categories'])) {
			$this->data->post_categories = $datas['post_to_categories'];
		}else {
			$this->data->post_categories = [];
		}

		if ($this->data->image && is_file($this->data->dir_image . $this->data->image)) {
			$this->data->thumb = $this->filemanager->resize($this->data->image, 100, 100);
		} else {
			$this->data->thumb = $this->filemanager->resize('no_image.png', 100, 100);
		}

		$this->data->placeholder = $this->filemanager->resize('no_image.png', 100, 100);

		$this->data->action = (($datas['action'])? $datas['action']:'');
		$this->data->titlelist = (($datas['titlelist'])? $datas['titlelist']:'');

		return view('website.post.form', ['data' => $this->data]);
	}

}
