<?php

namespace App\Http\Controllers;

use App\SmToDo;
use App\SmStaff;
use App\SmParent;
use App\SmHoliday;
use App\SmStudent;
use App\YearCheck;
use App\SmItemSell;
use App\SmAddIncome;
use App\SmAddExpense;
use App\SmFeesPayment;
use App\SmItemReceive;
use App\SmNoticeBoard;
use App\SmHrPayrollGenerate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('PM');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function dashboard()
    {


        try {
            $role_id = Session::get('role_id');
            if ($role_id == 2) {
                return redirect('student-dashboard');
            } elseif ($role_id == 3) {
                return redirect('parent-dashboard');
            } elseif ($role_id == 10) {
                return redirect('customer-dashboard');
            } elseif ($role_id == "") {
                return redirect('login');
            } else {
                return redirect('admin-dashboard');
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }



    // for display dashboard

    public function index(Request $request)
    {


        try {

            $user_id = Auth()->user()->id;
            $totalStudents = SmStudent::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            $totalTeachers = SmStaff::where('active_status', 1)->where('role_id', 4)->get();
            $totalParents = SmParent::join('sm_students', 'sm_students.parent_id', '=', 'sm_parents.id')
                ->join('sm_classes', 'sm_classes.id', '=', 'sm_students.class_id')
                ->where('sm_classes.created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            $totalStaffs = SmStaff::where('active_status', 1)->where('role_id', '!=', 1)->where('role_id', '!=', 4)->get();
            $toDoLists = SmToDo::where('complete_status', 'P')->where('created_by', $user_id)->get();
            $toDoListsCompleteds = SmToDo::where('complete_status', 'C')->where('created_by', $user_id)->get();

            $notices = SmNoticeBoard::select('*')->where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();

            // for current month

            $m_add_incomes = SmAddIncome::where('active_status', 1)->where('date', 'like', date('Y-m-') . '%')->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->sum('amount');

            $m_fees_payments = SmFeesPayment::where('active_status', 1)->where('payment_date', 'like', date('Y-m-') . '%')->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->sum('amount');

            $m_item_sells = SmItemSell::where('active_status', 1)->where('sell_date', 'like', date('Y-m-') . '%')->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->sum('total_paid');

            $m_total_income = $m_add_incomes + $m_fees_payments + $m_item_sells;


            $m_add_expenses = SmAddExpense::where('active_status', 1)->where('date', 'like', date('Y-m-') . '%')->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->sum('amount');
            $m_item_receives = SmItemReceive::where('active_status', 1)->where('receive_date', 'like', date('Y-m-') . '%')->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->sum('total_paid');
            $m_payroll_payments = SmHrPayrollGenerate::where('active_status', 1)->where('payroll_status', 'P')->where('created_at', 'like', date('Y-m-') . '%')->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->sum('net_salary');

            $m_total_expense = $m_add_expenses + $m_item_receives + $m_payroll_payments;

            // for current year


            $y_add_incomes = SmAddIncome::where('active_status', 1)->where('date', 'like', date('Y-') . '%')->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->sum('amount');

            $y_fees_payments = SmFeesPayment::where('active_status', 1)->where('payment_date', 'like', date('Y-') . '%')->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->sum('amount');

            $y_item_sells = SmItemSell::where('active_status', 1)->where('sell_date', 'like', date('Y-') . '%')->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->sum('total_paid');

            $y_total_income = $y_add_incomes + $y_fees_payments + $y_item_sells;


            $y_add_expenses = SmAddExpense::where('active_status', 1)->where('date', 'like', date('Y-') . '%')->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->sum('amount');
            $y_item_receives = SmItemReceive::where('active_status', 1)->where('receive_date', 'like', date('Y-') . '%')->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->sum('total_paid');
            $y_payroll_payments = SmHrPayrollGenerate::where('active_status', 1)->where('payroll_status', 'P')->where('created_at', 'like', date('Y-') . '%')->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->sum('net_salary');

            $y_total_expense = $y_add_expenses + $y_item_receives + $y_payroll_payments;

            $general_settings_data = DB::table('sm_general_settings')->first();
            $currency = $general_settings_data->currency_symbol;

            $holidays = SmHoliday::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();


            if (Session::has('info_check')) {
                session(['info_check' => 'no']);
            } else {
                session(['info_check' => 'yes']);
            }

            $year = YearCheck::getYear();

            return view('backEnd.dashboard', compact('currency', 'totalStudents', 'totalTeachers', 'totalParents', 'totalStaffs', 'toDoLists', 'notices', 'toDoListsCompleteds', 'm_total_income', 'm_total_expense', 'y_total_income', 'y_total_expense', 'holidays', 'year'));
        } catch (\Exception $e) {

            Auth::logout();
            session(['role_id' => '']);
            Session::flush();

            Toastr::error('Operation Failed', 'Failed');

            return redirect('login');
        }
    }

    public function saveToDoData(Request $request)
    {


        try {

            $toDolists = new SmToDo();
            $toDolists->todo_title = $request->todo_title;
            $toDolists->date = date('Y-m-d', strtotime($request->date));
            $toDolists->created_by = Auth()->user()->id;
            $results = $toDolists->save();

            if ($results) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
                // return redirect()->back()->with('message-success', 'To Do Data added successfully');
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
                // return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function viewToDo($id)
    {

        try {

            $toDolists = SmToDo::where('id', $id)->first();
            return view('backEnd.dashboard.viewToDo', compact('toDolists'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function editToDo($id)
    {
        try {

            $editData = SmToDo::find($id);
            return view('backEnd.dashboard.editToDo', compact('editData', 'id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function updateToDo(Request $request)
    {


        try {
            $to_do_id = $request->to_do_id;
            $toDolists = SmToDo::find($to_do_id);
            $toDolists->todo_title = $request->todo_title;
            $toDolists->date = date('Y-m-d', strtotime($request->date));
            $toDolists->complete_status = $request->complete_status;
            $toDolists->updated_by = Auth()->user()->id;
            $results = $toDolists->update();

            if ($results) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
                // return redirect()->back()->with('message-success', 'To Do Data updated successfully');
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
                // return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function removeToDo(Request $request)
    {

        try {
            $to_do = SmToDo::find($request->id);
            $to_do->complete_status = "C";
            $to_do->save();
            $html = "";
            return response()->json('html');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function getToDoList(Request $request)
    {
        try {

            $to_do_list = SmToDo::where('complete_status', 'C')->get();
            $datas = [];
            foreach ($to_do_list as $to_do) {
                $datas[] = array(
                    'title' => $to_do->todo_title,
                    'date' => date('jS M, Y', strtotime($to_do->date))
                );
            }
            return response()->json($datas);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function viewNotice($id)
    {
        try {

            $notice = SmNoticeBoard::find($id);
            return view('backEnd.dashboard.view_notice', compact('notice'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function updatePassowrd()
    {
        try {
            $ROLE_ID = Auth::user()->role_id;
            if ($ROLE_ID == 2) {

                $LoginUser = SmStudent::where('user_id', Auth::user()->id)->first();
                if (empty($LoginUser)) {
                    $profile = 'public/backEnd/img/admin/message-thumb.png';
                } else {
                    $profile = $LoginUser->student_photo;
                }
            } elseif ($ROLE_ID == 3) {
                $LoginUser = SmParent::where('user_id', Auth::user()->id)->first();
                if (empty($LoginUser)) {
                    $profile = 'public/backEnd/img/admin/message-thumb.png';
                } else {
                    $profile = $LoginUser->fathers_photo;
                }
            } else {
                $LoginUser = SmStaff::where('user_id', Auth::user()->id)->first();

                if (empty($LoginUser)) {
                    $profile = 'public/backEnd/img/admin/message-thumb.png';
                } else {
                    $profile = $LoginUser->staff_photo;
                }
            }
            return view('backEnd.update_password', compact('profile', 'LoginUser'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function updatePassowrdStore(Request $request)
    {

        $request->validate([
            'current_password' => "required",
            'new_password'  => "required|same:confirm_password|min:6|different:current_password",
            'confirm_password'  => 'required|min:6'
        ]);

        try {

            $user = Auth::user();
            if (Hash::check($request->current_password, $user->password)) {

                $user->password = Hash::make($request->new_password);
                $result = $user->save();

                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                    // return redirect()->back()->with('message-success', 'Password has been changed successfully');
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                    // return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
                }
            } else {
                Toastr::error('Current password not match!', 'Failed');
                return redirect()->back();
                // return redirect()->back()->with('password-error', 'You have entered a wrong current password');
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
