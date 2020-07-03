<?php 
namespace App;
use App\SmRolePermission;
use Illuminate\Support\Facades\Auth;
class GlobalVariable{

	public $Names = array('Aaron', 'Abbey', 'Abbie', 'Abby', 'Abdul', 'Abe', 'Abel', 'Abigail', 'Abraham', 'Abram', 'Ada', 'Adah', 'Adalberto', 'Adaline', 'Adam','Adam', 'Adan', 'Addie', 'Adela', 'Adelaida', 'Adelaide', 'Adele', 'Adelia', 'Adelina', 'Adeline', 'Adell', 'Adella', 'Adelle', 'Adena', 'Adina' );

	public static function GlobarModuleLinks(){
        try {
            $module_links = [];
            $permissions = SmRolePermission::where('role_id', Auth::user()->role_id)->get();
            foreach ($permissions as $permission) {
                $module_links[] = $permission->module_link_id;
            }
            return $module_links;
        } catch (\Exception $e) {
            $data=[];
            return $data;
        }
	}
}
