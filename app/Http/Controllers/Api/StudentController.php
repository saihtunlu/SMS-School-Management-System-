<?php

namespace App\Http\Controllers\Api;

use App\Classroom;
use App\Father;
use App\Mother;
use App\Address;
use App\Previous_School;
use App\Http\Controllers\Controller;
use App\Student;
use Illuminate\Http\Request;
use App\Events\AddStudent;
use Illuminate\Support\Facades\Auth;
use App\Relationship;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getParent($id)
    {
        $data = [];
        $father = Father::where('user_id', $id)->with('children.classroom')->first();
        $mother = Mother::where('user_id', $id)->with('children.classroom')->first();
        if (empty($father) && !empty($mother)) {
            $data = $mother;
        } else {
            $data = $father;
        }
        return response()->json($data);
    }
    public function Year($id)
    {
        $this->authorize('isStudent_Read');
        $Students = Student::where('academic_year_id', $id)->with('user', 'father', 'mother', 'address', 'previous_schools', 'classroom')->get();

        return response()->json($Students);
    }
    public function index($id)
    {
        $this->authorize('isStudent_Read');
        $Students = Student::where('classroom_id', $id)->with('classroom')->get();

        return response()->json($Students);
    }
    public function general(Request $request)
    {
        $this->authorize('isStudent_Update');
        $file = $request->file;
        $data = json_decode($request->data);
        $id = $request->id;
        $profile_image = null;
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
        $student = Student::find($id);
        $student->first_name = $data->first_name;
        $student->last_name = $data->last_name;
        $student->nick_name = $data->nick_name;
        $student->gender = $data->gender;
        $student->date_of_birth = $data->date_of_birth;
        $student->classroom_id = $data->classroom->id;
        if ($data->NRC) {
            $student->NRC = $data->NRC;
        }
        if ($profile_image) {
            $student->profile_image = $profile_image;
        }
        $student->save();
        return response()->json(['message' => 'Success'], 200);
    }
    public function father(Request $request)
    {
        $this->authorize('isStudent_Update');
        $file = $request->file;
        $data = json_decode($request->data);
        $id = $request->id;
        $profile_image = null;
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
        $father = Father::find($id);
        $father->first_name = $data->first_name;
        $father->last_name = $data->last_name;
        $father->email = $data->email;
        $father->NRC = $data->NRC;
        $father->phone = $data->phone;
        $father->occupation = $data->occupation;
        $father->company = $data->company;
        if ($profile_image) {
            $father->profile_image = $profile_image;
        }
        $father->save();
        return response()->json(['message' => 'Success'], 200);
    }
    public function mother(Request $request)
    {
        $this->authorize('isStudent_Update');
        $file = $request->file;
        $data = json_decode($request->data);
        $id = $request->id;
        $profile_image = null;
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
        $mother = Mother::find($id);
        $mother->first_name = $data->first_name;
        $mother->last_name = $data->last_name;
        $mother->email = $data->email;
        $mother->NRC = $data->NRC;
        $mother->phone = $data->phone;
        $mother->occupation = $data->occupation;
        $mother->company = $data->company;
        if ($profile_image) {
            $mother->profile_image = $profile_image;
        }
        $mother->save();
        return response()->json(['message' => 'Success'], 200);
    }
    public function store(Request $request)
    {
        $this->authorize('isStudent_Create');
        //Set Variables
        $student = json_decode($request->student);
        $address = $student->address;
        $father = $student->father;
        $mother = $student->mother;
        $student_file = $request->student_profile_image;
        $father_file = $request->father_profile_image;
        $mother_file = $request->mother_profile_image;

        $previous_schools = json_decode($request->previous_schools);

        //Save File
        if ($student_file) {
            $image = $student_file; // base64 encoded
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = time() . '-file(avatar)' . '.png';
            $path = public_path() . "/images/uploads/" . $imageName;
            $image_decoded = base64_decode($image); // decoding File
            file_put_contents($path, $image_decoded);
            $student_profile_image = 'http://127.0.0.1:8000/images/uploads/' . $imageName; //Profile Image For Student
        }
        if ($father_file) {
            $image = $father_file; // base64 encoded
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = time() . '-file_father(avatar)' . '.png';
            $path = public_path() . "/images/uploads/" . $imageName;
            $image_decoded = base64_decode($image); // decoding File
            file_put_contents($path, $image_decoded);
            $father_profile_image = 'http://127.0.0.1:8000/images/uploads/' . $imageName; //Profile Image For Father
        }
        if ($mother_file) {
            $image = $mother_file; // base64 encoded
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = time() . '-file_mother(avatar)' . '.png';
            $path = public_path() . "/images/uploads/" . $imageName;
            $image_decoded = base64_decode($image); // decoding File
            file_put_contents($path, $image_decoded);
            $mother_profile_image = 'http://127.0.0.1:8000/images/uploads/' . $imageName; //Profile Image For Mother
        }

        //Check and Create Father
        $Get_Father = Father::where('NRC', $father->NRC)->first();

        if ($Get_Father) {
            $father_id = $Get_Father->id; //Get Father_id For Student (Check 1)
        } else {
            $Father = new Father();
            $Father->first_name = $father->first_name;
            $Father->last_name = $father->last_name;
            $Father->profile_image = $father_profile_image;
            $Father->NRC =  $father->NRC;
            $Father->phone =  $father->phone;
            $Father->email =  $father->email;
            $Father->occupation =  $father->occupation;
            $Father->company =  $father->company;
            $Father->save();
            $father_id = $Father->id; //Get Father_id For Student
        }

        //Check and Create Mother
        $Get_Mother = Mother::where('NRC', $mother->NRC)->first();

        if ($Get_Mother) {
            $mother_id = $Get_Mother->id; //Get mother_id For Student (Check 1)
        } else {
            $Mother = new Mother();
            $Mother->first_name = $mother->first_name;
            $Mother->last_name = $mother->last_name;
            $Mother->profile_image = $mother_profile_image;
            $Mother->NRC =  $mother->NRC;
            $Mother->phone =  $mother->phone;
            $Mother->email =  $mother->email;
            $Mother->occupation =  $mother->occupation;
            $Mother->company =  $mother->company;
            $Mother->save();
            $mother_id = $Mother->id; //Get mother_id For Student
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

        $address_id = $Address->id; //Get Address_id For Student


        //Check Classroom
        $Get_Classroom = Classroom::where('id', $student->grade)->first();
        if ($Get_Classroom) {
            $classroom_id = $Get_Classroom->id; //Get position For Student (Check 1)
        } else {
            $Classroom = new Classroom();
            $Classroom->grade =  $student->grade;
            $Classroom->save();
            $classroom_id = $Classroom->id; //Get position For Student (Check 2)
        }


        //Create Employee
        $Student = new Student();
        $Student->profile_image = $student_profile_image;
        $Student->first_name = $student->first_name;
        $Student->last_name = $student->last_name;
        $Student->nick_name = $student->nick_name;
        $Student->date_of_birth = $student->date_of_birth;
        if (!empty($student->NRC)) {
            $Student->NRC = $student->NRC;
        }
        $Student->gender = $student->gender;
        $Student->address_id = $address_id;
        $Student->academic_year_id = $student->year_id;
        $Student->father_id  = $father_id;
        $Student->mother_id = $mother_id;
        $Student->classroom_id = $classroom_id;
        $Student->emergency_contact_person = $student->emergency_contact_person;
        $Student->emergency_contact_relationship = $student->emergency_contact_relationship;
        $Student->emergency_contact_person_phone = $student->emergency_contact_person_phone;
        $Student->save();

        //Create Previous Schools
        foreach ($previous_schools as $data) {
            $Previous_School = new Previous_School();
            $Previous_School->name = $data->school;
            $Previous_School->started_date = $data->started_date;
            $Previous_School->ended_date = $data->ended_date;
            $Previous_School->student_id = $Student->id;
            $Previous_School->save();
        }
        $auth = Auth::user();
        $data = array();
        $data['Auth'] =  $auth->name;
        $data['Image'] =  $auth->profile_image;
        $data['Student'] = $student->first_name . ' ' . $student->last_name;
        event(new AddStudent($data));

        return response()->json(['success' => "Successfully Added!"]);
    }
    public function previous(Request $request)
    {
        $this->authorize('isStudent_Update');
        $previous_schools = $request->data;
        $id = $request->id;
        Previous_School::where("student_id", $id)->delete();
        //Create Work Histories
        foreach ($previous_schools as $data) {
            $Previous_School = new Previous_School();
            $Previous_School->name = $data['name'];
            $Previous_School->started_date = $data['started_date'];
            $Previous_School->ended_date = $data['ended_date'];
            $Previous_School->student_id = $id;
            $Previous_School->save();
        }
        return response()->json(['message' => 'success'], 200);
    }
    public function emergency(Request $request)
    {
        $this->authorize('isStudent_Update');
        $data = $request->data;
        $id = $request->id;
        $Student = Student::find($id);
        $Student->emergency_contact_person = $data['emergency_contact_person'];
        $Student->emergency_contact_relationship = $data['emergency_contact_relationship'];
        $Student->emergency_contact_person_phone = $data['emergency_contact_person_phone'];
        $Student->save();

        $Get_RS = Relationship::where("name", $data['emergency_contact_relationship'])->first();
        if (!$Get_RS) {
            $Relationship = new Relationship();
            $Relationship->name = $data['emergency_contact_relationship'];
            $Relationship->save();
        }
        return response()->json(['message' => 'success'], 200);
    }
    public function address(Request $request)
    {
        $this->authorize('isStudent_Update');
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
    public function edit(Request $request)
    {
        $this->authorize('isStudent_Update');
        $id = $request->id;
        $name = $request->name;
        $Employee = Student::find($id);
        $Employee->name = $name;
        $Employee->save();
        return response()->json($Employee);
    }
    public function show($id)
    {
        $this->authorize('isStudent_Read');
        $Student = Student::with('user', 'father', 'mother', 'address', 'previous_schools', 'classroom')->where('id', $id)->first();
        return response()->json($Student);
    }
    public function destroy($id)
    {
        $this->authorize('isStudent_Delete');
        $Employee = Student::find($id);
        $Employee->delete();
        return response()->json(['success' => 'Stock has been deleted Successfully']);
    }
}
