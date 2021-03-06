<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language;
use DB;

class Post extends Model {

	protected $table = 'post';
	protected $fillable = ['post_type_id', 'author_id', 'updated_by_author_id', 'image', 'sort_order', 'status', 'viewed'];

	public function getPost($post_id) {
		$result = DB::table(DB::raw('
				(SELECT
				DISTINCT *, (
						SELECT
							keyword
						FROM
							url_alias
						WHERE
							QUERY = \'post_id=\'"'.$post_id.'"
					) AS keyword
					, (SELECT title FROM post_description AS pd WHERE pd.post_id = "'.$post_id.'" AND pd.language_id=\'1\') AS title
				FROM
					post
				WHERE
					post_id = "'.$post_id.'") AS post
			'))->first();
		return $result;
	}

	public function getPostDescriptions($post_id) {
		$result = DB::table(DB::raw('
				(SELECT
					pd.*
				FROM
					post AS p
				LEFT JOIN post_description AS pd ON pd.post_id = p.post_id
				WHERE p.post_id = "'.$post_id.'") AS post_description
			'))->get();
		return $result;
	}

	public function getPostToLayouts($post_id) {
		$result = DB::table(DB::raw('
				(SELECT
					ptl.*
				FROM
					post AS p
				LEFT JOIN post_to_layout AS ptl ON ptl.post_id = p.post_id
				WHERE p.post_id = "'.$post_id.'") AS post_to_layout
			'))->get();
		return $result;
	}

	public function getPosts($filter_data=[]) {
		$db = DB::table(DB::raw('
                        (
							SELECT
								p.post_id AS post_id,
								p.created_at AS created_at,
								pd.title AS title,
								u.name AS author_name,
								p.status AS status
							FROM
								post AS p
							INNER JOIN users AS u ON u.id = p.author_id
							LEFT JOIN post_description AS pd ON p.post_id = pd.post_id AND pd.language_id = \'1\'
						) AS posts
                    '))->orderBy($filter_data['sort'], $filter_data['order']);
		return $db;
	}

	public function getPostCategories($post_id) {
		$result = DB::table('post_to_category')->where('post_id', '=', $post_id)->get();
		return $result;
	}

	public function getAutocompletePosts($filter_data=[]) {
		$db = DB::table(DB::raw('
				(
					SELECT
						p.post_id AS post_id,
						p.created_at AS created_at,
						pd.title AS title,
						u.name AS author_name,
						p.status AS status
					FROM
						post AS p
					INNER JOIN users AS u ON u.id = p.author_id
					LEFT JOIN post_description AS pd ON p.post_id = pd.post_id AND pd.language_id = \'1\'
				) AS posts
			'));
		if ($filter_data['filter_title']!='') {
			$db->where('title', 'like', '%'.$filter_data['filter_title'].'%');
		}
		$db->orderBy($filter_data['sort'], $filter_data['order'])->take($filter_data['limit']);
		$result = $db->get();
		return $result;
	}

	public function insertPostDescription($datas=[]) {
		$sql = '';
		if(isset($datas['post_description_datas']) && count($datas['post_description_datas']) > 0) {
			foreach ($datas['post_description_datas'] as $language_id => $post_description) {
				$sql .= " INSERT INTO `post_description`(`post_id`, `language_id`, `title`, `description`, `tag`, `meta_title`, `meta_description`, `meta_keyword`) VALUES ('".$datas['post_id']."', '".$language_id."', '".$post_description['title']."', '".$post_description['description']."', '".$post_description['tag']."', '".$post_description['meta_title']."', '".$post_description['meta_description']."', '".$post_description['meta_keyword']."'); ";
			}
			DB::connection()->getPdo()->exec($sql);
		}
	}

	public function insertPostToLayout($datas=[]) {
		$sql = '';
		if(isset($datas['post_to_layout_datas']) && count($datas['post_to_layout_datas']) > 0) {
			foreach ($datas['post_to_layout_datas'] as $post_to_layout) {
				$sql .= " INSERT INTO `post_to_layout`(`post_id`, `website_id`, `layout_id`) VALUES ('".$post_to_layout['post_id']."', '".$post_to_layout['website_id']."', '".$post_to_layout['layout_id']."'); ";
			}
			DB::connection()->getPdo()->exec($sql);
		}
	}

	public function insertPostCategory($datas=[]) {
		$sql = '';
		if(isset($datas['post_category_datas']) && count($datas['post_category_datas']) > 0) {
			foreach ($datas['post_category_datas'] as $post_category_value) {
				$sql .= " INSERT INTO `post_to_category`(`post_id`, `category_id`) VALUES ('".$datas['post_id']."', '".$post_category_value."'); ";
			}
			DB::connection()->getPdo()->exec($sql);
		}
	}

	public function deletedPostDescription($post_id) {
		DB::table('post_description')->where('post_id', '=', $post_id)->delete();
	}

	public function deletedUrlAlias($post_id) {
		DB::table('url_alias')->where('query', '=', 'post_id='.$post_id)->delete();
	}

	public function deletedPostToLayout($post_id) {
		DB::table('post_to_layout')->where('post_id', '=', $post_id)->delete();
	}

	public function deletedPostToCategory($post_id) {
		DB::table('post_to_category')->where('post_id', '=', $post_id)->delete();
	}

	public function destroyPosts($array_id) {
		DB::table('post')->whereIn('post_id', $array_id)->delete();
		DB::table('post_to_category')->whereIn('post_id', $array_id)->delete();
		DB::table('post_description')->whereIn('post_id', $array_id)->delete();
		DB::table('post_to_layout')->whereIn('post_id', $array_id)->delete();
	}

	public function validationForm($datas=[]) {
		$this->language = new Language();
		$languages = $this->language->getLanguages(['sort'=>'name', 'order'=>'asc'])->get();
		$error = false;
		$rules = [];
		$messages = [];

		foreach ($languages as $language) {
			$rules['post_description.'.$language->language_id.'.title'] = 'required';
			$rules['post_description.'.$language->language_id.'.description'] = 'required';
			$description_len = str_replace(['<p>','</p>','<br>','</br>'], ['','','',''], $datas['request']['post_description'][$language->language_id]['description']);
			if(mb_strlen($description_len) < 5){
				$rules['description_len'.$language->language_id] = 'required';
			}
			$rules['post_description.'.$language->language_id.'.meta_title'] = 'required';
		}
		$rules['sort_order'] = 'integer';

		foreach ($languages as $language) {
			$messages['post_description.'.$language->language_id.'.title.required'] = 'The <b>Post Title "'.$language->name.'"</b> field is required.';
			$messages['post_description.'.$language->language_id.'.description.required'] = 'The <b>Description "'.$language->name.'"</b> field is required.';
			$messages['description_len'.$language->language_id.'.required'] = 'The <b>Description "'.$language->name.'"</b> must be at least 5 characters.';
			$messages['post_description.'.$language->language_id.'.meta_title.required'] = 'The <b>Meta Tag Title "'.$language->name.'"</b> field is required.';
		}

		$messages['sort_order.integer'] = 'The <b>Sort Order</b> must be an integer.';

		$validator = \Validator::make($datas['request'], $rules, $messages);
		if ($validator->fails()) {
			$error = ['error'=>'1','success'=>'0','msg'=>'Warning : save post unsuccessfully!','validatormsg'=>$validator->messages()];
        }
		return $error;
	}

}
