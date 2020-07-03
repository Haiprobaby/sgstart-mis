<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\discount;

class AjaxEditController extends Controller
{
	//Phạm Trọng Hải
    public function editdiscount(Request $request)
    {
    	//cập nhật lại table discount theo dữ liệu gửi lên từ ajax
    	$discount = discount::find($request->id_discount);
    	$discount->Discount_for = $request->discount_for;
    	$discount->Year1st = $request->first_year;
    	$discount->Year2nd = $request->second_year;
    	$discount->Year3rd = $request->third_year;
    	$discount->Year4th = $request->fourth_year;
    	$discount->Year5th = $request->fifth_year;
    	$discount->save();


    }

    public function adddiscount(Request $request)
    {
    	
    	$discount=new discount;
    	$discount->Discount_for = $request->discount_for;
    	$discount->Year1st = $request->first_year;
    	$discount->Year2nd = $request->second_year;
    	$discount->Year3rd = $request->third_year;
    	$discount->Year4th = $request->fourth_year;
    	$discount->Year5th = $request->fifth_year;
    	$discount->save();
    }
}
