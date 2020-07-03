<?php

namespace App\Http\Controllers;

use Throwable;
use App\SmAddOns;
use App\Envato\Envato;
use App\SmGeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Nwidart\Modules\Facades\Module;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SmAddOnsController extends Controller
{
    protected $systemConfigModule = "FeesCollection";
    public function setActive($active)
    {
        return $this->json()->set('active', $active)->save();
    }

    // ManageAddOns by rashed


    public function ModuleRefresh()
    {
        try {
            exec('php composer.phar dump-autoload');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('config:clear');
            Toastr::success('Refresh successful', 'Success');
            return redirect()->back();
        } catch (\Throwable $th) {
            Toastr::error($th->getMessage(), 'Failed');
            return redirect('manage-adons');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage(), 'Failed');
            return redirect('manage-adons');
        }
    }

    public function ManageAddOns()
    {
        try {
            $is_module_available = Module::all();
            if (empty(@$is_module_available)) {
                $module_list = [
                    [
                        'name' => 'FeesCollection',
                        'url' => 'https://infixedu.com/package'
                    ],
                    [
                        'name' => 'InfixBiometrics',
                        'url' => 'https://infixedu.com/package'
                    ],
                ];
            } else {
                $module_list = [];
            }
            return view('backEnd.systemSettings.ManageAddOns', compact('is_module_available', 'module_list'));
        } catch (\Throwable $th) {
            Toastr::error($th->getMessage(), 'Failed');
            return redirect('manage-adons');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage(), 'Failed');
            return redirect('manage-adons');
        }
    }



    public function moduleAddOnsEnable($name)
    {
        $module_tables['FeesCollection'] = [
            'infix_fees_terms', 'infix_fees_types', 'infix_fees_type_assigns', 'infix_discounts', 'infix_assign_discounts',
            'infix_fines', 'infix_fine_setups', 'infix_fees_masters', 'infix_student_fees_assigns', 'infix_fees_payment_details', 'infix_fees_payments', 'infix_fine_records', 'infix_discount_records'
        ];
        $module_tables['InfixBiometrics'] = [
            'infix_bio_settings', 'infix_bio_attendance_reports', 'infix_bio_atendances', 'device_log'
        ];

        $module_tables['ResultReports'] = [];

        $is_module_available = 'Modules/' . $name . '/Providers/' . $name . 'ServiceProvider.php';
        if (file_exists($is_module_available)) {
            try {
                $modulestatus =  Module::find($name)->isDisabled();
                if ($modulestatus) {
                    $ModuleManage = Module::find($name)->enable();
                    $tables = $module_tables[$name];
                    $dbCount = 0;
                    foreach ($tables as $table) {
                        $dbNumbering = 100000 + $dbCount++;
                        $path = 'Modules/' . $name . '/Database/Migrations/2020_02_25_' . $dbNumbering . '_create_' . $table . '_table.php';
                        if (file_exists($path)) {
                            try {
                                $command = 'migrate:refresh --path=' . $path;
                                Log::info($command);
                                Artisan::call($command);
                            } catch (Throwable $th) {
                                Toastr::error($th->getMessage(), 'Failed');
                                return response()->json(['error' => 'Operation Failed! Module file missing']);
                                return redirect()->back()->with(['error' => $th->getMessage()]);
                            } catch (\Exception $e) {
                                return $e;
                                Log::info($e->getMessage());
                                return response()->json(['error' => 'Operation Failed! Module file missing']);
                                return redirect()->back()->with(['error' => $e->getMessage()]);
                            }
                        } else {
                            $error = "Module File is missing, Please contact with administrator";
                            Log::info($error);
                            return response()->json(['error' => $error]);
                            return redirect()->back()->with(['error' => $error]);
                        }
                    }

                    $data['data'] = 'enable';
                } else {
                    $ModuleManage = Module::find($name)->disable();

                    $tables = $module_tables[$name];
                    foreach ($tables as $table) {
                        if (Schema::hasTable($table)) {


                            //remove module tables from database 
                            try {
                                DB::beginTransaction();
                                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                                Schema::dropIfExists($table);
                                DB::commit();
                            } catch (\Exception $e) {
                                DB::rollback();
                                $data['error'] = $e->getMessage();
                                return response()->json($data, 200);
                            }

                            //remove migration name from migrations database 
                            try {
                                DB::beginTransaction();
                                DB::table('migrations')->where('migration', 'LIKE', '%' . $table . '%')->delete();
                                DB::commit();
                            } catch (\Exception $e) {
                                DB::rollback();
                                Log::info($e->getMessage());
                                $data['error'] = $e->getMessage();
                                return response()->json($data, 200);
                            }
                        }
                    }
                    $data['data'] = 'disable';
                    $data['Module'] = $ModuleManage;
                }
                $data['success'] = 'Operation success!';
                return response()->json($data, 200);
            } catch (\Exception $e) {
                Log::info($e->getMessage());
                $data['error'] = $e->getMessage();
                return response()->json($data, 200);
            }
        } else {
            $data['error'] = 'Operation Failed! Module file missing !';
            return response()->json($data, 200);
        }
    }


    public function ManageAddOnsValidation(Request $request)
    {
        if ($request->purchase_code == "") {
            Toastr::error('Purchase code is required', 'Failed');
            return redirect()->back();
        } else {
            try {
                $UserData = Envato::verifyPurchase($request->purchase_code);
                $name = $request->name;
                if (!empty($UserData['verify-purchase']['item_id'])) {
                    $config = SmGeneralSettings::find(1);
                    $config->$name = 1;
                    $r = $config->save();
                    $ModuleManage = Module::find($name)->disable();
                    if ($r) {
                        Toastr::success('Validation successful', 'Success');
                        return redirect()->back();
                    } else {
                        Toastr::error('Operation Failed', 'Failed');
                        return redirect()->back();
                    }
                } else {
                    Toastr::error('Validation Failed', 'Failed');
                    return redirect()->back();
                }
            } catch (\Exception $e) {
                Log::info($e->getMessage());
                return response()->json(['error' => 'Operation Failed!']);
                return redirect()->back()->with(['error' => $e->getMessage()]);
            }
        }
        Toastr::error('Validation Failed', 'Failed');
        return redirect()->back();
    }
}
