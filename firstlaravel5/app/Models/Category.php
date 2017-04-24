<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Category extends Model {

	protected $table = 'category';
	protected $fillable = ['image', 'parent_id', 'top', 'column', 'sort_order', 'status'];

	public function getCategories($filter_data=[]) {
		$db = DB::table('category as c')
		->select('c.category_id as category_id', 'c.sort_order as sort_order', 'cd.name as name')
		->join('category_description as cd', 'cd.category_id', '=', 'c.category_id')
		->where('cd.language_id', '=', '1')
		->orderBy($filter_data['sort'], $filter_data['order']);
		return $db;
	}

}
