import Vue from "vue";
import VueRouter from "vue-router";
import About from "../views/About.vue";
import Dashboard from "../views/dashboard/index.vue";

//User
import Users from "../views/user/index.vue";
import Add_User from "../views/user/create.vue";
import Edit_User from "../views/user/edit.vue";
import User from "../views/user/view.vue";


//employee
import Employees from "../views/employee/index.vue";
import Add_Employee from "../views/employee/create.vue";
import Employee from "../views/employee/view.vue";

//student (Group)
import Add_Student from "../views/student/create.vue";
import Students from "../views/student/index.vue";
import Student from "../views/student/view.vue";




//Chat
import Chat from "../views/chat/index.vue";

//Mail
import Mail from "../views/mail/index.vue";

//Todo
import Todo from "../views/todo/index.vue";

//Calendar
import Calendar from "../views/calendar/index.vue";

//settings
import Settings from "../views/settings/index.vue";

//Invoices
import Invoices from "../views/invoice/index.vue";
import Invoice from "../views/invoice/view.vue";
import Create_Invoice from "../views/invoice/create.vue";

//Timetable
import Timetable from "../views/timetable/index.vue";

//Auth
import SignIn from "../views/auth/signIn.vue";
import Reset from "../views/auth/reset.vue";
import Activate from "../views/auth/activation.vue";





Vue.use(VueRouter);

const routes = [{
    path: "/signIn", //Start Auth
    name: "SignIn",
    component: SignIn,
    meta: { requiresVisitor: true }
},
{
    path: "/reset_password/:token",
    name: "Reset",
    component: Reset,
    meta: { requiresVisitor: true }
},
{
    path: "/activation/:token",
    name: "Activate",
    component: Activate,
    meta: { requiresVisitor: true }
},
{
    path: "/mail",
    name: "Mail",
    component: Mail,
    meta: { requiresAuth: true, index: 9 }
},
{
    path: "/todo",
    name: "Todo",
    component: Todo,
    meta: { requiresAuth: true, index: 10 }
},
{
    path: "/calendar",
    name: "Calendar",
    component: Calendar,
    meta: { requiresAuth: true, index: 12 }
},
{
    path: "/",
    name: "Dashboard",
    component: Dashboard,
    meta: { requiresAuth: true, index: 1 }
},
{
    path: "/users", //Starting of User
    name: "Users",
    component: Users,
    meta: { requiresAuth: true, index: 2 }
},
{
    path: "/add_user",
    name: "Add_User",
    component: Add_User,
    meta: { requiresAuth: true, index: 2 }
},
{
    path: "/user/:id",
    name: "User",
    component: User,
    meta: { requiresAuth: true, index: 2 }
},
{
    path: "/employees", //Starting of Employee
    name: "Employees",
    component: Employees,
    meta: { requiresAuth: true, index: 3.1 }
},
{
    path: "/employee/:id",
    name: "Employee",
    component: Employee,
    meta: { requiresAuth: true, index: 3.1 }
},
{
    path: "/add_employee",
    name: "Add_Employee",
    component: Add_Employee,
    meta: { requiresAuth: true, index: 3.2 }
},

{
    path: "/students", //Starting of Student
    name: "Students",
    component: Students,
    meta: { requiresAuth: true, index: 4.1 }
},
{
    path: "/student/:id",
    name: "Student",
    component: Student,
    meta: { requiresAuth: true, index: 4.1 }
},
{
    path: "/add_student",
    name: "Add_Student",
    component: Add_Student,
    meta: { requiresAuth: true, index: 4.2 }
},
{
    path: "/chat",
    name: "Chat",
    component: Chat,
    meta: { requiresAuth: true, index: 7 }
},

{
    path: "/invoices", //Starting of invoices
    name: "Invoices",
    component: Invoices,
    meta: { requiresAuth: true, index: 5.1 }
},
{
    path: "/invoice",
    name: "Invoice",
    component: Invoice,
    meta: { requiresAuth: true, index: 5.1 }
}, {
    path: "/create_invoice", //Starting of invoices
    name: "Create_Invoice",
    component: Create_Invoice,
    meta: { requiresAuth: true, index: 5.2 }
},
{
    path: "/settings", //Starting of settings
    name: "Settings",
    component: Settings,
    meta: { requiresAuth: true, index: 11 }
},

{
    path: "/timetable", //Starting of grade
    name: "Timetable",
    component: Timetable,
    meta: { requiresAuth: true, index: 14 }
},
];

const router = new VueRouter({
    mode: "history",
    base: process.env.BASE_URL,
    routes
});

function isLoggedIn() {
    return localStorage.getItem("isLoggedIn");
}

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (!isLoggedIn()) {
            next({
                name: "SignIn"
            });
        } else {
            next();
        }
    } else if (to.matched.some(record => record.meta.requiresVisitor)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (isLoggedIn()) {
            next({
                name: "Dashboard"
            });
        } else {
            next();
        }
    } else {
        next(); // make sure to always call next()!
    }
});

export default router;