<template>
  <div class="row" id="students" v-if="$gate.isStudent_Read()">
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
        <h3 class="BC_header">Students</h3>
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
          <a href="#" class="vs-breadcrumb--link">Students</a>
        </li>
      </ol>
    </nav>
    <!--End of breadcrumb -->

    <fade-transition>
      <div class="col-lg-12 padding div_shadow mb-base">
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
              class="pointer mr-2 custom_icon"
              @click="resetFilter()"
              icon-pack="feather"
            />
            <!-- <vs-icon icon="icon-x" class="pointer" @click="filter=!filter" icon-pack="feather" /> -->
          </div>
        </div>
        <collapse-transition>
          <!--- Vue2 transition --->
          <div class="filter_content row" v-if="show">
            <div class="col-lg-6">
              <div class="custom_input">
                <label for class="vs-input--label" style="display:block;">Academic Year</label>
                <el-select
                  v-model="year_id"
                  @change="changer_year()"
                  filterable
                  default-first-option
                  placeholder="Choose a academic year"
                >
                  <el-option
                    v-for="year in years"
                    :key="year.id"
                    :label="year.title"
                    :value="year.id"
                  ></el-option>
                </el-select>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="custom_input">
                <label for class="vs-input--label" style="display:block;">Classrooms</label>
                <el-select
                  v-model="filter.classroom_id"
                  @change="getStudents()"
                  filterable
                  allow-create
                  default-first-option
                  placeholder="Choose a classroom"
                >
                  <el-option
                    v-for="data in classrooms"
                    :key="data.id"
                    :label="data.grade"
                    :value="data.id"
                  ></el-option>
                </el-select>
              </div>
            </div>
          </div>
        </collapse-transition>
      </div>
    </fade-transition>

    <div class="col-lg-12 employee open padding div_shadow">
      <vs-table
        hoverFlat
        id="loading"
        class="vs-con-loading__container"
        multiple
        v-model="data_selected"
        pagination
        :max-items="max_pagination"
        search
        :data="students"
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
              <vs-dropdown-item @click="max_pagination = 10">10</vs-dropdown-item>
              <vs-dropdown-item @click="max_pagination = 20">20</vs-dropdown-item>
              <vs-dropdown-item @click="max_pagination = 30">30</vs-dropdown-item>
              <vs-dropdown-item @click="max_pagination = 40">40</vs-dropdown-item>
            </vs-dropdown-menu>
          </vs-dropdown>
          <vs-button
            v-if="!mobile  && $gate.isStudent_Create()"
            class="add_new"
            color="primary"
            type="filled"
            icon-pack="feather"
            icon="icon-user-plus"
            to="/add_student"
          >Add</vs-button>
          <vs-dropdown v-if="!mobile" vs-trigger-click class="action">
            <div class="a-icon">
              <span>Actions</span>
              <vs-icon icon-pack="feather" icon="icon-chevron-down"></vs-icon>
            </div>

            <vs-dropdown-menu class="action_dropdown">
              <vs-dropdown-item @click="deleteStudents()">
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
                <vs-dropdown-item @click="max_pagination = 10">10</vs-dropdown-item>
                <vs-dropdown-item @click="max_pagination = 20">20</vs-dropdown-item>
                <vs-dropdown-item @click="max_pagination = 30">30</vs-dropdown-item>
                <vs-dropdown-item @click="max_pagination = 40">40</vs-dropdown-item>
              </vs-dropdown-menu>
            </vs-dropdown>
            <vs-button
              v-if="$gate.isStudent_Create()"
              class="add_new"
              color="primary"
              type="filled"
              icon-pack="feather"
              icon="icon-user-plus"
              to="/add_student"
            >Add</vs-button>
            <vs-dropdown vs-trigger-click class="action">
              <div class="a-icon">
                <span>Actions</span>
                <vs-icon icon-pack="feather" icon="icon-chevron-down"></vs-icon>
              </div>

              <vs-dropdown-menu class="action_dropdown">
                <vs-dropdown-item @click="deleteStudents()">
                  <vs-icon icon-pack="feather" icon="icon-trash-2" />Move to trash
                </vs-dropdown-item>
              </vs-dropdown-menu>
            </vs-dropdown>
          </div>
        </template>
        <template slot="thead">
          <vs-th sort-key="No." class="table_no">No.</vs-th>
          <vs-th sort-key="id">Student ID</vs-th>
          <vs-th sort-key="name">Name</vs-th>
          <vs-th sort-key="email">Grade</vs-th>
          <vs-th sort-key="email">Gender</vs-th>
          <vs-th sort-key="actions">View More</vs-th>
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

            <vs-td :data="data[indextr].classroom.grade">{{ data[indextr].classroom.grade }}</vs-td>
            <vs-td :data="data[indextr].gender">
              <vs-chip
                color="primary"
                v-if="data[indextr].gender==='male'"
              >{{ data[indextr].gender }}</vs-chip>
              <vs-chip
                color="success"
                v-if="data[indextr].gender==='female'"
              >{{ data[indextr].gender }}</vs-chip>
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
                :to="{name: 'Student', params: { id: data[indextr].id ,type:'Student'}}"
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
    mobile: false,
    current_year_id: null
  },
  data() {
    return {
      show: true,
      students: {},
      filter: {
        classroom_id: null
      },
      total: "",
      classrooms: {},
      max_pagination: 5,
      data_selected: [],
      Delete_type: "Student",
      delete_active: false,
      delete_id: [],
      years: [],
      year_id: null
    };
  },
  mounted() {
    this.getClassrooms();
    this.getYears();
  },
  components: {
    Delete
  },
  watch: {},
  created() {
    if (localStorage.getItem("current_year_id")) {
      this.year_id = parseInt(localStorage.getItem("current_year_id"));
    }
    var index = this.$route.meta.index;
    this.$emit("index", index);
  },
  methods: {
    resetFilter() {
      this.year_id = this.current_year_id;
      this.changer_year();
    },
    deleteStudents() {
      this.delete_id = [];
      this.data_selected.forEach(data => {
        this.delete_id.push(data.id);
      });
      this.delete_active = true;
    },
    getClassrooms() {
      axios.get(`/web/classrooms`).then(response => {
        this.classroom_data = response.data;
        this.changer_year();
      });
    },
    getStudents() {
      this.$vs.loading({
        container: "#loading",
        background: "rgba(var(--vs-theme),0.4)",
        color: "primary",
        type: "border",
        scale: 0.6
      });
      axios
        .get(`/web/students/${this.filter.classroom_id}`)
        .then(response => {
          this.students = response.data;
          this.total = this.students.length;

          // this.filterClassroom();
          this.$vs.loading.close("#loading > .con-vs-loading");
        })
        .catch(error => {
          this.$vs.loading.close("#loading > .con-vs-loading");
        });
    },
    successDelete() {
      this.getStudents();
      this.delete_active = false;
    },
    changer_year() {
      this.classrooms = this.classroom_data.filter(data => {
        return parseInt(data.academic_year_id) === this.year_id;
      });
    },
    getYears() {
      axios
        .get(`web/years`)
        .then(response => {
          this.years = response.data;
        })
        .catch(error => {});
    }
  }
};
</script>
