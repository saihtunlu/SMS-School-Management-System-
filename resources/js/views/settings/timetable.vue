<template>
  <div class="row vs-con-loading__container" style="overflow: visible;" id="permission_loading">
    <vs-prompt id="send_invoice" title="Adding New Timetable" :active.sync="active">
      <div class="row">
        <div class="col-lg-12">
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Classroom</label>
            <el-select
              v-model="timetable.classroom_id"
              filterable
              default-first-option
              placeholder="Choose a classroom"
            >
              <el-option
                v-for="classroom in classrooms"
                :key="classroom.id"
                :label="classroom.grade"
                :value="classroom.id"
              ></el-option>
            </el-select>
          </div>

          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Start Date</label>
            <el-date-picker
              v-model="timetable.from"
              format="dd/MM/yyyy"
              value-format="dd/MM/yyyy"
              type="date"
              placeholder="Pick a date"
            ></el-date-picker>
          </div>
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">End Date</label>
            <el-date-picker
              v-model="timetable.to"
              format="dd/MM/yyyy"
              value-format="dd/MM/yyyy"
              type="date"
              placeholder="Pick a date"
            ></el-date-picker>
          </div>
        </div>

        <div class="col-lg-12 flex-end">
          <vs-button color="primary" class="edit_btn" @click="save()" type="filled">Save</vs-button>
          <vs-button color="warning" class="edit_btn" type="border" @click="active=false">Cancel</vs-button>
        </div>
      </div>
    </vs-prompt>
    <vs-prompt id="send_invoice" title="Edit Timetable" :active.sync="editactive">
      <div class="row">
        <div class="col-lg-12">
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Classroom</label>
            <el-select
              v-model="timetable.classroom_id"
              filterable
              default-first-option
              placeholder="Choose a classroom"
            >
              <el-option
                v-for="classroom in classrooms"
                :key="classroom.id"
                :label="classroom.grade"
                :value="classroom.id"
              ></el-option>
            </el-select>
          </div>

          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Start Date</label>
            <el-date-picker
              v-model="timetable.from"
              format="dd/MM/yyyy"
              value-format="dd/MM/yyyy"
              type="date"
              placeholder="Pick a date"
            ></el-date-picker>
          </div>
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">End Date</label>
            <el-date-picker
              v-model="timetable.to"
              format="dd/MM/yyyy"
              value-format="dd/MM/yyyy"
              type="date"
              placeholder="Pick a date"
            ></el-date-picker>
          </div>
        </div>

        <div class="col-lg-12 flex-end">
          <vs-button color="primary" class="edit_btn" @click="update()" type="filled">Save</vs-button>
          <vs-button
            color="warning"
            class="edit_btn"
            type="border"
            @click="editactive=false;subject={}"
          >Cancel</vs-button>
        </div>
      </div>
    </vs-prompt>

    <div class="col-lg-12 flex-between-center">
      <h4 class="header-text flex-align-center" style="margin-bottom:0.6rem !important;">
        <vs-button
          radius
          size="40px"
          color="primary"
          icon-pack="feather"
          icon="icon-credit-card"
          type="flat"
        ></vs-button>Timetables
      </h4>
      <div class="flex-align-center">
        <div class="custom_input" style="margin-top:0px !important;">
          <el-select
            v-model="year_id"
            @change="changer_year()"
            filterable
            default-first-option
            placeholder="Choose a academic year"
          >
            <el-option v-for="year in years" :key="year.id" :label="year.title" :value="year.id"></el-option>
          </el-select>
        </div>
        <vs-button
          color="primary"
          type="filled"
          class="add_new"
          icon-pack="feather"
          icon="icon-plus"
          @click="add_new()"
        >Add</vs-button>
      </div>
    </div>
    <vs-divider class="mt-0" color="rgb(var(--vs-gray2),0.5)" />

    <div class="col-lg-12 mt-4">
      <div class="default_permissions_content">
        <div style="overflow-x:auto; overflow-y:hidden;" class="vs-table--tbody">
          <table class="permisson_table" id="subject-table" cellspacing="10">
            <thead>
              <tr class="table_header">
                <th class="px-3 text-center py-2">No.</th>
                <th class="px-3 text-center py-2">Classroom</th>
                <th class="px-3 text-center py-2">From</th>
                <th class="px-3 text-center py-2">To</th>
                <th class="px-3 text-center py-2">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr class="table_content" v-for="(timetable,index) in timetables" v-bind:key="index">
                <td
                  style="border-top-left-radius: .5rem;border-bottom-left-radius: .5rem;"
                >{{parseInt(index)+1}}</td>
                <td>{{timetable.classroom.grade}}</td>
                <td>{{timetable.from}}</td>
                <td>{{timetable.to}}</td>

                <td
                  class="flex-center"
                  style="border-top-right-radius: .5rem;border-bottom-right-radius: .5rem;"
                >
                  <vs-button
                    color="dark"
                    type="flat"
                    icon-pack="feather"
                    class="mr-1"
                    @click="editTimetable(timetable,index)"
                    icon="icon-edit"
                  ></vs-button>
                  <vs-button
                    color="dark"
                    type="flat"
                    @click="deleteTimetable(timetable.id,index)"
                    icon-pack="feather"
                    icon="icon-trash"
                  ></vs-button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      editactive: false,
      active: false,
      classroom_id: null,
      timetables: [],
      data: [{}],
      timetable: {},
      classrooms: {},
      edit_index: 0,
      years: [],
      year_id: null
    };
  },
  created() {
    if (localStorage.getItem("current_year_id")) {
      this.year_id = parseInt(localStorage.getItem("current_year_id"));
    }
    this.getYears();
  },
  mounted() {},
  methods: {
    add_new() {
      this.timetable.classroom_id = this.classroom_id;
      this.active = true;
    },
    update() {
      this.editactive = false;
      this.$vs.loading({
        container: "#permission_loading",
        background: "rgba(var(--vs-theme),0.4)",
        color: "primary",
        type: "border",
        scale: 0.6
      });
      axios
        .post(`web/updateTimetable`, { data: this.timetable })
        .then(response => {
          this.$message({
            message: "Successfully updated!",
            type: "success"
          });
          this.getTimetables();
          this.$vs.loading.close("#permission_loading > .con-vs-loading");
        })
        .catch(error => {
          this.$vs.loading.close("#permission_loading > .con-vs-loading");
          this.$message({
            message: "Oops! There is an error while updating.",
            type: "danger"
          });
        });
    },
    editTimetable(data, index) {
      this.timetable = data;
      this.timetable.classroom_id = parseInt(this.timetable.classroom_id);

      this.edit_index = index;
      this.editactive = true;
    },
    save() {
      this.active = false;
      this.$vs.loading({
        container: "#permission_loading",
        background: "rgba(var(--vs-theme),0.4)",
        color: "primary",
        type: "border",
        scale: 0.6
      });
      axios
        .post("/web/createTimetable", {
          data: this.timetable
        })
        .then(response => {
          this.$message({
            message: "Successfully added!",
            type: "success"
          });
          this.getTimetables();
          this.$vs.loading.close("#permission_loading > .con-vs-loading");
        })
        .catch(error => {
          this.$vs.loading.close("#permission_loading > .con-vs-loading");
          this.$message({
            message: "Oops! There is an error while adding.",
            type: "danger"
          });
        });
    },
    getClassrooms() {
      axios.get(`/web/classrooms`).then(response => {
        this.classrooms = response.data.filter(data => {
          return data.academic_year_id === this.year_id;
        });
      });
    },
    getTimetables() {
      this.$vs.loading({
        container: "#permission_loading",
        background: "rgba(var(--vs-theme),0.4)",
        color: "primary",
        type: "border",
        scale: 0.6
      });
      axios
        .get(`web/timetables`)
        .then(response => {
          this.data = response.data;
          this.changer_year();
          this.$vs.loading.close("#permission_loading > .con-vs-loading");
        })
        .catch(error => {
          this.$vs.loading.close("#permission_loading > .con-vs-loading");
        });
    },
    deleteTimetable(id) {
      axios
        .delete(`web/removeTimetable/${id}`)
        .then(response => {
          this.getTimetables();
        })
        .catch(error => {});
    },
    changer_year() {
      this.timetables = this.data.filter(data => {
        return parseInt(data.classroom.academic_year_id) === this.year_id;
      });
      this.getClassrooms();
    },
    getYears() {
      axios
        .get(`web/years`)
        .then(response => {
          this.years = response.data;
          this.getTimetables();
        })
        .catch(error => {});
    }
  }
};
</script>

