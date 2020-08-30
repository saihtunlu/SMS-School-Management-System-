<template>
  <div class="row" v-if="$gate.isEmployee_Read()">
    <!-- popups -->
    <Delete
      :active="delete_active"
      :type="Delete_type"
      :delete_id="delete_id"
      @success="successDelete"
      @error="delete_active = false"
      @cancel="delete_active=false"
    />
    <upload :active="profile" @close="profile = false" @saved_file="saved_file" />
    <vs-prompt class="edit_model" title="Editing General Information" :active.sync="general">
      <div class="row">
        <div class="col-lg-6">
          <div class="row">
            <div class="col-6">
              <vs-input
                class="edit2_btns"
                label="First Name*"
                v-model="general_data.first_name"
                required
              />
            </div>
            <div class="col-6">
              <vs-input class="edit2_btns" label="Last Name*" v-model="general_data.last_name" />
            </div>
          </div>

          <vs-input class="edit2_btns" label="Nick Name*" v-model="general_data.nick_name" />
          <vs-input type="email" class="edit2_btns" label="Email*" v-model="general_data.email" />
        </div>
        <div class="col-lg-6">
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Position</label>
            <el-select
              v-model="general_data.position"
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
          <!-- <div
            v-if="general_data.position === 'Teacher'"
            style="animation:open 0.3s;"
            class="custom_input"
          >
            <label for class="vs-input--label" style="display:block;">Assigned Classroom</label>
            <el-select
              v-model="general_data.classroom.grade"
              filterable
              allow-create
              default-first-option
              placeholder="Choose a classroom"
            >
              <el-option
                v-for="classroom in classrooms"
                :key="classroom.value"
                :label="classroom.grade"
                :value="classroom.grade"
              ></el-option>
            </el-select>
          </div>-->

          <vs-input class="edit2_btns" label="Mobile Phone" v-model="general_data.phone" />
        </div>
        <div class="col-lg-12 flex-end">
          <vs-button color="primary" class="edit_btn" @click="saveGeneral()" type="filled">Save</vs-button>
          <vs-button color="warning" class="edit_btn" type="border" @click="general=false">Cancel</vs-button>
        </div>
      </div>
    </vs-prompt>

    <vs-prompt
      class="edit_model"
      title="Editing Personal Information"
      @close="personal_data.employee_language=[];"
      :active.sync="personal"
    >
      <div class="row">
        <div class="col-lg-6">
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Birth Date*</label>
            <el-date-picker
              required
              v-model="personal_data.date_of_birth"
              type="date"
              placeholder="Pick a date"
              format="yyyy/MM/dd"
            ></el-date-picker>
          </div>
          <vs-input class="edit2_btns" label="NRC or Passport*" v-model="personal_data.NRC" />
        </div>
        <div class="col-lg-6">
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Languages*</label>
            <el-select
              v-model="personal_data.employee_language"
              multiple
              filterable
              allow-create
              default-first-option
              placeholder="Choose tags for your article"
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
              <vs-radio v-model="personal_data.gender" vs-value="male">Male</vs-radio>
              <vs-radio v-model="personal_data.gender" vs-value="female">Female</vs-radio>
              <vs-radio v-model="personal_data.gender" vs-value="other">Other</vs-radio>
            </div>
          </div>
        </div>
        <div class="col-lg-12 flex-end">
          <vs-button color="primary" class="edit_btn" @click="savePersonal()" type="filled">Save</vs-button>
          <vs-button
            color="warning"
            class="edit_btn"
            type="border"
            @click="personal=false; personal_data.employee_language=[]"
          >Cancel</vs-button>
        </div>
      </div>
    </vs-prompt>

    <vs-prompt class="edit_model" title="Editing Address" :active.sync="address">
      <div class="row">
        <div class="col-lg-6">
          <vs-input
            class="edit2_btns"
            label="Address Line 1*"
            v-model="address_data.street_line_1"
            required
          />

          <vs-input
            class="edit2_btns"
            label="Address Line 2*"
            v-model="address_data.street_line_2"
          />

          <vs-input
            class="edit2_btns"
            type="number"
            label="Postal Code*"
            v-model="address_data.postal_code"
          />
        </div>
        <div class="col-lg-6">
          <vs-input class="edit2_btns" label="Township*" v-model="address_data.township" />
          <vs-input class="edit2_btns" label="City*" v-model="address_data.city" />

          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Country</label>
            <el-select
              v-model="address_data.country"
              filterable
              allow-create
              default-first-option
              placeholder="Choose a bank"
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
        <div class="col-lg-12 flex-end">
          <vs-button color="primary" class="edit_btn" @click="saveAddress()" type="filled">Save</vs-button>
          <vs-button color="warning" class="edit_btn" type="border" @click="address=false">Cancel</vs-button>
        </div>
      </div>
    </vs-prompt>

    <vs-prompt
      class="edit_model"
      title="Editing Emergency Contact Information"
      :active.sync="emergency"
    >
      <div class="row">
        <div class="col-lg-12">
          <vs-input
            class="edit2_btns"
            label="Emergency Contact Person*"
            v-model="emergency_data.emergency_contact_person"
            required
          />
          <div class="custom_input">
            <label
              for
              class="vs-input--label"
              style="display:block;"
            >Emergency Contact Relationship*</label>
            <el-select
              v-model="emergency_data.emergency_contact_relationship"
              filterable
              allow-create
              default-first-option
              placeholder="Choose tags for your article"
            >
              <el-option
                v-for="relationship in relationships"
                :key="relationship.id"
                :label="relationship.name"
                :value="relationship.name"
              ></el-option>
            </el-select>
          </div>
          <vs-input
            class="edit2_btns"
            type="number"
            :min="0"
            label="Emergency Contact Person's Mobile*"
            v-model="emergency_data.emergency_contact_person_phone"
          />
        </div>

        <div class="col-lg-12 flex-end">
          <vs-button color="primary" class="edit_btn" @click="saveEmergency()" type="filled">Save</vs-button>
          <vs-button color="warning" class="edit_btn" type="border" @click="emergency=false">Cancel</vs-button>
        </div>
      </div>
    </vs-prompt>

    <vs-prompt class="edit_model" title="Editing Bank Information" :active.sync="bank">
      <div class="row">
        <div class="col-lg-6">
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Bank Name</label>
            <el-select
              v-model="bank_data.bank_name"
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
        </div>
        <div class="col-lg-6">
          <vs-input class="edit2_btns" label="Bank Account Number*" v-model="bank_data.bank_no" />
        </div>
        <div class="col-lg-12 flex-end">
          <vs-button color="primary" class="edit_btn" @click="saveBank()" type="filled">Save</vs-button>
          <vs-button color="warning" class="edit_btn" type="border" @click="bank=false">Cancel</vs-button>
        </div>
      </div>
    </vs-prompt>

    <vs-prompt class="edit_model" title="Editing Work History" :active.sync="workhistory">
      <div class="popup_content">
        <div class="row" v-for="(work_history,n) in work_history_data" v-bind:key="work_history.id">
          <div class="col-lg-6">
            <vs-input class="edit2_btns" label="Employer" v-model="work_history.employer" />
          </div>
          <div class="col-lg-6 flex-y-end">
            <div class="custom_input flex-between-center">
              <div class="custom_col mr-1">
                <label for class="vs-input--label" style="display:block;">Started Date</label>
                <el-date-picker
                  v-model="work_history.started_date"
                  type="month"
                  format="yyyy/MM/dd"
                ></el-date-picker>
              </div>
              <div class="custom_col ml-1">
                <label for class="vs-input--label" style="display:block;">Ended Date</label>
                <el-date-picker v-model="work_history.ended_date" type="month" format="yyyy/MM/dd"></el-date-picker>
              </div>
            </div>
            <vs-button
              size="20px"
              color="danger"
              icon="icon-minus"
              class="deleteWork"
              icon-pack="feather"
              @click="deleteWork(n)"
              type="flat"
              radius
            ></vs-button>
          </div>
          <vs-divider color="rgb(var(--vs-primary),0.2)" class="w-100" />
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12 flex-end">
          <vs-button
            color="primary"
            class="edit_btn"
            @click="addWorkHistory()"
            type="filled"
          >Add New</vs-button>
          <vs-button color="primary" class="edit_btn" @click="saveWorkHistory()" type="filled">Save</vs-button>
          <vs-button color="warning" class="edit_btn" type="border" @click="Clearworkhistory">Cancel</vs-button>
        </div>
      </div>
    </vs-prompt>
    <!-- breadcrumb -->
    <div class="flex-between col-12">
      <nav aria-label="breadcrumb" class="vs-breadcrumb vs-align-left">
        <ol class="vs-breadcrumb--ol">
          <h3 class="BC_header">Employee</h3>
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
            <a class="vs-breadcrumb--link">{{ employee.first_name + " " + employee.last_name }}</a>
          </li>
        </ol>
      </nav>
      <div>
        <vs-button
          v-if="!employee.user_id && $gate.isUser_Create()"
          class="add_new"
          icon-pack="feather"
          icon="icon-plus"
          color="primary"
          type="filled"
          :to="{name: 'Add_User', params: { data: employee ,type:'Employee'}}"
        >Add As User</vs-button>

        <vs-button
          v-if="!editing && $gate.isEmployee_Update()"
          @click="editing = true"
          class="setting_btn"
          icon-pack="feather"
          icon="icon-edit"
          color="primary"
          type="flat"
        >Edit</vs-button>

        <vs-button
          v-if="editing"
          @click="editing = false"
          class="setting_btn"
          color="success"
          type="flat"
          icon-pack="feather"
          icon="icon-check-circle"
        >Done</vs-button>
        <vs-button
          v-if="editing  && $gate.isEmployee_Delete()"
          @click="setDelete(employee.id)"
          class="setting_btn"
          color="danger"
          type="flat"
          icon-pack="feather"
          icon="icon-trash"
        >Delete</vs-button>
      </div>
    </div>
    <!--End of breadcrumb -->

    <!-- Contents -->
    <div class="col-lg-12 open padding mb-base div_shadow">
      <div class="row">
        <div class="col-lg-12 mb-2 flex-between">
          <h4 class="header-text pb-2">General</h4>
          <vs-icon
            class="pointer icon_btns pulse two-second animated infinite btn_primary"
            size="18px"
            v-if="editing"
            icon-pack="feather"
            icon="icon-edit"
            @click="open_edit_general()"
          ></vs-icon>
        </div>
        <div class="col-lg-2 col-sm-12">
          <vs-button
            v-if="editing"
            id="edit_profile"
            @click="profile=true"
            class="pulse two-second animated infinite"
            radius
            size="40px"
            color="primary"
            icon-pack="feather"
            icon="icon-camera"
            type="flat"
          ></vs-button>
          <el-image class="employee_profile_image" :preview-src-list="srcList" :src="url">
            <div slot="placeholder" class="image-slot">
              <i class="el-icon-loading" style="font-size:18px !important; color:#fff;"></i>
            </div>
            <div slot="error" class="image-slot">
              <i class="feather icon-image"></i>
            </div>
          </el-image>
        </div>
        <div class="col-lg-5 col-md-12 table_div col-sm-12">
          <table class="employee_table">
            <tr>
              <td class="detail_p">Fullname</td>
              <td>{{ employee.first_name +" " +employee.last_name}}</td>
            </tr>
            <tr>
              <td class="detail_p">Nick Name</td>
              <td>{{ employee.nick_name }}</td>
            </tr>
            <tr>
              <td class="detail_p">Email</td>
              <td>{{ employee.email }}</td>
            </tr>
          </table>
        </div>
        <div class="col-lg-5 col-md-12 table_div col-sm-12">
          <table class="employee_table">
            <tr>
              <td class="detail_p">Position</td>
              <td>
                <vs-chip
                  transparent
                  v-if="employee.position == 'Administrator'"
                  color="success"
                >{{ employee.position }}</vs-chip>
                <vs-chip
                  transparent
                  v-if="employee.position == 'Manager'"
                  color="primary"
                >{{ employee.position }}</vs-chip>
                <vs-chip
                  transparent
                  v-if="employee.position == 'Teacher'"
                  color="warning"
                >{{ employee.position }}</vs-chip>
              </td>
            </tr>

            <tr>
              <td class="detail_p">Mobile Phone</td>
              <td>{{ employee.phone }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <div class="col-lg-12">
      <div class="row row_inner_col">
        <div class="col-lg-6">
          <div class="padding mb-base fadeInUp div_shadow">
            <div class="flex-between">
              <h4 class="header-text flex-align-center">
                <vs-button
                  radius
                  size="40px"
                  color="primary"
                  icon-pack="feather"
                  icon="icon-user"
                  type="flat"
                ></vs-button>Personal Information
              </h4>
              <vs-icon
                class="pointer icon_btns pulse two-second animated infinite btn_primary"
                size="18px"
                v-if="editing"
                icon-pack="feather"
                icon="icon-edit"
                @click="setPersonal()"
              ></vs-icon>
            </div>

            <vs-divider class="mt-0" color="rgba(var(--vs-primary),0.2)" />
            <table class="personal_table employee_table">
              <tr>
                <td class="detail_p">Date Of Birth</td>
                <td>
                  {{
                  employee.date_of_birth
                  | moment("dddd, MMMM Do YYYY")
                  }}
                </td>
              </tr>

              <tr>
                <td class="detail_p">NRC Or Passport</td>
                <td>{{ employee.NRC }}</td>
              </tr>

              <tr v-for="(language, n) in employee.languages" v-bind:key="language.id">
                <td class="detail_p">
                  Language
                  <span v-if="employee.languages.length != 0">{{ parseInt(n) + 1 }}</span>
                </td>
                <td>{{ language.language.name }}</td>
              </tr>
              <tr>
                <td class="detail_p">Gender</td>
                <td>{{ employee.gender }}</td>
              </tr>
            </table>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="padding mb-base fadeInUp div_shadow">
            <div class="flex-between">
              <h4 class="header-text flex-align-center">
                <vs-button
                  radius
                  size="40px"
                  color="warning"
                  icon-pack="feather"
                  icon="icon-map-pin"
                  type="flat"
                ></vs-button>Address
              </h4>
              <vs-icon
                class="pointer icon_btns pulse two-second animated infinite btn_primary"
                size="18px"
                v-if="editing"
                icon-pack="feather"
                icon="icon-edit"
                @click="setAddress()"
              ></vs-icon>
            </div>
            <vs-divider class="mt-0" color="rgba(var(--vs-warning),0.2)" />

            <table class="personal_table employee_table">
              <tr>
                <td class="detail_p">Address Line 1</td>
                <td>{{ employee.address.street_line_1 }}</td>
              </tr>

              <tr>
                <td class="detail_p">Address Line 2</td>
                <td>{{ employee.address.street_line_2 }}</td>
              </tr>
              <tr>
                <td class="detail_p">Postal Code</td>
                <td>{{ employee.address.postal_code }}</td>
              </tr>
              <tr>
                <td class="detail_p">Township</td>
                <td>{{ employee.address.township }}</td>
              </tr>
              <tr>
                <td class="detail_p">City</td>
                <td>{{ employee.address.city }}</td>
              </tr>
              <tr>
                <td class="detail_p">Country</td>
                <td>{{ employee.address.country }}</td>
              </tr>
            </table>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="padding mb-base fadeInUp div_shadow">
            <div class="flex-between">
              <h4 class="header-text flex-align-center">
                <vs-button
                  radius
                  size="40px"
                  color="warning"
                  icon-pack="feather"
                  icon="icon-alert-triangle"
                  type="flat"
                ></vs-button>Emergency Contact Information
              </h4>

              <vs-icon
                class="pointer icon_btns pulse two-second animated infinite btn_primary"
                size="18px"
                v-if="editing"
                icon-pack="feather"
                icon="icon-edit"
                @click="setEmergency()"
              ></vs-icon>
            </div>
            <vs-divider class="mt-0" color="rgba(var(--vs-warning),0.2)" />

            <table class="emergency_table employee_table">
              <tr>
                <td class="detail_p">Emergency Contact Person</td>
                <td>{{ employee.emergency_contact_person }}</td>
              </tr>

              <tr>
                <td class="detail_p">Emergency Contact Relationship</td>
                <td>{{ employee.emergency_contact_relationship }}</td>
              </tr>
              <tr>
                <td class="detail_p">Emergency Contact Person's Mobile</td>
                <td>{{ employee.emergency_contact_person_phone }}</td>
              </tr>
            </table>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="padding mb-base fadeInUp div_shadow">
            <div class="flex-between">
              <h4 class="header-text flex-align-center">
                <vs-button
                  radius
                  size="40px"
                  color="success"
                  icon-pack="feather"
                  icon="icon-credit-card"
                  type="flat"
                ></vs-button>Account Information
              </h4>

              <vs-icon
                class="pointer icon_btns pulse two-second animated infinite btn_primary"
                size="18px"
                v-if="editing"
                icon-pack="feather"
                icon="icon-edit"
                @click="setBank()"
              ></vs-icon>
            </div>
            <vs-divider class="mt-0" color="rgba(var(--vs-success),0.2)" />

            <table class="personal_table employee_table">
              <tr>
                <td class="detail_p">Bank Name</td>
                <td>{{ employee.bank_name }}</td>
              </tr>

              <tr>
                <td class="detail_p">Account Number</td>
                <td>{{ employee.bank_no }}</td>
              </tr>
            </table>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="padding mb-base fadeInUp div_shadow">
            <div class="flex-between">
              <h4 class="header-text flex-align-center">
                <vs-button
                  radius
                  size="40px"
                  color="success"
                  icon-pack="feather"
                  icon="icon-user-check"
                  type="flat"
                ></vs-button>Work Histories
              </h4>
              <vs-icon
                class="pointer icon_btns pulse two-second animated infinite btn_primary"
                size="18px"
                v-if="editing"
                icon-pack="feather"
                icon="icon-edit"
                @click="setWork()"
              ></vs-icon>
            </div>
            <vs-divider class="mt-0" color="rgba(var(--vs-success),0.2)" />

            <vs-table search hoverFlat :data="employee.work_history" id="work_hostories">
              <template slot="thead">
                <vs-th sort-key="No">No.</vs-th>
                <vs-th sort-key="name">Employer</vs-th>
                <vs-th sort-key="started_date">Started Date</vs-th>
                <vs-th sort-key="ended_date">Ended Date</vs-th>
                <vs-th sort-key="year_employed">Year Employed</vs-th>
              </template>

              <template slot-scope="{data}">
                <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td :data="parseInt(indextr)+1">{{parseInt(indextr)+1}}</vs-td>
                  <vs-td :data="data[indextr].employer">{{data[indextr].employer}}</vs-td>
                  <vs-td :data="data[indextr].started_date">
                    <vs-chip color="success">{{data[indextr].started_date | moment('MMMM-YYYY')}}</vs-chip>
                  </vs-td>
                  <vs-td :data="data[indextr].ended_date">
                    <vs-chip color="warning">{{data[indextr].ended_date | moment('MMMM-YYYY')}}</vs-chip>
                  </vs-td>
                  <vs-td :data="data[indextr].year_employed">{{data[indextr].year_employed}}</vs-td>
                </vs-tr>
              </template>
            </vs-table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import upload from "../../components/upload";
import countries from "../../assets/Json/countries.json";
import Delete from "../../components/delete.vue";

export default {
  data() {
    return {
      Delete_type: "Employee",
      delete_active: false,
      delete_id: [],
      countries: countries,
      workhistory_calculated: [],
      workhistory: false,
      work_history_data: [{}],
      bank: false,
      bank_data: {
        bank_name: "",
        bank_no: ""
      },
      emergency: false,
      emergency_data: {
        emergency_contact_person: "",
        emergency_contact_relationship: "",
        emergency_contact_person_phone: ""
      },
      spouse: false,
      spouse_data: {
        full_name: "",
        NRC: "",
        email: "",
        phone: "",
        occupation: "",
        company: ""
      },
      father: false,
      father_data: {
        full_name: "",
        NRC: "",
        email: "",
        phone: "",
        occupation: "",
        company: ""
      },
      address: false,
      address_data: {
        street_line_1: "",
        street_line_1: "",
        township: "",
        postal_code: "",
        city: "",
        country: ""
      },
      personal: false,
      personal_data: {
        date_of_birth: "",
        NRC: "",
        gender: "",
        employee_language: []
      },
      profile: false,
      general: false,
      general_data: {
        first_name: "",
        last_name: "",
        nick_name: "",
        position: "",
        phone: "",
        classroom: {
          grade: null
        }
      },
      editing: false,
      errorMessage: "",
      employee: {
        address: {},
        father: {},
        spouse: {}
      },
      banks: {},
      file: "",
      url: null,
      roles: {},
      classrooms: {},
      srcList: [],
      languages: {},
      relationships: {}
    };
  },
  components: {
    upload,
    Delete
  },
  created() {
    var index = this.$route.meta.index;
    this.$emit("index", index);
  },
  mounted() {
    var id = this.$route.params.id;
    this.getEmployee(id);
  },
  methods: {
    successDelete() {
      this.$router.push({ name: "Employees" });
    },
    setDelete(id) {
      this.delete_id.push(id);
      this.delete_active = true;
    },
    addWorkHistory() {
      var array = {
        employer: "",
        started_date: "",
        ended_date: ""
      };
      this.work_history_data.push(array);
    },
    Clearworkhistory() {
      this.workhistory_calculated = [];
      this.workhistory = false;
    },
    deleteWork(data) {
      this.work_history_data.splice(data, 1);
    },
    saveWorkHistory() {
      this.workhistory = false;
      var array = {};
      this.work_history_data.forEach(data => {
        var started_date = data.started_date;
        var ended_date = data.ended_date;
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
        array.started_date = data.started_date;
        array.ended_date = data.ended_date;
        array.employer = data.employer;
        array.year_employed = year_employed;
        this.workhistory_calculated.push(array);
        array = {};
      });
      axios
        .post(`/web/updateEmployeeWork`, {
          data: this.workhistory_calculated,
          id: this.employee.id
        })
        .then(response => {
          this.$message({
            message: "Successfully upaded!",
            type: "success"
          });
          this.employee.work_history = this.workhistory_calculated;
          this.workhistory_calculated = [];
        })
        .catch(error => {
          this.$message({
            message: "Oops! There is an error while updating.",
            type: "danger"
          });
        });
    },
    setWork() {
      this.workhistory = true;
      this.work_history_data = this.employee.work_history;
      console.log("work_history_data: ", this.work_history_data);
    },
    saveBank() {
      this.bank = false;
      axios
        .post(`/web/updateEmployeeBank`, {
          data: this.bank_data,
          id: this.employee.id
        })
        .then(response => {
          this.$message({
            message: "Successfully upaded!",
            type: "success"
          });
          this.employee.bank_name = this.bank_data.bank_name;
          this.employee.bank_no = this.bank_data.bank_no;
        })
        .catch(error => {
          this.$message({
            message: "Oops! There is an error while updating.",
            type: "danger"
          });
        });
    },
    setBank() {
      this.bank = true;
      this.getBanks();
      this.bank_data.bank_name = this.employee.bank_name;
      this.bank_data.bank_no = this.employee.bank_no;
    },
    saveEmergency() {
      this.emergency = false;
      axios
        .post(`/web/updateEmployeeEmergency`, {
          data: this.emergency_data,
          id: this.employee.id
        })
        .then(response => {
          this.$message({
            message: "Successfully upaded!",
            type: "success"
          });
          this.employee.emergency_contact_person = this.emergency_data.emergency_contact_person;
          this.employee.emergency_contact_relationship = this.emergency_data.emergency_contact_relationship;
          this.employee.emergency_contact_person_phone = this.emergency_data.emergency_contact_person_phone;
        })
        .catch(error => {
          this.$message({
            message: "Oops! There is an error while updating.",
            type: "danger"
          });
        });
    },
    setEmergency() {
      this.emergency = true;
      this.getRelationships();
      this.emergency_data.emergency_contact_person = this.employee.emergency_contact_person;
      this.emergency_data.emergency_contact_relationship = this.employee.emergency_contact_relationship;
      this.emergency_data.emergency_contact_person_phone = this.employee.emergency_contact_person_phone;
    },
    setFather() {
      this.father = true;
      this.father_data.full_name = this.employee.father.full_name;
      this.father_data.NRC = this.employee.father.NRC;
      this.father_data.email = this.employee.father.email;
      this.father_data.phone = this.employee.father.phone;
      this.father_data.occupation = this.employee.father.occupation;
      this.father_data.company = this.employee.father.company;
    },
    saveFather() {
      this.father = false;
      axios
        .post(`/web/updateEmployeeFather`, {
          data: this.father_data,
          id: this.employee.father.id
        })
        .then(response => {
          this.$message({
            message: "Successfully upaded!",
            type: "success"
          });
          this.employee.father = this.father_data;
        })
        .catch(error => {
          this.$message({
            message: "Oops! There is an error while updating.",
            type: "danger"
          });
        });
    },
    setSpouse() {
      this.spouse = true;
      this.spouse_data.full_name = this.employee.spouse.full_name;
      this.spouse_data.NRC = this.employee.spouse.NRC;
      this.spouse_data.email = this.employee.spouse.email;
      this.spouse_data.phone = this.employee.spouse.phone;
      this.spouse_data.occupation = this.employee.spouse.occupation;
      this.spouse_data.company = this.employee.spouse.company;
    },
    saveSpouse() {
      this.spouse = false;
      axios
        .post(`/web/updateEmployeeSpouse`, {
          data: this.spouse_data,
          id: this.employee.spouse.id
        })
        .then(response => {
          this.$message({
            message: "Successfully upaded!",
            type: "success"
          });
          this.employee.spouse = this.spouse_data;
        })
        .catch(error => {
          this.$message({
            message: "Oops! There is an error while updating.",
            type: "danger"
          });
        });
    },
    saveAddress() {
      this.address = false;
      axios
        .post(`/web/updateEmployeeAddress`, {
          data: this.address_data,
          id: this.employee.id,
          address_id: this.employee.address_id
        })
        .then(response => {
          this.$message({
            message: "Successfully upaded!",
            type: "success"
          });
          this.employee.address.street_line_1 = this.address_data.street_line_1;
          this.employee.address.street_line_2 = this.address_data.street_line_2;
          this.employee.address.township = this.address_data.township;
          this.employee.address.postal_code = this.address_data.postal_code;
          this.employee.address.city = this.address_data.city;
          this.employee.address.country = this.address_data.country;
        })
        .catch(error => {
          this.$message({
            message: "Oops! There is an error while updating.",
            type: "danger"
          });
          this.personal_data.employee_language = [];
        });
    },
    setAddress() {
      this.address = true;
      this.address_data.street_line_1 = this.employee.address.street_line_1;
      this.address_data.street_line_2 = this.employee.address.street_line_2;
      this.address_data.township = this.employee.address.township;
      this.address_data.postal_code = this.employee.address.postal_code;
      this.address_data.city = this.employee.address.city;
      this.address_data.country = this.employee.address.country;
    },
    savePersonal() {
      this.personal = false;
      axios
        .post(`/web/updateEmployeePersonal`, {
          data: this.personal_data,
          id: this.employee.id
        })
        .then(response => {
          this.$message({
            message: "Successfully upaded!",
            type: "success"
          });
          this.employee.date_of_birth = this.personal_data.date_of_birth;
          this.employee.NRC = this.employee.NRC;
          this.employee.gender = this.personal_data.gender;
          this.employee.languages[0].language.name = this.personal_data.employee_language[0];
          this.employee.languages[1].language.name = this.personal_data.employee_language[1];

          this.personal_data.employee_language = [];
        })
        .catch(error => {
          this.$message({
            message: "Oops! There is an error while updating.",
            type: "danger"
          });
          this.personal_data.employee_language = [];
        });
    },
    setPersonal() {
      this.getLanguages();
      this.personal = true;
      this.personal_data.date_of_birth = this.employee.date_of_birth;
      this.personal_data.NRC = this.employee.NRC;
      this.personal_data.gender = this.employee.gender;
      var employee_languages = this.employee.languages;
      employee_languages.forEach(data => {
        var array = {};
        array = data.language.name;
        this.personal_data.employee_language.push(array);
        array = {};
      });
    },
    saved_file(data) {
      setTimeout(() => (this.profile = false), 500);
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
      formData.append("id", this.employee.id);

      axios
        .post("web/updateEmployeeProfile", formData, config)
        .then(response => {
          this.$message({
            message: "Successfully upaded!",
            type: "success"
          });
          this.file = data.data;
          this.url = data.url;
          this.srcList.push(this.url);
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
      this.general = false;

      axios
        .post(`/web/updateEmployeeGeneral`, {
          data: this.general_data,
          id: this.employee.id
        })
        .then(response => {
          this.$message({
            message: "Successfully upaded!",
            type: "success"
          });
          this.employee.first_name = this.general_data.first_name;
          this.employee.last_name = this.general_data.last_name;
          this.employee.nick_name = this.general_data.nick_name;
          this.employee.email = this.general_data.email;
          this.employee.phone = this.general_data.phone;
          this.employee.position = this.general_data.position;
        })
        .catch(error => {
          this.$message({
            message: "Oops! There is an error while updating.",
            type: "danger"
          });
        });
    },
    open_edit_general() {
      this.getRoles();
      this.getClassrooms();
      this.general = true;
      this.general_data.first_name = this.employee.first_name;
      this.general_data.last_name = this.employee.last_name;
      this.general_data.nick_name = this.employee.nick_name;
      this.general_data.email = this.employee.email;
      this.general_data.phone = this.employee.phone;
      this.general_data.position = this.employee.position;
      if (this.employee.position === "Teacher") {
        this.general_data.classroom.grade = this.employee.classroom.grade;
      } else {
        this.general_data.classroom.grade = "";
      }
    },
    remove(data) {
      this.add_work_histories.splice(data, 1);
    },
    add_work_history() {
      var employer = this.handler.employer;
      var year_employed = this.handler.year_employed;
      if (!employer) {
        this.employer_error = true;
        return false;
      }
      if (year_employed === null) {
        this.year_employed_error = true;
        return false;
      }
      this.add_work_histories.push(this.handler);
      this.handler = {};
    },
    getClassrooms() {
      axios.get(`/web/classrooms`).then(response => {
        this.classrooms = response.data;
      });
    },
    getRelationships() {
      axios.get(`/web/Relationship`).then(response => {
        this.relationships = response.data;
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
    getBanks() {
      axios.get(`/web/Bank`).then(response => {
        this.banks = response.data;
      });
    },
    getEmployee(id) {
      axios
        .get(`web/Employee_detail/${id}`)
        .then(response => {
          this.employee = response.data;
          this.url = this.employee.profile_image;
          console.log(" this.url: ", this.url);
          this.srcList.push(this.url);
          this.work_history = this.employee.work_history;
        })
        .catch(error => {});
    }
  }
};
</script>

