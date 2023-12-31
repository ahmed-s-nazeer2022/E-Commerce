<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class VendorController extends Controller
{
    //
    public function VendorDashboard(){
        return view('vendor.index');
    }//end

    public function vendorLogin() {
        return view('vendor.vendor_login');
    }//end

    public function vendorLogout(Request $request) {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/vendor/login');
    }//

    public function VendorProfile() {
        $id = Auth::user()->id;
        $vendorData = User::find($id);
        //i will return these data to another view
        return view('vendor.vendor_profile_view',['vendorData'=>$vendorData]);
    }

    public function VendorProfileStore(Request $request) {
    $id = Auth::user()->id;
    $data = User::find($id); //data from table
    $data->name = $request->name;
    $data->email = $request->email;
    $data->phone = $request->phone;
    $data->address = $request->address;

    if($request->file('photo')) {
        $file = $request->file('photo');
        @unlink(public_path('uploads/vendor_images/'.$data->photo));
        $filename = date('YmdHi').$file->getClientOriginalName(); //get New name of image
        $file->move(public_path('uploads/vendor_images'),$filename);
        $data['photo'] = $filename; //to store at db
    }

    $data->save();
    $notification = array(
        'message'=>'vendor Profile Updated successfully',
        'alert-type'=>'success'
    );
    return redirect()->back()->with($notification);

    }//end method

    public function VendorchangePassword(Request $request) {
        return view('vendor.vendor_change_password');
    } //end

    public function VendorUpdatePassword(Request $request) {
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
