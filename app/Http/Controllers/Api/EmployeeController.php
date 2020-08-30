<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Employee;
use App\Role;
use App\Address;
use App\Work_History;
use App\Language;
use App\User_Language;
use App\Classroom;
use App\Relationship;
use App\Bank;
use App\User;
use App\User_Role;
use App\DefaultPermission;
use App\Permission_Employee;
use App\Permission_Student;
use App\Permission_User;
use App\PermissionInvoice;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('isEmployee_Read');

        $Employee = Employee::get();
        return response()->json($Employee);
    }

    public function getTeachers()
    {

        $Teacher = Employee::where('position', 'Teacher')->get();
        return response()->json($Teacher);
    }
    public function getEmployees()
    {
        $this->authorize('isEmployee_Read');

        $Employees = Employee::get();
        return response()->json($Employees);
    }
    public function show($id)
    {
        $this->authorize('isEmployee_Read');
        $Employee = Employee::with('user',  'address',  'work_history', 'languages', 'languages.language', 'classroom')->where('id', $id)->first();
        return response()->json($Employee);
    }

    //Start of edit employee
    public function work(Request $request)
    {
        $this->authorize('isEmployee_Update');
        $work_history = $request->data;
        $id = $request->id;
        Work_History::where("employee_id", $id)->delete();
        //Create Work Histories
        foreach ($work_history as $data) {
            $Work_History = new Work_History();
            $Work_History->employer = $data['employer'];
            $Work_History->started_date = $data['started_date'];
            $Work_History->ended_date = $data['ended_date'];
            $Work_History->year_employed = $data['year_employed'];
            $Work_History->employee_id = $id;
            $Work_History->save();
        }
        return response()->json(['message' => 'success'], 200);
    }
    public function bank(Request $request)
    {
        $this->authorize('isEmployee_Update');
        $data = $request->data;
        $id = $request->id;
        $employee = Employee::find($id);
        $employee->bank_name = $data['bank_name'];
        $employee->bank_no = $data['bank_no'];
        $employee->save();

        $Get_Bank = Bank::where("name", $data['bank_name'])->first();
        if (!$Get_Bank) {
            $Bank = new Bank();
            $Bank->name = $data['bank_name'];
            $Bank->save();
        }
        return response()->json(['message' => 'success'], 200);
    }
    public function emergency(Request $request)
    {
        $this->authorize('isEmployee_Update');
        $data = $request->data;
        $id = $request->id;
        $employee = Employee::find($id);
        $employee->emergency_contact_person = $data['emergency_contact_person'];
        $employee->emergency_contact_relationship = $data['emergency_contact_relationship'];
        $employee->emergency_contact_person_phone = $data['emergency_contact_person_phone'];
        $employee->save();

        $Get_RS = Relationship::where("name", $data['emergency_contact_relationship'])->first();
        if (!$Get_RS) {
            $Relationship = new Relationship();
            $Relationship->name = $data['emergency_contact_relationship'];
            $Relationship->save();
        }
        return response()->json(['message' => 'success'], 200);
    }
    // public function father(Request $request)
    // {
    //     $this->authorize('isEmployee_Update');
    //     $data = $request->data;
    //     $id = $request->id;
    //     $Father = Employee_Father::find($id);
    //     $Father->full_name = $data['full_name'];
    //     $Father->NRC = $data['NRC'];
    //     $Father->email = $data['email'];
    //     $Father->phone = $data['phone'];
    //     $Father->occupation = $data['occupation'];
    //     $Father->company = $data['company'];
    //     $Father->save();
    //     return response()->json(['message' => 'success'], 200);
    // }
    // public function spouse(Request $request)
    // {
    //     $this->authorize('isEmployee_Update');
    //     $data = $request->data;
    //     $id = $request->id;
    //     $Spouse = Spouse::find($id);
    //     $Spouse->full_name = $data['full_name'];
    //     $Spouse->NRC = $data['NRC'];
    //     $Spouse->email = $data['email'];
    //     $Spouse->phone = $data['phone'];
    //     $Spouse->occupation = $data['occupation'];
    //     $Spouse->company = $data['company'];
    //     $Spouse->save();
    //     return response()->json(['message' => 'success'], 200);
    // }
    public function address(Request $request)
    {
        $this->authorize('isEmployee_Update');
        $data = $request->data;
        $id = $request->address_id;
        $address = Address::find($id);
        $address->street_line_1 = $data['street_line_1'];
        $address->street_line_2 = $data['street_line_2'];
        $address->postal_code = $data['postal_code'];
        $address->township = $data['township'];
        $address->country = $data['country'];
        $address->city = $data['city'];
        $address->save();
        return response()->json(['message' => 'success'], 200);
    }
    public function personal(Request $request)
    {
        $this->authorize('isEmployee_Update');
        $data = $request->data;
        $id = $request->id;
        $languages = $data['employee_language'];

        $Employee = Employee::find($id);
        $Employee->date_of_birth = $data['date_of_birth'];
        $Employee->NRC = $data['NRC'];
        $Employee->gender = $data['gender'];
        $Employee->save();

        $delete_language = User_Language::where("employee_id", $id)->delete();


        //Check || Cerate Language
        foreach ($languages as $data) {
            $Get_Language = Language::where("name", $data)->first();
            if ($Get_Language) {
                $User_Language = new User_Language();
                $User_Language->language_id = $Get_Language->id;
                $User_Language->employee_id = $Employee->id;
                $User_Language->save();
            } else {
                $Language = new Language();
                $Language->name = $data;
                $Language->save();

                $User_Language = new User_Language();
                $User_Language->language_id = $Language->id;
                $User_Language->employee_id = $Employee->id;
                $User_Language->save();
            }
        }
        return response()->json(["message" => "success"]);
    }
    public function general(Request $request)
    {

        $this->authorize('isEmployee_Update');
        $data = $request->data;
        $id = $request->id;
        $Employee = Employee::find($id);
        $Employee->first_name = $data['first_name'];
        $Employee->last_name = $data['last_name'];
        $Employee->nick_name = $data['nick_name'];
        $Employee->position = $data['position'];
        $Employee->phone = $data['phone'];
        $Employee->email = $data['email'];
        $Employee->save();
        $user_id = $Employee->user_id;
        $check_role = Role::where('name', $data['position'])->first();
        $role_id = $check_role->id;

        if ($user_id) {
            $check_user_role = User_Role::where('user_id', $user_id)->first();
            $check_user_role->role_id = $role_id;
            $check_user_role->save();

            $get_permissions = DefaultPermission::where('role_id', $role_id)->get();

            foreach ($get_permissions as $data) {
                if ($data->name == 'User') {
                    Permission_User::where("user_id", $id)->delete();
                    //user_permission
                    $Permission_User = new Permission_User;
                    $Permission_User->name = $data->name;
                    $Permission_User->create = $data->create;
                    $Permission_User->update = $data->update;
                    $Permission_User->read = $data->read;
                    $Permission_User->delete = $data->delete;
                    $Permission_User->user_id = $user_id;
                    $Permission_User->save();
                }
                if ($data->name == 'Employee') {
                    Permission_Employee::where("user_id", $id)->delete();
                    //Employee_permission
                    $Permission_Employee = new Permission_Employee;
                    $Permission_Employee->name = $data->name;
                    $Permission_Employee->create = $data->create;
                    $Permission_Employee->update = $data->update;
                    $Permission_Employee->read = $data->read;
                    $Permission_Employee->delete = $data->delete;
                    $Permission_Employee->user_id = $user_id;
                    $Permission_Employee->save();
                }
                if ($data->name == 'Student') {
                    Permission_Student::where("user_id", $id)->delete();
                    //Student_permission
                    $Permission_Student = new Permission_Student;
                    $Permission_Student->name = $data->name;
                    $Permission_Student->create = $data->create;
                    $Permission_Student->update = $data->update;
                    $Permission_Student->read = $data->read;
                    $Permission_Student->delete = $data->delete;
                    $Permission_Student->user_id = $user_id;
                    $Permission_Student->save();
                }
                if ($data->name == 'Invoice') {
                    PermissionInvoice::where("user_id", $id)->delete();
                    //Student_permission
                    $PermissionInvoice = new PermissionInvoice;
                    $PermissionInvoice->name = $data->name;
                    $PermissionInvoice->create = $data->create;
                    $PermissionInvoice->update = $data->update;
                    $PermissionInvoice->read = $data->read;
                    $PermissionInvoice->delete = $data->delete;
                    $PermissionInvoice->user_id = $user_id;
                    $PermissionInvoice->save();
                }
            }
        }
        return response()->json(["Message" => $Employee]);
    }
    public function profile(Request $request)
    {
        $this->authorize('isEmployee_Update');
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
            $Employee = Employee::find($id);
            $Employee->profile_image = $profile_image;
            $Employee->save();

            return response()->json(['message' => "success"], 200);
        } else {
            return response()->json(['error' => "error"], 422);
        }
    }
    //end of edit employee

    //Start of create employees
    public function store(Request $request)
    {
        //Set Variables
        $this->authorize('isEmployee_Create');
        $employee = json_decode($request->employee);
        $address = $employee->address;
        $father = $employee->father;
        $spouse = $employee->spouse;
        $file = $request->profile_image;
        $languages = explode(',', $request->language);

        $classroom = $request->classroom;
        $work_history = json_decode($request->work_histories);

        //Save File
        if ($file) {
            $image = $file; // base64 encoded
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = time() . '-file(avatar)' . '.png';
            $path = public_path() . "/images/uploads/" . $imageName;
            $image_decoded = base64_decode($image); // decoding File
            file_put_contents($path, $image_decoded);
            $profile_image = 'http://127.0.0.1:8000/images/uploads/' . $imageName; //Profile Image For Employee
        }



        //Create Address
        $Address = new Address();
        $Address->street_line_1 = $address->street_line_1;
        $Address->street_line_2 =  $address->street_line_2;
        $Address->postal_code =  $address->postal_code;
        $Address->township =  $address->township;
        $Address->city =  $address->city;
        $Address->country =  $address->country;
        $Address->save();

        $address_id = $Address->id; //Get Address_id For Employee

        //Check Position
        $Get_Position = Role::where('name', $employee->position)->first();
        if ($Get_Position) {
            $position = $Get_Position->name; //Get position For Employee (Check 1)
        } else {
            $Role = new Role();
            $Role->name = $employee->position;
            $Role->save();
            $position = $Role->name; //Get position For Employee (Check 2)
        }
        //Check Classroom
        if ($classroom) {
            $Get_Classroom = Classroom::where('grade', $classroom)->first();
            if ($Get_Classroom) {
                $classroom_id = $Get_Classroom->id; //Get position For Employee (Check 1)
            } else {
                $Classroom = new Classroom();
                $Classroom->grade =  $classroom;
                $Classroom->save();
                $classroom_id = $Classroom->id; //Get position For Employee (Check 2)
            }
        }

        //Create Employee
        $Employee = new Employee();
        $Employee->profile_image = $profile_image;
        $Employee->first_name = $employee->first_name;
        $Employee->last_name = $employee->last_name;
        $Employee->nick_name = $employee->nick_name;
        $Employee->email = $employee->email;
        $Employee->position = $position;
        $Employee->bank_name = $employee->bank_name;
        $Employee->bank_no = $employee->bank_no;
        $Employee->date_of_birth = $employee->date_of_birth;
        $Employee->NRC = $employee->NRC;
        $Employee->phone = $employee->phone;
        $Employee->gender = $employee->gender;
        $Employee->emergency_contact_person = $employee->emergency_contact_person;
        $Employee->emergency_contact_relationship = $employee->emergency_contact_relationship;
        $Employee->emergency_contact_person_phone = $employee->emergency_contact_person_phone;
        $Employee->address_id = $address_id;
        $Employee->save();

        $Get_Bank = Bank::where("name", $employee->bank_name)->first();
        if (!$Get_Bank) {
            $Bank = new Bank();
            $Bank->name = $employee->bank_name;
            $Bank->save();
        }

        //Check || Cerate Language
        foreach ($languages as $data) {
            $Get_Language = Language::where("name", $data)->first();
            if ($Get_Language) {
                $User_Language = new User_Language();
                $User_Language->language_id = $Get_Language->id;
                $User_Language->employee_id = $Employee->id;
                $User_Language->save();
            } else {
                $Language = new Language();
                $Language->name = $data;
                $Language->save();

                $User_Language = new User_Language();
                $User_Language->language_id = $Language->id;
                $User_Language->employee_id = $Employee->id;
                $User_Language->save();
            }
        }

        //Create Work Histories
        foreach ($work_history as $data) {
            $Work_History = new Work_History();
            $Work_History->employer = $data->employer;
            $Work_History->started_date = $data->started_date;
            $Work_History->ended_date = $data->ended_date;
            $Work_History->year_employed = $data->year_employed;
            $Work_History->employee_id = $Employee->id;
            $Work_History->save();
        }
        return response()->json(['success' => "Successfully Added!"]);
    }
    public function destroy($id)
    {
        $this->authorize('isEmployee_Delete');
        $Employee = Employee::find($id);
        $Employee->delete();
        return response()->json(['success' => 'Successfully']);
    }
}
