<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    //
    public function AdminDashboard(){
        return view('admin.index');
    }

public function adminLogin(){
    return view('admin.admin_login');
}


public function AdminLogout(Request $request) {
    Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
}//end method

public function AdminProfile() {
    $id = Auth::user()->id;
    $adminData = User::find($id);
    //i will return these data to another view
    return view('admin.admin_profile_view',['adminData'=>$adminData]);
}

public function AdminProfileStore(Request $request) {
$id = Auth::user()->id;
$data = User::find($id); //data from table
$data->name = $request->name;
$data->email = $request->email;
$data->phone = $request->phone;
$data->address = $request->address;

if($request->file('photo')) {
    $file = $request->file('photo');
    @unlink(public_path('uploads/admin_images/'.$data->photo));
    $filename = date('YmdHi').$file->getClientOriginalName(); //get New name of image
    $file->move(public_path('uploads/admin_images'),$filename);
    $data['photo'] = $filename; //to store at db
}

$data->save();
$notification = array(
    'message'=>'Admin Profile Updated successfully',
    'alert-type'=>'success'
);
return redirect()->back()->with($notification);

}//end method


public function AdminchangePassword(Request $request) {
    $id = Auth::user()->id;
    return view('admin.admin_change_password');
}

public function AdminUpdatePassword(Request $request) {
    //validation
    $request->validate([
        'old_password'=>'required',
        'new_password'=>'required|confirmed',
    ]);
    //match old password
    if(!Hash::check($request->old_password,auth::user()->password)) {
        //return it with error
        return back()->with("error","old password doesnt match !!");
    }

    //update new password
    User::whereId(auth()->user()->id)->update([
        "password"=>Hash::make($request->new_password)
    ]);
    return back()->with("status","password changed successfully ");
}

}


