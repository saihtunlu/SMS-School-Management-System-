<template>
  <div class="row">
    <!-- popup -->
    <vs-prompt class="add-task" title="Add Timetable" :active.sync="add_active">
      <div class="row email_composer vs-con-loading__container" id="loading">
        <div class="col-lg-6">
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Academic Year</label>
            <el-select
              v-model="add_option.year_id"
              @change="changer_year_()"
              filterable
              default-first-option
              placeholder="Choose a academic year"
            >
              <el-option v-for="year in years" :key="year.id" :label="year.title" :value="year.id"></el-option>
            </el-select>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Classrooms</label>
            <el-select
              v-model="add_option.classroom_id"
              @change="changeClassroom()"
              filterable
              allow-create
              default-first-option
              placeholder="Choose a classroom"
            >
              <el-option
                v-for="data in add_option.classrooms"
                :key="data.id"
                :label="data.grade"
                :value="data.id"
              ></el-option>
            </el-select>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Time</label>
            <el-select
              v-model="add_option.time"
              @change="changeTime()"
              filterable
              default-first-option
              placeholder="Choose a time"
            >
              <el-option
                v-for="data in add_option.time_sheet"
                :key="data"
                :label="data"
                :value="data"
              ></el-option>
            </el-select>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Subject</label>
            <el-select
              v-model="add.subject_id"
              filterable
              default-first-option
              @change="changeSubject()"
              placeholder="Choose a subject"
            >
              <el-option
                v-for="data in subjects"
                :key="data.id"
                :label="data.name"
                :value="data.id"
              ></el-option>
            </el-select>
          </div>
        </div>
        <div class="col-lg-6">
          <vs-input class="edit2_btns" label="Title*" v-model="add.title" />
        </div>
        <div class="col-lg-6">
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Time Sheet</label>
            <el-time-picker
              value-format="HH:mm"
              is-range
              arrow-control
              id="time_sheet"
              v-model="add.time_sheet"
              range-separator="To"
              start-placeholder="Start time"
              end-placeholder="End time"
            ></el-time-picker>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Day</label>
            <el-select v-model="add.day" filterable default-first-option placeholder="Choose a day">
              <el-option v-for="data in weedground" :key="data" :label="data" :value="data"></el-option>
            </el-select>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Pick a color for highting</label>
            <div class="color_div">
              <vs-button
                class="theme_color"
                :class="{ 'active_color': add.color == 'rgb(180, 122, 243)' }"
                style="background:rgb(180, 122, 243) !important;"
                @click="setColor('rgb(180, 122, 243)')"
              ></vs-button>

              <vs-button
                class="theme_color"
                :class="{ 'active_color': add.color == 'rgb(255, 91, 87)' }"
                style="background:rgb(255, 91, 87) !important;"
                @click="setColor('rgb(255, 91, 87)')"
              ></vs-button>

              <vs-button
                class="theme_color"
                :class="{ 'active_color': add.color == 'rgb(120, 201, 58)' }"
                style="background:rgb(120, 201, 58) !important;"
                @click="setColor('rgb(120, 201, 58)')"
              ></vs-button>

              <vs-button
                class="theme_color"
                :class="{ 'active_color': add.color == 'rgb(157, 68, 204)' }"
                style="background:rgb(157, 68, 204) !important;"
                @click="setColor('rgb(157, 68, 204)')"
              ></vs-button>

              <vs-button
                class="theme_color"
                :class="{ 'active_color': add.color == 'rgb(255, 236, 0)' }"
                style="background:rgb(255, 236, 0) !important;"
                @click="setColor('rgb(255, 236, 0)')"
              ></vs-button>
              <el-color-picker class="theme_color" v-model="add.color"></el-color-picker>
            </div>
          </div>
        </div>
        <div class="col-lg-12 flex-end email_btns">
          <vs-button color="primary" class="edit_btn" @click="addNew()" type="filled">Add</vs-button>
          <vs-button
            color="warning"
            class="edit_btn"
            type="border"
            @click="add={};add_active=false"
          >Cancel</vs-button>
        </div>
      </div>
    </vs-prompt>

    <vs-prompt class="add-task" :key="show_data.title" :active.sync="show_active">
      <div class="row email_composer vs-con-loading__container" id="loading">
        <div class="col-12 flex-end">
          <vs-icon
            icon="icon-trash"
            @click="deleteTimetable(show_data.id)"
            icon-pack="feather"
            size="18px"
            class="pointer custom_icon ml-2"
          />
        </div>
        <div class="col-lg-6">
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Subject</label>
            <el-select
              v-model="show_data.subject_id"
              filterable
              default-first-option
              @change="changeShowSubject()"
              placeholder="Choose a subject"
            >
              <el-option
                v-for="data in subjects"
                :key="data.id"
                :label="data.name"
                :value="data.id"
              ></el-option>
            </el-select>
          </div>
        </div>
        <div class="col-lg-6">
          <vs-input class="edit2_btns" label="Title*" v-model="show_data.title" />
        </div>
        <div class="col-lg-6">
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Time Sheet</label>
            <el-time-picker
              value-format="HH:mm"
              is-range
              arrow-control
              id="time_sheet"
              v-model="show_data.time_sheet"
              range-separator="To"
              start-placeholder="Start time"
              end-placeholder="End time"
            ></el-time-picker>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Day</label>
            <el-select
              v-model="show_data.day"
              filterable
              default-first-option
              placeholder="Choose a day"
            >
              <el-option v-for="data in weedground" :key="data" :label="data" :value="data"></el-option>
            </el-select>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Pick a color for highting</label>
            <div class="color_div">
              <vs-button
                class="theme_color"
                :class="{ 'active_color': show_data.color == 'rgb(180, 122, 243)' }"
                style="background:rgb(180, 122, 243) !important;"
                @click="show_data.color='rgb(180, 122, 243)'"
              ></vs-button>

              <vs-button
                class="theme_color"
                :class="{ 'active_color': show_data.color == 'rgb(255, 91, 87)' }"
                style="background:rgb(255, 91, 87) !important;"
                @click="show_data.color='rgb(255, 91, 87)'"
              ></vs-button>

              <vs-button
                class="theme_color"
                :class="{ 'active_color': show_data.color == 'rgb(120, 201, 58)' }"
                style="background:rgb(120, 201, 58) !important;"
                @click="show_data.color='rgb(120, 201, 58)'"
              ></vs-button>

              <vs-button
                class="theme_color"
                :class="{ 'active_color': show_data.color == 'rgb(157, 68, 204)' }"
                style="background:rgb(157, 68, 204) !important;"
                @click="show_data.color='rgb(157, 68, 204)'"
              ></vs-button>

              <vs-button
                class="theme_color"
                :class="{ 'active_color': show_data.color == 'rgb(255, 236, 0)' }"
                style="background:rgb(255, 236, 0) !important;"
                @click="show_data.color='rgb(255, 236, 0)'"
              ></vs-button>
              <el-color-picker class="theme_color" v-model="show_data.color"></el-color-picker>
            </div>
          </div>
        </div>
        <div class="col-lg-12 flex-end email_btns">
          <vs-button color="primary" class="edit_btn" @click="Update()" type="filled">Update</vs-button>
          <vs-button
            color="warning"
            class="edit_btn"
            type="border"
            @click="show_data={};show_active=false"
          >Cancel</vs-button>
        </div>
      </div>
    </vs-prompt>
    <!-- breadcrumb -->

    <nav aria-label="breadcrumb" class="vs-breadcrumb vs-align-left">
      <ol class="vs-breadcrumb--ol">
        <h3 class="BC_header">Timetables</h3>
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
          <a class="vs-breadcrumb--link">Timetables</a>
        </li>
      </ol>
    </nav>

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
            <div class="col-lg-4">
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
            <div class="col-lg-4">
              <div class="custom_input">
                <label for class="vs-input--label" style="display:block;">Classrooms</label>
                <el-select
                  v-model="filter.classroom_id"
                  @change="filterClassroom()"
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
            <div class="col-lg-4">
              <div class="custom_input">
                <label for class="vs-input--label" style="display:block;">Sections</label>
                <el-select
                  v-model="filter.time_sheet"
                  @change="getTimetable()"
                  filterable
                  allow-create
                  default-first-option
                  placeholder="Choose a time"
                >
                  <el-option v-for="data in time_sheet" :key="data" :label="data" :value="data"></el-option>
                </el-select>
              </div>
            </div>
          </div>
        </collapse-transition>
      </div>
    </fade-transition>
    <div class="col-12 div_shadow padding vs-con-loading__container" id="div-with-loading">
      <div class="row mb-3">
        <div class="col-12 flex-end">
          <vs-button
            color="primary"
            type="filled"
            icon-pack="feather"
            icon="icon-plus"
            class="add_new"
            @click="add_active=true"
          >Add New</vs-button>
        </div>
      </div>
      <timetable
        :time-ground="['08:00', '17:00']"
        :week-ground="weedground"
        :task-detail="table_detail"
        @clickDetail="showDetail"
        @clickTime="clickTime"
      ></timetable>
    </div>
  </div>
</template>

<script>
import timetable from "../../components/timetable.vue";

export default {
  props: {
    current_year_id: null
  },
  data() {
    return {
      add_active: false,
      data: [],
      add_option: {},
      add: {
        color: ""
      },
      show_active: false,
      weedground: [
        "Sunday",
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday"
      ],
      table_detail: [[], [], [], [], [], [], []],
      show: true,
      classrooms: {},
      changedClassroom: [],
      filter: {},
      times: [],
      filter: {
        classroom_id: null,
        time_sheet: ""
      },
      time_sheet: [],
      subjects: [],
      subjects_data: [],
      timetables: [],
      show_data: {
        time_sheet: []
      },
      classroom_data: [],
      year_id: null,
      years: []
    };
  },
  created() {
    var check = localStorage.getItem("current_year_id");
    if (check) {
      this.year_id = parseInt(check);
      this.add_option.year_id = this.year_id;
    }
    this.getYears();
    this.getClassrooms();
    var index = this.$route.meta.index;
    this.$emit("index", index);
    // this.year_id = this.current_year_id;
  },
  mounted() {
    this.getTimetables();
    this.getSubjects();
  },
  components: {
    timetable
  },
  methods: {
    setColor(data) {
      this.add.color = data;
    },
    changeShowSubject() {
      var id = this.show_data.subject_id;
      var index = this.subjects_data
        .map(x => {
          return x.id;
        })
        .indexOf(id);
      this.show_data.title = this.subjects_data[index].name;
    },
    changeSubject() {
      var id = this.add.subject_id;
      var index = this.subjects_data
        .map(x => {
          return x.id;
        })
        .indexOf(id);
      this.add.title = this.subjects_data[index].name;
    },
    changeTime() {
      var data = this.add_option.time.split(" to ");
      var from = data[0];
      var to = data[1];
      var timetable = this.changedClassroom.filter(data => {
        return data.from === from && data.to === to;
      })[0];
      this.add.timetable_id = timetable.id;
    },
    changeClassroom() {
      this.changedClassroom = this.data.filter(data => {
        return data.classroom_id === this.add_option.classroom_id;
      });
      this.add_option.time_sheet = [];
      this.changedClassroom.forEach(data => {
        var time = data.from + " to " + data.to;
        this.add_option.time_sheet.push(time);
      });
      this.subjects = this.subjects_data.filter(data => {
        return parseInt(data.classroom_id) === this.add_option.classroom_id;
      });
    },
    clickTime(data) {},
    showDetail(data) {
      this.show_data = data;
      this.show_data.time_sheet = [data.from, data.to];
      this.show_active = true;
    },
    getClassrooms() {
      axios.get(`/web/classrooms`).then(response => {
        this.classroom_data = response.data;
        this.changer_year();
        this.changer_year_();
      });
    },
    filterClassroom() {
      this.timetables = this.data.filter(data => {
        return data.classroom_id === this.filter.classroom_id;
      });
      this.time_sheet = [];
      this.timetables.forEach(data => {
        var time = data.from + " to " + data.to;
        this.time_sheet.push(time);
      });

      this.getTimetable();
    },
    getTimetable() {
      this.subjects = this.subjects_data.filter(data => {
        return parseInt(data.classroom_id) === this.filter.classroom_id;
      });

      var data = this.filter.time_sheet.split(" to ");
      var from = data[0];
      var to = data[1];
      var timetable = this.timetables.filter(data => {
        return data.from === from && data.to === to;
      })[0];

      if (timetable) {
        var details = timetable.timetable_details;
        this.table_detail = [[], [], [], [], [], [], []];

        this.table_detail[0] = details.filter(data => {
          return data.day === "Sunday";
        });
        this.table_detail[1] = details.filter(data => {
          return data.day === "Monday";
        });
        this.table_detail[2] = details.filter(data => {
          return data.day === "Tuesday";
        });
        this.table_detail[3] = details.filter(data => {
          return data.day === "Wednesday";
        });
        this.table_detail[4] = details.filter(data => {
          return data.day === "Thursday";
        });
        this.table_detail[5] = details.filter(data => {
          return data.day === "Friday";
        });
        this.table_detail[6] = details.filter(data => {
          return data.day === "Saturday";
        });
      }
    },
    getTimetables() {
      this.add_active = false;
      this.$vs.loading({
        background: "rgba(var(--vs-theme),0.2)",
        color: "dark",
        container: "#div-with-loading",
        type: "border",
        scale: 0.6
      });
      axios
        .get(`web/getTimetables`)
        .then(response => {
          this.$vs.loading.close("#div-with-loading > .con-vs-loading");
          this.data = response.data;
          console.log(" this.data: ", this.data);
          this.filterClassroom();
        })
        .catch(error => {
          this.$vs.loading.close("#div-with-loading > .con-vs-loading");
        });
    },
    getSubjects() {
      axios
        .get(`web/subjects/`)
        .then(response => {
          this.subjects_data = response.data;
        })
        .catch(error => {});
    },
    addNew() {
      this.add_active = false;
      this.$vs.loading({
        background: "rgba(var(--vs-theme),0.2)",
        color: "dark",
        type: "border",
        scale: 0.4
      });
      axios
        .post(`web/addTimetable`, { data: this.add })
        .then(response => {
          this.getTimetables();
          this.$vs.loading.close();
          this.$message({
            message: "Successfully added!",
            type: "success"
          });
        })
        .catch(error => {
          this.$vs.loading.close();
          this.$message({
            message: "Oops! There is an error while adding.",
            type: "danger"
          });
        });
    },
    Update() {
      this.show_active = false;
      this.$vs.loading({
        background: "rgba(var(--vs-theme),0.2)",
        color: "dark",
        type: "border",
        scale: 0.4
      });
      axios
        .post(`web/updateTimetable2`, { data: this.show_data })
        .then(response => {
          this.getTimetables();
          this.$vs.loading.close();
          this.$message({
            message: "Successfully added!",
            type: "success"
          });
        })
        .catch(error => {
          this.$vs.loading.close();
          this.$message({
            message: "Oops! There is an error while adding.",
            type: "danger"
          });
        });
    },
    deleteTimetable(id) {
      this.show_active = false;
      axios
        .delete(`web/removeTimetable2/${id}`)
        .then(response => {
          this.getTimetables();
        })
        .catch(error => {});
    },
    resetFilter() {
      this.filter = {
        classroom_id: 1,
        time_sheet: ""
      };
      this.table_detail = [];
    },
    changer_year_() {
      this.add_option.classrooms = this.classroom_data.filter(data => {
        return parseInt(data.academic_year_id) === this.add_option.year_id;
      });
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
