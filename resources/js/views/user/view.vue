<template>
  <div class="row" v-if="$gate.isUser_Read()">
    <!-- popups -->
    <vs-prompt class="edit_model" title="Editing User Infomation" :active.sync="general">
      <div class="row vs-con-loading__container" id="loading">
        <div class="col-lg-6">
          <vs-input class="edit2_btns" label="First Name*" v-model="general_data.name" required />
        </div>
        <div class="col-lg-6">
          <vs-input type="email" class="edit2_btns" label="Email*" v-model="general_data.email" />
        </div>
        <div class="col-lg-12 mt-base">
          <div class="permission_content">
            <div class="flex-between">
              <h4 class="header-text flex-align-center" style="margin-bottom:0.7rem !important;">
                <vs-icon size="small" icon-pack="feather" icon="icon-lock"></vs-icon>Permissions
              </h4>
            </div>
            <vs-divider class="mt-0" color="rgb(var(--vs-gray2),0.5)" />
            <table class="permisson_table" cellspacing="10">
              <tr class="table_header">
                <td class="px-3 py-2">Module</td>
                <td class="px-3 py-2">Read</td>
                <td class="px-3 py-2">Update</td>
                <td class="px-3 py-2">Create</td>
                <td class="px-3 py-2">Delete</td>
              </tr>
              <tr
                class="table_content"
                v-for="(permission,index) in edit_permissions"
                v-bind:key="index"
              >
                <td class="px-3 py-2">{{permission.name}}</td>
                <td class="px-3 py-2">
                  <vs-checkbox class="checkbox_sign" v-model="permission.read"></vs-checkbox>
                </td>
                <td class="px-3 py-2">
                  <vs-checkbox class="checkbox_sign" v-model="permission.update"></vs-checkbox>
                </td>
                <td class="px-3 py-2">
                  <vs-checkbox class="checkbox_sign" v-model="permission.create"></vs-checkbox>
                </td>
                <td class="px-3 py-2">
                  <vs-checkbox class="checkbox_sign" v-model="permission.delete"></vs-checkbox>
                </td>
              </tr>
            </table>
          </div>
        </div>
        <div class="col-lg-12 flex-end">
          <vs-button color="primary" class="edit_btn" @click="saveGeneral()" type="filled">Save</vs-button>
          <vs-button color="warning" class="edit_btn" type="border" @click="cancel()">Cancel</vs-button>
        </div>
      </div>
    </vs-prompt>
    <upload :active="profile" @close="profile = false" @saved_file="saved_file" />
    <Delete
      :active="delete_active"
      :type="Delete_type"
      :delete_id="delete_id"
      @success="successDelete"
      @error="delete_active = false"
      @cancel="delete_active=false"
    />
    <!-- breadcrumb -->
    <div class="flex-between col-12">
      <nav aria-label="breadcrumb" class="vs-breadcrumb vs-align-left">
        <ol class="vs-breadcrumb--ol">
          <h3 class="BC_header">User</h3>
          <li class="BC_link">
            <router-link :to="{ name: 'Dashboard' }">
              <vs-icon icon-pack="feather" icon="icon-home" size="small" color="primary"></vs-icon>
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
            <router-link :to="{ name: 'Employees' }">Users</router-link>
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
            <a class="vs-breadcrumb--link">{{ user.name }}</a>
          </li>
        </ol>
      </nav>
      <div>
        <vs-button
          v-if="!editing && $gate.isUser_Update()"
          @click="editing = true"
          class="setting_btn"
          icon-pack="feather"
          icon="icon-edit"
          color="primary"
          type="flat"
        >Edit</vs-button>

        <vs-button
          v-if="editing"
          @click="editing = false"
          class="setting_btn"
          color="success"
          type="flat"
          icon-pack="feather"
          icon="icon-check-circle"
        >Done</vs-button>
        <vs-button
          v-if="editing"
          @click="setDelete(user.id)"
          class="setting_btn"
          color="danger"
          type="flat"
          icon-pack="feather"
          icon="icon-trash"
        >Delete</vs-button>
      </div>
    </div>
    <!--End of breadcrumb -->

    <!-- Contents -->
    <div class="col-lg-12 padding mb-base div_shadow">
      <div class="row">
        <div class="col-lg-12 mb-2 flex-between">
          <h4 class="header-text pb-2">Account</h4>
          <vs-icon
            class="pointer icon_btns pulse two-second animated infinite btn_primary"
            size="18px"
            v-if="editing"
            icon-pack="feather"
            icon="icon-edit"
            @click="open_edit_general()"
          ></vs-icon>
        </div>
        <div class="col-lg-2 col-sm-12 profile_div">
          <vs-button
            v-if="editing"
            id="edit_profile"
            @click="profile=true"
            class="pulse two-second animated infinite"
            radius
            size="40px"
            color="primary"
            icon-pack="feather"
            icon="icon-camera"
            type="flat"
          ></vs-button>
          <el-image class="employee_profile_image" :preview-src-list="srcList" :src="url">
            <div slot="placeholder" class="image-slot">
              <i class="el-icon-loading" style="font-size:18px !important; color:#fff;"></i>
            </div>
            <div slot="error" class="image-slot">
              <i class="feather icon-image"></i>
            </div>
          </el-image>
          <vs-button
            v-if="user.type==='Employee'  && mobile"
            color="primary"
            :to="{name: 'Employee', params: { data: user.employees}}"
            class="view_more mobile"
            type="filled"
          >View More</vs-button>
          <vs-button
            v-if="user.type==='Student'  && mobile"
            color="primary"
            :to="{name: 'Student', params: { id: user.student.id}}"
            class="view_more mobile"
            type="filled"
          >View More</vs-button>
        </div>
        <div class="col-lg-5 col-md-12 table_div col-sm-12">
          <table class="employee_table">
            <tr>
              <td class="detail_p">User Name</td>
              <td>{{ user.name}}</td>
            </tr>
            <tr>
              <td class="detail_p">Email</td>
              <td>{{ user.email }}</td>
            </tr>
          </table>
          <vs-button
            v-if="user.type==='Employee'"
            color="primary"
            :to="{name: 'Employee', params: { id: user.employee.id}}"
            class="view_more"
            type="filled"
          >View More</vs-button>
          <vs-button
            v-if="user.type==='Student'"
            color="primary"
            :to="{name: 'Student', params: { id: user.student.id}}"
            class="view_more"
            type="filled"
          >View More</vs-button>
        </div>
        <div class="col-lg-5 col-md-12 table_div col-sm-12">
          <table class="employee_table">
            <tr>
              <td class="detail_p">Role</td>
              <td>{{ user.user_role.role.name }}</td>
            </tr>

            <tr>
              <td class="detail_p">Verified</td>
              <td>
                <vs-chip
                  transparent
                  closable
                  icon-pack="feather"
                  v-if="user.email_verified_at"
                  color="success"
                  close-icon="icon-check"
                >Verified</vs-chip>
                <vs-chip
                  transparent
                  closable
                  icon-pack="feather"
                  v-if="!user.email_verified_at"
                  color="warning"
                  close-icon="icon-x"
                >No verified</vs-chip>
              </td>
            </tr>
            <tr>
              <td class="detail_p">Status</td>
              <td>
                <vs-chip
                  transparent
                  closable
                  icon-pack="feather"
                  v-if="user.status==='online'"
                  color="success"
                  close-icon="icon-wifi"
                >Online</vs-chip>
                <vs-chip
                  transparent
                  closable
                  icon-pack="feather"
                  v-if="user.status==='offline'"
                  color="warning"
                  close-icon="icon-wifi-off"
                >Offline</vs-chip>
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <div class="col-lg-12 padding mb-base div_shadow" v-if="user.type ==='Parent'">
      <div class="row">
        <div class="col-lg-12">
          <div class="flex-between">
            <h4 class="header-text flex-align-center" style="margin-bottom:0.7rem !important;">
              <vs-icon size="small" class="custom_icon" icon="mood"></vs-icon>Children
            </h4>
          </div>
          <vs-divider class="mt-0" color="rgb(var(--vs-gray2),0.5)" />
        </div>

        <div class="col-lg-6" v-for="child in children" :key="child.id">
          <div class="children_ flex-y-end">
            <el-image
              class="employee_profile_image"
              :preview-src-list="[child.profile_image]"
              :src="child.profile_image"
            >
              <div slot="placeholder" class="image-slot">
                <i class="el-icon-loading" style="font-size:18px !important; color:#fff;"></i>
              </div>
              <div slot="error" class="image-slot">
                <i class="feather icon-image"></i>
              </div>
            </el-image>
            <div class="child_details ml-3">
              <p class="text-md mb-1">
                <span class="text-bold">Name :</span>
                {{child.first_name+' '+child.last_name}}
              </p>
              <p class="text-md mb-1">
                <span class="text-bold">Classroom :</span>
                {{child.classroom.grade}}
              </p>
              <vs-button
                color="primary"
                :to="{name: 'Student', params: { id: child.id}}"
                class="view_more mobile"
                type="filled"
              >View More</vs-button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12 padding mb-base div_shadow">
      <div class="flex-between">
        <h4 class="header-text flex-align-center" style="margin-bottom:0.7rem !important;">
          <vs-icon size="small" class="custom_icon" icon-pack="feather" icon="icon-lock"></vs-icon>Permissions
        </h4>
      </div>
      <vs-divider class="mt-0" color="rgb(var(--vs-gray2),0.5)" />
      <table class="permisson_table" cellspacing="10">
        <tr class="table_header">
          <td class="px-3 py-2">Module</td>
          <td class="px-3 py-2">Read</td>
          <td class="px-3 py-2">Update</td>
          <td class="px-3 py-2">Create</td>
          <td class="px-3 py-2">Delete</td>
        </tr>
        <tr class="table_content" v-for="(permission,index) in permissions" v-bind:key="index">
          <td class="px-3 py-2">{{permission.name}}</td>
          <td class="px-3 py-2">
            <vs-checkbox class="checkbox_sign" disabled v-model="permission.read"></vs-checkbox>
          </td>
          <td class="px-3 py-2">
            <vs-checkbox class="checkbox_sign" disabled v-model="permission.update"></vs-checkbox>
          </td>
          <td class="px-3 py-2">
            <vs-checkbox class="checkbox_sign" disabled v-model="permission.create"></vs-checkbox>
          </td>
          <td class="px-3 py-2">
            <vs-checkbox class="checkbox_sign" disabled v-model="permission.delete"></vs-checkbox>
          </td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import upload from "../../components/upload";
import Delete from "../../components/delete.vue";

export default {
  props: {
    mobile: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      data: [{}],
      general: false,
      general_data: {
        name: "",
        email: ""
      },
      Delete_type: "User",
      delete_active: false,
      delete_id: [],
      profile: false,
      permissions: [],
      edit_permissions: [],
      editing: false,
      errorMessage: "",
      user: {},
      file: "",
      url: null,
      srcList: [],
      children: [],
      current_year_id: null
    };
  },
  components: {
    upload,
    Delete
  },
  created() {
    //set localstorage for route refreshing
    var check_year = localStorage.getItem("current_year_id");
    if (check_year) {
      this.current_year_id = check_year;
    }
    var check = JSON.parse(localStorage.getItem("user"));
    var data = this.$route.params.data;
    var id = this.$route.params.id;
    var index = this.$route.meta.index;
    this.$emit("index", index);

    if (data) {
      if (check) {
        if (check.id == data.id) {
          this.user = data;
        } else {
          localStorage.removeItem("user");
          this.user = data;
          let parsed = JSON.stringify(this.user);
          localStorage.setItem("user", parsed);
        }
      } else {
        this.user = data;
        let parsed = JSON.stringify(this.user);
        localStorage.setItem("user", parsed);
      }
    } else {
      this.user = check;
    }

    if (this.user.type === "Parent") {
      this.getParent(id);
    }
    this.permissions.push(this.user.permission_employee);
    this.permissions.push(this.user.permission_user);
    this.permissions.push(this.user.permission_student);
    this.permissions.push(this.user.permission_invoice);
    this.url = this.user.profile_image;
    this.srcList.push(this.url);
  },
  mounted() {},
  methods: {
    cancel() {
      this.user = JSON.parse(localStorage.getItem("user"));
      this.permissions = [];
      this.permissions.push(this.user.permission_employee);
      this.permissions.push(this.user.permission_user);
      this.permissions.push(this.user.permission_student);
      this.general = false;
    },
    saveGeneral() {
      this.$vs.loading({
        container: "#loading",
        background: "rgba(var(--vs-theme),0.4)",
        color: "primary",
        type: "border",
        scale: 0.6
      });
      axios
        .post(`web/updateUserGeneral`, {
          data: this.general_data,
          user_id: this.user.id,
          permissions: this.edit_permissions
        })
        .then(response => {
          this.$message({
            message: "Successfully upaded!",
            type: "success"
          });
          localStorage.removeItem("user");
          let parsed = JSON.stringify(this.user);
          localStorage.setItem("user", parsed);
          this.$vs.loading.close("#loading > .con-vs-loading");
          this.general = false;
        })
        .catch(error => {
          this.$vs.loading.close("#loading > .con-vs-loading");
          this.$message({
            message: "Oops! There is an error while updating.",
            type: "danger"
          });
          const key = Object.keys(error.response.data.errors)[0];
        });
    },
    open_edit_general() {
      this.general = true;
      this.general_data.name = this.user.name;
      this.general_data.email = this.user.email;
      this.edit_permissions = this.permissions;
    },
    setDelete(id) {
      this.delete_id.push(id);
      this.delete_active = true;
    },
    clear_file() {
      this.file = "";
      this.url = null;
      this.srcList = [];
    },
    saved_file(data) {
      setTimeout(() => (this.profile = false), 500);
      this.$vs.loading({
        background: "rgba(255,255,255,0.4)",
        color: "primary",
        type: "point",
        scale: 0.9
      });
      const config = {
        headers: { "content-type": "multipart/form-data" }
      };
      let formData = new FormData();
      formData.append("file", data.data);
      formData.append("id", this.user.id);

      axios
        .post("web/updateUserProfile", formData, config)
        .then(response => {
          this.$message({
            message: "Successfully upaded!",
            type: "success"
          });
          this.file = data.data;
          this.url = data.url;
          this.srcList.push(this.url);

          this.$vs.loading.close();
        })
        .catch(function(error) {
          this.$vs.loading.close();
          this.$message({
            message: "Oops! There is an error while updating.",
            type: "danger"
          });
        });
    },
    successDelete() {
      // this.delete_active = false;
      this.$router.push({ name: "Users" });
    },
    getParent(id) {
      axios
        .get(`web/getParent/${id}`)
        .then(response => {
          var data = response.data.children;
          this.children = data.filter(data => {
            return data.academic_year_id === parseInt(this.current_year_id);
          });
        })
        .catch(error => {});
    }
  }
};
</script>

