<template>
  <div class="row" v-if="$gate.isUser_Read()">
    <!-- v-if="$gate.isAdmin()" -->

    <Delete
      :active="delete_active"
      :type="Delete_type"
      :delete_id="delete_id"
      @success="successDelete"
      @error="delete_active = false"
    />
    <!-- breadcrumb -->
    <nav aria-label="breadcrumb" class="vs-breadcrumb vs-align-left">
      <ol class="vs-breadcrumb--ol">
        <h3 class="BC_header">Employees</h3>
        <li class="BC_link">
          <router-link :to="{ name: 'Dashboard' }">
            <vs-icon icon="icon-home" icon-pack="feather" size="24px" color="primary" />
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
        <li class="vs-active BC_link">
          <a href="#" class="vs-breadcrumb--link">Users</a>
        </li>
      </ol>
    </nav>
    <!--End of breadcrumb -->

    <!-- Start of Filter content -->
    <fade-transition>
      <div class="col-lg-12 padding div_shadow mb-base" v-if="filter">
        <div class="flex-between-center">
          <h4 class="header-text mb0">Filters</h4>
          <div class="filter_action_btn">
            <vs-icon
              icon="icon-chevron-down"
              v-if="show"
              @click="show=!show"
              class="pointer mr-2 custom_icon"
              icon-pack="feather"
            />
            <vs-icon
              icon="icon-chevron-up"
              v-if="!show"
              @click="show=!show"
              class="pointer mr-2 custom_icon"
              icon-pack="feather"
            />

            <vs-icon
              icon="icon-rotate-cw"
              class="pointer custom_icon mr-2"
              @click="resetfilter()"
              icon-pack="feather"
            />
            <!-- <vs-icon icon="icon-x" class="pointer" @click="filter=!filter" icon-pack="feather" /> -->
          </div>
        </div>
        <collapse-transition>
          <!--- Vue2 transition --->
          <div class="filter_content" v-if="show">
            <div class="custom_input mr-2">
              <label for class="vs-input--label" style="display:block;">Types</label>
              <el-select
                v-model="type"
                @change="filtering()"
                filterable
                allow-create
                default-first-option
                placeholder="Choose a position or Create new"
              >
                <el-option
                  v-for="type in types"
                  :key="type.name"
                  :label="type.name"
                  :value="type.name"
                ></el-option>
              </el-select>
            </div>
            <div class="custom_input open mr-2" v-if="type==='Employee'">
              <label for class="vs-input--label" style="display:block;">Roles</label>
              <el-select
                v-model="role"
                @change="filtering()"
                filterable
                allow-create
                default-first-option
                placeholder="Choose a position or Create new"
              >
                <el-option
                  v-for="role in roles"
                  :key="role.value"
                  :label="role.name"
                  :value="role.name"
                ></el-option>
              </el-select>
            </div>
            <div class="custom_input mr-2">
              <label for class="vs-input--label" style="display:block;">Verified</label>
              <el-select
                v-model="verify"
                @change="filtering()"
                filterable
                allow-create
                default-first-option
                placeholder="Choose a position or Create new"
              >
                <el-option
                  v-for="verify in verifies"
                  :key="verify.name"
                  :label="verify.name"
                  :value="verify.name"
                ></el-option>
              </el-select>
            </div>
          </div>
        </collapse-transition>
      </div>
    </fade-transition>

    <!-- Start of Table Content -->
    <div class="col-lg-12 padding div_shadow">
      <vs-table
        hoverFlat
        id="users_loading"
        class="vs-con-loading__container"
        multiple
        v-model="data_selected"
        pagination
        :max-items="max_pagination"
        search
        :data="data"
      >
        <template slot="header">
          <vs-dropdown vs-trigger-click>
            <vs-button
              class="pagenation"
              icon-after
              color="dark"
              type="border"
              icon-pack="feather"
              icon="icon-chevron-down"
            >1 - {{ max_pagination }} 0f {{ data_length}}</vs-button>
            <vs-dropdown-menu class="page_dropdown">
              <vs-dropdown-item @click="max_pagination = '10'">10</vs-dropdown-item>
              <vs-dropdown-item @click="max_pagination = '20'">20</vs-dropdown-item>
              <vs-dropdown-item @click="max_pagination = '30'">30</vs-dropdown-item>
              <vs-dropdown-item @click="max_pagination = '40'">40</vs-dropdown-item>
            </vs-dropdown-menu>
          </vs-dropdown>
          <vs-dropdown vs-trigger-click class="action mr-2">
            <div class="a-icon">
              <span>Actions</span>
              <vs-icon icon-pack="feather" icon="icon-chevron-down"></vs-icon>
            </div>

            <vs-dropdown-menu class="action_dropdown">
              <vs-dropdown-item @click="deleteUsers()">
                <vs-icon icon-pack="feather" icon="icon-trash-2" />Move to trash
              </vs-dropdown-item>
            </vs-dropdown-menu>
          </vs-dropdown>
        </template>
        <template slot="thead">
          <vs-th sort-key="No." class="table_no">No.</vs-th>
          <vs-th sort-key="name">User Name</vs-th>
          <vs-th sort-key="email">Email</vs-th>
          <vs-th sort-key="role">Role</vs-th>
          <vs-th sort-key="Verified">Verified</vs-th>
          <vs-th sort-key="actions">View More</vs-th>
        </template>

        <template slot-scope="{ data }">
          <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
            <vs-td :data="data[indextr].id">{{ parseInt(indextr)+1}}</vs-td>

            <vs-td :data="data[indextr].name" class="profile_column">
              <div
                class="dot"
                style="background:rgb(var(--vs-success))"
                v-if="data[indextr].status == 'online'"
              ></div>
              <div
                class="dot"
                style="background:rgb(var(--vs-warning))"
                v-if="data[indextr].status == 'offline'"
              ></div>
              <vs-avatar size="38px" class="mr-3" :src="data[indextr].profile_image" />
              {{ data[indextr].name}}
            </vs-td>
            <vs-td :data="data[indextr].email">{{ data[indextr].email }}</vs-td>
            <vs-td :data="data[indextr].user_role.role.name" v-if="data[indextr].type==='Employee'">
              <vs-chip
                v-if="data[indextr].user_role.role.name == 'Administrator'"
                color="success"
              >{{ data[indextr].user_role.role.name }}</vs-chip>
              <vs-chip
                v-if="data[indextr].user_role.role.name == 'Manager'"
                color="primary"
              >{{ data[indextr].user_role.role.name }}</vs-chip>
              <vs-chip
                v-if="data[indextr].user_role.role.name == 'Teacher'"
                color="warning"
              >{{ data[indextr].user_role.role.name }}</vs-chip>
            </vs-td>
            <vs-td
              :data="data[indextr].type"
              v-if="data[indextr].type==='Student' || data[indextr].type==='Parent'"
            >
              <vs-chip
                v-if="data[indextr].type == 'Student'"
                color="success"
              >{{ data[indextr].type }}</vs-chip>
              <vs-chip
                v-if="data[indextr].type == 'Parent'"
                color="primary"
              >{{ data[indextr].type }}</vs-chip>
            </vs-td>
            <vs-td :data="data[indextr].email_verified_at">
              <vs-chip
                transparent
                closable
                icon-pack="feather"
                v-if="data[indextr].email_verified_at"
                color="primary"
                close-icon="icon-check"
              >Yes</vs-chip>
              <vs-chip
                transparent
                closable
                icon-pack="feather"
                v-if="!data[indextr].email_verified_at"
                color="warning"
                close-icon="icon-shield-off"
              >No</vs-chip>
            </vs-td>
            <vs-td :data="data[indextr]">
              <vs-button
                size="35px"
                radius
                class="p-1"
                color="primary"
                icon-pack="feather"
                icon="icon-chevron-right"
                type="flat"
                :to="{name: 'User', params: { data: data[indextr],id:data[indextr].id}}"
              ></vs-button>
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
    </div>
  </div>
</template>
<script>
import Delete from "../../components/delete.vue";

export default {
  data() {
    return {
      Delete_type: "User",
      delete_active: false,
      delete_id: [],
      data: [],
      data_length: "",
      show: true,
      filter: true,
      employee_Length: 0,
      student_Length: 0,
      parent_Length: 0,
      colorx: "primary",
      roles: [
        {
          name: "All"
        }
      ],
      employees: [{}],
      students: [{}],
      parents: [{}],
      max_pagination: "10",
      data_selected: [],
      verifies: [
        {
          name: "All"
        },
        {
          name: "Yes"
        },
        {
          name: "No"
        }
      ],
      types: [
        {
          name: "All"
        },
        {
          name: "Employee"
        },
        {
          name: "Student"
        },
        {
          name: "Parent"
        }
      ],
      role: "All",
      type: "All",
      verify: "All"
    };
  },
  mounted() {
    this.getEmployees();
    this.getStudents();
    this.getParent();
    this.getRoles();
    this.listen();
  },
  components: {
    Delete
  },
  watch: {},
  created() {
    var index = this.$route.meta.index;
    this.$emit("index", index);
  },
  methods: {
    deleteUsers() {
      this.delete_id = [];
      this.data_selected.forEach(data => {
        this.delete_id.push(data.id);
      });
      this.delete_active = true;
    },
    listen() {
      Echo.join("App")
        .listen("UserOnline", e => {
          var id = e.user.id;
          var index = this.data
            .map(x => {
              return x.id;
            })
            .indexOf(id);
          this.data[index].status = "online";
        })
        .listen("UserOffline", e => {
          var id = e.user.id;
          var index = this.data
            .map(x => {
              return x.id;
            })
            .indexOf(id);
          this.data[index].status = "offline";
        });
    },
    resetfilter() {
      this.type = "All";
      this.role = "All";
      this.verify = "All";
      this.filtering();
    },
    filtering() {
      let role = this.role;
      let type = this.type;
      let verify = this.verify;
      let data = {};

      if (verify === "All") {
        //Filtering All
        if (type === "All") {
          this.data = [];
          this.employees.forEach(employee => {
            this.data.push(employee);
          });
          this.students.forEach(student => {
            this.data.push(student);
          });
          this.parents.forEach(parent => {
            this.data.push(parent);
          });
        }
        //End of Filtering All

        //Start of Filtering Employees
        if (type === "Employee" && role === "All") {
          this.data = this.employees;
        } //For Employee all roles

        if (type === "Employee" && role !== "All") {
          data = this.employees;
          this.data = data.filter(function(employee) {
            if (employee.user_role.role.name === role) return true;
          });
        }
        // if (type === "Employee" && role === "Teacher" && classroom === "All") {
        //   data = this.employees;
        //   this.data = data.filter(function(employee) {
        //     if (employee.user_role.role.name === role) return true;
        //   });
        // }
        // if (type === "Employee" && role === "Teacher" && classroom !== "All") {
        //   data = this.employees;
        //   this.data = data.filter(function(employee) {
        //     if (
        //       employee.user_role.role.name === role &&
        //       employee.employees.classroom_id === classroom
        //     )
        //       return true;
        //   });
        // } // End Of Filtering Employees

        // Start Of Filtering Students
        if (type === "Student") {
          this.data = this.students;
        }
        // if (type === "Student" && classroom !== "All") {
        //   data = this.students;
        //   this.data = data.filter(function(student) {
        //     if (student.students.classroom_id === classroom) return true;
        //   });
        // }
        // End Of Filtering Students

        // Start Of Filtering Parents
        if (type === "Parent") {
          this.data = this.parents;
        }
        // End Of Filtering Parents
      }
      if (verify === "Yes") {
        //Filtering All
        if (type === "All") {
          this.data = [];

          var employees = this.employees.filter(function(employee) {
            if (employee.email_verified_at) return true;
          });
          employees.forEach(employee => {
            this.data.push(employee);
          });

          var students = this.students.filter(function(student) {
            if (student.email_verified_at) return true;
          });
          students.forEach(student => {
            this.data.push(student);
          });

          var parents = this.parents.filter(function(parent) {
            if (parent.email_verified_at) return true;
          });
          parents.forEach(parent => {
            this.data.push(parent);
          });
        }
        //End of Filtering All

        //Start of Filtering Employees
        if (type === "Employee" && role === "All") {
          this.data = this.employees.filter(function(employee) {
            if (employee.email_verified_at) return true;
          });
        } //For Employee all roles

        if (type === "Employee" && role !== "All") {
          data = this.employees;
          var data_role = data.filter(function(employee) {
            if (employee.user_role.role.name === role) return true;
          });
          this.data = data_role.filter(function(employee) {
            if (employee.email_verified_at) return true;
          });
        }
        // if (type === "Employee" && role === "Teacher" && classroom === "All") {
        //   data = this.employees;
        //   var data_role = data.filter(function(employee) {
        //     if (employee.user_role.role.name === role) return true;
        //   });
        //   this.data = data_role.filter(function(employee) {
        //     if (employee.email_verified_at) return true;
        //   });
        // }
        // if (type === "Employee" && role === "Teacher" && classroom !== "All") {
        //   data = this.employees;
        //   var data_role = data.filter(function(employee) {
        //     if (
        //       employee.user_role.role.name === role &&
        //       employee.employees.classroom_id === classroom
        //     )
        //       return true;
        //   });
        //   this.data = data_role.filter(function(employee) {
        //     if (employee.email_verified_at) return true;
        //   });
        // } // End Of Filtering Employees

        // Start Of Filtering Students
        if (type === "Student") {
          this.data = this.students.filter(function(student) {
            if (student.email_verified_at) return true;
          });
        }
        // if (type === "Student" && classroom !== "All") {
        //   data = this.students;
        //   var classroom_data = data.filter(function(student) {
        //     if (student.students.classroom_id === classroom) return true;
        //   });
        //   this.data = classroom_data.filter(function(student) {
        //     if (student.email_verified_at) return true;
        //   });
        // }
        // End Of Filtering Students

        // Start Of Filtering Parents
        if (type === "Parent") {
          this.data = this.parents.filter(function(parent) {
            if (parent.email_verified_at) return true;
          });
        }
        // End Of Filtering Parents
      }
      if (verify === "No") {
        //Filtering All
        if (type === "All") {
          this.data = [];

          var employees = this.employees.filter(function(employee) {
            if (!employee.email_verified_at) return true;
          });
          employees.forEach(employee => {
            this.data.push(employee);
          });

          var students = this.students.filter(function(student) {
            if (!student.email_verified_at) return true;
          });
          students.forEach(student => {
            this.data.push(student);
          });

          var parents = this.parents.filter(function(parent) {
            if (!parent.email_verified_at) return true;
          });
          parents.forEach(parent => {
            this.data.push(parent);
          });
        } //End of Filtering All

        //Start of Filtering Employees
        if (type === "Employee" && role === "All") {
          this.data = this.employees.filter(function(employee) {
            if (!employee.email_verified_at) return true;
          });
        } //For Employee all roles

        if (type === "Employee" && role !== "All") {
          data = this.employees;
          var data_role = data.filter(function(employee) {
            if (employee.user_role.role.name === role) return true;
          });
          this.data = data_role.filter(function(employee) {
            if (!employee.email_verified_at) return true;
          });
        }
        // if (type === "Employee" && role === "Teacher" && classroom === "All") {
        //   data = this.employees;
        //   var data_role = data.filter(function(employee) {
        //     if (employee.user_role.role.name === role) return true;
        //   });
        //   this.data = data_role.filter(function(employee) {
        //     if (!employee.email_verified_at) return true;
        //   });
        // }
        // if (type === "Employee" && role === "Teacher" && classroom !== "All") {
        //   data = this.employees;
        //   var data_role = data.filter(function(employee) {
        //     if (
        //       employee.user_role.role.name === role &&
        //       employee.employees.classroom_id === classroom
        //     )
        //       return true;
        //   });
        //   this.data = data_role.filter(function(employee) {
        //     if (!employee.email_verified_at) return true;
        //   });
        // } // End Of Filtering Employees

        // Start Of Filtering Students
        if (type === "Student") {
          this.data = this.students.filter(function(student) {
            if (!student.email_verified_at) return true;
          });
        }
        // if (type === "Student" && classroom !== "All") {
        //   data = this.students;
        //   var classroom_data = data.filter(function(student) {
        //     if (student.students.classroom_id === classroom) return true;
        //   });
        //   this.data = classroom_data.filter(function(student) {
        //     if (!student.email_verified_at) return true;
        //   });
        // }
        // End Of Filtering Students

        // Start Of Filtering Parents
        if (type === "Parent") {
          this.data = this.parents.filter(function(parent) {
            if (!parent.email_verified_at) return true;
          });
        }
        // End Of Filtering Parents
      }
      this.data_length = this.data.length;
      this.$vs.loading.close("#users_loading > .con-vs-loading");
    },
    getRoles() {
      axios.get(`/web/roles`).then(response => {
        let array = response.data;
        array.forEach(data => {
          this.roles.push(data);
        });
      });
    },
    getEmployees() {
      if (this.$gate.isUser_Read()) {
        this.$vs.loading({
          container: "#users_loading",
          background: "rgba(var(--vs-theme),0.4)",
          color: "primary",
          type: "border",
          scale: 0.6
        });
        axios
          .get("/web/employeeUser")
          .then(response => {
            this.employees = response.data;
            console.log('response.data: ', response.data);
            this.employee_Length = parseInt(response.data.length);
          })
          .catch(error => {});
      }
    },
    getStudents() {
      if (this.$gate.isUser_Read()) {
        axios
          .get("/web/studentUser")
          .then(response => {
            this.students = response.data;
            this.student_Length = parseInt(response.data.length);
          })
          .catch(error => {});
      }
    },
    getParent() {
      if (this.$gate.isUser_Read()) {
        axios
          .get("/web/parentUser")
          .then(response => {
            this.parents = response.data;
            this.filtering();
          })
          .catch(error => {});
      }
    },
    successDelete() {
      this.getEmployees();
      this.getStudents();
      this.getParent();
      this.delete_active = false;
    }
  }
};
</script>
<style></style>
