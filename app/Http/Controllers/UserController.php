<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function UserDashboard() {
        $id = Auth::user()->id;
        $userData = User::find($id); //data from table
        return view('index',['userData'=>$userData]);
    }//end method


    public function UserLogout(Request $request) {
        Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect('/login');
    }//end method

    public function UserProfileStore(Request $request) {
        $id = Auth::user()->id;
        $data = User::find($id); //data from table
        $data->name = $request->name;
        $data->username = $request->username;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;

        if($request->file('photo')) {
        $file = $request->file('photo');
        @unlink(public_path('uploads/user_images/'.$data->photo));
        $filename = date('YmdHi').$file->getClientOriginalName(); //get New name of image
        $file->move(public_path('uploads/user_images'),$filename);
        $data['photo'] = $filename; //to store at db
        }

        $data->save();
        $notification = array(
        'message'=>'User Profile Updated successfully',
        'alert-type'=>'success'
        );
        return redirect()->back()->with($notification);

    }//end

}
