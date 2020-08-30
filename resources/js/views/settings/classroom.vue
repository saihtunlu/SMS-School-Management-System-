<template>
  <div class="row vs-con-loading__container" style="overflow: visible;" id="permission_loading">
    <vs-prompt id="send_invoice" title="Adding New Classroom" :active.sync="active">
      <div class="row">
        <div class="col-lg-12">
          <vs-input class="edit2_btns" label="Grade Name*" v-model="grade.grade" />
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Academic year</label>
            <el-select
              v-model="grade.academic_year_id"
              filterable
              default-first-option
              placeholder="Choose a year"
            >
              <el-option v-for="year in years" :key="year.id" :label="year.title" :value="year.id"></el-option>
            </el-select>
          </div>
        </div>

        <div class="col-lg-12 flex-end">
          <vs-button color="primary" class="edit_btn" @click="save()" type="filled">Save</vs-button>
          <vs-button color="warning" class="edit_btn" type="border" @click="active=false">Cancel</vs-button>
        </div>
      </div>
    </vs-prompt>
    <vs-prompt id="send_invoice" :title="grade.grade" :active.sync="editactive">
      <div class="row">
        <div class="col-lg-12">
          <vs-input class="edit2_btns" label="Grade Name*" v-model="grade.grade" />
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Academic year</label>
            <el-select
              v-model="grade.academic_year_id"
              filterable
              default-first-option
              placeholder="Choose a teacher"
            >
              <el-option
                v-for="year in years"
                :key="year.id"
                :label="year.from+'-'+year.to"
                :value="year.id"
              ></el-option>
            </el-select>
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
          icon="icon-book"
          type="flat"
        ></vs-button>Classrooms
      </h4>
      <div class="flex-align-center">
        <div class="custom_input" style="margin-top:0px !important;">
          <el-select
            v-model="year_id"
            @change="changer_year()"
            filterable
            default-first-option
            placeholder="Choose a classroom"
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
                <th class="px-3 text-center py-2">Grade</th>
                <th class="px-3 text-center py-2">Academic Year</th>
                <th class="px-3 text-center py-2">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr class="table_content" v-for="(classroom,index) in classrooms" v-bind:key="index">
                <td
                  style="border-top-left-radius: .5rem;border-bottom-left-radius: .5rem;"
                >{{parseInt(index)+1}}</td>
                <td>{{classroom.grade}}</td>
                <td>{{classroom.academic_year.from+'-'+classroom.academic_year.to}}</td>
                <td
                  class="flex-center"
                  style="border-top-right-radius: .5rem;border-bottom-right-radius: .5rem;"
                >
                  <vs-button
                    color="dark"
                    type="flat"
                    icon-pack="feather"
                    class="mr-1"
                    @click="editClassroom(classroom,index)"
                    icon="icon-edit"
                  ></vs-button>
                  <vs-button
                    color="dark"
                    type="flat"
                    @click="deleteClassroom(classroom.id)"
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
  props: {
    current_year_id: null
  },
  data() {
    return {
      editactive: false,
      active: false,
      year_id: null,
      classrooms: [],
      data: [{}],
      grade: {
        year_id: null
      },
      years: {},
      edit_index: 0
    };
  },
  created() {
    if (localStorage.getItem("current_year_id")) {
      this.year_id = parseInt(localStorage.getItem("current_year_id"));
      this.grade.year_id = this.year_id;
    }

    this.getClassrooms();
    this.getYears();
  },
  mounted() {},
  methods: {
    deleteClassroom(id) {
      axios
        .delete(`web/removeClassroom/${id}`)
        .then(response => {
          this.getClassrooms();
        })
        .catch(error => {});
    },
    add_new() {
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
        .post(`web/updateClassroom`, { data: this.grade })
        .then(response => {
          this.$message({
            message: "Successfully updated!",
            type: "success"
          });
          this.getClassrooms();
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
    editClassroom(data, index) {
      this.grade = data;
      this.grade.academic_year_id = parseInt(this.grade.academic_year_id);
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
        .post("/web/createClassroom", {
          grade: this.grade.grade,
          academic_year_id: this.grade.academic_year_id
        })
        .then(response => {
          this.$message({
            message: "Successfully added!",
            type: "success"
          });
          this.getClassrooms();
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
    changer_year() {
      this.classrooms = this.data.filter(data => {
        return parseInt(data.academic_year_id) === this.year_id;
      });
      console.log("this.classrooms: ", this.classrooms);
    },
    getClassrooms() {
      axios.get(`/web/classrooms`).then(response => {
        this.data = response.data;
        this.changer_year();
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

