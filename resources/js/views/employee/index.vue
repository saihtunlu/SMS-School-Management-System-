<template>
  <div class="row" id="employees" v-if="$gate.isEmployee_Read()">
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
        <li class="vs-active BC_link">
          <a href="#" class="vs-breadcrumb--link">Emloyees</a>
        </li>
      </ol>
    </nav>
    <!--End of breadcrumb -->

    <div class="col-lg-12 employee open padding div_shadow">
      <vs-table
        hoverFlat
        id="employees_loading"
        class="vs-con-loading__container"
        multiple
        v-model="data_selected"
        pagination
        :max-items="max_pagination"
        search
        :data="employees"
      >
        <template slot="header">
          <vs-dropdown vs-trigger-click v-if="!mobile">
            <vs-button
              class="pagenation"
              icon-after
              color="dark"
              type="border"
              icon-pack="feather"
              icon="icon-chevron-down"
            >1 - {{ max_pagination }} 0f {{ total }}</vs-button>
            <vs-dropdown-menu class="page_dropdown">
              <vs-dropdown-item @click="max_pagination = '10'">10</vs-dropdown-item>
              <vs-dropdown-item @click="max_pagination = '20'">20</vs-dropdown-item>
              <vs-dropdown-item @click="max_pagination = '30'">30</vs-dropdown-item>
              <vs-dropdown-item @click="max_pagination = '40'">40</vs-dropdown-item>
            </vs-dropdown-menu>
          </vs-dropdown>
          <vs-button
            v-if="!mobile && $gate.isEmployee_Create()"
            class="add_new"
            color="primary"
            type="filled"
            icon-pack="feather"
            icon="icon-user-plus"
            to="/add_employee"
          >Add</vs-button>
          <vs-dropdown v-if="!mobile" vs-trigger-click class="action">
            <div class="a-icon">
              <span>Actions</span>
              <vs-icon icon-pack="feather" icon="icon-chevron-down"></vs-icon>
            </div>

            <vs-dropdown-menu class="action_dropdown">
              <vs-dropdown-item @click="deleteEmployee()">
                <vs-icon icon-pack="feather" icon="icon-trash-2" />Move to trash
              </vs-dropdown-item>
            </vs-dropdown-menu>
          </vs-dropdown>

          <div class="header_btns" v-if="mobile">
            <vs-dropdown vs-trigger-click>
              <vs-button
                class="pagenation"
                icon-after
                color="dark"
                type="border"
                icon-pack="feather"
                icon="icon-chevron-down"
              >1 - {{ max_pagination }} 0f {{ total }}</vs-button>
              <vs-dropdown-menu class="page_dropdown">
                <vs-dropdown-item @click="max_pagination = '10'">10</vs-dropdown-item>
                <vs-dropdown-item @click="max_pagination = '20'">20</vs-dropdown-item>
                <vs-dropdown-item @click="max_pagination = '30'">30</vs-dropdown-item>
                <vs-dropdown-item @click="max_pagination = '40'">40</vs-dropdown-item>
              </vs-dropdown-menu>
            </vs-dropdown>
            <vs-button
              v-if="$gate.isEmployee_Create()"
              class="add_new"
              color="primary"
              type="filled"
              icon-pack="feather"
              icon="icon-user-plus"
              to="/add_employee"
            >Add</vs-button>
            <vs-dropdown vs-trigger-click class="action">
              <div class="a-icon">
                <span>Actions</span>
                <vs-icon icon-pack="feather" icon="icon-chevron-down"></vs-icon>
              </div>

              <vs-dropdown-menu class="action_dropdown">
                <vs-dropdown-item>
                  <vs-icon icon-pack="feather" icon="icon-trash-2" />Move to trash
                </vs-dropdown-item>
              </vs-dropdown-menu>
            </vs-dropdown>
          </div>
        </template>
        <template slot="thead">
          <vs-th sort-key="No." class="table_no">No.</vs-th>
          <vs-th sort-key="id">Employee ID</vs-th>
          <vs-th sort-key="name">Name</vs-th>
          <vs-th sort-key="email">Email</vs-th>
          <vs-th sort-key="joined_date">Mobile Phone</vs-th>

          <vs-th sort-key="role">Role</vs-th>

          <vs-th sort-key="actions">Actions</vs-th>
        </template>

        <template slot-scope="{ data }">
          <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
            <vs-td :data="data[indextr].id">{{ parseInt(indextr)+1}}</vs-td>
            <vs-td :data="data[indextr].id">{{ data[indextr].id }}</vs-td>
            <vs-td
              :data="data[indextr].first_name +' ' +data[indextr].last_name"
              class="profile_column"
            >
              <vs-avatar size="38px" class="mr-3" :src="data[indextr].profile_image" />
              {{
              data[indextr].first_name +
              " " +
              data[indextr].last_name
              }}
            </vs-td>
            <vs-td :data="data[indextr].email">{{ data[indextr].email }}</vs-td>
            <vs-td :data="data[indextr].created_at">{{ data[indextr].phone }}</vs-td>

            <vs-td :data="data[indextr].position">
              <vs-chip
                v-if="data[indextr].position == 'Administrator'"
                color="success"
              >{{ data[indextr].position }}</vs-chip>
              <vs-chip
                v-if="data[indextr].position == 'Manager'"
                color="primary"
              >{{ data[indextr].position }}</vs-chip>
              <vs-chip
                v-if="data[indextr].position == 'Teacher'"
                color="warning"
              >{{ data[indextr].position }}</vs-chip>
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
                :to="{name: 'Employee', params: { id: data[indextr].id }}"
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
  props: {
    mobile: false
  },
  data() {
    return {
      delete_active: false,
      Delete_type: "Employee",
      delete_id: [],
      query: null,
      employees: [{}],
      max_pagination: "10",
      data_selected: [],
      errorMessage: "",
      total: ""
    };
  },
  mounted() {
    this.getEmployee();
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
    deleteEmployee() {
      this.delete_id = [];
      this.data_selected.forEach(data => {
        this.delete_id.push(data.id);
      });
      this.delete_active = true;
    },
    getEmployee() {
      this.$vs.loading({
        container: "#employees_loading",
        background: "rgba(var(--vs-theme),0.4)",
        color: "primary",
        type: "border",
        scale: 0.6
      });
      axios
        .get("/web/showEmployee")
        .then(response => {
          this.employees = response.data;
          this.total = response.data.length;
          this.$vs.loading.close("#employees_loading > .con-vs-loading");
        })
        .catch(error => {
          const key = Object.keys(error.response.data.errors)[0];
          this.errorMessage = error.response.data.errors[key][0];
        });
    },
    successDelete() {
      this.getEmployee();
      this.delete_active = false;
    }
  }
};
</script>
