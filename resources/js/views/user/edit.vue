<template>
  <div>
    <!-- breadcrumb -->
    <nav aria-label="breadcrumb" class="vs-breadcrumb vs-align-left">
      <ol class="vs-breadcrumb--ol">
        <h3 class="BC_header">Employees</h3>
        <li class="BC_link">
          <router-link :to="{ name: 'Dashboard' }">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24px"
              height="24px"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="feather feather-home h-5 w-5 mb-1 stroke-current text-primary1"
            >
              <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
              <polyline points="9 22 9 12 15 12 15 22" />
            </svg>
          </router-link>
          <span
            translate="translate"
            aria-hidden="true"
            class="separator notranslate vs-breadcrum--separator"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24px"
              height="24px"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="feather feather-chevrons-right w-4 h-4"
            >
              <polyline points="13 17 18 12 13 7" />
              <polyline points="6 17 11 12 6 7" />
            </svg>
          </span>
        </li>
        <li class="BC_link">
          <router-link :to="{ name: 'employee' }">Orders</router-link>
          <span
            translate="translate"
            aria-hidden="true"
            class="separator notranslate vs-breadcrum--separator"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24px"
              height="24px"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="feather feather-chevrons-right w-4 h-4"
            >
              <polyline points="13 17 18 12 13 7" />
              <polyline points="6 17 11 12 6 7" />
            </svg>
          </span>
        </li>
        <li class="vs-active BC_link">
          <a href="#" class="vs-breadcrumb--link">Details</a>
        </li>
      </ol>
    </nav>
    <!--End of breadcrumb -->

    <div class="row div_shadow edit_user">
      <div class="col-lg-12">
        <h3 class="text-center">Editing User</h3>
      </div>

      <div class="col-lg-6 user_detail">
        <vs-input label="User Name" class="form_input" v-model="user.name" />
        <vs-input label="Email" type="email" class="form_input" v-model="user.email" />
        <vs-input label="Role" class="form_input" type="text" v-model="user.type" />
        <vs-input
          label="Role"
          class="form_input"
          type="text"
          v-model="user.user_role[0].role.name"
        />
      </div>
      <div class="col-lg-6 user_detail">
        <vs-input label="Employee Name" class="form_input" v-model="user.employees[0].name" />
        <vs-input label="Phone" type="number" class="form_input" v-model="user.employees[0].phone" />
        <vs-input
          label="Position"
          class="form_input"
          type="text"
          v-model="user.employees[0].position"
        />
      </div>
      <div class="col-lg-12 flex-auto">
        <p>Employee Permission</p>
        <vs-checkbox class="checkbox_sign" v-model="permission_employee.create">Create</vs-checkbox>
        <vs-checkbox class="checkbox_sign" v-model="permission_employee.read">Read</vs-checkbox>
        <vs-checkbox class="checkbox_sign" v-model="permission_employee.update">Update</vs-checkbox>
        <vs-checkbox class="checkbox_sign" v-model="permission_employee.delete">Delete</vs-checkbox>
      </div>
      <div class="col-lg-12 flex-auto">
        <p>User Permission</p>
        <vs-checkbox class="checkbox_sign" v-model="permission_user.create">Create</vs-checkbox>
        <vs-checkbox class="checkbox_sign" v-model="permission_user.read">Read</vs-checkbox>
        <vs-checkbox class="checkbox_sign" v-model="permission_user.update">Update</vs-checkbox>
        <vs-checkbox class="checkbox_sign" v-model="permission_user.delete">Delete</vs-checkbox>
      </div>
      <div class="col-lg-12">
        <vs-button class="add_btn" @click="UpdateUser()">Update</vs-button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: {},
      permission_employee: {},
      permission_user: {}
    };
  },
  created() {
    this.user = this.$route.params.data;
    this.getUser();
  },
  mounted() {},
  methods: {
    getUser() {
      this.permission_employee.create = parseInt(
        this.user.employees[0].permission_employee[0].create
      );
      this.permission_employee.read = parseInt(
        this.user.employees[0].permission_employee[0].read
      );
      this.permission_employee.update = parseInt(
        this.user.employees[0].permission_employee[0].update
      );
      this.permission_employee.delete = parseInt(
        this.user.employees[0].permission_employee[0].delete
      );

      this.permission_user.create = parseInt(
        this.user.employees[0].permission_user[0].create
      );
      this.permission_user.read = parseInt(
        this.user.employees[0].permission_user[0].read
      );
      this.permission_user.update = parseInt(
        this.user.employees[0].permission_user[0].update
      );
      this.permission_user.delete = parseInt(
        this.user.employees[0].permission_user[0].delete
      );
    },
    UpdateUser() {
      axios
        .post("/web/updateUser", {
          user_id: this.user.id,
          user_name: this.user.name,
          user_email: this.user.email,
          user_type: this.user.type,
          role: this.user.user_role[0].role.name,
          employee_id: this.user.employees[0].id,
          employee_name: this.user.employees[0].name,
          employee_phone: this.user.employees[0].phone,
          employee_position: this.user.employees[0].position,
          employee_create: this.permission_employee.create,
          employee_read: this.permission_employee.read,
          employee_update: this.permission_employee.update,
          employee_delete: this.permission_employee.delete,
          user_create: this.permission_user.create,
          user_read: this.permission_user.read,
          user_update: this.permission_user.update,
          user_delete: this.permission_user.delete
        })
        .then(response => {
          this.$notify({
            title: "Success",
            message: "Congrats, sccessfully updated.",
            type: "success"
          });
        })
        .catch(error => {
          const key = Object.keys(error.response.data.errors)[0];
          this.errorMessage = error.response.data.errors[key][0];
        });
    }
  }
};
</script>
