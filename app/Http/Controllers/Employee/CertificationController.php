<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificationController extends Controller
{
    public function show(){

        $employee_id = Auth::guard('employee')->user()->id;

        $certification = Certification::with(['employee','course'])->where('employee_id','=',$employee_id)->get();

        return view('employee.certifications.show',compact('certification'));
    }
}
