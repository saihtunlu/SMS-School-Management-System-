<template>
  <div class="row" v-if="$gate.isStudent_Create()">
    <!-- breadcrumb -->
    <nav aria-label="breadcrumb" class="vs-breadcrumb vs-align-left">
      <ol class="vs-breadcrumb--ol">
        <h3 class="BC_header">Add Student</h3>
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
          <router-link :to="{ name: 'Students' }">Students</router-link>
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
          <a href="#" class="vs-breadcrumb--link">Add</a>
        </li>
      </ol>
    </nav>
    <!--End of breadcrumb -->

    <div class="col-lg-12 padding open div_shadow">
      <form-wizard
        @on-complete="save"
        shape="circle"
        color="rgba(var(--vs-primary),1)"
        error-color="rgb(var(--vs-danger))"
      >
        <tab-content title="General" icon="feather icon-user" :before-change="validateFirstStep">
          <div class="con-tab-ejemplo">
            <div class="row">
              <vs-alert
                title="Validate Error!"
                :active.sync="first_error"
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
                  @click="upload('Student')"
                  v-if="mobile"
                  radius
                  size="40px"
                  color="primary"
                  icon-pack="feather"
                  icon="icon-camera"
                  type="filled"
                ></vs-button>
                <el-image
                  class="edit_profile_image"
                  :preview-src-list="student_srcList"
                  :src="student_url"
                >
                  <div slot="placeholder" class="image-slot">
                    <i class="el-icon-loading" style="font-size:18px !important; color:#fff;"></i>
                  </div>
                  <div slot="error" class="image-slot">
                    <i class="el-icon-picture-outline"></i>
                  </div>
                </el-image>
                <div class="edit1_btn_div" v-if="!mobile">
                  <p
                    class="text-lg"
                    v-if="student.first_name && student.last_name && student.nick_name"
                  >{{ student.first_name +" " +student.last_name}} ({{ student.nick_name }})</p>
                  <p
                    class="text-lg"
                    v-if="!student.first_name || !student.last_name || !student.nick_name"
                  >Enter student Name</p>
                  <vs-button color="primary" @click="upload('Student')" type="filled">Add Photo</vs-button>
                  <vs-button
                    color="danger"
                    @click="clear_file('Student')"
                    type="border"
                  >Remove Photo</vs-button>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-6">
                <div class="row">
                  <div class="col-6">
                    <vs-input
                      class="edit2_btns"
                      label="First Name*"
                      v-model="student.first_name"
                      required
                    />
                  </div>
                  <div class="col-6">
                    <vs-input class="edit2_btns" label="Last Name*" v-model="student.last_name" />
                  </div>
                </div>

                <vs-input class="edit2_btns" label="Nick Name*" v-model="student.nick_name" />
                <vs-input class="edit2_btns" label="NRC (Optional)" v-model="student.NRC" />
                <div class="custom_input">
                  <label for class="vs-input--label" style="display:block;">Birth Date*</label>
                  <el-date-picker
                    required
                    v-model="student.date_of_birth"
                    type="date"
                    placeholder="Pick a date"
                    format="yyyy/MM/dd"
                  ></el-date-picker>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="custom_input">
                  <label for class="vs-input--label" style="display:block;">Gender*</label>

                  <div class="gender">
                    <vs-radio v-model="student.gender" vs-value="male">Male</vs-radio>
                    <vs-radio v-model="student.gender" vs-value="female">Female</vs-radio>
                    <vs-radio v-model="student.gender" vs-value="other">Other</vs-radio>
                  </div>
                </div>
                <div class="custom_input">
                  <label for class="vs-input--label" style="display:block;">Academic Year</label>
                  <el-select
                    v-model="year_id"
                    @change="changer_year()"
                    filterable
                    default-first-option
                    placeholder="Choose a classroom"
                  >
                    <el-option
                      v-for="year in years"
                      :key="year.id"
                      :label="year.title"
                      :value="year.id"
                    ></el-option>
                  </el-select>
                </div>
                <div style="animation:open 0.3s;" class="custom_input">
                  <label for class="vs-input--label" style="display:block;">Classroom</label>
                  <el-select
                    v-model="student.grade"
                    filterable
                    allow-create
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
              </div>
            </div>
          </div>
        </tab-content>

        <tab-content
          title="Relationship"
          icon="feather icon-users"
          :before-change="validateSecStep"
        >
          <div class="con-tab-ejemplo">
            <div class="row">
              <vs-alert
                title="Validate Error!"
                :active.sync="sec_error"
                color="danger"
                icon-pack="feather"
                icon="icon-alert-circle"
                closable
                close-icon="icon-x"
              >
                <span>Please fill out all required fields!</span>
              </vs-alert>

              <div class="col-lg-6 edit_col">
                <div class="form_header">
                  <vs-button icon-pack="feather" type="flat" radius icon="icon-user"></vs-button>
                  <span class="text-md">Father Information</span>
                </div>
                <div class="row mt-base">
                  <div class="col-12 flex-y-end edit1">
                    <vs-button
                      class="profile_upload_btns openicon"
                      @click="upload('Father')"
                      v-if="mobile"
                      radius
                      size="40px"
                      color="primary"
                      icon-pack="feather"
                      icon="icon-camera"
                      type="filled"
                    ></vs-button>
                    <el-image
                      class="edit_profile_image"
                      :preview-src-list="father_srcList"
                      :src="father_url"
                    >
                      <div slot="placeholder" class="image-slot">
                        <i class="el-icon-loading" style="font-size:18px !important; color:#fff;"></i>
                      </div>
                      <div slot="error" class="image-slot">
                        <i class="el-icon-picture-outline"></i>
                      </div>
                    </el-image>
                    <div class="edit1_btn_div" id="father_upload_btn" v-if="!mobile">
                      <vs-button
                        color="primary"
                        style="margin-bottom:0px !important;"
                        @click="upload('Father')"
                        type="filled"
                      >Add Photo</vs-button>
                      <vs-button
                        color="danger"
                        style="margin-bottom:0px !important;"
                        @click="clear_file('Father')"
                        type="border"
                      >Remove Photo</vs-button>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-6">
                    <vs-input
                      class="edit2_btns"
                      label="First Name*"
                      v-model="student.father.first_name"
                      required
                    />
                  </div>
                  <div class="col-6">
                    <vs-input
                      class="edit2_btns"
                      label="Last Name*"
                      v-model="student.father.last_name"
                    />
                  </div>
                </div>
                <vs-input class="edit2_btns" label="NRC OR Passport" v-model="student.father.NRC" />
                <vs-input
                  min="0"
                  type="number"
                  class="edit2_btns"
                  label="Mobile Phone"
                  v-model="student.father.phone"
                />
                <vs-input
                  type="email"
                  class="edit2_btns"
                  label="Email"
                  v-model="student.father.email"
                />
                <vs-input
                  class="edit2_btns"
                  label="Occupation"
                  v-model="student.father.occupation"
                />
                <vs-input class="edit2_btns" label="Company" v-model="student.father.company" />
              </div>
              <div class="col-lg-6 edit_col">
                <div class="form_header">
                  <vs-button icon-pack="feather" type="flat" radius icon="icon-user"></vs-button>
                  <span class="text-md">Mother Information</span>
                </div>
                <div class="row mt-base">
                  <div class="col-12 flex-y-end edit1">
                    <vs-button
                      class="profile_upload_btns openicon"
                      @click="upload('Mother')"
                      v-if="mobile"
                      radius
                      size="40px"
                      color="primary"
                      icon-pack="feather"
                      icon="icon-camera"
                      type="filled"
                    ></vs-button>
                    <el-image
                      class="edit_profile_image"
                      :preview-src-list="mother_srcList"
                      :src="mother_url"
                    >
                      <div slot="placeholder" class="image-slot">
                        <i class="el-icon-loading" style="font-size:18px !important; color:#fff;"></i>
                      </div>
                      <div slot="error" class="image-slot">
                        <i class="el-icon-picture-outline"></i>
                      </div>
                    </el-image>
                    <div class="edit1_btn_div" v-if="!mobile" id="father_upload_btn">
                      <vs-button
                        color="primary"
                        style="margin-bottom:0px !important;"
                        @click="upload('Mother')"
                        type="filled"
                      >Add Avatar</vs-button>
                      <vs-button
                        color="danger"
                        style="margin-bottom:0px !important;"
                        @click="clear_file('Mother')"
                        type="border"
                      >Remove Avatar</vs-button>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6">
                    <vs-input
                      class="edit2_btns"
                      label="First Name*"
                      v-model="student.mother.first_name"
                      required
                    />
                  </div>
                  <div class="col-6">
                    <vs-input
                      class="edit2_btns"
                      label="Last Name*"
                      v-model="student.mother.last_name"
                    />
                  </div>
                </div>
                <vs-input class="edit2_btns" label="NRC OR Passport" v-model="student.mother.NRC" />
                <vs-input
                  min="0"
                  type="number"
                  class="edit2_btns"
                  label="Mobile Phone"
                  v-model="student.mother.phone"
                />
                <vs-input
                  type="email"
                  class="edit2_btns"
                  label="Email"
                  v-model="student.mother.email"
                />
                <vs-input
                  class="edit2_btns"
                  label="Occupation"
                  v-model="student.mother.occupation"
                />
                <vs-input class="edit2_btns" label="Company" v-model="student.mother.company" />
              </div>
            </div>
          </div>
        </tab-content>
        <tab-content
          title="Other Information"
          icon="feather icon-info"
          :before-change="validateThirdStep"
        >
          <div class="con-tab-ejemplo">
            <div class="row">
              <vs-alert
                title="Validate Error!"
                :active.sync="third_error"
                color="danger"
                icon-pack="feather"
                icon="icon-alert-circle"
                closable
                close-icon="icon-x"
              >
                <span>Please fill out all required fields!</span>
              </vs-alert>
              <div class="col-lg-6 edit_col">
                <div class="form_header">
                  <vs-button icon-pack="feather" type="flat" radius icon="icon-map-pin"></vs-button>
                  <span class="text-md">Address</span>
                </div>

                <vs-input
                  class="edit2_btns"
                  label="Address Line 1*"
                  v-model="student.address.street_line_1"
                />
                <vs-input
                  class="edit2_btns"
                  label="Address Line 2*"
                  v-model="student.address.street_line_2"
                />
                <vs-input
                  type="number"
                  min="0"
                  class="edit2_btns"
                  label="Postal Code*"
                  v-model="student.address.postal_code"
                />
                <vs-input class="edit2_btns" label="Township*" v-model="student.address.township" />
                <vs-input class="edit2_btns" label="City*" v-model="student.address.city" />
                <div class="custom_input">
                  <label for class="vs-input--label" style="display:block;">Country</label>
                  <el-select
                    v-model="student.address.country"
                    filterable
                    allow-create
                    default-first-option
                    placeholder="Choose a country"
                  >
                    <el-option
                      v-for="country in countries"
                      :key="country.name"
                      :label="country.name"
                      :value="country.name"
                    ></el-option>
                  </el-select>
                </div>
              </div>
              <div class="col-lg-6 edit_col">
                <div class="form_header">
                  <vs-button icon-pack="feather" type="flat" radius icon="icon-alert-triangle"></vs-button>
                  <span class="text-md">Emergency Contact</span>
                </div>

                <vs-input
                  class="edit2_btns"
                  label="Emergency Contact Person (Fullname)"
                  v-model="student.emergency_contact_person"
                />

                <vs-input
                  class="edit2_btns"
                  label="Emergency Contact Relationship"
                  v-model="student.emergency_contact_relationship"
                />
                <vs-input
                  class="edit2_btns mb-base"
                  label="Emergency Contact Person Mobile Phone"
                  v-model="student.emergency_contact_person_phone"
                />

                <div class="flex-between">
                  <div>
                    <vs-button icon-pack="feather" type="flat" radius icon="icon-user"></vs-button>
                    <span class="text-md">Previous Schools</span>
                  </div>
                  <vs-button
                    size="small"
                    color="primary"
                    icon-pack="feather"
                    icon="icon-plus"
                    type="filled"
                    @click="add_new_previous_school()"
                  >Add New</vs-button>
                </div>

                <div
                  class="work_histoies"
                  v-for="(previous_school,index) in previous_schools "
                  v-bind:key="previous_school.school"
                >
                  <i
                    class="close_icon vs-dialog-cancel"
                    style="color:rgb(var(--vs-danger));"
                    @click="remove(index)"
                  />
                  <vs-input
                    v-if="previous_schools[index].school===''"
                    class="edit2_btns"
                    label="School Name*"
                    v-model="school"
                    v-bind:key="index"
                    @change="change(index)"
                  />
                  <vs-input
                    v-if="previous_school.school!==''"
                    class="edit2_btns"
                    label="School Name*"
                    @click="changeEdit(previous_school.school,index)"
                    v-model="previous_school.school"
                  />
                  <div class="custom_input flex-between-center">
                    <div class="custom_col mr-1">
                      <label for class="vs-input--label" style="display:block;">Started Date</label>
                      <el-date-picker
                        v-model="previous_school.started_date"
                        type="month"
                        format="yyyy/MM/dd"
                      ></el-date-picker>
                    </div>
                    <div class="custom_col ml-1">
                      <label for class="vs-input--label" style="display:block;">Ended Date</label>
                      <el-date-picker
                        v-model="previous_school.ended_date"
                        type="month"
                        format="yyyy/MM/dd"
                      ></el-date-picker>
                    </div>
                  </div>
                </div>

                <!-- </slide-x-left-transition> -->
              </div>
            </div>
          </div>
        </tab-content>

        <el-button type="primary" class="wz_btn" slot="prev">Back</el-button>
        <el-button type="primary" class="wz_btn" slot="next">Next</el-button>
        <el-button type="primary" class="wz_btn" slot="finish">Add Student</el-button>
      </form-wizard>
    </div>

    <upload :active="active" @close="active = false" :type="type" @saved_file="saved_file" />
  </div>
</template>
<script>
import upload from "../../components/upload";
import countries from "../../assets/Json/countries.json";

export default {
  props: {
    mobile: false
  },
  data() {
    return {
      school: "",
      type: "",
      countries: countries,
      active: false,
      first_error: false,
      sec_error: false,
      third_error: false,
      school_error: false,
      year_attended_error: false,
      errorMessage: "",
      student: {
        address: {},
        father: {},
        mother: {}
      },
      years: {},
      year_id: null,
      banks: {},
      student_file: "",
      student_url: null,
      student_srcList: [],
      father_file: "",
      father_url: null,
      father_srcList: [],
      mother_file: "",
      mother_url: null,
      mother_srcList: [],
      classrooms: {},
      classrooms_data: [{}],
      grade: "",
      languages: {},
      previous_schools: [
        {
          school: "",
          started_date: "",
          ended_date: ""
        }
      ]
    };
  },
  watch: {},
  components: {
    upload
  },
  created() {
    if (localStorage.getItem("current_year_id")) {
      this.year_id = parseInt(localStorage.getItem("current_year_id"));
    }
    var index = this.$route.meta.index;
    this.$emit("index", index);
  },
  mounted() {
    this.getYears();
  },
  methods: {
    changer_year() {
      this.classrooms = this.classrooms_data.filter(data => {
        return parseInt(data.academic_year_id) === this.year_id;
      });
    },
    getYears() {
      axios
        .get(`web/years`)
        .then(response => {
          this.years = response.data;
          this.getClassrooms();
        })
        .catch(error => {});
    },
    upload(type) {
      this.active = true;
      this.type = type;
    },
    changeEdit(data, index) {
      this.school = data;
      this.previous_schools[index].school = "";
    },
    change(index) {
      this.previous_schools[index].school = this.school;
      this.school = "";
    },
    saved_file(data) {
      if (data.type === "Student") {
        this.student_file = "";
        this.student_url = null;
        this.student_srcList = [];
        this.student_file = data.data;
        this.student_url = data.url;
        this.student_srcList.push(this.student_url);
      }
      if (data.type === "Father") {
        this.father_file = "";
        this.father_url = null;
        this.father_srcList = [];
        this.father_file = data.data;
        this.father_url = data.url;
        this.father_srcList.push(this.father_url);
      }
      if (data.type === "Mother") {
        this.mother_file = "";
        this.mother_url = null;
        this.mother_srcList = [];
        this.mother_file = data.data;
        this.mother_url = data.url;
        this.mother_srcList.push(this.mother_url);
      }
      setTimeout(() => (this.active = false), 500);
    },
    validateFirstStep() {
      if (
        !this.student.first_name ||
        !this.student.last_name ||
        !this.student.nick_name ||
        !this.student_file ||
        !this.student.date_of_birth ||
        !this.student.grade ||
        !this.student.gender
      ) {
        this.first_error = true;
        return false;
      } else {
        this.first_error = false;
        return true;
      }
    },
    validateThirdStep() {
      if (
        this.previous_schools === [{}] ||
        !this.student.address.street_line_1 ||
        !this.student.address.street_line_2 ||
        !this.student.address.postal_code ||
        !this.student.address.city ||
        !this.student.address.township ||
        !this.student.address.country
      ) {
        this.third_error = true;
        return false;
      } else {
        this.third_error = false;
        return true;
      }
    },
    validateSecStep() {
      if (
        !this.father_file ||
        !this.student.father.first_name ||
        !this.student.father.last_name ||
        !this.student.father.phone ||
        !this.student.father.NRC ||
        !this.student.father.email ||
        !this.student.father.occupation ||
        !this.student.father.company ||
        !this.mother_file ||
        !this.student.mother.first_name ||
        !this.student.mother.last_name ||
        !this.student.mother.phone ||
        !this.student.mother.NRC ||
        !this.student.mother.email ||
        !this.student.mother.occupation ||
        !this.student.mother.company
      ) {
        this.sec_error = true;
        return false;
      } else {
        this.sec_error = false;
        return true;
      }
    },
    add_new_previous_school() {
      var array = {
        school: "",
        started_date: "",
        ended_date: "",
        year_attended: ""
      };
      this.previous_schools.push(array);
    },
    remove(data) {
      this.previous_schools.splice(data, 1);
    },
    save() {
      this.$vs.loading({
        background: "rgba(255,255,255,0.4)",
        color: "primary",
        type: "border",
        scale: 0.6
      });
      const config = {
        headers: { "content-type": "multipart/form-data" }
      };
      this.student.year_id = this.year_id;
      let formData = new FormData();
      formData.append("student", JSON.stringify(this.student));
      formData.append("student_profile_image", this.student_file);
      formData.append("father_profile_image", this.father_file);
      formData.append("mother_profile_image", this.mother_file);
      formData.append(
        "previous_schools",
        JSON.stringify(this.previous_schools)
      );
      axios
        .post("web/createStudent", formData, config)
        .then(response => {
          this.$vs.loading.close();
          this.$message({
            message: "Congratulation! You have successfully added an employee.",
            type: "success"
          });
        })
        .catch(function(error) {
          this.$vs.loading.close();
          this.$message({
            message: "Oops! There is an error while adding new employee.",
            type: "error"
          });
        });
    },
    getClassrooms() {
      axios.get(`/web/classrooms`).then(response => {
        this.classrooms_data = response.data;
        this.changer_year();
      });
    },
    clear_file(type) {
      if (type === "Student") {
        this.student_file = "";
        this.student_url = null;
        this.student_srcList = [];
      }
      if (type === "Father") {
        this.father_file = "";
        this.father_url = null;
        this.father_srcList = [];
      }
      if (type === "Mother") {
        this.mother_file = "";
        this.mother_url = null;
        this.mother_srcList = [];
      }
    }
  }
};
</script>
