<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function AdminDashbord(){
        return view('admin.index');
    }//end admin
     public function AdminLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        $notification=array(
            'message'=>'Admin Logout succesfully',
            'alert-type'=>'success'
        );

        return redirect('/admin/login')->with($notification);
    }//end method
    public function AdminLogin(){
        return view('admin.admin_login');
    }//end method
    public function AdminProfile(){
        $id=Auth::user()->id;
        $profileData=User::find($id);
        return view('admin.admin_profile_page',compact('profileData'));
    }//end method
    public function AdminProfileStore(Request $request){
        $id=Auth::user()->id;
        $data=User::find($id);
        $data->username=$request->username;
        $data->name=$request->name;
        $data->email=$request->email;
        $data->phone=$request->phone;
        $data->address=$request->address;
        if ($request->file('photo')) {
            $file=$request->file('photo');
            @unlink(Public_path('upload/admin_images'.$data->photo));
            $filename=date('ymdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'),$filename);
            $data['photo']=$filename;
        }
        $data->save();
        $notification=array(
            'message'=>'Admin profile updated succesfully',
            'alert-type'=>'success'
        );
        return redirect()->back()->with($notification);
        

    }//end method
    public function AdminChangePassword(){
        $id=Auth::user()->id;
        $profileData=User::find($id);
        return view('admin.admin_change_password',compact('profileData'));
    
    }//end method
    public function AdminUpdatePassword(Request $request){
        //validate
        $request->validate([
            'new_password'=>'required',
            'old_password'=>'required'
        ]);
        ///match the old password
        if(!Hash::check($request->old_password, auth::user()->password)){
            $notification=array(
                'message'=>'Old Password does not match!',
                'alert-type'=>'error'
            );
            return back()->with($notification);
    }
    ///update the new password
    User::whereId(auth()->user()->id)->update([
        'password'=>Hash::make($request->new_password)
    ]);
    $notification=array(
        'message'=>'password changed Succesfully',
        'alert-type'=>'success'
    );
    return back()->with($notification);
}//end method
public function AllAgent(){
    $allagent=User::where('role','agent')->get();
    return view('backend.agentuser.all_agent',compact('allagent'));

}//end method
public function AddAgent(){
    return view('backend.agentuser.add_agent');
}//end method
public function StoreAgent(Request $request){
    User::insert([
        'name'=>$request->name,
        'email'=>$request->email,
        'phone'=>$request->phone,
        'address'=>$request->address,
        'password'=>Hash::make($request->password),
        'role'=>'agent',
        'status'=>'active',
    ]);
        $notification=array(
        'message'=>'Agent Created Succesfully',
        'alert-type'=>'success'
    );
    return redirect()->route('all.agent')->with($notification);
}//end method
public function EditAgent($id){
    $EditAgent=User::findOrFail($id);
    return view('backend.agentuser.edit_agent',compact('EditAgent'));
}//end method
public function UpdateAgent(Request $request){
    $updateagent=$request->id;
    User::findOrFail($updateagent)->update([
         'name'=>$request->name,
        'email'=>$request->email,
        'phone'=>$request->phone,
        'address'=>$request->address,
    ]);
        $notification=array(
        'message'=>'Agent updated Succesfully',
        'alert-type'=>'success'
    );
    return redirect()->route('all.agent')->with($notification);

}//end method
public function DeleteAgent($id){
    User::findOrFail($id)->delete();
    $notification=array(
        'message'=>'Agent deleted Succesfully',
        'alert-type'=>'success'
    );
    return redirect()->back()->with($notification);

}//end method
public function changeStatus(Request $request){

    $user = User::find($request->user_id);
    $user->status = $request->status;
    $user->save();

    return response()->json(['success'=>'Status Change Successfully']);

  }// End Method 
}