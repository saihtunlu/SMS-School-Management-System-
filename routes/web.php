<?php

use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/{any?}', function () {
    return view('home');
});

Route::get('/employee/{id}', function () {
    return view('home');
});
Route::get('/user/{id}', function () {
    return view('home');
});
Route::get('/student/{id}', function () {
    return view('home');
});

//Reset Password
Route::post('/password/create', 'Api\PasswordResetController@create');
Route::post('/password/find', 'Api\PasswordResetController@find');
Route::post('/password/reset', 'Api\PasswordResetController@reset');
Route::get('/reset_password/{token}', function () {
    return view('home');
});


//Email verification
Route::post('/activation', 'Api\VerificationController@signupActivate');
Route::post('/re-activation', 'Api\VerificationController@activate');
Route::get('/activation/{token}', function () {
    return view('home');
});

//Get Theme
Route::get('web/getGeneral', 'Api\GeneralController@index');
Route::get('web/getTheme', 'Api\ThemeController@index');



Route::group(['middleware' => 'auth'], function () {

    //auth
    Route::get('/web/auth', 'Api\UserController@auth')->name('auth');

    //User
    Route::get('/web/allUsers', 'Api\UserController@index');
    Route::get('/web/employeeUser', 'Api\UserController@employee')->name('users');
    Route::get('/web/getUserEmployees', 'Api\UserController@getemployee'); // no relationship
    Route::get('/web/studentUser', 'Api\UserController@student')->name('users');
    Route::get('/web/parentUser', 'Api\UserController@parent')->name('users');
    Route::get('/web/user/{id}', 'Api\UserController@show')->name('show_User');
    Route::post('/web/createUser', 'Api\UserController@store')->name('create_user');
    Route::post('/web/updateUser', 'Api\UserController@update')->name('update_user');
    Route::delete('/web/deleteUser/{id}', 'Api\UserController@destroy')->name('delete_user');
    Route::post('/web/userDetail', 'Api\UserController@detail'); //for creating user
    Route::post('/web/updateUserProfile', 'Api\UserController@profile');
    Route::post('/web/updateUserGeneral', 'Api\UserController@general');
    Route::post('/web/updateAuthGeneral', 'Api\UserController@AuthGeneral');
    Route::post('/web/updateAuthPassword', 'Api\UserController@Password');

    //Delete
    Route::post('/web/delete', 'Api\DeleteController@index');
    Route::get('/web/restore', 'Api\DeleteController@restore');

    //employee
    Route::get('/web/getEmployees', 'Api\EmployeeController@getEmployees')->name('getEmployees'); // no relationships
    Route::get('/web/showEmployee', 'Api\EmployeeController@index')->name('employees');
    Route::post('/web/createEmployee', 'Api\EmployeeController@store')->name('Create_Employee');
    Route::post('/web/editEmployee', 'Api\EmployeeController@edit')->name('Edit_Employee');
    Route::delete('/web/deleteEmployee/{id}', 'Api\EmployeeController@destroy')->name('Delete_Employee');
    Route::get('/web/Employee_detail/{id}', 'Api\EmployeeController@show')->name('show_Employee');
    Route::post('/web/updateEmployeeGeneral', 'Api\EmployeeController@general');
    Route::post('/web/updateEmployeePersonal', 'Api\EmployeeController@personal');
    Route::post('/web/updateEmployeeProfile', 'Api\EmployeeController@profile');
    Route::post('/web/updateEmployeeAddress', 'Api\EmployeeController@address');
    Route::post('/web/updateEmployeeFather', 'Api\EmployeeController@father');
    Route::post('/web/updateEmployeeSpouse', 'Api\EmployeeController@spouse');
    Route::post('/web/updateEmployeeEmergency', 'Api\EmployeeController@emergency');
    Route::post('/web/updateEmployeeBank', 'Api\EmployeeController@bank');
    Route::post('/web/updateEmployeeWork', 'Api\EmployeeController@work');
    Route::get('/web/getTeachers', 'Api\EmployeeController@getTeachers');


    //Invoice
    Route::get('/web/invoices', 'Api\InvoiceController@index');
    Route::post('web/createInvoice', 'Api\InvoiceController@store');
    Route::post('web/SetInvoice', 'Api\InvoiceController@defaultDetail');
    Route::post('web/sendInvoice', 'Api\InvoiceController@SendInvoice');
    Route::get('web/defaultInvoice', 'Api\InvoiceController@Getdefault');
    Route::post('web/SetInvoiceItems', 'Api\InvoiceController@SetInvoiceItems');
    Route::post('web/DownloadInvoice/{id}', 'Api\PdfController@Invoice');
    Route::get('web/GetInvoiceItems', 'Api\InvoiceController@GetInvoiceItems');
    Route::post('web/updateInvoiceStatus/', 'Api\InvoiceController@updateStatus');



    //Mail
    Route::post('web/sendEmail', 'Api\MailController@send');
    Route::get('web/getParentsMail', 'Api\MailController@parents');
    Route::get('web/getStudentsMail', 'Api\MailController@students');
    Route::get('web/getMailCategories', 'Api\MailController@categories');
    Route::delete('/web/MailCategory/{id}', 'Api\MailController@removeCat');
    Route::get('/web/mails', 'Api\MailController@index');
    Route::post('web/starredMail', 'Api\MailController@starred');
    Route::get('/web/mail/{id}', 'Api\MailController@restore');
    Route::delete('/web/mail/{id}', 'Api\MailController@destroy');
    Route::delete('/web/removeMailCat/{id}', 'Api\MailController@removeMailCat');
    Route::post('web/addMailCat', 'Api\MailController@addCategory');


    //Subject
    Route::post('web/createSubject', 'Api\SubjectController@store');
    Route::get('web/subjects', 'Api\SubjectController@index');
    Route::post('web/updateSubject', 'Api\SubjectController@update');
    Route::delete('/web/removeSubject/{id}', 'Api\SubjectController@destroy');

    //Grade
    Route::post('web/addGrade', 'Api\GradeController@store');
    Route::post('web/updateGrade', 'Api\GradeController@update');
    Route::post('web/updateGradeSetting', 'Api\GradeController@updateSetting');
    Route::get('web/grade/{id}', 'Api\GradeController@index');
    Route::get('web/grades', 'Api\GradeController@all');
    Route::delete('/web/removeGrade/{id}', 'Api\GradeController@destroy');

    //Theme
    Route::post('web/updateTheme', 'Api\ThemeController@update');

    //General
    Route::post('web/setLogo', 'Api\GeneralController@logo');
    Route::post('web/setDetail', 'Api\GeneralController@detail');
    Route::post('web/setColor', 'Api\GeneralController@color');

    //Timetable
    // 2 is for timetable details and another is for in timetable in setting
    Route::get('web/timetables', 'Api\TimetableController@index');
    Route::get('web/getTimetables', 'Api\TimetableController@index2');
    Route::post('web/createTimetable', 'Api\TimetableController@store');
    Route::post('web/updateTimetable', 'Api\TimetableController@update');
    Route::delete('/web/removeTimetable/{id}', 'Api\TimetableController@destroy');
    Route::delete('/web/removeTimetable2/{id}', 'Api\TimetableController@destroy2');
    Route::post('web/addTimetable', 'Api\TimetableController@store2');
    Route::post('web/updateTimetable2', 'Api\TimetableController@update2');

    //Todo
    Route::post('web/addTodo', 'Api\TodoController@store');
    Route::post('web/updateTodo', 'Api\TodoController@update');
    Route::get('/web/todo', 'Api\TodoController@index')->name('todo');
    Route::get('/web/getCategories', 'Api\TodoController@categories');
    Route::get('/web/todo/{id}', 'Api\TodoController@restore');
    Route::get('/web/todoNoti', 'Api\TodoController@noti');
    Route::delete('/web/todo/{id}', 'Api\TodoController@destroy');
    Route::delete('/web/TodoCategory/{id}', 'Api\TodoController@removeCat');

    //Calendar Event
    Route::post('web/addEvent', 'Api\CalendarController@store');
    Route::post('web/updateEvent', 'Api\CalendarController@update');
    Route::get('/web/events', 'Api\CalendarController@index');
    Route::get('/web/getCalendarCategories', 'Api\CalendarController@categories');
    Route::delete('/web/event/{id}', 'Api\CalendarController@destroy');
    Route::delete('/web/removeCalendarCat/{id}', 'Api\CalendarController@removeCat');

    //Year
    Route::get('/web/years', 'Api\YearController@index');
    Route::get('/web/currentYear', 'Api\YearController@current');
    Route::post('web/updateYear', 'Api\YearController@update');
    Route::post('web/createYear', 'Api\YearController@store');
    Route::delete('web/removeYear/{id}', 'Api\YearController@destroy');

    //Status
    Route::post('web/online', 'Api\StatusController@online');
    Route::post('web/offline', 'Api\StatusController@offline');

    //Role
    Route::get('/web/roles', 'Api\RoleController@index')->name('roles');
    Route::post('/web/createRole', 'Api\RoleController@store')->name('Create_Role');
    Route::delete('/web/deleteRole/{id}', 'Api\RoleController@destroy');

    //Language
    Route::get('/web/languages', 'Api\LanguageController@index')->name('languages');

    //Relationship
    Route::get('/web/Relationship', 'Api\RelationshipController@index')->name('Relationship');

    //Bank
    Route::get('/web/Bank', 'Api\BankController@index')->name('Bank');

    //Default Permissions   
    Route::get('/web/getPermissions', 'Api\DefaultPermissionController@index');
    Route::post('/web/createPermission', 'Api\DefaultPermissionController@store');

    //Attendance   
    Route::get('/web/getAttendances/{classroom_id}', 'Api\AttendanceController@show');
    Route::get('/web/getAttendancesSetting', 'Api\AttendanceController@index');
    Route::post('/web/updateAttendanceSetting', 'Api\AttendanceController@update2');
    Route::post('/web/addAttendance', 'Api\AttendanceController@store');
    Route::post('/web/filterAttendances', 'Api\AttendanceController@filter');
    Route::post('/web/updateAttendance', 'Api\AttendanceController@update');
    Route::delete('/web/removeAttendance/{id}', 'Api\AttendanceController@destroy');

    //Classroom
    Route::get('/web/classrooms', 'Api\ClassroomController@index')->name('classrooms');
    Route::get('/web/classroomsList', 'Api\ClassroomController@list');
    Route::get('/web/classroomStudent/{id}', 'Api\ClassroomController@students');
    Route::post('/web/createClassroom', 'Api\ClassroomController@store')->name('Create_Classroom');
    Route::post('/web/updateClassroom', 'Api\ClassroomController@update');
    Route::delete('/web/removeClassroom/{id}', 'Api\ClassroomController@destroy')->name('Delete_Classroom');

    //Student
    Route::get('/web/studentsByYear/{id}', 'Api\StudentController@Year'); //year
    Route::get('/web/students/{id}', 'Api\StudentController@index'); //with classroom
    Route::get('/web/student_Detail/{id}', 'Api\StudentController@show'); //personal details
    Route::post('/web/createStudent', 'Api\StudentController@store')->name('Create_Student');
    Route::delete('/web/deleteStudent/{id}', 'Api\StudentController@destroy')->name('Delete_Student');
    Route::post('/web/updateStudentGeneral', 'Api\StudentController@general');
    Route::post('/web/updateStudentFather', 'Api\StudentController@father');
    Route::post('/web/updateStudentMother', 'Api\StudentController@mother');
    Route::post('/web/updateStudentAddress', 'Api\StudentController@address');
    Route::post('/web/updateStudentEmergency', 'Api\StudentController@emergency');
    Route::post('/web/updateStudentPrevious', 'Api\StudentController@previous');
    Route::get('/web/getParent/{id}', 'Api\StudentController@getParent');


    //Chat
    Route::get('web/contacts', 'Api\ContactsController@get');
    Route::get('web/getRecent', 'Api\ContactsController@getRecent');
    Route::get('web/getNoti', 'Api\ContactsController@getNoti');
    Route::post('web/conversation/', 'Api\ContactsController@getMessagesFor');
    Route::post('web/conversation/send', 'Api\ContactsController@send');
    Route::post('web/typing', 'Api\ContactsController@typing');
    Route::post('web/deliveredMessage', 'Api\ContactsController@delivered');
    Route::post('web/removeMessage', 'Api\ContactsController@remove');
    Route::post('web/readAllMessage', 'Api\ContactsController@readAll'); //when not in conversation

    //AssignedTeacher
    Route::get('/web/AssignedTeachers', 'Api\AssignedTeacherController@index');
    Route::post('web/updateAssignedTeacher', 'Api\AssignedTeacherController@update');
    Route::post('web/createAssignedTeacher', 'Api\AssignedTeacherController@store');
    Route::delete('web/removeAssignedTeacher/{id}', 'Api\AssignedTeacherController@destroy');

    //Subjects
    Route::get('web/subjects', 'Api\SubjectController@index');

    //Work Histories
    Route::post('web/createWorkHistory', 'Api\WorkHistoryController@store');

    //Dashboard
    Route::get('web/invoiceStatus/{year}', 'Api\DashboardController@invoice');
    Route::get('web/studentDashboard/{id}', 'Api\DashboardController@students');
    Route::get('web/calendarDashboard/', 'Api\DashboardController@calendar');
    Route::get('web/classroomDashboard/{id}', 'Api\DashboardController@classroom');
});
