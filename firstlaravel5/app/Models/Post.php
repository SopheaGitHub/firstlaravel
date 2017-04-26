<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Post extends Model {

	protected $table = 'post';
	protected $fillable = ['post_type_id', 'author_id', 'image', 'sort_order', 'status', 'viewed'];

	public function getPosts($filter_data=[]) {
		$db = DB::table(DB::raw('
                        (
							SELECT
								p.post_id AS post_id,
								p.created_at AS created_at,
								pd.title AS post_title,
								pt.name AS post_type_name,
								cg.category_name AS category_name,
								u.name AS author_name
							FROM
								post AS p
							INNER JOIN users AS u ON u.id = p.author_id
							LEFT JOIN post_description AS pd ON p.post_id = pd.post_id AND pd.language_id = \'1\'
							INNER JOIN post_type AS pt ON pt.post_type_id = p.post_type_id
							LEFT JOIN (
								SELECT
									c.category_id AS category_id,
									cd. NAME AS category_name
								FROM
									category AS c
								INNER JOIN category_description AS cd ON cd.category_id = c.category_id AND cd.language_id = \'1\'
							) AS cg ON cg.category_id = p.category_id
						) AS posts
                    '))->orderBy($filter_data['sort'], $filter_data['order']);
		return $db;
	}

}
