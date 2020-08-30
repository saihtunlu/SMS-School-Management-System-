<?php

namespace App\Http\Controllers\Api;

use App\Employee;
use App\Father;
use App\Mother;
use App\Http\Controllers\Controller;
use App\Permission_Employee;
use App\Permission_Student;
use App\Permission_User;
use App\Role;
use App\Student;
use app\User;
use App\User_Role;
use App\PermissionInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\SignupActivate;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getemployee()
    {
        $this->authorize('isUser_Read');
        $user = User::where('type', 'Employee')->get();
        return response()->json($user);
    }
    public function index()
    {
        $user = User::with('user_role', 'user_role.role')->get();
        return response()->json($user);
    }
    public function employee()
    {
        $this->authorize('isUser_Read');
        $user = User::where('type', 'Employee')->orderBy('created_at', 'desc')->with('employee', 'user_role.role', 'permission_student', 'permission_user',  'permission_employee', 'permission_invoice')->get();
        return response()->json($user);
    }
    public function student()
    {
        $this->authorize('isUser_Read');
        $user = User::where('type', 'Student')->with('user_role.role', 'student', 'permission_student', 'permission_user',  'permission_employee', 'permission_invoice')->orderBy('created_at', 'desc')->get();
        return response()->json($user);
    }
    public function parent()
    {
        $this->authorize('isUser_Read');
        $user = User::where('type', 'parent')->with('user_role.role', 'permission_student', 'permission_user', 'permission_employee', 'permission_invoice')->orderBy('created_at', 'desc')->get();
        return response()->json($user);
    }
    public function auth()
    {
        $id = Auth::id();
        $user = User::where('id', $id)->with('employee.classroom',  'user_role', 'user_role.role', 'permission_student', 'permission_user',  'permission_employee', 'permission_invoice')->first();
        return response()->json($user);
    }
    public function show($id)
    {
        $this->authorize('isUser_Read');
        $user = User::where('id', $id)->with('employee.permission_employee', 'employee.permission_user', 'user_role.role')->get();
        return response()->json($user);
    }
    public function store(Request $request)
    {


        $this->authorize('isUser_Create');
        $user = json_decode($request->user);
        $permissions = json_decode($request->permissions);
        $password = Hash::make($user->password);
        $activation_token = Str::random(60);

        $email = $user->email;
        $checkEmail = User::where('email', $email)->first();
        if (!empty($checkEmail)) {
            return response()->json(['alert' => 'This email is already taken!']);
        } else {

            $file = $request->file;
            if ($file) {
                $image = $file; // base64 encoded
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $imageName = time() . '-file(avatar)' . '.png';
                $path = public_path() . "/images/uploads/" . $imageName;
                $image_decoded = base64_decode($image); // decoding File
                file_put_contents($path, $image_decoded);
                $profile_image = 'http://127.0.0.1:8000/images/uploads/' . $imageName; //Profile Image For Employee
            } else {
                $profile_image = $user->profile_image;
            }
            //register user
            $User = new User;
            $User->profile_image = $profile_image;
            $User->email = $user->email;
            $User->name = $user->name;
            $User->password = $password;
            $User->type = $user->type;
            $User->activation_token = $activation_token;
            $User->save();




            //set permissions
            foreach ($permissions as $data) {
                if ($data->name == 'User') {
                    //user_permission
                    $Permission_User = new Permission_User;
                    $Permission_User->name = $data->name;
                    $Permission_User->create = $data->create;
                    $Permission_User->update = $data->update;
                    $Permission_User->read = $data->read;
                    $Permission_User->delete = $data->delete;
                    $Permission_User->user_id = $User->id;
                    $Permission_User->save();
                }
                if ($data->name == 'Employee') {
                    //Employee_permission
                    $Permission_Employee = new Permission_Employee;
                    $Permission_Employee->name = $data->name;
                    $Permission_Employee->create = $data->create;
                    $Permission_Employee->update = $data->update;
                    $Permission_Employee->read = $data->read;
                    $Permission_Employee->delete = $data->delete;
                    $Permission_Employee->user_id = $User->id;
                    $Permission_Employee->save();
                }
                if ($data->name == 'Student') {
                    //Student_permission
                    $Permission_Student = new Permission_Student;
                    $Permission_Student->name = $data->name;
                    $Permission_Student->create = $data->create;
                    $Permission_Student->update = $data->update;
                    $Permission_Student->read = $data->read;
                    $Permission_Student->delete = $data->delete;
                    $Permission_Student->user_id = $User->id;
                    $Permission_Student->save();
                }
                if ($data->name == 'Invoice') {
                    //invoice_permission
                    $PermissionInvoice = new PermissionInvoice;
                    $PermissionInvoice->name = $data->name;
                    $PermissionInvoice->create = $data->create;
                    $PermissionInvoice->update = $data->update;
                    $PermissionInvoice->read = $data->read;
                    $PermissionInvoice->delete = $data->delete;
                    $PermissionInvoice->user_id = $User->id;
                    $PermissionInvoice->save();
                }
            }

            if ($user->type === 'Employee') {
                $Employee = Employee::where('id', $user->type_id)->first();
                $Employee->user_id = $User->id;
                $Employee->save();

                $Role = Role::where('name', $user->position)->first();
                $User_Role = new User_Role;
                $User_Role->user_id = $User->id;
                $User_Role->role_id = $Role->id;
                $User_Role->save();
            }
            if ($user->type === 'Student') {
                $Student = Student::where('id', $user->type_id)->first();
                $Student->user_id = $User->id;
                $Student->save();

                $Role = Role::where('name', $user->type)->first();
                $User_Role = new User_Role;
                $User_Role->user_id = $User->id;
                $User_Role->role_id = $Role->id;
                $User_Role->save();
            }
            if ($user->type === 'Parent') {
                if ($user->isfather) {
                    $Father = Father::where('id', $user->type_id)->first();
                    $Father->user_id = $User->id;
                    $Father->save();

                    $Role = Role::where('name', $user->type)->first();
                    $User_Role = new User_Role;
                    $User_Role->user_id = $User->id;
                    $User_Role->role_id = $Role->id;
                    $User_Role->save();
                }
                if ($user->ismother) {
                    $Mother = Mother::where('id', $user->type_id)->first();
                    $Mother->user_id = $User->id;
                    $Mother->save();

                    $Role = Role::where('name', $user->type)->first();
                    $User_Role = new User_Role;
                    $User_Role->user_id = $User->id;
                    $User_Role->role_id = $Role->id;
                    $User_Role->save();
                }
            }

            $User->notify(new SignupActivate($User));
            return response()->json($User);
        }
    }

    public function profile(Request $request)
    {
        $this->authorize('isUser_Update');
        $file = $request->file;
        $id = $request->id;
        if ($file) {
            $image = $file; // base64 encoded
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = time() . '-file(avatar)' . '.png';
            $path = public_path() . "/images/uploads/" . $imageName;
            $image_decoded = base64_decode($image); // decoding File
            file_put_contents($path, $image_decoded);
            $profile_image = 'http://127.0.0.1:8000/images/uploads/' . $imageName; //Profile Image For Employee
            $User = User::find($id);
            $User->profile_image = $profile_image;
            $User->save();

            return response()->json($profile_image, 200);
        } else {
            return response()->json(['error' => "error"], 422);
        }
    }
    public function general(Request $request)
    {

        $this->authorize('isUser_Update');

        $user_id = $request->user_id;
        $user_data = $request->data;
        $name = $user_data['name'];
        $email = $user_data['email'];
        $permissions = $request->permissions;

        $User = User::find($user_id);
        $User->name = $name;
        $User->email = $email;
        if ($User->email !== $email) {
            $User->active = false;
            $User->email_verified_at = '';
        }
        $User->save();

        foreach ($permissions as $data) {
            if ($data['name'] == 'User') {
                Permission_User::where("user_id", $user_id)->delete();
                //user_permission
                $Permission_User = new Permission_User;
                $Permission_User->name = $data['name'];
                $Permission_User->create = $data['create'];
                $Permission_User->update = $data['update'];
                $Permission_User->read = $data['read'];
                $Permission_User->delete = $data['delete'];
                $Permission_User->user_id = $user_id;
                $Permission_User->save();
            }
            if ($data['name'] == 'Employee') {
                Permission_Employee::where("user_id", $user_id)->delete();
                //Employee_permission
                $Permission_Employee = new Permission_Employee;
                $Permission_Employee->name = $data['name'];
                $Permission_Employee->create = $data['create'];
                $Permission_Employee->update = $data['update'];
                $Permission_Employee->read = $data['read'];
                $Permission_Employee->delete = $data['delete'];
                $Permission_Employee->user_id = $user_id;
                $Permission_Employee->save();
            }
            if ($data['name'] == 'Student') {
                Permission_Student::where("user_id", $user_id)->delete();
                //Student_permission
                $Permission_Student = new Permission_Student;
                $Permission_Student->name = $data['name'];
                $Permission_Student->create = $data['create'];
                $Permission_Student->update = $data['update'];
                $Permission_Student->read = $data['read'];
                $Permission_Student->delete = $data['delete'];
                $Permission_Student->user_id = $user_id;
                $Permission_Student->save();
            }
            if ($data['name'] == 'Student') {
                Permission_Student::where("user_id", $user_id)->delete();
                //Student_permission
                $Permission_Student = new Permission_Student;
                $Permission_Student->name = $data['name'];
                $Permission_Student->create = $data['create'];
                $Permission_Student->update = $data['update'];
                $Permission_Student->read = $data['read'];
                $Permission_Student->delete = $data['delete'];
                $Permission_Student->user_id = $user_id;
                $Permission_Student->save();
            }
            if ($data['name'] == 'Invoice') {
                PermissionInvoice::where("user_id", $user_id)->delete();
                //Student_permission
                $PermissionInvoice = new PermissionInvoice;
                $PermissionInvoice->name = $data['name'];
                $PermissionInvoice->create = $data['create'];
                $PermissionInvoice->update = $data['update'];
                $PermissionInvoice->read = $data['read'];
                $PermissionInvoice->delete = $data['delete'];
                $PermissionInvoice->user_id = $user_id;
                $PermissionInvoice->save();
            }
        }

        if ($User->email !== $email) {
            $User->notify(new SignupActivate($User));
        }

        return response()->json($User);
    }

    public function detail(Request $request)
    {
        $id = $request->id;
        $type = $request->type;
        if ($type == 'student') {
            $detail = Student::find($id);
        }
        if ($type == 'employee') {
            $detail = Employee::find($id);
        }
        if ($type == 'father') {
            $detail = Father::find($id);
        }
        return response()->json($detail);
    }
    public function destroy($id)
    {
        $this->authorize('isUser_Delete');
        $User = User::find($id);
        $User->delete();
        return response()->json(['success' => 'User has been deleted Successfully']);
    }
    public function AuthGeneral(Request $request)
    {

        $user_id = $request->user_id;
        $user_data = $request->data;
        $name = $user_data['name'];
        $email = $user_data['email'];

        $User = User::find($user_id);
        $User->name = $name;
        $User->email = $email;
        if ($User->email !== $email) {
            $User->active = false;
            $User->email_verified_at = '';
        }
        $User->save();
        return response()->json(['success']);
    }

    public function Password(Request $request)
    {

        $user_id = $request->user_id;
        $user_data = $request->data;
        $old_password =  Hash::make($user_data['old_password']);
        $new_password = Hash::make($user_data['new_password']);

        $User = User::find($user_id);
        if (Hash::check($old_password, $User->password)) {
            return response()->json(['error' => 'The old password you have entered is incorrect'], 401);
        } else {
            $User->password = $new_password;
            $User->save();
        }

        return response()->json(['success']);
    }
}
