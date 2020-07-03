<?php

namespace App\Http\Controllers;
use App\SmNotification;
use Brian2694\Toastr\Facades\Toastr;
class SmNotificationController extends Controller
{
    public function __construct(){
        $this->middleware('PM');
    }
    
    public function viewSingleNotification($id){
    	
		try{
			$notification = SmNotification::find($id);
			$notification->is_read = 1;
			$notification->save();
	
			return redirect()->back();
		}catch (\Exception $e) {
		   Toastr::error('Operation Failed', 'Failed');
		   return redirect()->back(); 
		}
    }

    public function viewAllNotification($id){

    	
		try{
			$user = Auth()->user();
			if(Auth()->user()->role_id != 1){
				if($user->role_id == 2){
					SmNotification::where('user_id', $user->student->id)->where('role_id', 2)->update(['is_read' => 1]);
				}else{
					SmNotification::where('user_id', $user->staff->id)->where('role_id', '!=', 2)->update(['is_read' => 1]);
				}
			}			
			return redirect()->back();
		}catch (\Exception $e) {
		   Toastr::error('Operation Failed', 'Failed');
		   return redirect()->back(); 
		}
    }

    public function viewNotice($id){
		Toastr::error('Operation Failed', 'Failed');
		return redirect()->back(); 
    }
}
