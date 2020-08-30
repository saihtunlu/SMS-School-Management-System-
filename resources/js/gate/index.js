export default class gate {
    constructor(user) {
        this.user = user;
    }
    isAdmin() {
        return this.user.user_role.role.name === "Administrator";
    }
    isTeacher() {
        return this.user.user_role.role.name === "Teacher";
    }

    isEmployee() {
        return this.user.type === "Employee";
    }

    isEmployee_Read() {
        return this.user.permission_employee.read === 1;
    }
    isEmployee_Create() {
        return this.user.permission_employee.create === 1;
    }
    isEmployee_Update() {
        return this.user.permission_employee.update === 1;
    }
    isEmployee_Delete() {
        return this.user.permission_employee.delete === 1;
    }

    isUser_Read() {
        return this.user.permission_user.read === 1;
    }
    isUser_Create() {
        return this.user.permission_user.create === 1;
    }
    isUser_Update() {
        return this.user.permission_user.update === 1;
    }
    isUser_Delete() {
        return this.user.permission_user.delete === 1;
    }

    isStudent_Read() {
        return this.user.permission_student.read === 1;
    }
    isStudent_Create() {
        return this.user.permission_student.create === 1;
    }
    isStudent_Update() {
        return this.user.permission_student.update === 1;
    }
    isStudent_Delete() {
        return this.user.permission_student.delete === 1;
    }

    isInvoice_Read() {
        return this.user.permission_invoice.read === 1;
    }
    isInvoice_Create() {
        return this.user.permission_invoice.create === 1;
    }
    isInvoice_Update() {
        return this.user.permission_invoice.update === 1;
    }
    isInvoice_Delete() {
        return this.user.permission_invoice.delete === 1;
    }
}
