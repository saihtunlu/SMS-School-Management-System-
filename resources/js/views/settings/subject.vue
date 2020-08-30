<template>
  <div class="row vs-con-loading__container" style="overflow: visible;" id="permission_loading">
    <vs-prompt id="send_invoice" title="Adding New Subject" :active.sync="active">
      <div class="row">
        <div class="col-lg-12">
          <vs-input class="edit2_btns" label="Name*" v-model="subject.name" />
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Assigned Teacher</label>
            <el-select
              v-model="subject.employee_id"
              filterable
              default-first-option
              placeholder="Choose a teacher"
            >
              <el-option
                v-for="data in teachers"
                :key="data.teacher.id"
                :label="data.teacher.first_name+' '+data.teacher.last_name"
                :value="data.teacher.id"
              ></el-option>
            </el-select>
          </div>
          <div class="custom_input" style="margin-top:0px !important;">
            <label for class="vs-input--label" style="display:block;">Academic Year</label>
            <el-select
              v-model="subject.year_id"
              @change="changer_year_()"
              filterable
              default-first-option
              placeholder="Choose a classroom"
            >
              <el-option v-for="year in years" :key="year.id" :label="year.title" :value="year.id"></el-option>
            </el-select>
          </div>
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Classroom</label>
            <el-select
              v-model="subject.classroom_id"
              filterable
              default-first-option
              placeholder="Choose a classroom"
            >
              <el-option
                v-for="classroom in subject.classrooms"
                :key="classroom.id"
                :label="classroom.grade"
                :value="classroom.id"
              ></el-option>
            </el-select>
          </div>
        </div>

        <div class="col-lg-12 flex-end">
          <vs-button color="primary" class="edit_btn" @click="save()" type="filled">Save</vs-button>
          <vs-button color="warning" class="edit_btn" type="border" @click="active=false">Cancel</vs-button>
        </div>
      </div>
    </vs-prompt>
    <vs-prompt id="send_invoice" :title="subject.name" :active.sync="editactive">
      <div class="row">
        <div class="col-lg-12">
          <vs-input class="edit2_btns" label="Name*" v-model="subject.name" />
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Assigned Teacher</label>
            <el-select
              v-model="subject.employee_id"
              filterable
              default-first-option
              placeholder="Choose a teacher"
            >
              <el-option
                v-for="data in teachers"
                :key="data.teacher.id"
                :label="data.teacher.first_name+' '+data.teacher.last_name"
                :value="data.teacher.id"
              ></el-option>
            </el-select>
          </div>
          <div class="custom_input" style="margin-top:0px !important;">
            <label for class="vs-input--label" style="display:block;">Academic Year</label>
            <el-select
              v-model="subject.year_id"
              @change="changer_year_()"
              filterable
              default-first-option
              placeholder="Choose a classroom"
            >
              <el-option v-for="year in years" :key="year.id" :label="year.title" :value="year.id"></el-option>
            </el-select>
          </div>
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Classroom</label>
            <el-select
              v-model="subject.classroom_id"
              filterable
              default-first-option
              placeholder="Choose a classroom"
            >
              <el-option
                v-for="classroom in subject.classrooms"
                :key="classroom.id"
                :label="classroom.grade"
                :value="classroom.id"
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
        ></vs-button>Subjects
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
        <div class="custom_input ml-3" style="margin-top:0px !important;">
          <el-select
            v-model="classroom_id"
            @change="changer_classroom()"
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
                <th class="px-3 text-center py-2">Subject</th>
                <th class="px-3 text-center py-2">Classroom</th>
                <th class="px-3 text-center py-2">Teacher</th>
                <th class="px-3 text-center py-2">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr class="table_content" v-for="(subject,index) in subjects" v-bind:key="index">
                <td
                  style="border-top-left-radius: .5rem;border-bottom-left-radius: .5rem;"
                >{{parseInt(index)+1}}</td>
                <td>{{subject.name}}</td>
                <td>{{subject.classroom.grade}}</td>
                <td>{{subject.teacher.first_name+' '+subject.teacher.last_name}}</td>
                <td
                  class="flex-center"
                  style="border-top-right-radius: .5rem;border-bottom-right-radius: .5rem;"
                >
                  <vs-button
                    color="dark"
                    type="flat"
                    icon-pack="feather"
                    class="mr-1"
                    @click="editSubject(subject,index)"
                    icon="icon-edit"
                  ></vs-button>
                  <vs-button
                    color="dark"
                    type="flat"
                    @click="deleteSubject(subject.id)"
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
      classroom_id: 1,
      classroom: "Grade 1",
      subjects: [],
      data: [{}],
      year_id: 2,
      subject: {
        classroom_id: 0,
        classrooms: {}
      },
      classroom_data: [{}],
      classrooms: {},
      teachers: {},
      teachers_data: [{}],
      edit_index: 0,
      years: []
    };
  },
  created() {
    if (localStorage.getItem("current_year_id")) {
      this.year_id = parseInt(localStorage.getItem("current_year_id"));
      this.subject.year_id = this.year_id;
    }

    this.getYears();
    this.getTeachers();
  },
  mounted() {
    this.year_id = this.current_year_id;
  },
  methods: {
    deleteSubject(id) {
      axios
        .delete(`web/removeSubject/${id}`)
        .then(response => {
          this.getSubjects();
        })
        .catch(error => {});
    },
    add_new() {
      this.subject.classroom_id = this.classroom_id;
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
        .post(`web/updateSubject`, { data: this.subject })
        .then(response => {
          this.$message({
            message: "Successfully updated!",
            type: "success"
          });
          this.getSubjects();
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
    editSubject(data, index) {
      this.subject = data;
      this.subject.classroom_id = parseInt(this.subject.classroom_id);
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
        .post("/web/createSubject", {
          name: this.subject.name,
          classroom_id: this.subject.classroom_id,
          employee_id: this.subject.employee_id
        })
        .then(response => {
          this.$message({
            message: "Successfully added!",
            type: "success"
          });
          this.getSubjects();
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
    changer_classroom() {
      this.subjects = this.data.filter(data => {
        return parseInt(data.classroom_id) === this.classroom_id;
      });
      this.teachers = this.teachers_data.filter(data => {
        return data.classroom_id === this.classroom_id;
      });
    },
    getTeachers() {
      axios.get(`/web/AssignedTeachers`).then(response => {
        this.teachers_data = response.data;
      });
    },
    getClassrooms() {
      axios.get(`/web/classrooms`).then(response => {
        this.classroom_data = response.data;
        this.changer_year();
        this.changer_year_();
      });
    },
    getSubjects() {
      this.$vs.loading({
        container: "#permission_loading",
        background: "rgba(var(--vs-theme),0.4)",
        color: "primary",
        type: "border",
        scale: 0.6
      });
      axios
        .get(`web/subjects`)
        .then(response => {
          this.data = response.data;
          this.changer_classroom();
          this.$vs.loading.close("#permission_loading > .con-vs-loading");
        })
        .catch(error => {
          this.$vs.loading.close("#permission_loading > .con-vs-loading");
        });
    },
    changer_year() {
      this.classrooms = this.classroom_data.filter(data => {
        return parseInt(data.academic_year_id) === this.year_id;
      });
    },
    changer_year_() {
      this.subject.classrooms = this.classroom_data.filter(data => {
        return parseInt(data.academic_year_id) === this.subject.year_id;
      });
    },
    getYears() {
      axios
        .get(`web/years`)
        .then(response => {
          this.years = response.data;
          this.getClassrooms();
          this.getSubjects();
        })
        .catch(error => {});
    }
  }
};
</script>

