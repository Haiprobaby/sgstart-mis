<?php

namespace App\Http\Controllers;
use App\SmQuestionLevel;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
class SmQuestionLevelController extends Controller
{
    public function __construct(){
        $this->middleware('PM');
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        try{
            $levels = SmQuestionLevel::where('active_status', 1)->get();
            return view('backEnd.examination.question_level', compact('levels'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back(); 
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'level' => "required|unique:sm_question_levels"
        ]);        
        try{
            $level = new SmQuestionLevel();
            $level->level = $request->level;
            $result = $level->save();
            if($result){
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
                // return redirect()->back()->with('message-success', 'Level has been created successfully');
            }else{
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
                // return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back(); 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {       
        try{
            $level = SmQuestionLevel::find($id);
            $levels = SmQuestionLevel::where('active_status', 1)->get();
            return view('backEnd.examination.question_level', compact('levels', 'level'));
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back(); 
        }
    }
    public function update(Request $request, $id)
    {        
        try{
            $request->validate([
                'level' => "required|unique:sm_question_levels,level,".$request->id
            ]);
    
            $level = SmQuestionLevel::find($request->id);
            $level->level = $request->level;
            $result = $level->save();
            if($result){
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
                // return redirect()->back()->with('message-success', 'Level has been updated successfully');
            }else{
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
                // return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back(); 
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        try{
            $level = SmQuestionLevel::destroy($id);
            if($level){
                Toastr::success('Operation successful', 'Success');
                return redirect('question-level');
                // return redirect('question-level')->with('message-success-delete', 'Level has been deleted successfully');
            }else{
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
                // return redirect()->back()->with('message-danger-delete', 'Something went wrong, please try again');
            }
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back(); 
        }
    }
}
