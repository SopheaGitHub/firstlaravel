<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Post extends Model {

	protected $table = 'post';
	protected $fillable = ['post_type_id', 'author_id', 'image', 'sort_order', 'status', 'viewed'];

	public function getPosts($filter_data=[]) {
		$db = DB::table('post as p')
		->select('p.post_id as post_id', 'pd.title as post_title')
		->join('post_description as pd', 'pd.post_id', '=', 'p.post_id')
		->where('pd.language_id', '=', '1')
		->orderBy($filter_data['sort'], $filter_data['order']);
		return $db;
	}

}
