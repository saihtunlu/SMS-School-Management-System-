<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\User;


Route::post('/password/create', 'Api\PasswordResetController@create');
Route::get('/password/find/{token}', 'Api\PasswordResetController@find');
Route::post('/password/reset', 'Api\PasswordResetController@reset');

Route::get('signup/activate/{token}', 'Api\VerificationController@signupActivate');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/auth', 'Api\UserController@auth')->name('auth');

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logouts', function (Request $request) {
        $request->user()->tokens()->delete();
        return response('Loggedout', 200);
    });

    //auth


    //User
    Route::get('/users', 'Api\UserController@index')->name('users');
    Route::get('/user/{id}', 'Api\UserController@show')->name('show_User');
    Route::post('/createUser', 'Api\UserController@store')->name('create_user');
    Route::post('/updateUser', 'Api\UserController@update')->name('update_user');
    Route::delete('/deleteUser/{id}', 'Api\UserController@destroy')->name('delete_user');

    //employee
    Route::get('/showEmployee', 'Api\EmployeeController@index')->name('employees');
    Route::post('/createEmployee', 'Api\EmployeeController@store')->name('Create_Employee');
    Route::post('/editEmployee', 'Api\EmployeeController@edit')->name('Edit_Employee');
    Route::delete('/deleteEmployee/{id}', 'Api\EmployeeController@destroy')->name('Delete_Employee');
    Route::get('/Employee_detail/{id}', 'Api\EmployeeController@show')->name('show_Employee');

    //Role
    Route::get('/roles', 'Api\RoleController@index')->name('roles');
    Route::post('/createRole', 'Api\RoleController@store')->name('Create_Role');

    //Classroom
    Route::get('/classrooms', 'Api\ClassroomController@index')->name('classrooms');
    Route::post('/createClassroom', 'Api\ClassroomController@store')->name('Create_Classroom');
    Route::delete('/deleteClassroom/{id}', 'Api\ClassroomController@destroy')->name('Delete_Classroom');

    //Student
    Route::get('/students', 'Api\StudentController@index')->name('students');
    Route::post('/createStudent', 'Api\StudentController@store')->name('Create_Student');
    Route::delete('/deleteStudent/{id}', 'Api\StudentController@destroy')->name('Delete_Student');
});






// Get_Token For Mobile App
Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required'
    ]);
    $user = User::where('email', $request->email)->first();
    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }
    $token = $user->createToken($request->device_name)->plainTextToken;
    $response = [
        'user' => $user,
        'token' => $token,
    ];
    return response($response, 201);
});
