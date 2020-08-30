<template>
  <div class="row">
    <!-- breadcrumb -->
    <nav aria-label="breadcrumb" class="vs-breadcrumb vs-align-left">
      <ol class="vs-breadcrumb--ol">
        <h3 class="BC_header">Add User</h3>
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
          <router-link :to="{ name: 'Users' }">Users</router-link>
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
          <a class="vs-breadcrumb--link">Add</a>
        </li>
      </ol>
    </nav>
    <!--End of breadcrumb -->

    <div class="col-lg-12 padding open div_shadow vs-con-loading__container" id="loading">
      <div class="row">
        <vs-alert
          title="Validate Error!"
          :active.sync="error"
          color="danger"
          icon-pack="feather"
          icon="icon-alert-circle"
          closable
          close-icon="icon-x"
        >
          <span>Please fill out all required fields!</span>
        </vs-alert>
        <div class="col-12 edit1">
          <vs-button
            class="profile_upload_btns openicon"
            @click="active = true"
            v-if="mobile"
            radius
            size="40px"
            color="primary"
            icon-pack="feather"
            icon="icon-camera"
            type="filled"
          ></vs-button>
          <el-image class="edit_profile_image" :preview-src-list="srcList" :src="url">
            <div slot="placeholder" class="image-slot">
              <i class="el-icon-loading" style="font-size:18px !important; color:#fff;"></i>
            </div>
            <div slot="error" class="image-slot">
              <i class="el-icon-picture-outline"></i>
            </div>
          </el-image>
          <div class="edit1_btn_div" v-if="!mobile">
            <p class="text-lg" v-if="user.name">{{ user.name}}</p>
            <p class="text-lg" v-if="!user.name">Enter User Name</p>

            <vs-button color="primary" @click="active = true" type="filled">Add Avatar</vs-button>
            <vs-button color="danger" @click="clear_file" type="border">Remove Avatar</vs-button>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-6">
          <vs-input class="edit2_btns" label="User Name*" v-model="user.name" required />
          <vs-input type="email" class="edit2_btns" label="Email*" v-model="user.email" />
        </div>
        <div class="col-lg-6">
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Password*</label>

            <el-input v-model="user.password" show-password></el-input>
          </div>
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Password Confirmation*</label>

            <el-input
              v-model="user.password_confirmation"
              :class="{inputError : passwordError}"
              @change="checkPassword()"
              show-password
            ></el-input>
            <span
              class="text-danger text-small"
              v-if="passwordError"
            >Password confirmation does not match!</span>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="custom_input" v-if="user.type==='Employee'">
            <label for class="vs-input--label" style="display:block;">Position</label>
            <el-select
              v-model="user.position"
              @change="changer_position()"
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
          <div class="custom_input" v-if="user.type!=='Employee'">
            <label for class="vs-input--label" style="display:block;">Position</label>
            <el-select
              v-model="user.type"
              @change="changer_position()"
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
        </div>
      </div>
      <div class="row mt-base">
        <div class="col-lg-12">
          <div class="permission_content">
            <div class="flex-between">
              <h4 class="header-text flex-align-center" style="margin-bottom:0.6rem !important;">
                <vs-icon size="small" icon-pack="feather" class="custom_icon" icon="icon-lock"></vs-icon>Permissions
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
                v-for="(permission,index) in permissions"
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
      </div>
      <div class="row mt-base">
        <div class="col-12 flex-end">
          <vs-button
            color="primary"
            class="add_user_btn mr-3"
            @click="save()"
            type="filled"
          >Save Changes</vs-button>
          <vs-button color="warning" class="add_user_btn" @click="Goback()" type="border">Cancel</vs-button>
        </div>
      </div>
    </div>
    <upload :active="active" @close="active = false" @saved_file="saved_file" />
  </div>
</template>

<script>
import upload from "../../components/upload";

export default {
  props: {
    mobile: false
  },
  data() {
    return {
      passwordError: false,
      active: false,
      error: false,
      file: "",
      url: null,
      srcList: [],
      type: "",
      user: {
        name: "",
        email: "",
        position: "",
        password: "",
        password_confirmation: "",
        profile_image: "",
        isfather: false,
        ismother: false
      },
      user_detail: {},
      roles: {},
      data: [{}],
      permissions: [{}]
    };
  },
  created() {
    var index = this.$route.meta.index;
    this.$emit("index", index);
    var data = this.$route.params.data;
    var type = this.$route.params.type;
    this.user.isfather = this.$route.params.father;
    console.log(" this.isfather: ", this.user.isfather);
    this.user.ismother = this.$route.params.mother;
    console.log(" this.ismother: ", this.user.ismother);

    if (data) {
      this.user_detail = data;
      this.user.name =
        this.user_detail.first_name + " " + this.user_detail.last_name;
      this.user.type = type;
      if (type === "Student" || type === "Parent") {
        this.user.position = type;
      } else {
        this.user.position = this.user_detail.position;
      }
      console.log("this.user.position: ", this.user.position);

      this.url = this.user_detail.profile_image;
      this.srcList.push(this.url);
      if (this.user_detail.email) {
        this.user.email = this.user_detail.email;
      }
      this.user.type_id = this.user_detail.id;
      this.user.profile_image = this.user_detail.profile_image;
    }
  },
  mounted() {
    this.getRoles();
    this.getPermissions();
  },
  components: {
    upload
  },
  methods: {
    Goback() {
      this.$router.go(-1);
    },
    checkPassword() {
      if (this.user.password != this.user.password_confirmation) {
        this.passwordError = true;
      } else {
        this.passwordError = false;
      }
    },
    changer_position() {
      if (this.user.position) {
        var role = this.user.position;
      } else {
        var role = this.user.type;
      }
      var data = this.data;
      this.permissions = data.filter(function(permission) {
        if (permission.roles.name === role) return true;
      });
    },
    getRoles() {
      axios.get(`/web/roles`).then(response => {
        this.roles = response.data;
      });
    },
    clear_file() {
      this.file = "";
      this.url = null;
      this.srcList = [];
    },
    saved_file(data) {
      this.file = "";
      this.url = null;
      this.srcList = [];
      this.file = data.data;
      this.url = data.url;
      this.srcList.push(this.url);
      setTimeout(() => (this.active = false), 500);
    },
    save() {
      this.$vs.loading({
        container: "#loading",
        background: "rgba(var(--vs-theme),0.4)",
        color: "primary",
        type: "border",
        scale: 0.6
      });
      const config = {
        headers: { "content-type": "multipart/form-data" }
      };
      let formData = new FormData();
      formData.append("user", JSON.stringify(this.user));
      formData.append("permissions", JSON.stringify(this.permissions));
      formData.append("file", this.file);
      axios
        .post("web/createUser", formData, config)
        .then(response => {
          this.$vs.loading.close("#loading > .con-vs-loading");

          if (response.data.alert) {
            this.$message({
              message: response.data.alert,
              type: "warning"
            });
          } else {
            this.$message({
              message:
                "Congratulation! A user has been successfuly registered, please check email to verifty.",
              type: "success"
            });
          }
        })
        .catch(function(error) {
          this.$vs.loading.close("#loading > .con-vs-loading");
          this.$message({
            message: "Oops! There is an error while registering.",
            type: "error"
          });
        });
    },
    getPermissions() {
      axios
        .get(`web/getPermissions`)
        .then(response => {
          this.data = response.data;
          this.changer_position();
        })
        .catch(error => {});
    }
  }
};
</script>
