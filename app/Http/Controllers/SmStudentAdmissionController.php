<?php

namespace App\Http\Controllers;

use Mail;
use App\User;
use App\SmClass;
use App\SmEmailSetting;
use App\SmRoute;
use App\SmStaff;
use App\SmParent;
use App\SmSection;
use App\SmStudent;
use App\SmVehicle;
use App\YearCheck;
use App\SmExamType;
use App\SmRoomList;
use App\SmBaseSetup;
use App\SmFeesAssign;
use App\SmMarksGrade;
use App\ApiBaseMethod;
use App\SmAcademicYear;
use App\SmClassSection;
use App\SmExamSchedule;
use App\SmAssignVehicle;
use App\SmDormitoryList;
use Barryvdh\DomPDF\Facade as PDF;
use App\SmGeneralSettings;
use App\SmStudentCategory;
use App\SmStudentDocument;
use App\SmStudentTimeline;
use App\SmStudentPromotion;
use App\SmStudentAttendance;
use Illuminate\Http\Request;
use App\SmFeesAssignDiscount;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SmStudentAdmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    }

    function admissionCheck($val)
    {
        $data = DB::table('sm_students')->where('admission_no', $val)->first();
        if (!is_null($data)) {
            $msg = 'found';
            $status = 200;
            return response()->json($msg, $status);
        }else {
            $msg = 'not_found';
            $status = 200;
            return response()->json($msg, $status);
        }
    }
    function admissionCheckUpdate($val,$id)
    {
        $data = DB::table('sm_students')->where('admission_no', $val)->first();

        $student = SmStudent::find($id);
        
        if (!is_null($data) && $student->admission_no != $data->admission_no) {
            $msg = 'found';
            $status = 200;
            return response()->json($msg, $status);
        }else {
            $msg = 'not_found';
            $status = 200;
            return response()->json($msg, $status);
        }
    }

    public function admission()
    {
        try {
            $max_admission_id = SmStudent::max('admission_no');
            $max_roll_id = SmStudent::max('roll_no');
            $classes = SmClass::where('active_status', '=', '1')->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            $religions = SmBaseSetup::where('active_status', '=', '1')->where('base_group_id', '=', '2')->get();
            $blood_groups = SmBaseSetup::where('active_status', '=', '1')->where('base_group_id', '=', '3')->get();
            $genders = SmBaseSetup::where('active_status', '=', '1')->where('base_group_id', '=', '1')->get();
            $route_lists = SmRoute::where('active_status', '=', '1')->get();
            $vehicles = SmVehicle::where('active_status', '=', '1')->get();
            $driver_lists = SmStaff::where([['active_status', '=', '1'], ['role_id', 9]])->get();
            $dormitory_lists = SmDormitoryList::where('active_status', '=', '1')->get();
            $categories = SmStudentCategory::get();
            $sessions = SmAcademicYear::where('active_status', '=', '1')->get();
            return view('backEnd.studentInformation.student_admission', compact('classes', 'religions', 'blood_groups', 'genders', 'route_lists', 'vehicles', 'dormitory_lists', 'categories', 'sessions', 'max_admission_id', 'max_roll_id', 'driver_lists'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }



    public function ajaxSectionStudent(Request $request)
    {
        try {
            $sectionIds = SmClassSection::where('class_id', '=', $request->id)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            $sections = [];
            foreach ($sectionIds as $sectionId) {
                $sections[] = SmSection::find($sectionId->section_id);
            }
            return response()->json([$sections]);
        } catch (\Exception $e) {
            return response()->json("", 404);
        }
    }

    public function ajaxSectionSibling(Request $request)
    {
        try {
            $sectionIds = SmClassSection::where('class_id', '=', $request->id)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();

            $sibling_sections = [];
            foreach ($sectionIds as $sectionId) {
                $sibling_sections[] = SmSection::find($sectionId->section_id);
            }
            return response()->json([$sibling_sections]);
        } catch (\Exception $e) {
            return response()->json("", 404);
        }
    }
    public function ajaxSiblingInfo(Request $request)
    {
        try {
            if ($request->id == "") {
                $siblings = SmStudent::where('class_id', '=', $request->class_id)->where('section_id', '=', $request->section_id)->where('active_status', 1)->get();
            } else {
                $siblings = SmStudent::where('class_id', '=', $request->class_id)->where('section_id', '=', $request->section_id)->where('active_status', 1)->where('id', '!=', $request->id)->get();
            }
            return response()->json($siblings);
        } catch (\Exception $e) {
            return response()->json("", 404);
        }
    }

    public function ajaxSiblingInfoDetail(Request $request)
    {
        try {
            $sibling_detail = SmStudent::find($request->id);
            $parent_detail =  $sibling_detail->parents;
            return response()->json([$sibling_detail, $parent_detail]);
        } catch (\Exception $e) {
            return response()->json("", 404);
        }
    }

    public function ajaxGetVehicle(Request $request)
    {
        try {
            $vehicle_detail = SmAssignVehicle::where('route_id', $request->id)->first();
            $vehicles = explode(',', $vehicle_detail->vehicle_id);
            $vehicle_info = [];
            foreach ($vehicles as $vehicle) {
                $vehicle_info[] = SmVehicle::find($vehicle[0]);
            }
            return response()->json([$vehicle_info]);
        } catch (\Exception $e) {
            return response()->json("", 404);
        }
    }

    public function ajaxVehicleInfo(Request $request)
    {
        try {
            $vehivle_detail = SmVehicle::find($request->id);
            return response()->json([$vehivle_detail]);
        } catch (\Exception $e) {
            return response()->json("", 404);
        }
    }

    public function ajaxRoomDetails(Request $request)
    {
        try {
            $room_details = SmRoomList::where('dormitory_id', '=', $request->id)->get();
            $rest_rooms = [];
            foreach ($room_details as $room_detail) {
                $count_room = SmStudent::where('room_id', $room_detail->id)->count();
                if ($count_room < $room_detail->number_of_bed) {
                    $rest_rooms[] = $room_detail;
                }
            }
            return response()->json([$rest_rooms]);
        } catch (\Exception $e) {
            return response()->json("", 404);
        }
    }

    public function ajaxGetRollId(Request $request)
    {
        try {
            $max_roll = SmStudent::where('class_id', $request->class)->where('section_id', $request->section)->max('roll_no');

            if ($max_roll == "") {
                $max_roll = 1;
            } else {
                $max_roll = $max_roll + 1;
            }
            return response()->json([$max_roll]);
        } catch (\Exception $e) {
            return response()->json("", 404);
        }
    }

    public function ajaxGetRollIdCheck(Request $request)
    {
        try {
            $roll_no = SmStudent::where('class_id', $request->class)->where('section_id', $request->section)->where('roll_no', $request->roll_no)->get();

            // if($roll_no->count() == 0){
            //     $roll_no == 1;
            // }else{
            //     $roll_no == 0;
            // }

            return response()->json($roll_no);
        } catch (\Exception $e) {
            return response()->json("", 404);
        }
    }


    public function studentStore(Request $request)
    {
        if ($request->parent_id == "") {
            $request->validate([
                'admission_number' => 'required|unique:sm_students,admission_no',
                'email_address' => 'unique:users,email',
                'roll_number' => 'required',
                'class' => 'required',
                'section' => 'required',
                'session' => 'required',
                'gender' => 'required',
                'first_name' => 'required|max:100',
                'date_of_birth' => 'required',
                'guardians_email' => "required|unique:sm_parents,guardians_email",
                'guardians_phone' => "required|unique:sm_parents,guardians_mobile",
               
            ]);
        } else {
            $request->validate([
                'admission_number' => 'required|unique:sm_students,admission_no',
                'email_address' => 'unique:users,email',

                'roll_number' => 'required',
                'class' => 'required',
                'section' => 'required',
                'gender' => 'required',
                'first_name' => 'required|max:100',
                'date_of_birth' => 'required',
                'session' => 'required',
            ]);
        }


        $document_file_1 = "";
        if ($request->file('document_file_1') != "") {
            $file = $request->file('document_file_1');
            $document_file_1 = 'doc1-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/student/document/', $document_file_1);
            $document_file_1 =  'public/uploads/student/document/' . $document_file_1;
        }

        $document_file_2 = "";
        if ($request->file('document_file_2') != "") {
            $file = $request->file('document_file_2');
            $document_file_2 = 'doc2-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/student/document/', $document_file_2);
            $document_file_2 =  'public/uploads/student/document/' . $document_file_2;
        }

        $document_file_3 = "";
        if ($request->file('document_file_3') != "") {
            $file = $request->file('document_file_3');
            $document_file_3 = 'doc3-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/student/document/', $document_file_3);
            $document_file_3 =  'public/uploads/student/document/' . $document_file_3;
        }

        $document_file_4 = "";
        if ($request->file('document_file_4') != "") {
            $file = $request->file('document_file_4');
            $document_file_4 = 'doc4-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/student/document/', $document_file_4);
            $document_file_4 =  'public/uploads/student/document/' . $document_file_4;
        }



        // $get_admission_number = SmStudent::max('admission_no') + 1;

        $shcool_details = SmGeneralSettings::find(1);

        $school_name = explode(' ', $shcool_details->school_name);

        $short_form = '';

        foreach ($school_name as $value) {
            $ch = str_split($value);
            $short_form = $short_form . '' . $ch[0];
        }


        DB::beginTransaction();
        try {

            $user_stu = new User();
            $user_stu->role_id = 2;
            $user_stu->full_name = $request->first_name . ' ' . $request->last_name;

            if ($request->email_address != null) {
                $data_student['email'] = $request->email_address;

                $user_stu->username = $request->email_address;
            } else {

                $data_student['email'] = $request->admission_number;

                $user_stu->username = $request->admission_number;
            }


            if (empty($request->email_address)) {

                $user_stu->email = $request->admission_number;

            } else {

                $user_stu->email = $request->email_address;

            }

            $user_stu->password = Hash::make(123456);
            $user_stu->save();
            $user_stu->toArray();

            try {

                if ($request->parent_id == "") {
                    $user_parent = new User();
                    $user_parent->role_id = 3;
                    $user_parent->full_name = $request->fathers_name;

                    //$user_parent->username = 'par-'.$get_admission_number;

                    if (empty($request->guardians_email)) {
                        $data_parent['email'] = 'par_' . $get_admission_number;

                        $user_parent->username  = 'par_' . $get_admission_number;
                    } else {
                        $data_parent['email'] = $request->guardians_email;

                        $user_parent->username = $request->guardians_email;
                    }

                    $user_parent->email = $request->guardians_email;
                    $user_parent->password = Hash::make(123456);
                    $user_parent->save();
                    $user_parent->toArray();
                }

                try {
                    if ($request->parent_id == "") {
                        $parent = new SmParent();
                        $parent->user_id = $user_parent->id;
                        $parent->fathers_name = $request->fathers_name;
                        $parent->fathers_mobile = $request->fathers_phone;
                        $parent->fathers_occupation = $request->fathers_occupation;
                        $parent->fathers_photo = Session::get('fathers_photo');
                        $parent->mothers_name = $request->mothers_name;
                        $parent->mothers_mobile = $request->mothers_phone;
                        $parent->mothers_occupation = $request->mothers_occupation;
                        $parent->mothers_photo = Session::get('mothers_photo');
                        $parent->guardians_name = $request->guardians_name;
                        $parent->guardians_mobile = $request->guardians_phone;
                        $parent->guardians_email = $request->guardians_email;
                        $parent->guardians_occupation = $request->guardians_occupation;
                        $parent->guardians_relation = $request->relation;
                        $parent->relation = $request->relationButton;
                        $parent->guardians_photo = Session::get('guardians_photo');
                        $parent->guardians_address = $request->guardians_address;
                        $parent->is_guardian = $request->is_guardian;
                        $parent->save();
                        $parent->toArray();
                    }

                    try {

                        $student = new SmStudent();
                        //$student->siblings_id = $request->sibling_id;
                        $student->class_id = $request->class;
                        $student->section_id = $request->section;
                        $student->session_id = $request->session;
                        $student->user_id = $user_stu->id;

                        if ($request->parent_id == "") {

                            $student->parent_id = $parent->id;

                        } else {

                            $student->parent_id = $request->parent_id;

                        }

                        $student->role_id = 2;

                        $student->admission_no = $request->admission_number;

                        $student->roll_no = $request->roll_number;
                        $student->first_name = $request->first_name;
                        $student->last_name = $request->last_name;
                        $student->full_name = $request->first_name . ' ' . $request->last_name;
                        $student->gender_id = $request->gender;
                        $student->date_of_birth = date('Y-m-d', strtotime($request->date_of_birth));

                        if (@$request->category != "") {
                            $student->student_category_id = $request->category;
                        }

                        $student->caste = $request->caste;



                        $student->email = $request->email_address;



                        $student->mobile = $request->phone_number;
                        $student->admission_date = date('Y-m-d', strtotime($request->admission_date));
                        $student->student_photo = Session::get('student_photo');

                        if (@$request->blood_group != "") {
                            $student->bloodgroup_id = $request->blood_group;
                        }
                        if (@$request->religion != "") {
                            $student->religion_id = $request->religion;
                        }
                        $student->height = $request->height;
                        $student->weight = $request->weight;
                        $student->current_address = $request->current_address;
                        $student->permanent_address = $request->permanent_address;
                        if (@$request->route != "") {
                            $student->route_list_id = $request->route;
                        }
                        if (@$request->dormitory_name != "") {
                            $student->dormitory_id = $request->dormitory_name;
                        }
                        if (@$request->room_number != "") {
                            $student->room_id = $request->room_number;
                        }
                        //$driver_id=SmVehicle::where('id','=',$request->vehicle)->first();

                        if (!empty($request->vehicle)) {
                            $driver = SmVehicle::where('id', '=', $request->vehicle)
                                ->select('driver_id')
                                ->first();

                            if (!empty($driver)) {
                                $student->vechile_id = $request->vehicle;
                                $student->driver_id = $driver->driver_id;
                            }
                        }

                        // $student->driver_name = $request->driver_name;
                        // $student->driver_phone_no = $request->driver_phone;
                        $student->national_id_no = $request->national_id_number;
                        $student->local_id_no = $request->local_id_number;
                        $student->bank_account_no = $request->bank_account_number;
                        $student->bank_name = $request->bank_name;
                        $student->previous_school_details = $request->previous_school_details;
                        $student->aditional_notes = $request->additional_notes;
                        $student->document_title_1 = $request->document_title_1;
                        $student->document_file_1 =  $document_file_1;
                        $student->document_title_2 = $request->document_title_2;
                        $student->document_file_2 =  $document_file_2;
                        $student->document_title_3 = $request->document_title_3;
                        $student->document_file_3 = $document_file_3;
                        $student->document_title_4 = $request->document_title_4;
                        $student->document_file_4 = $document_file_4;

                        $student->save();
                        $student->toArray();



                        if($request->email_address != ""){
                            $user_info[] =  array('email' => $request->email_address, 'username' => $data_student['email']);
                        }


                        if($request->guardians_email != ""){
                            $user_info[] =  array('email' =>  $request->guardians_email, 'username' => $data_parent['email']);
                        }

                        if(count($user_info) != 0){
                            $systemSetting = SmGeneralSettings::select('school_name', 'email')->find(1);


                            $systemEmail = SmEmailSetting::find(1);

                            $system_email = $systemEmail->from_email;
                            $school_name = $systemSetting->school_name;


                            $sender['system_email'] = $system_email;
                            $sender['school_name'] = $school_name;

                            dispatch(new \App\Jobs\SendUserMailJob($user_info, $sender));

                        }




                        // if($request->email_address != ""){
                        //     $data['email'] = $data_student['email'];
                        //      Mail::send('backEnd.studentInformation.user_credential', compact('data'), function ($message) use ($request) {
                        //         $settings = SmEmailSetting::find(1);
                        //         $email = $settings->from_email;
                        //         $Schoolname = $settings->from_name;
                        //         $message->to($request->email_address, $Schoolname)->subject('Login Credentials');
                        //         $message->from($email, $Schoolname);
                        //     });
                        //  }

                        //  if($request->guardians_email != ""){
                        //     $data['email'] = $data_parent['email'];
                        //     Mail::send('backEnd.studentInformation.user_credential', compact('data'), function ($message) use ($request) {
                        //         $settings = SmEmailSetting::find(1);
                        //         $email = $settings->from_email;
                        //         $Schoolname = $settings->from_name;
                        //         $message->to($request->guardians_email, $Schoolname)->subject('Login Credentials');
                        //         $message->from($email, $Schoolname);
                        //     });
                        //  }

                         
                        /* Mail::send('backEnd.studentInformation.user_credential', compact('data'), function ($message) use ($request) {
                            $settings = SmEmailSetting::find(1);
                            $email = $settings->from_email;
                            $Schoolname = $settings->from_name;
                            $message->to($request->email, $Schoolname)->subject('Login Credentials');
                            $message->from($email, $Schoolname);
                        });*/

                        DB::commit();
                        Toastr::success('Operation successful', 'Success');
                        return redirect('student-list');
                    } catch (\Exception $e) {
                        DB::rollback();
                        Toastr::error('Operation Failed', 'Failed');
                        return redirect()->back();
                    }
                } catch (\Exception $e) {
                    DB::rollback();
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            } catch (\Exception $e) {
                DB::rollback();
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    function admissionPic(Request $r)
    {
        try {
            $validator = Validator::make($r->all(), [
                'logo_pic' => 'sometimes|required|mimes:jpg,png|max:40000',

            ]);
            if ($validator->fails()) {
                return response()->json(['error' => 'error'], 201);
            }
            $data = new SmStudent();
            $data_parent = new SmParent();
            if ($r->hasFile('logo_pic')) {
                $file = $r->file('logo_pic');
                $images = Image::make($file)->insert($file);
                $pathImage = 'public/uploads/student/';
                if (!file_exists($pathImage)) {
                    mkdir($pathImage, 0777, true);
                    $name = md5($file->getClientOriginalName() . time()) . "." . "png";
                    $images->save('public/uploads/student/' . $name);
                    $imageName = 'public/uploads/student/' . $name;
                    // $data->staff_photo =  $imageName;
                    Session::put('student_photo', $imageName);
                } else {
                    $name = md5($file->getClientOriginalName() . time()) . "." . "png";
                    if (file_exists(Session::get('student_photo'))) {
                        File::delete(Session::get('student_photo'));
                    }
                    $images->save('public/uploads/student/' . $name);
                    $imageName = 'public/uploads/student/' . $name;
                    // $data->student_photo =  $imageName;
                    Session::put('student_photo', $imageName);
                }
            }
            // parent
            if ($r->hasFile('fathers_photo')) {
                $file = $r->file('fathers_photo');
                $images = Image::make($file)->insert($file);
                $pathImage = 'public/uploads/student/';
                if (!file_exists($pathImage)) {
                    mkdir($pathImage, 0777, true);
                    $name = md5($file->getClientOriginalName() . time()) . "." . "png";
                    $images->save('public/uploads/student/' . $name);
                    $imageName = 'public/uploads/student/' . $name;
                    // $data->staff_photo =  $imageName;
                    Session::put('fathers_photo', $imageName);
                } else {
                    $name = md5($file->getClientOriginalName() . time()) . "." . "png";
                    if (file_exists(Session::get('fathers_photo'))) {
                        File::delete(Session::get('fathers_photo'));
                    }
                    $images->save('public/uploads/student/' . $name);
                    $imageName = 'public/uploads/student/' . $name;
                    // $data->fathers_photo =  $imageName;
                    Session::put('fathers_photo', $imageName);
                }
            }
            //mother
            if ($r->hasFile('mothers_photo')) {
                $file = $r->file('mothers_photo');
                $images = Image::make($file)->insert($file);
                $pathImage = 'public/uploads/student/';
                if (!file_exists($pathImage)) {
                    mkdir($pathImage, 0777, true);
                    $name = md5($file->getClientOriginalName() . time()) . "." . "png";
                    $images->save('public/uploads/student/' . $name);
                    $imageName = 'public/uploads/student/' . $name;
                    // $data->staff_photo =  $imageName;
                    Session::put('mothers_photo', $imageName);
                } else {
                    $name = md5($file->getClientOriginalName() . time()) . "." . "png";
                    if (file_exists(Session::get('mothers_photo'))) {
                        File::delete(Session::get('mothers_photo'));
                    }
                    $images->save('public/uploads/student/' . $name);
                    $imageName = 'public/uploads/student/' . $name;
                    // $data->mothers_photo =  $imageName;
                    Session::put('mothers_photo', $imageName);
                }
            }
            //guardians_photo
            if ($r->hasFile('guardians_photo')) {
                $file = $r->file('guardians_photo');
                $images = Image::make($file)->insert($file);
                $pathImage = 'public/uploads/student/';
                if (!file_exists($pathImage)) {
                    mkdir($pathImage, 0777, true);
                    $name = md5($file->getClientOriginalName() . time()) . "." . "png";
                    $images->save('public/uploads/student/' . $name);
                    $imageName = 'public/uploads/student/' . $name;
                    // $data->staff_photo =  $imageName;
                    Session::put('guardians_photo', $imageName);
                } else {
                    $name = md5($file->getClientOriginalName() . time()) . "." . "png";
                    if (file_exists(Session::get('guardians_photo'))) {
                        File::delete(Session::get('guardians_photo'));
                    }
                    $images->save('public/uploads/student/' . $name);
                    $imageName = 'public/uploads/student/' . $name;
                    // $data->guardians_photo =  $imageName;
                    Session::put('guardians_photo', $imageName);
                }
            }

            return response()->json('success', 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'error'], 201);
        }
    }
    public function studentDetails(Request $request)
    {
        try {
            $classes = SmClass::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            $student_list = DB::table('sm_students')
                ->join('sm_classes', 'sm_students.class_id', '=', 'sm_classes.id')
                ->join('sm_sections', 'sm_students.section_id', '=', 'sm_sections.id')
                ->where('sm_students.created_at', 'LIKE', '%' . YearCheck::getYear() . '%')
                ->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['student_list'] = $student_list->toArray();
                // $data['classes'] = $classes->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            $academic_years = SmAcademicYear::latest()->get();
            return view('backEnd.studentInformation.student_details', compact('classes', 'academic_years'));
        } catch (\Exception $e) {
             dd($e);
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentDetailsSearch(Request $request)
    {
        $request->validate([
            'class' => 'required',
        ]);
        try {
            $students = SmStudent::query();
            $students->where('active_status', 1);
            if ($request->class != "") {
                $students->where('class_id', $request->class);
            }
            if ($request->section != "") {
                $students->where('section_id', $request->section);
            }
            if ($request->name != "") {
                $students->where('full_name', 'like', '%' . $request->name . '%');
            }
            if ($request->roll_no != "") {
                $students->where('roll_no', 'like', '%' . $request->roll_no . '%');
            }


            $students = $students->get();
            $classes = SmClass::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();


            $class_id = $request->class;
            $name = $request->name;
            $roll_no = $request->roll_no;

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['students'] = $students->toArray();
                $data['classes'] = $classes->toArray();
                $data['class_id'] = $class_id;
                $data['name'] = $name;
                $data['roll_no'] = $roll_no;
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.studentInformation.student_details', compact('students', 'classes', 'class_id', 'name', 'roll_no'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentView(Request $request, $id)
    {
        try {
            $student_detail = SmStudent::find($id);

            $siblings = SmStudent::where('parent_id', $student_detail->parent_id)
                ->where('active_status', 1)
                ->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')
                ->where('id', '!=', $student_detail->id)
                ->get();

            $vehicle = DB::table('sm_vehicles')->where('id', $student_detail->vehicle_id)->first();
            // return $vehicle;
            $fees_assigneds = SmFeesAssign::where('student_id', $id)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            //  return $fees_assigneds;
            $fees_discounts = SmFeesAssignDiscount::where('student_id', $id)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            // $documents = SmStudentDocument::where('student_staff_id', $id)->where('type', 'stu')->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            $documents = SmStudentDocument::where('student_staff_id', $id)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();

            $timelines = SmStudentTimeline::where('staff_student_id', $id)->where('type', 'stu')->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            $exams = SmExamSchedule::where('class_id', $student_detail->class_id)->where('section_id', $student_detail->section_id)->get();
            $academic_year = SmAcademicYear::where('id', $student_detail->session_id)->first();
            $grades = SmMarksGrade::where('active_status', 1)->get();
            if (!empty($student_detail->vechile_id)) {
                $driver_id = SmVehicle::where('id', '=', $student_detail->vechile_id)->first();
                $driver_info = SmStaff::where('id', '=', $driver_id->driver_id)->first();
            } else {
                $driver_id = '';
                $driver_info = '';
            }

            // return $academic_year;

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['student_detail'] = $student_detail->toArray();
                $data['fees_assigneds'] = $fees_assigneds->toArray();
                $data['fees_discounts'] = $fees_discounts->toArray();
                $data['exams'] = $exams->toArray();
                $data['documents'] = $documents->toArray();
                $data['timelines'] = $timelines->toArray();
                $data['siblings'] = $siblings->toArray();
                $data['grades'] = $grades->toArray();
                $data['driver_info'] = $driver_info->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.studentInformation.student_view', compact('student_detail', 'driver_info', 'fees_assigneds', 'fees_discounts', 'exams', 'documents', 'timelines', 'siblings', 'grades', 'academic_year'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function uploadDocument(Request $request)
    {
        try {
            if ($request->file('photo') != "" && $request->title != "") {
                $document_photo = "";
                if ($request->file('photo') != "") {
                    $file = $request->file('photo');
                    $document_photo = 'stu-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                    $file->move('public/uploads/student/document/', $document_photo);
                    $document_photo =  'public/uploads/student/document/' . $document_photo;
                }

                $document = new SmStudentDocument();
                $document->title = $request->title;
                $document->student_staff_id = $request->student_id;
                $document->type = 'stf';
                $document->file = $document_photo;
                $document->save();
            }
            Toastr::success('Document uploaded successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function deleteDocument($id)
    {
        try {
            $document = SmStudentDocument::find($id);
            if ($document->file != "") {
                unlink($document->file);
            }
            $result = SmStudentDocument::destroy($id);
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentUploadDocument(Request $request)
    {
        try {
            if ($request->file('photo') != "" && $request->title != "") {
                $document_photo = "";
                if ($request->file('photo') != "") {
                    $file = $request->file('photo');
                    $document_photo = 'stu-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                    $file->move('public/uploads/student/document/', $document_photo);
                    $document_photo =  'public/uploads/student/document/' . $document_photo;
                }

                $document = new SmStudentDocument();
                $document->title = $request->title;
                $document->student_staff_id = $request->student_id;
                $document->type = 'stu';
                $document->file = $document_photo;
                $document->save();
            }

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    // timeline 
    public function studentTimelineStore(Request $request)
    {
        try {
            if ($request->title != "") {

                $document_photo = "";
                if ($request->file('document_file_4') != "") {
                    $file = $request->file('document_file_4');
                    $document_photo = 'stu-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                    $file->move('public/uploads/student/timeline/', $document_photo);
                    $document_photo =  'public/uploads/student/timeline/' . $document_photo;
                }

                $timeline = new SmStudentTimeline();
                $timeline->staff_student_id = $request->student_id;
                $timeline->type = 'stu';
                $timeline->title = $request->title;
                $timeline->date = date('Y-m-d', strtotime($request->date));
                $timeline->description = $request->description;
                if (isset($request->visible_to_student)) {
                    $timeline->visible_to_student = $request->visible_to_student;
                }
                $timeline->file = $document_photo;
                $timeline->save();
            }
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function deleteTimeline($id)
    {
        try {
            $document = SmStudentTimeline::find($id);
            if ($document->file != "") {
                unlink($document->file);
            }
            $result = SmStudentTimeline::destroy($id);
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    // public function studentDestroy(Request $request)
    // {
    //     //dd($request->all());
    //     DB::beginTransaction();
    //     try {
    //         $student = SmStudent::find($request->id);
    //         $student->active_status = 0;
    //         $student->save();
    //         $student_user = User::find($student->user_id);
    //         $student_user->active_status = 0;
    //         $student_user->access_status = 0;
    //         $student_user->save();
    //         DB::commit();
    //         if (ApiBaseMethod::checkUrl($request->fullUrl())) {
    //             return ApiBaseMethod::sendResponse(null, 'Student has been deleted successfully');
    //         }
    //         Toastr::success('Operation successful', 'Success');
    //         return redirect()->back();
    //     } catch (\Exception $e) {
    //         if (ApiBaseMethod::checkUrl($request->fullUrl())) {
    //             return ApiBaseMethod::sendResponse(null, 'Operation Failed');
    //         }
    //         Toastr::error('Operation Failed', 'Failed');
    //         return redirect()->back();
    //     }
    // }

    public function studentDelete1(Request $request)
    {

        try {

            $student_detail = SmStudent::find($request->id);

            $siblings = SmStudent::where('parent_id', $student_detail->parent_id)->get();


                DB::beginTransaction();

                $student = SmStudent::find($request->id);
                $student->active_status = 0;
                $student->save();
                $student_user = User::find($student_detail->user_id);
                $student_user->active_status = 0;
                $student_user->save();


                if (count($siblings) == 1) {
                    $parent = SmParent::find($student_detail->parent_id);
                    $parent->active_status = 0;
                    $parent->save();
                }
        

                $student_user = User::find($student_detail->user_id);
                $student_user->active_status = 0;
                $student_user->save();

    

                if (count($siblings) == 1) {

                    $parent_user = User::find($student_detail->parents->user_id);
                    $parent_user->active_status = 0;
                    $parent_user->save();

                }

                DB::commit();

                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendResponse(null, 'Student has been disabled successfully');
                }

                Toastr::success('Operation successful', 'Success');
                    return redirect()->back();



            } catch (\Exception $e) {
                DB::rollback();

                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendError('Something went wrong, please try again');
                }
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
                           

                    
    }
    public function studentDelete(Request $request)
    {
        
        try{
            $tables = \App\tableList::getTableList('student_id',$request->id);

            try {
                if ($tables==null) {
                    $student_detail = SmStudent::find($request->id);
                    $siblings = SmStudent::where('parent_id', $student_detail->parent_id)->get();

                    DB::beginTransaction();
                    $student = SmStudent::find($request->id);
                    $student->active_status = 0;
                    $student->save();
                    $student_user = User::find($student_detail->user_id);
                    $student_user->active_status = 0;
                    $student_user->save();

                    if (count($siblings) == 1) {
                        $parent = SmParent::find($student_detail->parent_id);
                        $parent->active_status = 0;
                        $parent->save();
                    }
            
                    $student_user = User::find($student_detail->user_id);
                    $student_user->active_status = 0;
                    $student_user->save();

                    if (count($siblings) == 1) {

                        $parent_user = User::find($student_detail->parents->user_id);
                        $parent_user->active_status = 0;
                        $parent_user->save();

                    }

                    DB::commit();
                    if ($student_detail) {
                        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                            if ($student_detail) {
                                return ApiBaseMethod::sendResponse(null, 'Room type has been deleted successfully');
                            } else {
                                return ApiBaseMethod::sendError('Something went wrong, please try again');
                            }
                        } else {
                            if ($student_detail) {
                                Toastr::success('Operation successful', 'Success');
                                return redirect()->back();
                            } else {
                                Toastr::error('Operation Failed', 'Failed');
                                return redirect()->back();
                            }
                        }
                    } else {
                        Toastr::error('Operation Failed', 'Failed');
                        return redirect()->back();
                    }
                } else {
                    $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                    Toastr::error($msg, 'Failed');
                    return redirect()->back();
                }
            } catch (\Illuminate\Database\QueryException $e) {
    
                $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                Toastr::error($msg, 'Failed');
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollback();
                //dd($e->getMessage(), $e->errorInfo);
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back(); 
        }
    }







    public function studentEdit(Request $request, $id)
    {
        try {
            $student = SmStudent::find($id);
            $classes = SmClass::where('active_status', '=', '1')->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            $sections = SmSection::where('active_status', '=', '1')->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            $religions = SmBaseSetup::where('active_status', '=', '1')->where('base_group_id', '=', '2')->get();
            $blood_groups = SmBaseSetup::where('active_status', '=', '1')->where('base_group_id', '=', '3')->get();
            $genders = SmBaseSetup::where('active_status', '=', '1')->where('base_group_id', '=', '1')->get();
            $route_lists = SmRoute::where('active_status', '=', '1')->get();
            $vehicles = SmVehicle::where('active_status', '=', '1')->get();
            $dormitory_lists = SmDormitoryList::where('active_status', '=', '1')->get();
            $driver_lists = SmStaff::where([['active_status', '=', '1'], ['role_id', 9]])->get();
            $categories = SmStudentCategory::all();
            $sessions = SmAcademicYear::where('active_status', '=', '1')->get();
            $siblings = SmStudent::where('parent_id', $student->parent_id)->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['student'] = $student;
                $data['classes'] = $classes->toArray();
                $data['religions'] = $religions->toArray();
                $data['blood_groups'] = $blood_groups->toArray();
                $data['genders'] = $genders->toArray();
                $data['route_lists'] = $route_lists->toArray();
                $data['vehicles'] = $vehicles->toArray();
                $data['dormitory_lists'] = $dormitory_lists->toArray();
                $data['categories'] = $categories->toArray();
                $data['sessions'] = $sessions->toArray();
                $data['siblings'] = $siblings->toArray();
                $data['driver_lists'] = $driver_lists->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.studentInformation.student_edit', compact('student', 'classes', 'sections', 'religions', 'blood_groups', 'genders', 'route_lists', 'vehicles', 'dormitory_lists', 'categories', 'sessions', 'siblings', 'driver_lists'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    function studentUpdatePic(Request $r, $id)
    {
        // try {
        $validator = Validator::make($r->all(), [
            'logo_pic' => 'sometimes|required|mimes:jpg,png|max:40000',
            'fathers_photo' => 'sometimes|required|mimes:jpg,png|max:40000',
            'mothers_photo' => 'sometimes|required|mimes:jpg,png|max:40000',
            'guardians_photo' => 'sometimes|required|mimes:jpg,png|max:40000',

        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'error'], 201);
        }
        try {
            $data = SmStudent::find($id);
            $data_parent = $data->parents;
            if ($r->hasFile('logo_pic')) {
                $file = $r->file('logo_pic');
                $images = Image::make($file)->insert($file);
                $pathImage = 'public/uploads/student/';
                if (!file_exists($pathImage)) {
                    mkdir($pathImage, 0777, true);
                    $name = md5($file->getClientOriginalName() . time()) . "." . "png";
                    $images->save('public/uploads/student/' . $name);
                    $imageName = 'public/uploads/student/' . $name;
                    // $data->staff_photo =  $imageName;
                    Session::put('student_photo', $imageName);
                } else {
                    $name = md5($file->getClientOriginalName() . time()) . "." . "png";
                    if (file_exists(Session::get('student_photo')) || file_exists($data->student_photo)) {
                        File::delete($data->student_photo);
                        File::delete(Session::get('student_photo'));
                    }
                    $images->save('public/uploads/student/' . $name);
                    $imageName = 'public/uploads/student/' . $name;
                    // $data->student_photo =  $imageName;
                    Session::put('student_photo', $imageName);
                }
            }
            // parent
            if ($r->hasFile('fathers_photo')) {
                $file = $r->file('fathers_photo');
                $images = Image::make($file)->insert($file);
                $pathImage = 'public/uploads/student/';
                if (!file_exists($pathImage)) {
                    mkdir($pathImage, 0777, true);
                    $name = md5($file->getClientOriginalName() . time()) . "." . "png";
                    $images->save('public/uploads/student/' . $name);
                    $imageName = 'public/uploads/student/' . $name;
                    // $data->staff_photo =  $imageName;
                    Session::put('fathers_photo', $imageName);
                } else {
                    $name = md5($file->getClientOriginalName() . time()) . "." . "png";
                    if (file_exists(Session::get('fathers_photo')) || file_exists($data_parent->fathers_photo)) {
                        File::delete(Session::get('fathers_photo'));
                        File::delete($data_parent->fathers_photo);
                    }
                    $images->save('public/uploads/student/' . $name);
                    $imageName = 'public/uploads/student/' . $name;
                    // $data->fathers_photo =  $imageName;
                    Session::put('fathers_photo', $imageName);
                }
            }
            //mother
            if ($r->hasFile('mothers_photo')) {
                $file = $r->file('mothers_photo');
                $images = Image::make($file)->insert($file);
                $pathImage = 'public/uploads/student/';
                if (!file_exists($pathImage)) {
                    mkdir($pathImage, 0777, true);
                    $name = md5($file->getClientOriginalName() . time()) . "." . "png";
                    $images->save('public/uploads/student/' . $name);
                    $imageName = 'public/uploads/student/' . $name;
                    // $data->staff_photo =  $imageName;
                    Session::put('mothers_photo', $imageName);
                } else {
                    $name = md5($file->getClientOriginalName() . time()) . "." . "png";
                    if (file_exists(Session::get('mothers_photo')) || file_exists($data_parent->mothers_photo)) {
                        File::delete(Session::get('mothers_photo'));
                        File::delete($data_parent->mothers_photo);
                    }
                    $images->save('public/uploads/student/' . $name);
                    $imageName = 'public/uploads/student/' . $name;
                    // $data->mothers_photo =  $imageName;
                    Session::put('mothers_photo', $imageName);
                }
            }
            //guardians_photo
            if ($r->hasFile('guardians_photo')) {
                $file = $r->file('guardians_photo');
                $images = Image::make($file)->insert($file);
                $pathImage = 'public/uploads/student/';
                if (!file_exists($pathImage)) {
                    mkdir($pathImage, 0777, true);
                    $name = md5($file->getClientOriginalName() . time()) . "." . "png";
                    $images->save('public/uploads/student/' . $name);
                    $imageName = 'public/uploads/student/' . $name;
                    // $data->staff_photo =  $imageName;
                    Session::put('guardians_photo', $imageName);
                } else {
                    $name = md5($file->getClientOriginalName() . time()) . "." . "png";
                    if (file_exists(Session::get('guardians_photo')) || file_exists($data_parent->guardians_photo)) {
                        File::delete(Session::get('guardians_photo'));
                        File::delete($data_parent->guardians_photo);
                    }
                    $images->save('public/uploads/student/' . $name);
                    $imageName = 'public/uploads/student/' . $name;
                    // $data->guardians_photo =  $imageName;
                    Session::put('guardians_photo', $imageName);
                }
            }

            return response()->json('success', 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'error'], 201);
        }
    }

    public function studentUpdate(Request $request)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $student_detail = SmStudent::find($request->id);


        if (($request->sibling_id == 0 || $request->sibling_id == 1) && $request->parent_id == "") {

            $request->validate([
                'admission_number' => 'required|unique:sm_students,admission_no,'.$student_detail->id,
                'email_address' => 'unique:users,email,'.$student_detail->user_id,
                'roll_number' => 'required',
                'class' => 'required',
                'section' => 'required',
                'gender' => 'required',
                'first_name' => 'required|max:100',
                'date_of_birth' => 'required',
                'guardians_email' => "required|unique:sm_parents,guardians_email," . $student_detail->parent_id,
                'guardians_phone' => "required|unique:sm_parents,guardians_mobile," . $student_detail->parent_id
            ]);
        } elseif ($request->sibling_id == 0 && $request->parent_id != "") {
            $request->validate([
                'admission_number' => 'required|unique:sm_students,admission_no,'.$student_detail->id,
                'email_address' => 'unique:users,email,'.$student_detail->user_id,
                'roll_number' => 'required',
                'class' => 'required',
                'section' => 'required',
                'gender' => 'required',
                'first_name' => 'required|max:100',
                'date_of_birth' => 'required'
            ]);
        } elseif (($request->sibling_id == 2 || $request->sibling_id == 1) && $request->parent_id != "") {
            $request->validate([
                'admission_number' => 'required|unique:sm_students,admission_no,'.$student_detail->id,
                'email_address' => 'unique:users,email,'.$student_detail->user_id,
                'roll_number' => 'required',
                'class' => 'required',
                'section' => 'required',
                'gender' => 'required',
                'first_name' => 'required|max:100',
                'date_of_birth' => 'required'
            ]);
        } elseif ($request->sibling_id == 2 && $request->parent_id == "") {
            $request->validate([
                'admission_number' => 'required|unique:sm_students,admission_no,'.$student_detail->id,
                'email_address' => 'unique:users,email,'.$student_detail->user_id,
                'roll_number' => 'required',
                'class' => 'required',
                'section' => 'required',
                'gender' => 'required',
                'first_name' => 'required|max:100',
                'date_of_birth' => 'required',
                'guardians_email' => "required|unique:sm_parents,guardians_email",
                'guardians_phone' => "required|unique:sm_parents,guardians_mobile"
            ]);
        }




        //always happen start
        // $student_photo = "";
        // if ($request->file('photo') != "") {
        //     if ($student_detail->student_photo != "") {
        //         if (file_exists($student_detail->student_photo)) {
        //             unlink($student_detail->student_photo);
        //         }
        //     }
        //     $file = $request->file('photo');
        //     $student_photo = 'stu-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
        //     $file->move('public/uploads/student/', $student_photo);
        //     $student_photo =  'public/uploads/student/' . $student_photo;
        // }



        // if (($request->sibling_id == 0 || $request->sibling_id == 1) && $request->parent_id == "") {

        //     //fathers photo
        //     $fathers_photo = "";
        //     if ($request->file('fathers_photo') != "") {
        //         if ($student_detail->parents->fathers_photo != "") {
        //             if (file_exists($student_detail->parents->fathers_photo)) {
        //                 unlink($student_detail->parents->fathers_photo);
        //             }
        //         }
        //         $file = $request->file('fathers_photo');
        //         $fathers_photo = 'fat-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
        //         $file->move('public/uploads/student/', $fathers_photo);
        //         $fathers_photo =  'public/uploads/student/' . $fathers_photo;
        //     }
        //     //Mothers photo
        //     $mothers_photo = "";
        //     if ($request->file('mothers_photo') != "") {
        //         if ($student_detail->parents->mothers_photo != "") {
        //             if (file_exists($student_detail->parents->mothers_photo)) {
        //                 unlink($student_detail->parents->mothers_photo);
        //             }
        //         }
        //         $file = $request->file('mothers_photo');
        //         $mothers_photo = 'mot-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
        //         $file->move('public/uploads/student/', $mothers_photo);
        //         $mothers_photo =  'public/uploads/student/' . $mothers_photo;
        //     }
        //     //always happen end


        //     $guardians_photo = "";
        //     if ($request->relationButton == "F") {
        //         if ($request->file('fathers_photo') == "") {
        //             if ($student_detail->parents->fathers_photo == "") {
        //                 $guardians_photo =  $fathers_photo;
        //             } else {
        //                 $guardians_photo =  $student_detail->parents->fathers_photo;
        //             }
        //         }
        //     } elseif ($request->relationButton == "M") {
        //         if ($request->file('mothers_photo') == "") {
        //             if ($student_detail->parents->mothers_photo == "") {
        //                 $guardians_photo =  $mothers_photo;
        //             } else {
        //                 $guardians_photo =   $student_detail->parents->mothers_photo;
        //             }
        //         }
        //     } else {
        //         if ($request->file('guardians_photo') != "") {
        //             if ($student_detail->parents->guardians_photo != "") {
        //                 if ($student_detail->parents->relation == "O") {
        //                     //unlink($sibling_detail->parents->guardians_photo);
        //                 }
        //             }
        //             $file = $request->file('guardians_photo');
        //             $guardians_photo = 'guar-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
        //             $file->move('public/uploads/student/', $guardians_photo);
        //             $guardians_photo =  'public/uploads/student/' . $guardians_photo;
        //         }
        //     }
        // } elseif ($request->sibling_id == 0 && $request->parent_id != "") {


        //     if ($student_detail->parents->fathers_photo != "") {
        //         if (file_exists($student_detail->parents->fathers_photo)) {
        //             unlink($student_detail->parents->fathers_photo);
        //         }
        //     }
        //     if ($student_detail->parents->mothers_photo != "") {
        //         if (file_exists($student_detail->parents->mothers_photo)) {
        //             unlink($student_detail->parents->mothers_photo);
        //         }
        //     }
        //     if ($student_detail->parents->relation != "") {
        //         if ($student_detail->parents->relation == "O") {
        //             //unlink($student_detail->parents->guardians_photo);
        //         }
        //     }
        // } elseif (($request->sibling_id == 2 || $request->sibling_id == 1) && $request->parent_id != "") {
        //     $fathers_photo = "";
        //     $mothers_photo = "";
        //     $guardians_photo = "";
        // } elseif ($request->sibling_id == 2 && $request->parent_id == "") {
        //     $student_photo = "";
        //     if ($request->file('photo') != "") {
        //         $file = $request->file('photo');
        //         $student_photo = 'stu-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
        //         $file->move('public/uploads/student/', $student_photo);
        //         $student_photo =  'public/uploads/student/' . $student_photo;
        //     }

        //     if ($request->parent_id == "") {

        //         $fathers_photo = "";
        //         if ($request->file('fathers_photo') != "") {
        //             $file = $request->file('fathers_photo');
        //             $fathers_photo = 'fat-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
        //             $file->move('public/uploads/student/', $fathers_photo);
        //             $fathers_photo =  'public/uploads/student/' . $fathers_photo;
        //         }

        //         $mothers_photo = "";
        //         if ($request->file('mothers_photo') != "") {
        //             $file = $request->file('mothers_photo');
        //             $mothers_photo = 'mot-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
        //             $file->move('public/uploads/student/', $mothers_photo);
        //             $mothers_photo =  'public/uploads/student/' . $mothers_photo;
        //         }
        //         $guardians_photo = "";
        //         if ($request->relationButton == "F") {
        //             $guardians_photo =  $fathers_photo;
        //         } elseif ($request->relationButton == "M") {
        //             $guardians_photo =  $mothers_photo;
        //         } else {
        //             if ($request->file('guardians_photo') != "") {
        //                 $file = $request->file('guardians_photo');
        //                 $guardians_photo = 'guar-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
        //                 $file->move('public/uploads/student/', $guardians_photo);
        //                 $guardians_photo =  'public/uploads/student/' . $guardians_photo;
        //             }
        //         }
        //     }
        // }



        // always happen start

        $document_file_1 = "";
        if ($request->file('document_file_1') != "") {
            if ($student_detail->document_file_1 != "") {
                if (file_exists($student_detail->document_file_1)) {
                    unlink($student_detail->document_file_1);
                }
            }
            $file = $request->file('document_file_1');
            $document_file_1 = 'doc1-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/student/document/', $document_file_1);
            $document_file_1 =  'public/uploads/student/document/' . $document_file_1;
        }

        $document_file_2 = "";
        if ($request->file('document_file_2') != "") {
            if ($student_detail->document_file_2 != "") {
                if (file_exists($student_detail->document_file_2)) {
                    unlink($student_detail->document_file_2);
                }
            }
            $file = $request->file('document_file_2');
            $document_file_2 = 'doc2-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/student/document/', $document_file_2);
            $document_file_2 =  'public/uploads/student/document/' . $document_file_2;
        }

        $document_file_3 = "";
        if ($request->file('document_file_3') != "") {
            if ($student_detail->document_file_3 != "") {
                if (file_exists($student_detail->document_file_3)) {
                    unlink($student_detail->document_file_3);
                }
            }
            $file = $request->file('document_file_3');
            $document_file_3 = 'doc3-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/student/document/', $document_file_3);
            $document_file_3 =  'public/uploads/student/document/' . $document_file_3;
        }

        $document_file_4 = "";
        if ($request->file('document_file_4') != "") {
            if ($student_detail->document_file_4 != "") {
                if (file_exists($student_detail->document_file_4)) {
                    unlink($student_detail->document_file_4);
                }
            }
            $file = $request->file('document_file_4');
            $document_file_4 = 'doc4-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/student/document/', $document_file_4);
            $document_file_4 =  'public/uploads/student/document/' . $document_file_4;
        }

        $shcool_details = SmGeneralSettings::find(1);
        $school_name = explode(' ', $shcool_details->school_name);
        $short_form = '';

        foreach ($school_name as $value) {
            $ch = str_split($value);
            $short_form = $short_form . '' . $ch[0];
        }



        DB::beginTransaction();

        try {
            $user_stu = User::find($student_detail->user_id);
            $user_stu->role_id = 2;

            if (empty($request->email_address)) {

                $user_stu->username = $request->admission_number;
            }else{
                $user_stu->username = $request->email_address;
            }

            if (empty($request->email_address)) {
                $user_stu->email = $request->admission_number;
            } else {
                $user_stu->email = $request->email_address;
            }

            $user_stu->password = Hash::make(123456);
            $user_stu->save();
            $user_stu->toArray();



            try {
                if (($request->sibling_id == 0 || $request->sibling_id == 1) && $request->parent_id == "") {

                    $user_parent = User::find($student_detail->parents->user_id);
                    $user_parent->role_id = 3;
                    $user_parent->username = $request->guardians_email;
                    $user_parent->email = $request->guardians_email;
                    $user_parent->password = Hash::make(123456);
                    $user_parent->save();
                    $user_parent->toArray();
                } elseif ($request->sibling_id == 0 && $request->parent_id != "") {
                    User::destroy($student_detail->parents->user_id);
                } elseif (($request->sibling_id == 2 || $request->sibling_id == 1) && $request->parent_id != "") {
                } elseif ($request->sibling_id == 2 && $request->parent_id == "") {
                    $user_parent = new User();
                    $user_parent->role_id = 3;

                    $user_parent->username = $request->guardians_email;
                    $user_parent->email = $request->guardians_email;

                    $user_parent->password = Hash::make(123456);
                    $user_parent->save();
                    $user_parent->toArray();
                }
                try {

                    if (($request->sibling_id == 0 || $request->sibling_id == 1) && $request->parent_id == "") {

                        $parent = SmParent::find($student_detail->parent_id);
                        $parent->user_id = $user_parent->id;
                        $parent->fathers_name = $request->fathers_name;
                        $parent->fathers_mobile = $request->fathers_phone;
                        $parent->fathers_occupation = $request->fathers_occupation;
                        $parent->fathers_photo = Session::get('fathers_photo');
                        $parent->mothers_name = $request->mothers_name;
                        $parent->mothers_mobile = $request->mothers_phone;
                        $parent->mothers_occupation = $request->mothers_occupation;
                        $parent->mothers_photo = Session::get('mothers_photo');
                        $parent->guardians_name = $request->guardians_name;
                        $parent->guardians_mobile = $request->guardians_phone;
                        $parent->guardians_email = $request->guardians_email;
                        $parent->guardians_occupation = $request->guardians_occupation;
                        $parent->guardians_relation = $request->relation;
                        $parent->relation = $request->relationButton;
                        $parent->guardians_photo = Session::get('guardians_photo');
                        $parent->guardians_address = $request->guardians_address;
                        $parent->is_guardian = $request->is_guardian;
                        $parent->save();
                        $parent->toArray();
                    } elseif ($request->sibling_id == 0 && $request->parent_id != "") {
                        SmParent::destroy($student_detail->parent_id);
                    } elseif (($request->sibling_id == 2 || $request->sibling_id == 1) && $request->parent_id != "") {
                    } elseif ($request->sibling_id == 2 && $request->parent_id == "") {
                        $parent = new SmParent();
                        $parent->user_id = $user_parent->id;
                        $parent->fathers_name = $request->fathers_name;
                        $parent->fathers_mobile = $request->fathers_phone;
                        $parent->fathers_occupation = $request->fathers_occupation;
                        $parent->fathers_photo = Session::get('fathers_photo');
                        $parent->mothers_name = $request->mothers_name;
                        $parent->mothers_mobile = $request->mothers_phone;
                        $parent->mothers_occupation = $request->mothers_occupation;
                        $parent->mothers_photo = Session::get('mothers_photo');
                        $parent->guardians_name = $request->guardians_name;
                        $parent->guardians_mobile = $request->guardians_phone;
                        $parent->guardians_email = $request->guardians_email;
                        $parent->guardians_occupation = $request->guardians_occupation;
                        $parent->guardians_relation = $request->relation;
                        $parent->relation = $request->relationButton;
                        $parent->guardians_photo = Session::get('guardians_photo');
                        $parent->guardians_address = $request->guardians_address;
                        $parent->is_guardian = $request->is_guardian;
                        $parent->save();
                        $parent->toArray();
                    }

                    try {
                        $student = SmStudent::find($request->id);

                        if (($request->sibling_id == 0 || $request->sibling_id == 1) && $request->parent_id == "") {
                            $student->parent_id = $parent->id;
                        } elseif ($request->sibling_id == 0 && $request->parent_id != "") {
                            $student->parent_id = $request->parent_id;
                        } elseif (($request->sibling_id == 2 || $request->sibling_id == 1) && $request->parent_id != "") {
                            $student->parent_id = $request->parent_id;
                        } elseif ($request->sibling_id == 2 && $request->parent_id == "") {
                            $student->parent_id = $parent->id;
                        }
                        $student->class_id = $request->class;
                        $student->section_id = $request->section;
                        $student->session_id = $request->session;
                        $student->user_id = $user_stu->id;

                        $student->admission_no = $request->admission_number;
                        
                        $student->roll_no = $request->roll_number;
                        $student->first_name = $request->first_name;
                        $student->last_name = $request->last_name;
                        $student->full_name = $request->first_name . ' ' . $request->last_name;
                        $student->gender_id = $request->gender;
                        $student->date_of_birth = date('Y-m-d', strtotime($request->date_of_birth));
                        if (@$request->category != "") {
                            $student->student_category_id = $request->category;
                        }
                        $student->caste = $request->caste;
                        $student->email = $request->email_address;
                        $student->mobile = $request->phone_number;
                        $student->admission_date = date('Y-m-d', strtotime($request->admission_date));
                        $student->student_photo = Session::get('student_photo');
                        /* if ($student_photo != "") {
                        } */
                        if (@$request->blood_group != "") {
                            $student->bloodgroup_id = $request->blood_group;
                        }
                        if (@$request->religion != "") {
                            $student->religion_id = $request->religion;
                        }

                        $student->height = $request->height;
                        $student->weight = $request->weight;
                        $student->current_address = $request->current_address;
                        $student->permanent_address = $request->permanent_address;

                        if (@$request->route != "") {
                            $student->route_list_id = $request->route;
                        }
                        if (@$request->dormitory_name != "") {
                            $student->dormitory_id = $request->dormitory_name;
                        }
                        if (@$request->room_number != "") {
                            $student->room_id = $request->room_number;
                        }

                        if (!empty($request->vehicle)) {
                            $driver = SmVehicle::where('id', '=', $request->vehicle)
                                ->select('driver_id')
                                ->first();

                            $student->vechile_id = $request->vehicle;
                            $student->driver_id = $driver->driver_id;
                        }
                        //$student->driver_id = $request->driver_id;

                        // $student->driver_phone_no = $request->driver_phone;
                        $student->national_id_no = $request->national_id_number;
                        $student->local_id_no = $request->local_id_number;
                        $student->bank_account_no = $request->bank_account_number;
                        $student->bank_name = $request->bank_name;
                        $student->previous_school_details = $request->previous_school_details;
                        $student->aditional_notes = $request->additional_notes;
                        $student->document_title_1 = $request->document_title_1;
                        if ($document_file_1 != "") {
                            $student->document_file_1 =  $document_file_1;
                        }

                        $student->document_title_2 = $request->document_title_2;
                        if ($document_file_2 != "") {
                            $student->document_file_2 =  $document_file_2;
                        }

                        $student->document_title_3 = $request->document_title_3;
                        if ($document_file_3 != "") {
                            $student->document_file_3 = $document_file_3;
                        }

                        $student->document_title_4 = $request->document_title_4;

                        if ($document_file_4 != "") {
                            $student->document_file_4 = $document_file_4;
                        }

                        $student->save();
                        DB::commit();
                        Toastr::success('Operation successful', 'Success');
                        return redirect('student-list');
                    } catch (\Exception $e) {
                        DB::rollback();
                        Toastr::error('Operation Failed', 'Failed');
                        return redirect()->back();
                    }
                } catch (\Exception $e) {
                    DB::rollback();
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            } catch (\Exception $e) {
                // return $e;
                DB::rollback();
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }




    public function studentPromote(Request $request)
    {
        try {
            $sessions = SmAcademicYear::where('active_status', 1)->get();
            $classes = SmClass::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['sessions'] = $sessions->toArray();
                $data['classes'] = $classes->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            $exams = SmExamType::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            return view('backEnd.studentInformation.student_promote', compact('sessions', 'classes', 'exams'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function ajaxStudentPromoteSection(Request $request)
    {
        $sectionIds = SmClassSection::where('class_id', '=', $request->id)->get();

        $promote_sections = [];
        foreach ($sectionIds as $sectionId) {
            $promote_sections[] = SmSection::find($sectionId->section_id);
        }

        return response()->json([$promote_sections]);
    }

    public function ajaxGetClass(Request $request)
    {
        $classes = SmClass::where('created_at', 'LIKE', $request->year . '%')->get();

        return response()->json([$classes]);
    }



    public function SearchMultipleSection(Request $request)
    {
        $sectionIds = SmClassSection::where('class_id', '=', $request->id)->get();
        return response()->json([$sectionIds]);
    }





    public function ajaxSelectStudent(Request $request)
    {
        $students = SmStudent::where('class_id', '=', $request->class)->where('section_id', $request->section)->where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();

        return response()->json([$students]);
    }


    public function studentCurrentSearch(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'current_session' => 'required',
            'current_class' => 'required',
            'section' => 'required',
            'result' => 'required',
            'exam' => 'required',
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            if ($request->result == 'P') {
                $students = SmGeneralSettings::make_merit_list($request->current_class, $request->section, $request->exam);
                //  dd($students);
                if (@$students == 0) {
                    Toastr::error('No result found', 'Failed');
                    return redirect()->back();
                    // return redirect()->back()->with('message-danger', 'Your result is not found!');
                    //return redirect('student-promote');
                } else
                    $students['students'] = [];
                foreach ($students['allresult_data'] as $key => $value) {
                    $d = SmStudent::where('id', $value->student_id)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->first();

                    if ($d->count() != 0) {
                        array_push($students['students'], $d);
                    }
                }
            } else {
                $students = SmStudent::where('class_id', '=', $request->current_class)->where('session_id', '=', $request->current_session)->where('section_id', $request->section)->where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            }
            $current_session = $request->current_session;
            $current_class = $request->current_class;
            $sessions = SmAcademicYear::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();

            $classes = SmClass::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            $UpYear = SmAcademicYear::find($current_session);
            $Upsessions = SmAcademicYear::where('active_status', 1)->whereYear('created_at', '>', date('Y', strtotime($UpYear->year)) . ' 00:00:00')->get();
            $Upcls = SmClass::find($current_class);
            $Upclasses = SmClass::where('active_status', 1)->whereYear('created_at', '>', date('Y', strtotime($UpYear->year)) . ' 00:00:00')->get();
            if (@$students['allresult_data'] ? $students['allresult_data']->isEmpty() : empty($students)) {
                Toastr::error('No result found', 'Failed');
                return redirect('student-promote');
            }

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['sessions'] = $sessions->toArray();
                $data['classes'] = $classes->toArray();
                $data['students'] = $students->toArray();
                $data['current_session'] = $current_session;
                $data['current_class'] = $current_class;
                return ApiBaseMethod::sendResponse($data, null);
            }
            $exams = SmExamType::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            return view('backEnd.studentInformation.student_promote', compact('exams', 'Upsessions', 'sessions', 'classes', 'students', 'current_session', 'current_class', 'Upclasses', 'Upcls', 'UpYear'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function ajaxPromoteYear(Request $request)
    {
        $year_name = SmAcademicYear::where('id', '=', $request->year)->select('year')->first();
        $classes = SmClass::where('created_at', 'LIKE', '%' . $year_name->year . '%')->get();

        return response()->json([$classes]);

        // return response()->json($year_name, 200);
        // return response()->json($request->year, 200);
    }
    public function studentPromoteStore(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'promote_session' => 'required',
            'promote_class' => 'required',
            'promote_section' => 'required',
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        // dd($request->all());

        // return $request;

        try {
            $current_session = $request->current_session;
            $current_class = $request->current_class;
            $UpYear = SmAcademicYear::find($current_session);
            $exams = SmExamType::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            $Upsessions = SmAcademicYear::where('active_status', 1)->whereYear('created_at', '>', date('Y', strtotime($UpYear->year)) . ' 00:00:00')->get();
            $sessions = SmAcademicYear::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            $classes = SmClass::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            $promot_year = SmAcademicYear::find($request->promote_session);
            // return $request;
            if ($request->promote_class == "" || $request->promote_session == "") {
                $students = SmStudent::where('class_id', '=', $request->promote_class)->where('session_id', '=', $request->promote_session)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
                //dd($students);            
                Session::flash('message-danger', 'Something went wrong, please try again');

                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    $data = [];
                    $data['sessions'] = $sessions->toArray();
                    $data['classes'] = $classes->toArray();
                    $data['students'] = $students->toArray();
                    $data['current_session'] = $current_session;
                    $data['current_class'] = $current_class;
                    return ApiBaseMethod::sendResponse($data, null);
                }
                return view('backEnd.studentInformation.student_promote', compact('exams', 'Upsessions', 'sessions', 'classes', 'students', 'current_session', 'current_class'));
            } else {

                DB::beginTransaction();

                try {
                    $std_info = [];
                    foreach ($request->id as $student_id) {
                        $student_details = SmStudent::findOrfail($student_id);

                        $new_academic_year = SmAcademicYear::findOrfail($request->promote_session);

                        $old_section = SmSection::findOrfail($student_details->section_id);

                        $new_section = $request->promote_section;

                        if ($request->result[$student_id] == 'P') {
                            $merit_list = \App\SmTemporaryMeritlist::where(['student_id' => $student_id, 'class_id' => $request->current_class, 'section_id' => $student_details->section_id])->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->first();
                            $roll = $merit_list->merit_order;
                        } else {
                            $roll = null;
                            $merit_list = null;
                        }
                        $student_promote = new SmStudentPromotion();
                        $student_promote->student_id = $student_id;
                        $student_promote->previous_class_id = $request->current_class;
                        $student_promote->current_class_id = $request->promote_class;
                        $student_promote->previous_session_id = $request->current_session;
                        $student_promote->current_session_id = $request->promote_session;

                        $student_promote->previous_section_id = $student_details->section_id;
                        $student_promote->current_section_id = $new_section;

                        $student_promote->admission_number = $student_details->admission_no;
                        $student_promote->student_info = $student_details->toJson();
                        $student_promote->merit_student_info = ($merit_list != null ? $merit_list->toJson() : $student_details->toJson());

                        $student_promote->previous_roll_number = $student_details->roll_no;
                        $student_promote->current_roll_number = $roll;

                        $student_promote->result_status = $request->result[$student_id];
                        $student_promote->save();

                        $student = SmStudent::find($student_id);
                        $student->class_id = $request->promote_class;
                        $student->session_id = $request->promote_session;
                        $student->section_id = $new_section;
                        $student->roll_no = $roll;
                        $student->created_at = $promot_year->starting_date . ' 12:00:00';
                        $student->save();
                    }


                    DB::commit();

                    $students = SmStudent::where('class_id', '=', $request->promote_class)->where('session_id', '=', $request->promote_session)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();

                    if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                        return ApiBaseMethod::sendResponse(null, 'Student has been promoted successfully');
                    }
                    Toastr::success('Operation successful', 'Success');
                    return redirect('student-promote');
                } catch (\Exception $e) {
                    DB::rollback();
                    $students = SmStudent::where('class_id', '=', $request->current_class)->where('session_id', '=', $request->current_session)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();

                    Session::flash('message-danger-table', 'Something went wrong, please try again');

                    if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                        $data = [];
                        $data['sessions'] = $sessions->toArray();
                        $data['classes'] = $classes->toArray();
                        $data['students'] = $students->toArray();
                        $data['current_session'] = $current_session;
                        $data['current_class'] = $current_class;
                        return ApiBaseMethod::sendResponse($data, 'Something went wrong, please try again');
                    }
                    Toastr::error('Operation Failed', 'Failed');
                    return view('backEnd.studentInformation.student_promote', compact('exams', 'Upsessions', 'sessions', 'classes', 'students', 'current_session', 'current_class'));
                }
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    //studentReport modified by jmrashed
    public function studentReport(Request $request)
    {
        try {
            $classes = SmClass::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            $types = SmStudentCategory::all();
            $genders = SmBaseSetup::where('active_status', '=', '1')->where('base_group_id', '=', '1')->get();
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['classes'] = $classes->toArray();
                $data['types'] = $types->toArray();
                $data['genders'] = $genders->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.studentInformation.student_report', compact('classes', 'types', 'genders'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    //student report search modified by jmrashed
    public function studentReportSearch(Request $request)
    {
        $request->validate([
            'class' => 'required'
        ]);
        try {
            $students = SmStudent::query();

            $students->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->where('active_status', 1);

            //if no class is selected
            if ($request->class != "") {
                $students->where('class_id', $request->class);
            }
            //if no section is selected
            if ($request->section != "") {
                $students->where('section_id', $request->section);
            }
            //if no student is category selected
            if ($request->type != "") {
                $students->where('student_category_id', $request->type);
            }

            //if no gender is selected
            if ($request->gender != "") {
                $students->where('gender_id', $request->gender);
            }
            $students = $students->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();

            $classes = SmClass::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            $types = SmStudentCategory::all();
            $genders = SmBaseSetup::where('active_status', '=', '1')->where('base_group_id', '=', '1')->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();

            $class_id = $request->class;
            $type_id = $request->type;
            $gender_id = $request->gender;

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['students'] = $students->toArray();
                $data['classes'] = $classes->toArray();
                $data['types'] = $types->toArray();
                $data['genders'] = $genders->toArray();
                $data['class_id'] = $class_id;
                $data['type_id'] = $type_id;
                $data['gender_id'] = $gender_id;
                return ApiBaseMethod::sendResponse($data, null);
            }
            $clas = SmClass::find($request->class);
            return view('backEnd.studentInformation.student_report', compact('students', 'classes', 'types', 'genders', 'class_id', 'type_id', 'gender_id', 'clas'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentAttendanceReport(Request $request)
    {
        try {
            $classes = SmClass::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            $types = SmStudentCategory::all();
            $genders = SmBaseSetup::where('active_status', '=', '1')->where('base_group_id', '=', '1')->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['classes'] = $classes->toArray();
                $data['types'] = $types->toArray();
                $data['genders'] = $genders->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.studentInformation.student_attendance_report', compact('classes', 'types', 'genders'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentAttendanceReportSearch(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'class' => 'required',
            'section' => 'required',
            'month' => 'required',
            'year' => 'required'
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $year = $request->year;
            $month = $request->month;
            $class_id = $request->class;
            $section_id = $request->section;
            $current_day = date('d');
            $clas = SmClass::findOrFail($request->class);
            $sec = SmSection::findOrFail($request->section);
            $days = cal_days_in_month(CAL_GREGORIAN, $request->month, $request->year);
            $classes = SmClass::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            $students = SmStudent::where('class_id', $request->class)->where('section_id', $request->section)->where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();

            $attendances = [];
            foreach ($students as $student) {
                $attendance = SmStudentAttendance::where('student_id', $student->id)->where('attendance_date', 'like', $request->year . '-' . $request->month . '%')->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
                if (count($attendance) != 0) {
                    $attendances[] = $attendance;
                }
            }

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['classes'] = $classes->toArray();
                $data['attendances'] = $attendances;
                $data['days'] = $days;
                $data['year'] = $year;
                $data['month'] = $month;
                $data['current_day'] = $current_day;
                $data['class_id'] = $class_id;
                $data['section_id'] = $section_id;
                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.studentInformation.student_attendance_report', compact('classes', 'attendances', 'days', 'year', 'month', 'current_day', 'class_id', 'section_id', 'clas', 'sec'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function studentAttendanceReportPrint($class_id, $section_id, $month, $year)
    {
        set_time_limit(2700);
        try {
            $current_day = date('d');

            $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $students = DB::table('sm_students')->where('class_id', $class_id)->where('section_id', $section_id)->where('active_status', 1)->get();
            $attendances = [];
            foreach ($students as $student) {
                $attendance = SmStudentAttendance::where('student_id', $student->id)->where('attendance_date', 'like', $year . '-' . $month . '%')->get();
                if ($attendance) {
                    $attendances[] = $attendance;
                }
            }
            $pdf = PDF::loadView(
                'backEnd.studentInformation.student_attendance_print',
                [
                    'attendances' => $attendances,
                    'days' => $days,
                    'year' => $year,
                    'month' => $month,
                    'class_id' => $class_id,
                    'section_id' => $section_id,
                ]
            )->setPaper('A4', 'landscape');
            return $pdf->stream('student_attendance.pdf');
            //return view('backEnd.studentInformation.student_attendance_print', compact('classes', 'attendances', 'days', 'year', 'month', 'current_day', 'class_id', 'section_id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function importStudent()
    {
        try {
            $classes = SmClass::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            $genders = SmBaseSetup::where('base_group_id', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            $blood_groups = SmBaseSetup::where('base_group_id', 3)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            $religions = SmBaseSetup::where('base_group_id', 2)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            $sessions = SmAcademicYear::where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            return view('backEnd.studentInformation.import_student', compact('classes', 'genders', 'blood_groups', 'religions', 'sessions'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function downloadStudentFile()
    {
        // try {
            $studentsArray = ['session', 'admission_number', 'roll_no', 'first_name', 'last_name', 'date_of_birth', 'religion', 'gender', 'caste', 'mobile', 'email', 'admission_date', 'blood_group', 'height', 'weight', 'father_name', 'father_phone', 'father_occupation', 'mother_name', 'mother_phone', 'mother_occupation', 'guardian_name', 'guardian_relation', 'guardian_email', 'guardian_phone', 'guardian_occupation', 'guardian_address', 'current_address', 'permanent_address', 'bank_account_no', 'bank_name', 'national_identification_no', 'local_identification_no', 'previous_school_details', 'note'];
            return Excel::create('students', function ($excel) use ($studentsArray) {
                $excel->sheet('students', function ($sheet) use ($studentsArray) {
                    $sheet->fromArray($studentsArray);
                });
            })->download('xlsx');
        // } catch (\Exception $e) {
        //     Toastr::error('Operation Failed', 'Failed');
        //     return redirect()->back();
        // }
    }


    public function studentBulkStore(Request $request)
    {
        $request->validate([
            'class' => 'required',
            'section' => 'required',
            'file' => 'required'
        ]);

       

        $file_type=strtolower($request->file->getClientOriginalExtension());
        if ($file_type<>'csv' && $file_type<>'xlsx' && $file_type<>'xls') {
            Toastr::warning('The file must be a file of type: xlsx, csv or xls', 'Warning');
            return redirect()->back();
        }else{
            try {
                
                $path = $request->file('file')->getRealPath();
                $data = Excel::load($path, function($reader){

                })->get();

              /*   $usersUnique = $data->unique('admission_number');
                $usersDupes = $data->diff($usersUnique);  
                dd(sizeof($usersDupes));
                if (sizeof($usersDupes) > sizeof($data)) {
                    return redirect()->back()->with("message-danger","Admission number required");
                 }                
                if (sizeof($usersDupes) >= 1) {
                   return redirect()->back()->with("message-danger","Admission number should be unique");
                } */
                

                $shcool_details = SmGeneralSettings::find(1);
                $school_name = explode(' ', $shcool_details->school_name);
                $short_form = '';
                foreach ($school_name as $value) {
                    $ch = str_split($value);
                    $short_form = $short_form . '' . $ch[0];
                }
    
                if (!empty($data)) {
                    DB::beginTransaction();    
                    foreach ($data as $key => $value) {  
                        if ($value->filter()->isNotEmpty()) {
                            $chk =  DB::table('sm_students')->where('admission_no', $value->admission_number)->count();
                             if ($chk >= 1) {
                                    return redirect()->back()->with("message-danger","Admission number should be unique");
                             }
                            try {
                                $user_stu = new User();
                                $user_stu->role_id = 2;
                                $user_stu->full_name = $value->first_name . ' ' . $value->last_name;
    
    
                                if ($value->email != null) {
                                    $user_stu->username = $value->email;
                                } else {
                                    $user_stu->username = $short_form . '-' . $value->admission_number;
                                }
    
    
                                if (empty($value->email)) {

                                    $data_student['email'] = $value->admission_number;



                                    $user_stu->email = $value->admission_number;
                                } else {
                                    $data_student['email'] = $value->email;

                                    $user_stu->email = $value->email;
                                } 
                                $user_stu->password = Hash::make(123456);
                                $user_stu->save();
                                $user_stu->toArray();
    
                                try {   
    
                                    $user_parent = new User();
                                    $user_parent->role_id = 3;
                                    $user_parent->full_name = $value->father_name;
    
    
    
                                    if (empty($value->guardian_email)) {
                                        $data_parent['email'] = 'par_' . $value->admission_number;
    
                                        $user_parent->username  = 'par_' . $value->admission_number;
                                    } else {

                                        $data_parent['email'] = $value->guardian_email;

                                        $user_parent->username = $value->guardian_email;
                                    }
    
                                    $user_parent->email = $value->guardian_email;    
    
                                    $user_parent->password = Hash::make(123456);
                                    $user_parent->save();
                                    $user_parent->toArray();    
    
                                    try {   
    
                                        $parent = new SmParent();
                                        $parent->user_id = $user_parent->id;
                                        $parent->fathers_name = $value->father_name;
                                        $parent->fathers_mobile = $value->father_phone;
                                        $parent->fathers_occupation = $value->fathe_occupation;
                                        $parent->mothers_name = $value->mother_name;
                                        $parent->mothers_mobile = $value->mother_phone;
                                        $parent->mothers_occupation = $value->mother_occupation;
                                        $parent->guardians_name = $value->guardian_name;
                                        $parent->guardians_mobile = $value->guardian_phone;
                                        $parent->guardians_occupation = $value->guardian_occupation;
                                        $parent->guardians_relation = $value->relation;
                                        $parent->relation = $value->relationButton;
                                        $parent->guardians_address = $value->guardian_address;
                                        $parent->save();
                                        $parent->toArray();
    
    
    
                                        try {
                                            $student = new SmStudent();
                                            // $student->siblings_id = $value->sibling_id;
                                            $student->class_id = $request->class;
                                            $student->section_id = $request->section;
                                            $student->session_id = $value->session;
                                            $student->user_id = $user_stu->id;
    
    
                                            $student->parent_id = $parent->id;
                                            $student->role_id = 2;  
    
                                            $student->admission_no = $value->admission_number;
                                            $student->roll_no = $value->roll_no;
                                            $student->first_name = $value->first_name;
                                            $student->last_name = $value->last_name;
                                            $student->full_name = $value->first_name . ' ' . $value->last_name;
                                            $student->gender_id = $value->gender;
                                            $student->date_of_birth = date('Y-m-d', strtotime($value->date_of_birth));
                                            $student->caste = $value->caste;
                                            $student->email = $value->email;
                                            $student->mobile = $value->mobile;
                                            $student->admission_date = date('Y-m-d', strtotime($value->admission_date));
                                            $student->bloodgroup_id = $value->blood_group;
                                            $student->religion_id = $value->religion;
                                            $student->height = $value->height;
                                            $student->weight = $value->weight;
                                            $student->current_address = $value->current_address;
                                            $student->permanent_address = $value->permanent_address;
                                            $student->national_id_no = $value->national_identification_no;
                                            $student->local_id_no = $value->local_identification_no;
                                            $student->bank_account_no = $value->bank_account_no;
                                            $student->bank_name = $value->bank_name;
                                            $student->previous_school_details = $value->previous_school_details;
                                            $student->aditional_notes = $value->note;
    
                                            $student->save();

                                            $user_info = [];

                                            if($value->email != ""){

                                                $user_info[] =  array('email' => $value->email, 'username' => $data_student['email']);
                                            }


                                            if($value->guardian_email != ""){
                                                $user_info[] =  array('email' =>  $value->guardian_email, 'username' => $data_parent['email']);
                                            }

                                            

                                            
                                        } catch (\Illuminate\Database\QueryException $e) {
                                            return redirect()->back()->with("message-danger","Admission number should be unique");
                                         }catch (\Exception $e) {
                                            DB::rollback();
                                            Toastr::error('Operation Failed', 'Failed');
                                            return redirect()->back();
                                        }
                                    } catch (\Exception $e) {
                                        DB::rollback();
                                        Toastr::error('Operation Failed', 'Failed');
                                        return redirect()->back();
                                    }
                                } catch (\Exception $e) {
                                    DB::rollback();
                                    Toastr::error('Operation Failed', 'Failed');
                                    return redirect()->back();
                                }
                            } catch (\Exception $e) {
                                DB::rollback();
                                Toastr::error('Operation Failed', 'Failed');
                                return redirect()->back();
                            }
                        }
                    }


                    // if(count($user_info) != 0){
                        
                    //     $systemSetting = SmGeneralSettings::select('school_name', 'email')->find(1);


                    //     $systemEmail = SmEmailSetting::find(1);

                    //     $system_email = $systemEmail->from_email;
                    //     $school_name = $systemSetting->school_name;


                    //     $sender['system_email'] = $system_email;
                    //     $sender['school_name'] = $school_name;

                    //     dispatch(new \App\Jobs\SendUserMailJob($user_info, $sender));

                    // }


                    DB::commit();
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                }
            } catch (\Exception $e) {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }
        
       
    }


    public function guardianReport(Request $request)
    {
        try {
            $students = SmStudent::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            $classes = SmClass::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['students'] = $students->toArray();
                $data['classes'] = $classes->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.studentInformation.guardian_report', compact('classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function guardianReportSearch(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'class' => 'required'
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $students = SmStudent::query();
            $students->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->where('active_status', 1);
            $students->where('class_id', $request->class);
            if ($request->section != "") {
                $students->where('section_id', $request->section);
            }
            $students = $students->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();

            $classes = SmClass::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();


            $class_id = $request->class;

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['students'] = $students->toArray();
                $data['classes'] = $classes->toArray();
                $data['class_id'] = $class_id;
                return ApiBaseMethod::sendResponse($data, null);
            }
            $clas = SmClass::find($request->class);
            return view('backEnd.studentInformation.guardian_report', compact('students', 'classes', 'class_id', 'clas'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentLoginReport(Request $request)
    {
        try {
            $students = SmStudent::all();
            $classes = SmClass::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['students'] = $students->toArray();
                $data['classes'] = $classes->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.studentInformation.login_info', compact('classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function studentLoginSearch(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'class' => 'required'
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $students = SmStudent::query();
            $students->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->where('active_status', 1);
            $students->where('class_id', $request->class);
            if ($request->section != "") {
                $students->where('section_id', $request->section);
            }
            $students = $students->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();

            $classes = SmClass::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            $class_id = $request->class;

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['students'] = $students->toArray();
                $data['classes'] = $classes->toArray();
                $data['class_id'] = $class_id;
                return ApiBaseMethod::sendResponse($data, null);
            }
            $clas = SmClass::find($request->class);
            return view('backEnd.studentInformation.login_info', compact('students', 'classes', 'class_id', 'clas'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function disabledStudent(Request $request)
    {
        try {
            $students = SmStudent::where('active_status', 0)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();

            $classes = SmClass::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['students'] = $students->toArray();
                $data['classes'] = $classes->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }
            return view('backEnd.studentInformation.disabled_student', compact('students', 'classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function disabledStudentSearch(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'class' => 'required'
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $students = SmStudent::query();
            $students->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->where('active_status', 0);
            if ($request->class != "") {
                $students->where('class_id', $request->class);
            }
            if ($request->section != "") {
                $students->where('section_id', $request->section);
            }
            if ($request->name != "") {
                $students->where('full_name', 'like', '%' . $request->name . '%');
            }
            if ($request->roll_no != "") {
                $students->where('roll_no', 'like', '%' . $request->roll_no . '%');
            }
            $students = $students->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();

            $classes = SmClass::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();

            $class_id = $request->class;
            $section_id = $request->section;
            $name = $request->name;
            $roll_no = $request->roll_no;


            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['students'] = $students->toArray();
                $data['classes'] = $classes->toArray();
                $data['class_id'] = $class_id;
                $data['section_id'] = $section_id;
                $data['name'] = $name;
                $data['roll_no'] = $roll_no;
                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.studentInformation.disabled_student', compact('students', 'classes', 'class_id', 'section_id', 'name', 'roll_no'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    
    public function disabledStudentDelete1(Request $request)
    {
        try {

            $student_detail = SmStudent::find($request->id);
            $parent_user = @$student_detail->parents->user_id;


            $siblings = SmStudent::where('parent_id', $student_detail->parent_id)->get();


                DB::beginTransaction();


                if($student_detail->student_photo != ""){
                    if(file_exists($student_detail->student_photo)){
                        unlink($student_detail->student_photo);
                    }
                }

                SmStudent::destroy($request->id);


                if (count($siblings) == 1) {
                    $parent = SmParent::find($student_detail->parent_id);

                    if($parent->fathers_photo != ""){
                        if(file_exists($parent->fathers_photo)){
                            unlink($parent->fathers_photo);
                        }
                    }
                    if($parent->mothers_photo != ""){
                        if(file_exists($parent->mothers_photo)){
                            unlink($parent->mothers_photo);
                        }
                    }
                    if($parent->guardians_photo != ""){
                        if(file_exists($parent->guardians_photo)){
                            unlink($parent->guardians_photo);
                        }
                    }

                    $parent->delete();
                }

                
        
                $student_user = User::find($student_detail->user_id);
                $student_user->delete();

                if (count($siblings) == 1) {

                    $parent_user = User::find($parent_user);
                    $parent_user->delete();

                }

                DB::commit();

                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendResponse(null, 'Student has been disabled successfully');
                }

                Toastr::success('Operation successful', 'Success');
                    return redirect()->back();


            } catch (\Exception $e) {
                DB::rollback();

                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendError('Something went wrong, please try again');
                }
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }          
    }
    public function disabledStudentDelete(Request $request)
    {
        
        try{
            $tables = \App\tableList::getTableList('student_id', $request->$id);
            try {
                if ($tables==null) {
                    $student_detail = SmStudent::find($request->id);
                    $parent_user = @$student_detail->parents->user_id;
                    $siblings = SmStudent::where('parent_id', $student_detail->parent_id)->get();
                        DB::beginTransaction();
                        if($student_detail->student_photo != ""){
                            if(file_exists($student_detail->student_photo)){
                                unlink($student_detail->student_photo);
                            }
                        }
        
                        SmStudent::destroy($request->id);
        
        
                        if (count($siblings) == 1) {
                            $parent = SmParent::find($student_detail->parent_id);
        
                            if($parent->fathers_photo != ""){
                                if(file_exists($parent->fathers_photo)){
                                    unlink($parent->fathers_photo);
                                }
                            }
                            if($parent->mothers_photo != ""){
                                if(file_exists($parent->mothers_photo)){
                                    unlink($parent->mothers_photo);
                                }
                            }
                            if($parent->guardians_photo != ""){
                                if(file_exists($parent->guardians_photo)){
                                    unlink($parent->guardians_photo);
                                }
                            }
        
                            $parent->delete();
                        }
                        $student_user = User::find($student_detail->user_id);
                        $student_user->delete();
        
                        if (count($siblings) == 1) {
        
                            $parent_user = User::find($parent_user);
                            $parent_user->delete();
        
                        }
        
                        DB::commit();
        
                        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                            return ApiBaseMethod::sendResponse(null, 'Student has been disabled successfully');
                        }
        
                        Toastr::success('Operation successful', 'Success');
                            return redirect()->back();
                } else {
                    DB::rollback();
                    $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                    Toastr::error($msg, 'Failed');
                    return redirect()->back();
                }
            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollback();
                $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                Toastr::error($msg, 'Failed');
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollback();
                //dd($e->getMessage(), $e->errorInfo);
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
            DB::rollback();
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back(); 
        }
    }


    public function enableStudent(Request $request)
    {
        // try {



                DB::beginTransaction();



                $student_detail = SmStudent::find($request->id);

                $student_detail->active_status = 1;
                // $student_detail->save();


                $parent = SmParent::find($student_detail->parent_id);
                $parent->active_status = 1;
                $parent->save();

                $student_user = User::find($student_detail->user_id);
                $student_user->active_status = 1;
                $student_user->save();


                $parent_user = User::find(@$student_detail->parents->user_id);
                $parent_user->active_status = 1;
                $parent_user->save();

                $student_detail->save();

                DB::commit();

                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    return ApiBaseMethod::sendResponse(null, 'Student has been enabled successfully');
                }

                Toastr::success('Operation successful', 'Success');
                    return redirect()->back();



            // } catch (\Exception $e) {
            //     DB::rollback();

            //     if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            //         return ApiBaseMethod::sendError('Something went wrong, please try again');
            //     }
            //     Toastr::error('Operation Failed', 'Failed');
            //     return redirect()->back();
            // }
                    
    }


    public function studentHistory(Request $request)
    {
        try {
            $classes = SmClass::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            $students = SmStudent::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            $admission_years = SmStudent::groupBy('admission_date')->select('admission_date')->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();

            $years = SmStudent::select('admission_date')->where('active_status', 1)
                ->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get()
                ->groupBy(function ($val) {
                    return Carbon::parse($val->admission_date)->format('Y');
                });

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['classes'] = $classes->toArray();
                $data['students'] = $students->toArray();
                $data['years'] = $years->toArray();
                return ApiBaseMethod::sendResponse($data, null);
            }

            return view('backEnd.studentInformation.student_history', compact('classes', 'years'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentHistorySearch(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'class' => 'required'
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $classes = SmClass::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            $students = SmStudent::query();
            $students->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->where('active_status', 1);
            $students->where('class_id', $request->class);
            $students->where('active_status', 1);
            if ($request->admission_year != "") {
                $students->where('admission_date', 'like',  $request->admission_year . '%');
            }

            $students = $students->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();


            $years = SmStudent::select('admission_date')->where('active_status', 1)
                ->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get()
                ->groupBy(function ($val) {
                    return Carbon::parse($val->admission_date)->format('Y');
                });


            $class_id = $request->class;
            $year = $request->admission_year;

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['students'] = $students->toArray();
                $data['classes'] = $classes->toArray();
                $data['years'] = $years->toArray();
                $data['class_id'] = $class_id;
                $data['year'] = $year;
                return ApiBaseMethod::sendResponse($data, null);
            }
            $clas = SmClass::find($request->class);
            return view('backEnd.studentInformation.student_history', compact('students', 'classes', 'years', 'class_id', 'year', 'clas'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }



    public function view_academic_performance(Request $request, $id)
    {

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            return ApiBaseMethod::sendResponse($id, null);
        }
        return $id;
    }

    function previousRecord()
    {
        try {
            $academic_years = SmAcademicYear::all();
            $exam_types = SmExamType::get();

            $classes = SmClass::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            return view('backEnd.examination.previous_record', compact('classes', 'exam_types', 'academic_years'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    function previousRecordSearch(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'promote_session' => 'required',
            'promote_class' => 'required',
            'promote_section' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $yearCh = SmAcademicYear::find($request->promote_session);
            $students = SmStudentPromotion::where('created_at', 'LIKE', '%' . $yearCh->year . '%');
            if ($request->promote_class != "") {
                $students->where('previous_class_id', $request->promote_class);
            }
            if ($request->promote_section != "") {
                $students->where('previous_section_id', $request->promote_section);
            }
            $year = $request->promote_session;
            $students = $students->get();

            $academic_years = SmAcademicYear::all();
            $exam_types = SmExamType::get();
            $classes = SmClass::where('active_status', 1)->where('created_at', 'LIKE', '%' . YearCheck::getYear() . '%')->get();
            $clas = SmClass::find($request->promote_class);
            $sec = SmSection::find($request->promote_section);
            return view('backEnd.examination.previous_record', compact('classes', 'exam_types', 'academic_years', 'students', 'year', 'clas', 'sec'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}