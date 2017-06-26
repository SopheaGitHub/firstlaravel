<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language;
use DB;

class Documentation extends Model {

	protected $table = 'documentation';
	protected $fillable = ['image', 'parent_id', 'top', 'column', 'sort_order', 'status'];

	public function getDocumentation($documentation_id) {
		$db = DB::table(DB::raw('
				(SELECT DISTINCT
					c.*, dd2.name, (
						SELECT
							GROUP_CONCAT(
								dd1. NAME
								ORDER BY
									LEVEL SEPARATOR \'&nbsp;&nbsp;&gt;&nbsp;&nbsp;\'
							)
						FROM
							documentation_path dp
						LEFT JOIN documentation_description dd1 ON (
							dp.path_id = dd1.documentation_id
							AND dp.documentation_id != dp.path_id AND dd1.language_id = \'1\'
						)
						WHERE
							dp.documentation_id = c.documentation_id
						GROUP BY
							dp.documentation_id
					) AS path,
					(
						SELECT DISTINCT
							keyword
						FROM
							url_alias
						WHERE
							QUERY = \'documentation_id='.$documentation_id.'\'
					) AS keyword
				FROM
					documentation c
				LEFT JOIN documentation_description dd2 ON (
					c.documentation_id = dd2.documentation_id AND dd2.language_id = \'1\'
				)
				WHERE
					c.documentation_id = \''.$documentation_id.'\') AS documentation
			'));
		$result = $db->first();
		return $result;
	}

	public function getDocumentationDescriptions($documentation_id) {
		$result = DB::table(DB::raw('
				(SELECT
					cd.*
				FROM
					documentation AS c
				LEFT JOIN documentation_description AS cd ON cd.documentation_id = c.documentation_id
				WHERE c.documentation_id = "'.$documentation_id.'") AS documentation_description
			'))->get();
		return $result;
	}

	public function getDocumentationToLayouts($documentation_id) {
		$result = DB::table(DB::raw('
				(SELECT
					ctl.*
				FROM
					documentation AS c
				LEFT JOIN documentation_to_layout AS ctl ON ctl.documentation_id = c.documentation_id
				WHERE c.documentation_id = "'.$documentation_id.'") AS documentation_to_layout
			'))->get();
		return $result;
	}

	public function getAllDocumentation($filter_data=[]) {
		$db = DB::table(DB::raw('
				(
					SELECT
						dp.documentation_id AS documentation_id,
						GROUP_CONCAT(
							dd1. name
							ORDER BY
								dp. LEVEL SEPARATOR \'&nbsp;&nbsp;&gt;&nbsp;&nbsp;\'
						) AS name,
						d1.parent_id,
						d1.sort_order AS sort_order
					FROM
						documentation_path dp
					LEFT JOIN documentation d1 ON (
						dp.documentation_id = d1.documentation_id
					)
					LEFT JOIN documentation c2 ON (dp.path_id = c2.documentation_id)
					LEFT JOIN documentation_description dd1 ON (dp.path_id = dd1.documentation_id AND dd1.language_id = \'1\')
					LEFT JOIN documentation_description dd2 ON (dp.documentation_id = dd2.documentation_id AND dd2.language_id = \'1\')
					GROUP BY
						dp.documentation_id
				) AS documentation
			'))->orderBy($filter_data['sort'], $filter_data['order']);
		return $db;
	}

	public function getAutocompleteDocumentation($filter_data=[]) {
		$db = DB::table(DB::raw('
				(
					SELECT
						dp.documentation_id AS documentation_id,
						GROUP_CONCAT(
							dd1. name
							ORDER BY
								dp. LEVEL SEPARATOR \'&nbsp;&nbsp;&gt;&nbsp;&nbsp;\'
						) AS name,
						d1.parent_id,
						d1.sort_order
					FROM
						documentation_path dp
					LEFT JOIN documentation d1 ON (
						dp.documentation_id = d1.documentation_id
					)
					LEFT JOIN documentation c2 ON (dp.path_id = c2.documentation_id)
					LEFT JOIN documentation_description dd1 ON (dp.path_id = dd1.documentation_id AND dd1.language_id = \'1\')
					LEFT JOIN documentation_description dd2 ON (dp.documentation_id = dd2.documentation_id AND dd2.language_id = \'1\')
					GROUP BY
						dp.documentation_id
				) AS documentation
			'));
		if ($filter_data['filter_name']!='') {
			$db->where('name', 'like', '%'.$filter_data['filter_name'].'%');
		}
		$db->orderBy($filter_data['sort'], $filter_data['order'])->take($filter_data['limit']);
		$result = $db->get();
		return $result;
	}

	public function insertDocumentationDescription($datas=[]) {
		$sql = '';
		if(isset($datas['documentation_description_datas']) && count($datas['documentation_description_datas']) > 0) {
			foreach ($datas['documentation_description_datas'] as $language_id => $documentation_description) {
				$sql .= " INSERT INTO `documentation_description`(`documentation_id`, `language_id`, `name`, `description`, `meta_title`, `meta_description`, `meta_keyword`) VALUES ('".$datas['documentation_id']."', '".$language_id."', '".$documentation_description['name']."', '".$documentation_description['description']."', '".$documentation_description['meta_title']."', '".$documentation_description['meta_description']."', '".$documentation_description['meta_keyword']."'); ";
			}
			DB::connection()->getPdo()->exec($sql);
		}
	}

	public function insertDocumentationToLayout($datas=[]) {
		$sql = '';
		if(isset($datas['documentation_to_layout_datas']) && count($datas['documentation_to_layout_datas']) > 0) {
			foreach ($datas['documentation_to_layout_datas'] as $documentation_to_layout) {
				$sql .= " INSERT INTO `documentation_to_layout`(`documentation_id`, `website_id`, `layout_id`) VALUES ('".$documentation_to_layout['documentation_id']."', '".$documentation_to_layout['website_id']."', '".$documentation_to_layout['layout_id']."'); ";
			}
			DB::connection()->getPdo()->exec($sql);
		}
	}

	public function insertDocumentationPath($datas=[]) {
		// MySQL Hierarchical Data Closure Table Pattern
		$level = 0;
		$sql = '';

		$documentation_path = DB::table('documentation_path')->where('documentation_id', '=', $datas['parent_id'])->orderBy('level', 'asc')->get();

		foreach ($documentation_path as $result) {
			$sql .= "INSERT INTO `documentation_path` SET `documentation_id` = '" . $datas['documentation_id'] . "', `path_id` = '" . $result->path_id . "', `level` = '" . $level . "'; ";
			$level++;
		}

		$sql .= "INSERT INTO `documentation_path` SET `documentation_id` = '" . $datas['documentation_id'] . "', `path_id` = '" . $datas['documentation_id'] . "', `level` = '" . $level . "'; ";
		DB::connection()->getPdo()->exec($sql);
	}

	public function deletedDocumentationDescription($documentation_id) {
		DB::table('documentation_description')->where('documentation_id', '=', $documentation_id)->delete();
	}

	public function deletedUrlAlias($documentation_id) {
		DB::table('url_alias')->where('query', '=', 'documentation_id='.$documentation_id)->delete();
	}

	public function deletedDocumentationToLayout($documentation_id) {
		DB::table('documentation_to_layout')->where('documentation_id', '=', $documentation_id)->delete();
	}

	public function deletedDocumentationPath($documentation_id) {
		DB::table('documentation_path')->where('documentation_id', '=', $documentation_id)->delete();
	}

	public function destroyDocumentation($array_id) {
		DB::table('documentation')->whereIn('documentation_id', $array_id)->delete();
		DB::table('documentation_path')->whereIn('documentation_id', $array_id)->delete();
		DB::table('documentation_to_layout')->whereIn('documentation_id', $array_id)->delete();
		DB::table('documentation_description')->whereIn('documentation_id', $array_id)->delete();
	}

	public function validationForm($datas=[]) {
		$this->language = new Language();
		$languages = $this->language->getLanguages(['sort'=>'name', 'order'=>'asc'])->get();
		$error = false;
		$rules = [];
		$messages = [];

		foreach ($languages as $language) {
			$rules['documentation_description.'.$language->language_id.'.name'] = 'required';
			$rules['documentation_description.'.$language->language_id.'.description'] = 'required';
			$description_len = str_replace(['<p>','</p>','<br>','</br>'], ['','','',''], $datas['request']['documentation_description'][$language->language_id]['description']);
			if(mb_strlen($description_len) < 5){
				$rules['description_len'.$language->language_id] = 'required';
			}
			$rules['documentation_description.'.$language->language_id.'.meta_title'] = 'required';
		}
		$rules['column'] = 'integer';
		$rules['sort_order'] = 'integer';

		foreach ($languages as $language) {
			$messages['documentation_description.'.$language->language_id.'.name.required'] = 'The <b>Documentation Name "'.$language->name.'"</b> field is required.';
			$messages['documentation_description.'.$language->language_id.'.description.required'] = 'The <b>Description "'.$language->name.'"</b> field is required.';
			$messages['description_len'.$language->language_id.'.required'] = 'The <b>Description "'.$language->name.'"</b> must be at least 5 characters.';
			$messages['documentation_description.'.$language->language_id.'.meta_title.required'] = 'The <b>Meta Tag Title "'.$language->name.'"</b> field is required.';
		}

		$messages['column.integer'] = 'The <b>Column</b> must be an integer.';
		$messages['sort_order.integer'] = 'The <b>Sort Order</b> must be an integer.';

		$validator = \Validator::make($datas['request'], $rules, $messages);
		if ($validator->fails()) {
			$error = ['error'=>'1','success'=>'0','msg'=>'Warning : save documentation unsuccessfully!','validatormsg'=>$validator->messages()];
        }
		return $error;
	}

}
