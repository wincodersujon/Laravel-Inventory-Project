<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class CustomerController extends Controller
{
    function CustomerPage(){
        return view('pages.dashboard.customer-page');
    }

    function CustomerCreate(Request $request){
        $user_id=$request->header('id');
        return Customer::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
            'user_id' => $user_id
        ]);
    }
    function CustomerUpdate(Request $request){
        $category_id=$request->input('id');
        $user_id=$request->header('id');
        return Customer::where('id',$category_id)->where('user_id',$user_id)->update([
            'name'=>$request->input('name'),
        ]);
    }
    function CustomerList(Request $request){
        $user_id=$request->header('id');
        return Customer::where('user_id',$user_id)->get();
    }
    public function CustomerDelete(Request $request){
        $customer_id=$request->input('id');
        $user_id=$request->header('id');
        return Customer::where('id', $customer_id)->where('user_id',$user_id)->delete();
    }
    public function CustomerByID(Request $request){
        $customer_id=$request->input('id');
        $user_id=$request->header('id');
        return Customer::where('id', $customer_id)->where('user_id',$user_id)->first();
    }
}
