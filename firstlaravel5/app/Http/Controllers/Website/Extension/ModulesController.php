<?php namespace App\Http\Controllers\Website\Extension;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Extension;
use App\Models\Setting;
use App\Http\Controllers\ConfigController;

use Illuminate\Http\Request;
use DB;

class ModulesController extends Controller {

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
		$this->module = new Module();
		$this->extension = new Extension();
		$this->setting = new Setting();
		$this->config = new ConfigController();
		$this->data->web_title = 'Modules';
		$this->data->breadcrumbs = [
			'home'	=> ['text' => 'Home', 'href' => url('home')],
			'module'	=> ['text' => 'Modules', 'href' => url('modules')]
		];
		$this->data->dir_application_http = $this->config->dir_application_http;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$this->data->actionlist = url('/modules/list');
		$this->data->add_module = url('/modules/create');
		return view('website.extension.module.index', ['data' => $this->data]);
	}

	public function getList() {
		$this->data->delete = url('extension/module/delete');
		$extensions = $this->extension->getInstalled('module');

		foreach ($extensions as $key => $value) {
			if (!file_exists($this->data->dir_application_http . 'Controllers/Module/Module'.$value.'Controller.php')) {
				$this->extension->uninstallExtension('module', $value);
				unset($extensions[$key]);
				$this->module->destroyModulesByCode($value);
				$this->setting->destroySetting($value);
			}
		}

		$data['extensions'] = array();
		$files = glob($this->data->dir_application_http.'Controllers/Module/*.php');
		if ($files) {
			foreach ($files as $file) {
				$extension = mb_strtolower(str_replace('Module', '', basename($file, 'Controller.php')));

				$module_data = array();

				$modules = $this->extension->getModulesByCode($extension);

				foreach ($modules as $module) {
					$module_data[] = array(
						'module_id' => $module['module_id'],
						'name'      => ucfirst($extension) . ' &gt; ' . $module['name'],
						'edit'      => url('/module/' . $extension . '/edit/' . $module['module_id']),
						'delete'    => url('/modules/destroy/' . $module['module_id'])
					);
				}

				$data['extensions'][] = array(
					'name'      => ucfirst($extension),
					'module'    => $module_data,
					'install'   => url('/modules/install/' . $extension),
					'uninstall' => url('/modules/uninstall/' . $extension),
					'installed' => in_array($extension, $extensions),
					'create'      => url('/module/' . $extension .'/create')
				);
			}
		}

		$sort_order = array();

		foreach ($data['extensions'] as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $data['extensions']);
		$this->data->extensions = $data['extensions'];

		// define column
		$this->data->column_name = "Module Name";
		$this->data->column_action = "Action";

		$this->data->button_install = 'Install';
		$this->data->button_uninstall = 'Uninstall';
		$this->data->button_create = 'Add';
		$this->data->button_edit = 'Edit';
		$this->data->button_delete = 'Delete';

		$this->data->text_confirm = "Are you sure?";

		return view('website.extension.module.list', ['data' => $this->data]);
	}

	public function getInstall($extension) {
		DB::beginTransaction();
		try {
			$this->extension->installExtension('module', $extension);
			DB::commit();
			return Redirect('/modules')->with('success', 'Success: install module successfully!');
		} catch (Exception $e) {
			DB::rollback();
			return Redirect('/modules')->with('error', 'Error: install module successfully!'.$e->getMessage());
			exit();
		}
		exit();
	}

	public function getUninstall($extension) {
		DB::beginTransaction();
		try {
			$this->extension->uninstallExtension('module', $extension);
			$this->module->destroyModulesByCode($extension);
			$this->setting->destroySetting($extension);
			DB::commit();
			return Redirect('/modules')->with('success', 'Success: uninstall module successfully!');
		} catch (Exception $e) {
			DB::rollback();
			return Redirect('/modules')->with('error', 'Error: uninstall module successfully!'.$e->getMessage());
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
	public function getDestroy($module_id)
	{
		DB::beginTransaction();
		try {
			$this->module->destroyModule($module_id);
			DB::commit();
			return Redirect('/modules')->with('success', 'Success: delete module successfully!');
		} catch (Exception $e) {
			DB::rollback();
			return Redirect('/modules')->with('error', 'Error: delete module successfully!'.$e->getMessage());
			exit();
		}
		exit();
	}

}
