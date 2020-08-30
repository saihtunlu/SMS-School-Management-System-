<template>
  <div class="row vs-con-loading__container" style="overflow: visible;" id="loading">
    <div class="col-lg-12">
      <div class="flex-between">
        <h4 class="header-text flex-align-center" style="margin-bottom:0.6rem !important;">
          <vs-button
            radius
            size="40px"
            color="primary"
            icon-pack="feather"
            icon="icon-user"
            type="flat"
          ></vs-button>General
        </h4>
      </div>
      <vs-divider class="mt-0" color="rgb(var(--vs-gray2),0.5)" />
    </div>
    <div class="col-12 edit1">
      <vs-button
        class="profile_upload_btns mb0 user_edit openicon"
        @click="active=true"
        radius
        size="40px"
        color="primary"
        icon-pack="feather"
        icon="icon-camera"
        type="filled"
      ></vs-button>
      <el-image class="edit_user_profile_image" :preview-src-list="srcList" :src="url">
        <div slot="placeholder" class="image-slot">
          <i class="el-icon-loading" style="font-size:18px !important; color:#fff;"></i>
        </div>
        <div slot="error" class="image-slot">
          <i class="el-icon-picture-outline"></i>
        </div>
      </el-image>
    </div>

    <div class="col-lg-6">
      <vs-input class="edit2_btns" label="Name*" v-model="user.name" />
    </div>
    <div class="col-lg-6">
      <vs-input type="email" class="edit2_btns" label="Email*" v-model="user.email" />
    </div>
    <div class="col-lg-12 flex-end mt-3">
      <vs-button color="primary" type="filled" class="add_new" @click="saveGeneral()">Save Changes</vs-button>
    </div>
    <div class="col-lg-12 mt-base">
      <div class="flex-between">
        <h4 class="header-text flex-align-center" style="margin-bottom:0.6rem !important;">
          <vs-button
            radius
            size="40px"
            color="primary"
            icon-pack="feather"
            icon="icon-lock"
            type="flat"
          ></vs-button>Password
        </h4>
      </div>
      <vs-divider class="mt-0" color="rgb(var(--vs-gray2),0.5)" />
    </div>
    <div class="col-lg-12 default_invoice_items">
      <div class="custom_input">
        <label for class="vs-input--label" style="display:block;">Old Password*</label>

        <el-input v-model="user.old_password" show-password></el-input>
      </div>
      <div class="custom_input">
        <label for class="vs-input--label" style="display:block;">New Password*</label>

        <el-input v-model="user.new_password" show-password></el-input>
      </div>
      <div class="custom_input">
        <label for class="vs-input--label" style="display:block;">New Password Confirmation*</label>

        <el-input
          v-model="user.new_password_confirmation"
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
    <div class="col-lg-12 flex-end mt-4">
      <vs-button color="primary" type="filled" class="add_new" @click="savePassword()">Save Changes</vs-button>
    </div>

    <upload :active="active" @close="active = false" @saved_file="saved_file" />
  </div>
</template>

<script>
import upload from "../../components/upload";

export default {
  props: {
    auth: {}
  },
  data() {
    return {
      passwordError: false,
      active: false,
      url: null,
      srcList: [],
      file: "",
      user: {}
    };
  },
  components: {
    upload
  },
  created() {
    this.url = this.auth.auth.profile_image;
    this.srcList.push(this.url);
    this.user.name = this.auth.auth.name;
    this.user.email = this.auth.auth.email;
  },
  mounted() {},
  methods: {
    checkPassword() {
      if (this.user.new_password !== this.user.new_password_confirmation) {
        this.passwordError = true;
        return false;
      } else {
        this.passwordError = false;
        return true;
      }
    },
    saved_file(data) {
      this.active = false;
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
      formData.append("id", this.auth.auth.id);

      axios
        .post("web/updateUserProfile", formData, config)
        .then(response => {
          this.$message({
            message: "Successfully updated!",
            type: "success"
          });
          this.file = data.data;
          this.url = data.url;
          this.srcList.push(this.url);
          setTimeout(() => (this.profile = false), 500);
          this.auth.auth.profile_image = response.data;
          localStorage.removeItem("Auth");
          let parsed = JSON.stringify(this.auth.auth);
          localStorage.setItem("Auth", parsed);
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
    saveGeneral() {
      this.$vs.loading({
        container: "#loading",
        background: "rgba(var(--vs-theme),0.4)",
        color: "primary",
        type: "border",
        scale: 0.6
      });
      axios
        .post(`web/updateAuthGeneral`, {
          data: this.user,
          user_id: this.auth.auth.id
        })
        .then(response => {
          this.$message({
            message: "Successfully updated!",
            type: "success"
          });
          this.auth.auth.name = this.user.name;
          this.auth.auth.email = this.user.email;
          localStorage.removeItem("Auth");
          let parsed = JSON.stringify(this.auth.auth);
          localStorage.setItem("Auth", parsed);
          this.$vs.loading.close("#loading > .con-vs-loading");
        })
        .catch(error => {
          this.$vs.loading.close("#loading > .con-vs-loading");
          this.$message({
            message: "Oops! There is an error while updating.",
            type: "danger"
          });
        });
    },
    savePassword() {
      this.$vs.loading({
        container: "#loading",
        background: "rgba(var(--vs-theme),0.4)",
        color: "primary",
        type: "border",
        scale: 0.6
      });
      axios
        .post(`web/updateAuthPassword`, {
          data: this.user,
          user_id: this.auth.auth.id
        })
        .then(response => {
          this.$message({
            message: "Successfully updated!",
            type: "success"
          });
          this.$vs.loading.close("#loading > .con-vs-loading");
        })
        .catch(error => {
          if (error.response.data.error) {
            this.$message({
              message: error.response.data.error,
              type: "danger"
            });
          } else {
            this.$message({
              message: "Oops! There is an error while updating.",
              type: "danger"
            });
          }
          this.$vs.loading.close("#loading > .con-vs-loading");
        });
    },

    onFileChangeEdit(e) {
      this.file = "";
      this.url = null;
      this.srcList = [];
      this.file = e.target.files[0];
      this.url = URL.createObjectURL(this.file);
      this.srcList.push(this.url);
    }
  }
};
</script>
