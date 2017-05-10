<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layout extends Model {

	protected $table = 'layout';
	protected $fillable = ['name'];

	public function getLayouts($filter_data=[]) {
		$db = Layout::orderBy($filter_data['sort'], $filter_data['order']);
		return $db;
	}

	public function getLayoutRoutes($layout_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_route WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->rows;
	}

}
