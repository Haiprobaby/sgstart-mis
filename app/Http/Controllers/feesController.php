<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\sm_fees_group;
use App\sm_fees;
use App\years;
use App\bus_fee;
use DB;
use App\discount;
use Brian2694\Toastr\Facades\Toastr;
use App\late_enrolment;
use App\school_grades;
use App\withdraw;
class feesController extends Controller
{

	public function getlist()
	{
        $discount=discount::all();
		$list = sm_fees_group::all();
        $late = late_enrolment::all();
        $grades=school_grades::all();
        $withdraw=withdraw::all();
		return view('backEnd.feesCollection.fee_policies.list_group',compact('list','discount','late','grades','withdraw'));
	}

    public function editfees($id)
    {
		$group=sm_fees_group::find($id);
		$years = years::all()->where('id_group',$id);
		$fees = sm_fees::all()->where('fees_group',$id);
		$bus = bus_fee::all();
    	return view('backEnd.feesCollection.fee_policies.middle_year.edit',['id'=>$id,'years'=>$years,'fees'=>$fees,'bus'=>$bus]);

    	
    }

    public function updatefees(Request $request)
    {
    	$fee_id=$request->fee_id;
    	$bus_id=$request->bus_id;
    	$year_id=$request->year_id;
    	$fees=str_replace(",","", $request->fees);
    	$bus =str_replace(",","", $request->bus);
    	$paymentA=str_replace(",","", $request->paymentA);
    	$paymentB1=str_replace(",","", $request->paymentB1);
    	$paymentB2=str_replace(",","", $request->paymentB2);
    	$paymentB3=str_replace(",","", $request->paymentB3);

    	//dd($fee_id,$fees,$bus,$OptionA,$OptionB1,$OptionB2,$OptionB3);

    	
    	 for($i=0;$i<count($fee_id);$i++)
    	 {
    	 	$update=sm_fees::find($fee_id[$i]);
    	 	$update->price=$fees[$i];
    	 	$update->save();
    	 	
    	 }

    	 for($i=0;$i<count($bus_id);$i++)
    	 {
    	 	$update=bus_fee::find($bus_id[$i]);
    	 	$update->price=$bus[$i];
    	 	$update->save();
    	 	
    	 }

    	 for($i=0;$i<count($year_id);$i++)
    	 {
    	 	$update=years::find($year_id[$i]);
    	 	$update->paymentA=$paymentA[$i];
    	 	$update->paymentB1=$paymentB1[$i];
    	 	$update->paymentB2=$paymentB2[$i];
    	 	$update->paymentB3=$paymentB3[$i];
    	 	$update->save();
    	 	
    	 }

    	 Toastr::success('Operation successful', 'Success');
            return redirect()->back(); 
              
    }
}
