<template>
  <div class="row vs-con-loading__container" style="overflow: visible;" id="permission_loading">
    <div class="col-lg-12 flex-between-center">
      <h4 class="header-text flex-align-center" style="margin-bottom:0.6rem !important;">
        <vs-button
          radius
          size="40px"
          color="primary"
          icon-pack="feather"
          icon="icon-lock"
          type="flat"
        ></vs-button>Default Permissions
      </h4>
      <div class="custom_input" style="margin-top:0px !important;">
        <el-select
          v-model="role_name"
          @change="changer_position()"
          filterable
          allow-create
          default-first-option
          placeholder="Choose a role"
        >
          <el-option
            v-for="(role,index) in roles"
            :key="role.id"
            :label="role.name"
            :value="role.name"
          >
            {{role.name}}
            <vs-icon
              icon="icon-x"
              style="display:none;"
              @click="deleteRole(role.id,index)"
              icon-pack="feather"
              class="delete_cat"
            />
          </el-option>
        </el-select>
      </div>
    </div>
    <vs-divider class="mt-0" color="rgb(var(--vs-gray2),0.5)" />

    <div class="col-lg-12 mt-4">
      <div class="default_permissions_content">
        <table class="permisson_table vs-table--tbody" style="overflow-y:hidden;" cellspacing="10">
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
    <div class="col-lg-12 flex-end mt-4">
      <vs-button color="primary" type="filled" class="add_new" @click="save()">Save Changes</vs-button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      role: 1,
      role_name: "Administrator",
      roles: [{}],
      data: [{}],
      permissions: [{}],
      Empty: [
        {
          name: "User",
          read: false,
          create: false,
          update: false,
          delete: false
        },
        {
          name: "Employee",
          read: false,
          create: false,
          update: false,
          delete: false
        },
        {
          name: "Student",
          read: false,
          create: false,
          update: false,
          delete: false
        },
        {
          name: "Invoice",
          read: false,
          create: false,
          update: false,
          delete: false
        }
      ]
    };
  },
  created() {
    this.getPermissions();
    this.getRoles();
  },
  mounted() {},
  methods: {
    deleteRole(id, index) {
      axios
        .delete(`web/deleteRole/${id}`)
        .then(response => {
          this.roles.splice(index, 1);
          this.getPermissions();
        })
        .catch(error => {});
    },

    save() {
      this.$vs.loading({
        container: "#permission_loading",
        background: "rgba(var(--vs-theme),0.4)",
        color: "primary",
        type: "border",
        scale: 0.6
      });
      axios
        .post(`web/createPermission`, {
          data: this.permissions,
          role_id: this.role,
          role_name: this.role_name
        })
        .then(response => {
          this.$message({
            message: "Successfully upaded!",
            type: "success"
          });
          this.$vs.loading.close("#permission_loading > .con-vs-loading");

          this.getPermissions;
        })
        .catch(error => {
          this.$vs.loading.close("#permission_loading > .con-vs-loading");

          this.$message({
            message: "Oops! There is an error while updating.",
            type: "danger"
          });
        });
    },
    changer_position() {
      var role_name = this.role_name;
      var index = this.roles
        .map(x => {
          return x.name;
        })
        .indexOf(role_name);

      if (index >= 0) {
        this.role = this.roles[index].id;
        var role = this.role;
        var data = this.data;
        var permissions = data.filter(function(permission) {
          if (permission.role_id === role) return true;
        });
        if (permissions.length !== 0) {
          this.permissions = permissions;
        } else {
          this.permissions = this.Empty;
        }
      } else {
        this.permissions = this.Empty;
      }
    },
    getRoles() {
      axios.get(`/web/roles`).then(response => {
        this.roles = response.data;
        this.changer_position();
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

