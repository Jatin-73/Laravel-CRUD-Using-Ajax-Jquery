<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use Validator;

class StudentController extends Controller
{

    public function index()
    {
        $students = Student::orderBy('id', 'DESC')->paginate(5);
        return view('studentform')->with('students', $students);
    }

    public function store(Request $request)
    {
        $rules = array(
            'fname'    =>  'required',
            'lname'    =>  'required',
            'course'   =>  'required',
            'section'  =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        $students = new Student;
        $students->fname    = $request->input('fname');
        $students->lname    = $request->input('lname');
        $students->course   = $request->input('course');
        $students->section  = $request->input('section'); 

        if($students->save()){
            return response()->json($students);
        }
    }

    public function edit($id)
    {   
        $where = array('id' => $id);
        $student = Student::where($where)->first();

        return response()->json($student);
    }

    public function update(Request $request)
    {
        $updated = Student::whereId($request->hidden_id)->update(array(
            'fname'    =>  $request->fname,
            'lname'    =>  $request->lname,
            'course'   =>  $request->course,
            'section'  =>  $request->section
        ));

        $student = Student::where(['id'=>$request->hidden_id])->first();

        if($updated){
            return response()->json($student);
        }
    }

    public function destroy($id)
    {
        $student = Student::where('id',$id)->delete();
        return response()->json($student);
    }
}
