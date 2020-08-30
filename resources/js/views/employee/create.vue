<template>
  <div class="row">
    <!-- breadcrumb -->
    <nav aria-label="breadcrumb" class="vs-breadcrumb vs-align-left">
      <ol class="vs-breadcrumb--ol">
        <h3 class="BC_header">Add Employee</h3>
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
          <router-link :to="{ name: 'Employees' }">Employees</router-link>
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

    <div class="col-lg-12 padding open div_shadow vs-con-loading__container" id="loading">
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
                  @click="active=true"
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
                <div class="edit1_btn_div" v-if=" !mobile">
                  <p
                    class="text-lg"
                    v-if="employee.first_name && employee.last_name && employee.nick_name"
                  >{{ employee.first_name +" " +employee.last_name}} ({{ employee.nick_name }})</p>
                  <p
                    class="text-lg"
                    v-if="!employee.first_name || !employee.last_name || !employee.nick_name"
                  >Enter Employee Name</p>
                  <vs-button color="primary" @click="active = true" type="filled">Add Photo</vs-button>
                  <vs-button color="danger" @click="clear_file" type="border">Remove Photo</vs-button>
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
                      v-model="employee.first_name"
                      required
                    />
                  </div>
                  <div class="col-6">
                    <vs-input class="edit2_btns" label="Last Name*" v-model="employee.last_name" />
                  </div>
                </div>

                <vs-input class="edit2_btns" label="Nick Name*" v-model="employee.nick_name" />
                <vs-input type="email" class="edit2_btns" label="Email*" v-model="employee.email" />
              </div>
              <div class="col-lg-6">
                <div class="custom_input">
                  <label for class="vs-input--label" style="display:block;">Position</label>
                  <el-select
                    v-model="employee.position"
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

                <div class="custom_input">
                  <label for class="vs-input--label" style="display:block;">Bank Name</label>
                  <el-select
                    v-model="employee.bank_name"
                    filterable
                    allow-create
                    default-first-option
                    placeholder="Choose a bank"
                  >
                    <el-option
                      v-for="bank in banks"
                      :key="bank.name"
                      :label="bank.name"
                      :value="bank.name"
                    ></el-option>
                  </el-select>
                </div>
                <vs-input
                  class="edit2_btns"
                  label="Bank Account Number"
                  v-model="employee.bank_no"
                />
              </div>
            </div>
          </div>
        </tab-content>
        <tab-content title="Infromation" icon="feather icon-info" :before-change="validateSecStep">
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
                  <span class="text-md">Personal Information</span>
                </div>
                <div class="custom_input">
                  <label for class="vs-input--label" style="display:block;">Birth Date*</label>
                  <el-date-picker
                    required
                    v-model="employee.date_of_birth"
                    type="date"
                    placeholder="Pick a date"
                    format="yyyy/MM/dd"
                  ></el-date-picker>
                </div>
                <vs-input class="edit2_btns" label="NRC OR Passport*" v-model="employee.NRC" />
                <vs-input
                  min="0"
                  type="number"
                  class="edit2_btns"
                  label="Mobile Phone*"
                  v-model="employee.phone"
                />
                <div class="custom_input">
                  <label for class="vs-input--label" style="display:block;">Languages*</label>
                  <el-select
                    v-model="employee_language"
                    multiple
                    filterable
                    allow-create
                    default-first-option
                    placeholder="Choose languages"
                  >
                    <el-option
                      v-for="language in languages"
                      :key="language.id"
                      :label="language.name"
                      :value="language.name"
                    ></el-option>
                  </el-select>
                </div>
                <div class="custom_input">
                  <label for class="vs-input--label" style="display:block;">Gender*</label>

                  <div class="gender">
                    <vs-radio v-model="employee.gender" vs-value="male">Male</vs-radio>
                    <vs-radio v-model="employee.gender" vs-value="female">Female</vs-radio>
                    <vs-radio v-model="employee.gender" vs-value="other">Other</vs-radio>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 edit_col">
                <div class="form_header">
                  <vs-button icon-pack="feather" type="flat" radius icon="icon-map-pin"></vs-button>
                  <span class="text-md">Address</span>
                </div>

                <vs-input
                  class="edit2_btns"
                  label="Address Line 1*"
                  v-model="employee.address.street_line_1"
                />
                <vs-input
                  class="edit2_btns"
                  label="Address Line 2*"
                  v-model="employee.address.street_line_2"
                />
                <vs-input
                  type="number"
                  min="0"
                  class="edit2_btns"
                  label="Postal Code*"
                  v-model="employee.address.postal_code"
                />
                <vs-input class="edit2_btns" label="Township*" v-model="employee.address.township" />
                <vs-input class="edit2_btns" label="City*" v-model="employee.address.city" />
                <div class="custom_input">
                  <label for class="vs-input--label" style="display:block;">Country</label>
                  <el-select
                    v-model="employee.address.country"
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
            </div>
          </div>
        </tab-content>
        <tab-content
          title="Emergency Contact"
          icon="feather icon-users"
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
              <div class="col-lg-12 edit_col">
                <div class="form_header">
                  <vs-button icon-pack="feather" type="flat" radius icon="icon-alert-triangle"></vs-button>
                  <span class="text-md">Emergency Contact</span>
                </div>

                <vs-input
                  class="edit2_btns"
                  label="Emergency Contact Person (Fullname)"
                  v-model="employee.emergency_contact_person"
                />

                <vs-input
                  class="edit2_btns"
                  label="Emergency Contact Relationship"
                  v-model="employee.emergency_contact_relationship"
                />
                <vs-input
                  class="edit2_btns"
                  label="Emergency Contact Person Mobile Phone"
                  v-model="employee.emergency_contact_person_phone"
                />
              </div>
            </div>
          </div>
        </tab-content>
        <tab-content
          title="Work Histories"
          icon="feather icon-tag"
          :before-change="validateFouthStep"
        >
          <div class="con-tab-ejemplo">
            <div class="row">
              <vs-alert
                title="Validate Error!"
                :active.sync="fouth_error"
                color="danger"
                icon-pack="feather"
                icon="icon-alert-circle"
                closable
                close-icon="icon-x"
              >
                <span>Please fill out all required fields!</span>
              </vs-alert>
              <div class="col-lg-12 edit_col">
                <div
                  class="flex-between"
                  style="border-bottom: 1px solid rgb(var(--vs-dark), 0.2);"
                >
                  <div class="form_header" style="border-bottom: 0px !important;">
                    <vs-button icon-pack="feather" type="flat" radius icon="icon-briefcase"></vs-button>
                    <span class="text-md">Work Histories</span>
                  </div>
                  <vs-button
                    size="small"
                    color="primary"
                    icon-pack="feather"
                    icon="icon-plus"
                    type="filled"
                    @click="addWorkHistory()"
                  >Add New Work History</vs-button>
                </div>

                <div
                  class="work_histoies"
                  v-for="(add_work_history,index) in add_work_histories "
                  v-bind:key="add_work_history.employer"
                >
                  <vs-icon
                    icon="icon-minus"
                    class="close_icon"
                    icon-pack="feather"
                    @click="remove(index)"
                  />

                  <vs-input
                    v-if="add_work_histories[index].employer===''"
                    class="edit2_btns"
                    label="Employer"
                    v-model="employer"
                    v-bind:key="index"
                    @change="change(index)"
                  />
                  <vs-input
                    v-if="add_work_history.employer!=''"
                    class="edit2_btns"
                    label="Employer"
                    @click="changeEdit(add_work_history.employer,index)"
                    v-model="add_work_history.employer"
                  />
                  <div class="custom_input flex-between-center">
                    <div class="custom_col mr-1">
                      <label for class="vs-input--label" style="display:block;">Started Date</label>
                      <el-date-picker
                        v-model="add_work_history.started_date"
                        type="month"
                        format="yyyy/MM/dd"
                      ></el-date-picker>
                    </div>
                    <div class="custom_col ml-1">
                      <label for class="vs-input--label" style="display:block;">Ended Date</label>
                      <el-date-picker
                        v-model="add_work_history.ended_date"
                        type="month"
                        format="yyyy/MM/dd"
                        @change="year_employed(index)"
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
        <el-button type="primary" class="wz_btn" slot="finish">Add Employee</el-button>
      </form-wizard>
    </div>

    <upload :active="active" @close="active = false" @saved_file="saved_file" />
  </div>
</template>
<script>
import upload from "../../components/upload";
import countries from "../../assets/Json/countries.json";

export default {
  props: {
    mobile: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      employer: "",
      countries: countries,
      active: false,
      first_error: false,
      sec_error: false,
      third_error: false,
      fouth_error: false,
      employer_error: false,
      year_employed_error: false,
      errorMessage: "",
      employee: {
        address: {},
        father: {},
        spouse: {}
      },
      banks: {},
      work_history: {},
      file: "",
      url: null,
      position: "",
      new_role: "",
      roles: {},
      classrooms: {},
      assigned_classroom: "",
      srcList: [],
      languages: {},
      employee_language: [],
      handler: {},
      add_work_histories: [
        {
          employer: "",
          started_date: "",
          ended_date: "",
          year_employed: ""
        }
      ]
    };
  },
  watch: {},
  components: {
    upload
  },
  created() {
    var index = this.$route.meta.index;
    this.$emit("index", index);
  },
  mounted() {
    this.getRoles();
    this.getClassrooms();
    this.getLanguages();
    this.getBanks();
  },
  methods: {
    changeEdit(data, index) {
      this.employer = data;
      this.add_work_histories[index].employer = "";
    },
    change(index) {
      this.add_work_histories[index].employer = this.employer;
      this.employer = "";
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
    validateFirstStep() {
      if (
        !this.employee.first_name ||
        !this.employee.last_name ||
        !this.employee.nick_name ||
        !this.employee.email ||
        !this.file
      ) {
        this.first_error = true;
        return false;
      } else {
        this.first_error = false;
        return true;
      }
    },
    validateSecStep() {
      if (
        !this.employee.date_of_birth ||
        !this.employee.phone ||
        !this.employee.NRC ||
        this.employee.employee_language == [] ||
        !this.employee.gender ||
        !this.employee.address.street_line_1 ||
        !this.employee.address.street_line_2 ||
        !this.employee.address.postal_code ||
        !this.employee.address.city ||
        !this.employee.address.township ||
        !this.employee.address.country
      ) {
        this.sec_error = true;
        return false;
      } else {
        this.sec_error = false;
        return true;
      }
    },
    validateThirdStep() {
      if (
        !this.employee.emergency_contact_person ||
        !this.employee.emergency_contact_relationship ||
        !this.employee.emergency_contact_person_phone
      ) {
        this.third_error = true;
        return false;
      } else {
        this.third_error = false;
        return true;
      }
    },
    addWorkHistory() {
      var array = {
        employer: "",
        started_date: "",
        ended_date: "",
        year_employed: ""
      };
      this.add_work_histories.push(array);
    },
    remove(data) {
      this.add_work_histories.splice(data, 1);
    },
    year_employed(index) {
      var started_date = this.add_work_histories[index].started_date;
      var ended_date = this.add_work_histories[index].ended_date;
      //calculate total year employeed
      var start = this.$moment(started_date);
      var end = this.$moment(ended_date);
      var result = end.diff(start, "years", true); //Get By year, eg. 1.75 => 1 year and 9 months
      var str = result.toString();
      var spl = str.split(".");
      var year = parseInt(spl[0]);
      var month_data = result - year;
      var month = (month_data * 12).toFixed(0);
      if (year != 0 && month != 0) {
        var year_employed = year + "Year(s) and " + month + " Months";
      }
      if (year != 0 && month == 0) {
        var year_employed = year + "Year(s)";
      }
      if (year === 0 && month != 0) {
        var year_employed = month + " Months";
      }
      this.add_work_histories[index].year_employed = year_employed;
    },
    validateFouthStep() {
      if (this.handler == []) {
        this.fouth_error = true;
        return false;
      } else {
        this.fouth_error = false;
        return true;
      }
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
      formData.append("employee", JSON.stringify(this.employee));
      formData.append("profile_image", this.file);
      formData.append(
        "work_histories",
        JSON.stringify(this.add_work_histories)
      );
      formData.append("language", this.employee_language);
      formData.append("classroom", this.assigned_classroom);
      axios
        .post("web/createEmployee", formData, config)
        .then(response => {
          this.$vs.loading.close("#loading > .con-vs-loading");
          this.$message({
            message: "Congratulation! You have successfully added an employee.",
            type: "success"
          });
        })
        .catch(function(error) {
          this.$vs.loading.close("#loading > .con-vs-loading");

          this.$message({
            message: "Oops! There is an error while adding new employee.",
            type: "danger"
          });
        });
    },
    getClassrooms() {
      axios.get(`/web/classrooms`).then(response => {
        this.classrooms = response.data;
      });
    },
    getLanguages() {
      axios.get(`/web/languages`).then(response => {
        this.languages = response.data;
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
    trigger() {
      this.$refs.fileInput.click();
    },
    onFileChangeEdit(e) {
      this.file = "";
      this.url = null;
      this.srcList = [];
      this.file = e.target.files[0];
      this.url = URL.createObjectURL(this.file);
      this.srcList.push(this.url);
    },
    getBanks() {
      axios.get(`/web/Bank`).then(response => {
        this.banks = response.data;
      });
    }
  }
};
</script>
<style>
.edit_profile_image {
  background: rgb(var(--vs-gray));
  width: 6rem !important;
  height: 6rem !important;
  border-radius: 0.25rem;
  align-items: center;
  display: flex !important;
  justify-content: center;
}
.edit_profile_image > img {
  object-fit: cover;
  width: 100%;
}
.col-12.edit1 {
  display: flex;
  align-items: center;
}
.edit1_btn_div {
  margin-left: 2rem !important;
}
.edit1_btn_div > p {
  margin-bottom: 0.5rem;
}
.edit1_btn_div > button,
.edit_btns {
  margin-right: 1rem !important;
  padding: 0.6rem 2rem !important;
  margin-bottom: 1rem !important;
}
.col-lg-12.footer_btn {
  display: flex;
  justify-content: flex-end;
}
</style>
