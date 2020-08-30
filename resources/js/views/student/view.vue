<template>
  <div class="row" id="student_view" v-if="$gate.isStudent_Read()">
    <!-- popups -->
    <Delete
      :active="delete_active"
      :type="Delete_type"
      :delete_id="delete_id"
      @success="successDelete"
      @error="delete_active = false"
      @cancel="delete_active=false"
    />

    <!-- breadcrumb -->
    <div class="flex-between col-12">
      <nav aria-label="breadcrumb" class="vs-breadcrumb vs-align-left">
        <ol class="vs-breadcrumb--ol">
          <h3 class="BC_header">Student</h3>
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
            <a class="vs-breadcrumb--link">{{ student.first_name + " " + student.last_name }}</a>
          </li>
        </ol>
      </nav>
    </div>
    <!--End of breadcrumb -->

    <!-- Contents -->
    <div class="col-lg-12 p-0 mb-base overflow_hide">
      <vs-tabs color="primary" id="student_tabs" :position="position">
        <vs-tab
          label="Personal"
          icon="icon-user"
          icon-pack="feather"
          class="div_shadow"
          style="box-shadow: 0px 4px 25px 0px rgba(var(--vs-dark), 0.02) !important;"
        >
          <div class="edit">
            <div class="row pt-3 mx-0 cover_bg">
              <div class="flex-between-center top_content">
                <h4 class="text-light flex-align-center">Student Information</h4>
                <div class="btns">
                  <vs-icon
                    class="pointer cover_icon_btns openicon"
                    size="18px"
                    v-if="edit_general"
                    icon-pack="feather"
                    icon="icon-check"
                    @click="saveGeneral()"
                  ></vs-icon>
                  <vs-icon
                    class="pointer cover_icon_btns openicon"
                    size="18px"
                    v-if="edit_general"
                    icon-pack="feather"
                    icon="icon-x"
                    @click="clearGeneral()"
                  ></vs-icon>
                  <vs-icon
                    class="pointer cover_icon_btns openicon"
                    size="18px"
                    v-if="!edit_general"
                    icon-pack="feather"
                    icon="icon-edit"
                    @click="setGeneral()"
                  ></vs-icon>
                  <vs-icon
                    class="pointer cover_icon_btns openicon"
                    size="18px"
                    icon-pack="feather"
                    v-if="$gate.isStudent_Delete()"
                    icon="icon-user-x"
                    @click="setDelete(student.id, 'Student')"
                  ></vs-icon>
                  <router-link
                    v-if="student.user_id===null && $gate.isUser_Create()"
                    :to="{name:'Add_User',params:{data:student,type:'Student'}}"
                  >
                    <vs-icon
                      class="pointer cover_icon_btns openicon"
                      size="18px"
                      icon-pack="feather"
                      icon="icon-user-plus"
                    ></vs-icon>
                  </router-link>
                </div>
              </div>
              <div class="col-12 flex-around px-0 pb-2">
                <div
                  class="circle_bg-bal circle_bg-bal_small"
                  style="position: relative;right: -30%;"
                ></div>
                <div
                  class="circle_bg-bal circle_bg-bal_md"
                  style="right: -30%;position: relative;bottom: -70px;"
                ></div>

                <div class="gradient-bg-figure" style="right: 30px;top: 80px;"></div>
                <div class="gradient-bg-figure" style="top: -150px;left: -90px;"></div>
                <div
                  class="circle_bg-bal circle_bg-bal_big"
                  style="position: relative;top: -150px; right:20%; "
                ></div>
              </div>
            </div>
            <div class="image_div col-lg-12 flex-align-center">
              <vs-button
                class="profile_upload_btns openicon"
                @click="upload('Student')"
                v-if="edit_general"
                radius
                size="40px"
                color="primary"
                icon-pack="feather"
                icon="icon-camera"
                type="filled"
              ></vs-button>
              <el-image
                class="employee_profile_image rounded-circle"
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
              <div class="edit1_btn_div" v-if="!edit_general">
                <p
                  class="text-lg text-white text-bold"
                >{{ student.first_name +" " +student.last_name}} ({{ student.nick_name }})</p>
              </div>
              <div class="edit1_btn_div" v-if="edit_general">
                <p
                  class="text-lg text-bold text-white"
                >{{ general_data.first_name +" " +general_data.last_name}} ({{ general_data.nick_name }})</p>
              </div>
            </div>

            <div class="row mx-0" v-if="!edit_general">
              <div class="col-lg-6 px-0">
                <div class="default_permissions_content mr-lg-2 mr-0">
                  <table class="personal_table employee_table">
                    <tr>
                      <td class="detail_p">Date Of Birth</td>
                      <td>
                        {{
                        student.date_of_birth
                        | moment("dddd, MMMM Do YYYY")
                        }}
                      </td>
                    </tr>

                    <tr v-if="student.NRC">
                      <td class="detail_p">NRC Or Passport</td>
                      <td>{{ student.NRC }}</td>
                    </tr>
                  </table>
                </div>
              </div>
              <div class="col-lg-6 px-0">
                <div class="default_permissions_content mt-3 mt-lg-0 ml-lg-2 ml-0">
                  <table class="personal_table employee_table">
                    <tr>
                      <td class="detail_p">Classroom</td>
                      <td>{{ student.classroom.grade }}</td>
                    </tr>
                    <tr>
                      <td class="detail_p">Gender</td>
                      <td>{{ student.gender }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            <div class="row open vs-con-loading__container" id="loading" v-if="edit_general">
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
                    <vs-input
                      class="edit2_btns"
                      label="Last Name*"
                      v-model="general_data.last_name"
                    />
                  </div>
                </div>

                <vs-input class="edit2_btns" label="Nick Name*" v-model="general_data.nick_name" />
                <vs-input class="edit2_btns" label="NRC (Optional)" v-model="general_data.NRC" />
              </div>
              <div class="col-lg-6">
                <div class="custom_input">
                  <label for class="vs-input--label" style="display:block;">Birth Date*</label>
                  <el-date-picker
                    required
                    v-model="general_data.date_of_birth"
                    type="date"
                    placeholder="Pick a date"
                    format="yyyy/MM/dd"
                  ></el-date-picker>
                </div>
                <div class="custom_input">
                  <label for class="vs-input--label" style="display:block;">Gender*</label>

                  <div class="gender">
                    <vs-radio v-model="general_data.gender" vs-value="male">Male</vs-radio>
                    <vs-radio v-model="general_data.gender" vs-value="female">Female</vs-radio>
                    <vs-radio v-model="general_data.gender" vs-value="other">Other</vs-radio>
                  </div>
                </div>
                <div class="custom_input">
                  <label for class="vs-input--label" style="display:block;">Grade</label>
                  <el-select
                    v-model="general_data.classroom.id"
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
              </div>
            </div>
          </div>
        </vs-tab>
        <vs-tab
          label="Father"
          icon="icon-user"
          class="div_shadow"
          style="box-shadow: 0px 4px 25px 0px rgba(var(--vs-dark), 0.02) !important;"
          icon-pack="feather"
        >
          <div class="row pt-3 mx-0 cover_bg">
            <div class="flex-between-center top_content">
              <h4 class="text-light flex-align-center">Father Information</h4>
              <div class="btns">
                <vs-icon
                  class="pointer cover_icon_btns openicon"
                  size="18px"
                  v-if="edit_father"
                  icon-pack="feather"
                  icon="icon-check"
                  @click="saveFather()"
                ></vs-icon>
                <vs-icon
                  class="pointer cover_icon_btns openicon"
                  size="18px"
                  v-if="edit_father"
                  icon-pack="feather"
                  icon="icon-x"
                  @click="clearFather()"
                ></vs-icon>
                <vs-icon
                  class="pointer cover_icon_btns openicon"
                  size="18px"
                  v-if="!edit_father && $gate.isStudent_Update()"
                  icon-pack="feather"
                  icon="icon-edit"
                  @click="setFather()"
                ></vs-icon>

                <router-link
                  v-if="student.father.user_id===null && $gate.isUser_Create()"
                  :to="{name:'Add_User',params:{data:student.father,type:'Parent',father:true,mother:false}}"
                >
                  <vs-icon
                    class="pointer cover_icon_btns openicon"
                    size="18px"
                    icon-pack="feather"
                    icon="icon-user-plus"
                  ></vs-icon>
                </router-link>
              </div>
            </div>
            <div class="col-12 flex-around px-0 pb-2">
              <div
                class="circle_bg-bal circle_bg-bal_small"
                style="position: relative;right: -30%;"
              ></div>
              <div
                class="circle_bg-bal circle_bg-bal_md"
                style="right: -30%;position: relative;bottom: -70px;"
              ></div>

              <div class="gradient-bg-figure" style="right: 30px;top: 80px;"></div>
              <div class="gradient-bg-figure" style="top: -150px;left: -90px;"></div>
              <div
                class="circle_bg-bal circle_bg-bal_big"
                style="position: relative;top: -150px; right:20%; "
              ></div>
            </div>
          </div>
          <div class="image_div col-lg-12 flex-align-center">
            <vs-button
              class="profile_upload_btns openicon"
              @click="upload('Father')"
              v-if="edit_father"
              radius
              size="40px"
              color="primary"
              icon-pack="feather"
              icon="icon-camera"
              type="filled"
            ></vs-button>
            <el-image
              class="employee_profile_image rounded-circle"
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
            <div class="edit1_btn_div" v-if="!edit_father">
              <p
                class="text-lg text-white text-bold"
              >{{ student.father.first_name +" " +student.father.last_name}}</p>
            </div>
            <div class="edit1_btn_div" v-if="edit_general">
              <p
                class="text-lg text-bold text-white"
              >{{ father_data.first_name +" " +father_data.last_name}}</p>
            </div>
          </div>
          <div class="edit">
            <div class="row mx-0" v-if="!edit_father">
              <div class="col-lg-6 px-0">
                <div class="default_permissions_content mr-lg-2 mr-0">
                  <table class="personal_table employee_table">
                    <tr>
                      <td class="detail_p">NRC Or Passport</td>
                      <td>{{ student.father.NRC }}</td>
                    </tr>
                    <tr>
                      <td class="detail_p">Email Address</td>
                      <td>{{ student.father.email }}</td>
                    </tr>
                    <tr>
                      <td class="detail_p">Mobile Phone</td>
                      <td>{{ student.father.phone }}</td>
                    </tr>
                  </table>
                </div>
              </div>
              <div class="col-lg-6 px-0">
                <div class="default_permissions_content ml-lg-2 mt-3 mt-lg-0 ml-0">
                  <table class="personal_table employee_table">
                    <tr>
                      <td class="detail_p">Occupation</td>
                      <td>{{ student.father.occupation }}</td>
                    </tr>
                    <tr>
                      <td class="detail_p">Company</td>
                      <td>{{ student.father.company }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            <div class="row open vs-con-loading__container" v-if="edit_father" id="loading2">
              <div class="col-lg-6">
                <div class="row">
                  <div class="col-6">
                    <vs-input
                      class="edit2_btns"
                      label="First Name*"
                      v-model="father_data.first_name"
                      required
                    />
                  </div>
                  <div class="col-6">
                    <vs-input
                      class="edit2_btns"
                      label="Last Name*"
                      v-model="father_data.last_name"
                    />
                  </div>
                </div>
                <vs-input class="edit2_btns" label="NRC OR Passport" v-model="father_data.NRC" />
                <vs-input
                  min="0"
                  type="number"
                  class="edit2_btns"
                  label="Mobile Phone"
                  v-model="father_data.phone"
                />
              </div>
              <div class="col-lg-6">
                <vs-input
                  type="email"
                  class="edit2_btns"
                  label="Email"
                  v-model="father_data.email"
                />
                <vs-input class="edit2_btns" label="Occupation" v-model="father_data.occupation" />
                <vs-input class="edit2_btns" label="Company" v-model="father_data.company" />
              </div>
            </div>
          </div>
        </vs-tab>
        <vs-tab
          label="Mother"
          icon="icon-user"
          class="div_shadow"
          style="box-shadow: 0px 4px 25px 0px rgba(var(--vs-dark), 0.02) !important;"
          icon-pack="feather"
        >
          <div class="row pt-3 mx-0 cover_bg">
            <div class="flex-between-center top_content">
              <h4 class="text-light flex-align-center">Mother Information</h4>
              <div class="btns">
                <vs-icon
                  class="pointer cover_icon_btns openicon"
                  size="18px"
                  v-if="edit_mother"
                  icon-pack="feather"
                  icon="icon-check"
                  @click="saveMother()"
                ></vs-icon>
                <vs-icon
                  class="pointer cover_icon_btns openicon"
                  size="18px"
                  v-if="edit_mother"
                  icon-pack="feather"
                  icon="icon-x"
                  @click="clearMother()"
                ></vs-icon>
                <vs-icon
                  class="pointer cover_icon_btns openicon"
                  size="18px"
                  v-if="!edit_mother && $gate.isStudent_Update()"
                  icon-pack="feather"
                  icon="icon-edit"
                  @click="setMother()"
                ></vs-icon>

                <router-link
                  v-if="student.mother.user_id===null && $gate.isUser_Create()"
                  :to="{name:'Add_User',params:{data:student.mother,type:'Parent',father:false,mother:true}}"
                >
                  <vs-icon
                    class="pointer cover_icon_btns openicon"
                    size="18px"
                    icon-pack="feather"
                    icon="icon-user-plus"
                  ></vs-icon>
                </router-link>
              </div>
            </div>
            <div class="col-12 flex-around px-0 pb-2">
              <div
                class="circle_bg-bal circle_bg-bal_small"
                style="position: relative;right: -30%;"
              ></div>
              <div
                class="circle_bg-bal circle_bg-bal_md"
                style="right: -30%;position: relative;bottom: -70px;"
              ></div>

              <div class="gradient-bg-figure" style="right: 30px;top: 80px;"></div>
              <div class="gradient-bg-figure" style="top: -150px;left: -90px;"></div>
              <div
                class="circle_bg-bal circle_bg-bal_big"
                style="position: relative;top: -150px; right:20%; "
              ></div>
            </div>
          </div>
          <div class="image_div col-lg-12 flex-align-center">
            <vs-button
              class="profile_upload_btns openicon"
              @click="upload('Mother')"
              v-if="edit_mother"
              radius
              size="40px"
              color="primary"
              icon-pack="feather"
              icon="icon-camera"
              type="filled"
            ></vs-button>
            <el-image
              class="employee_profile_image rounded-circle"
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
            <div class="edit1_btn_div" v-if="!edit_mother">
              <p
                class="text-lg text-white text-bold"
              >{{ student.mother.first_name +" " +student.mother.last_name}}</p>
            </div>
            <div class="edit1_btn_div" v-if="edit_general">
              <p
                class="text-lg text-bold text-white"
              >{{ mother_data.first_name +" " +mother_data.last_name}}</p>
            </div>
          </div>
          <div class="edit">
            <div class="row mx-0" v-if="!edit_mother">
              <div class="col-lg-6 px-0">
                <div class="default_permissions_content mr-lg-2 mr-0">
                  <table class="personal_table employee_table">
                    <tr>
                      <td class="detail_p">NRC Or Passport</td>
                      <td>{{ student.mother.NRC }}</td>
                    </tr>
                    <tr>
                      <td class="detail_p">Email Address</td>
                      <td>{{ student.mother.email }}</td>
                    </tr>
                    <tr>
                      <td class="detail_p">Mobile Phone</td>
                      <td>{{ student.mother.phone }}</td>
                    </tr>
                  </table>
                </div>
              </div>
              <div class="col-lg-6 px-0">
                <div class="default_permissions_content ml-lg-2 mt-3 mt-lg-0 ml-0">
                  <table class="personal_table employee_table">
                    <tr>
                      <td class="detail_p">Occupation</td>
                      <td>{{ student.mother.occupation }}</td>
                    </tr>
                    <tr>
                      <td class="detail_p">Company</td>
                      <td>{{ student.mother.company }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            <div class="row open edit vs-con-loading__container" id="loading3" v-if="edit_mother">
              <div class="col-lg-6">
                <div class="row">
                  <div class="col-6">
                    <vs-input
                      class="edit2_btns"
                      label="First Name*"
                      v-model="mother_data.first_name"
                      required
                    />
                  </div>
                  <div class="col-6">
                    <vs-input
                      class="edit2_btns"
                      label="Last Name*"
                      v-model="mother_data.last_name"
                    />
                  </div>
                </div>
                <vs-input class="edit2_btns" label="NRC OR Passport" v-model="student.mother.NRC" />
                <vs-input
                  min="0"
                  type="number"
                  class="edit2_btns"
                  label="Mobile Phone"
                  v-model="mother_data.phone"
                />
              </div>
              <div class="col-lg-6">
                <vs-input
                  type="email"
                  class="edit2_btns"
                  label="Email"
                  v-model="mother_data.email"
                />
                <vs-input class="edit2_btns" label="Occupation" v-model="mother_data.occupation" />
                <vs-input class="edit2_btns" label="Company" v-model="mother_data.company" />
              </div>
            </div>
          </div>
        </vs-tab>
        <vs-tab
          label="Other Information"
          icon="icon-info"
          class="div_shadow"
          style="box-shadow: 0px 4px 25px 0px rgba(var(--vs-dark), 0.02) !important;"
          icon-pack="feather"
        >
          <!-- Address Section  -->
          <div class="flex-between">
            <h4 class="header-text flex-align-center">
              <vs-button
                radius
                size="40px"
                color="primary"
                icon-pack="feather"
                icon="icon-map-pin"
                type="flat"
              ></vs-button>Address
            </h4>
            <div class="btns">
              <vs-icon
                class="pointer icon_btns openicon btn_primary"
                size="18px"
                v-if="edit_address"
                icon-pack="feather"
                icon="icon-check"
                @click="saveAddress()"
              ></vs-icon>
              <vs-icon
                class="pointer icon_btns openicon btn_primary"
                size="18px"
                v-if="edit_address"
                icon-pack="feather"
                icon="icon-x"
                @click="clearAddress()"
              ></vs-icon>
              <vs-icon
                class="pointer icon_btns openicon btn_primary"
                size="18px"
                v-if="!edit_address && $gate.isStudent_Update()"
                icon-pack="feather"
                icon="icon-edit"
                @click="setAddress()"
              ></vs-icon>
            </div>
          </div>
          <vs-divider class="mt-0" color="rgba(var(--vs-primary),0.2)" />
          <div class="edit" v-if="!edit_address">
            <div class="row mx-0">
              <div class="col-lg-6 px-0">
                <div class="default_permissions_content mr-lg-2 mr-0">
                  <table class="personal_table employee_table">
                    <tr>
                      <td class="detail_p">Street 1</td>
                      <td>{{ student.address.street_line_1 }}</td>
                    </tr>
                    <tr>
                      <td class="detail_p">Street 2</td>
                      <td>{{ student.address.street_line_2 }}</td>
                    </tr>
                    <tr>
                      <td class="detail_p">Postal Code</td>
                      <td>{{ student.address.postal_code }}</td>
                    </tr>
                  </table>
                </div>
              </div>
              <div class="col-lg-6 px-0">
                <div class="default_permissions_content ml-lg-2 mt-3 mt-lg-0 ml-0">
                  <table class="personal_table employee_table">
                    <tr>
                      <td class="detail_p">Township</td>
                      <td>{{ student.address.township }}</td>
                    </tr>
                    <tr>
                      <td class="detail_p">City</td>
                      <td>{{ student.address.city }}</td>
                    </tr>
                    <tr>
                      <td class="detail_p">country</td>
                      <td>{{ student.address.country }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>

            <div class="row mx-0">
              <div class="col-lg-12 px-0"></div>
            </div>
          </div>
          <div
            class="edit vs-con-loading__container"
            v-if="edit_address"
            id="loading4"
            style="overflow: visible;"
          >
            <div class="row open">
              <div class="col-lg-6">
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
              </div>
              <div class="col-lg-6">
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
            </div>
          </div>
          <!-- End Of Address Section  -->

          <!-- Emergency Section  -->
          <div class="flex-between mt-base">
            <h4 class="header-text flex-align-center">
              <vs-button
                radius
                size="40px"
                color="primary"
                icon-pack="feather"
                icon="icon-alert-triangle"
                type="flat"
              ></vs-button>Emergency Contact Information
            </h4>
            <div class="btns">
              <vs-icon
                class="pointer icon_btns openicon btn_primary"
                size="18px"
                v-if="edit_emergency"
                icon-pack="feather"
                icon="icon-check"
                @click="saveEmergency()"
              ></vs-icon>
              <vs-icon
                class="pointer icon_btns openicon btn_primary"
                size="18px"
                v-if="edit_emergency"
                icon-pack="feather"
                icon="icon-x"
                @click="clearEmergency()"
              ></vs-icon>
              <vs-icon
                class="pointer icon_btns openicon btn_primary"
                size="18px"
                v-if="!edit_emergency && $gate.isStudent_Update()"
                icon-pack="feather"
                icon="icon-edit"
                @click="setEmergency()"
              ></vs-icon>
            </div>
          </div>
          <vs-divider class="mt-0" color="rgba(var(--vs-primary),0.2)" />
          <div class="edit emergency_student" v-if="!edit_emergency">
            <div class="row mx-0">
              <div class="col-lg-12 px-0">
                <div class="default_permissions_content mr-2">
                  <table class="personal_table employee_table">
                    <tr>
                      <td class="detail_p">Emergency Contact Person</td>
                      <td>{{ student.emergency_contact_person }}</td>
                    </tr>
                    <tr>
                      <td class="detail_p">Emergency Contact Relationship</td>
                      <td>{{ student.emergency_contact_relationship }}</td>
                    </tr>
                    <tr>
                      <td class="detail_p">Emergency Contact Person's Mobile Phone</td>
                      <td>{{ student.emergency_contact_person_phone }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>

            <div class="row mx-0">
              <div class="col-lg-12 px-0"></div>
            </div>
          </div>
          <div
            class="edit vs-con-loading__container"
            v-if="edit_emergency"
            id="loading5"
            style="overflow: visible;"
          >
            <div class="row open">
              <div class="col-lg-12">
                <vs-input
                  class="edit2_btns"
                  label="Emergency Contact Person*"
                  v-model="emergency_data.emergency_contact_person"
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
                  type="number"
                  min="0"
                  class="edit2_btns"
                  label="Emergency Contact Person's Mobile Phone*"
                  v-model="emergency_data.emergency_contact_person_phone"
                />
              </div>
            </div>
          </div>
          <!-- End Of Emergency Section  -->

          <!-- Previous Section  -->
          <div class="flex-between mt-base">
            <h4 class="header-text flex-align-center">
              <vs-button
                radius
                size="40px"
                color="primary"
                icon-pack="el-icon"
                icon="el-icon-school"
                type="flat"
              ></vs-button>Previous Schools
            </h4>
            <div class="btns">
              <vs-icon
                class="pointer icon_btns openicon btn_primary"
                size="18px"
                v-if="edit_PS"
                icon-pack="feather"
                icon="icon-check"
                @click="savePS()"
              ></vs-icon>
              <vs-icon
                class="pointer icon_btns openicon btn_primary"
                size="18px"
                v-if="edit_PS"
                icon-pack="feather"
                icon="icon-x"
                @click="clearPS()"
              ></vs-icon>
              <vs-icon
                class="pointer icon_btns openicon btn_primary"
                size="18px"
                v-if="edit_PS"
                icon-pack="feather"
                icon="icon-plus"
                @click="addPS()"
              ></vs-icon>
              <vs-icon
                class="pointer icon_btns openicon btn_primary"
                size="18px"
                v-if="!edit_PS && $gate.isStudent_Update()"
                icon-pack="feather"
                icon="icon-edit"
                @click="setPS()"
              ></vs-icon>
            </div>
          </div>
          <vs-divider class="mt-0" color="rgba(var(--vs-primary),0.2)" />
          <div class="edit" v-if="!edit_PS">
            <div class="row mx-0">
              <div class="col-lg-12 px-0">
                <vs-table search hoverFlat :data="student.previous_schools" id="work_hostories">
                  <template slot="thead">
                    <vs-th sort-key="No">No.</vs-th>
                    <vs-th sort-key="name">School</vs-th>
                    <vs-th sort-key="started_date">Started Date</vs-th>
                    <vs-th sort-key="ended_date">Ended Date</vs-th>
                  </template>

                  <template slot-scope="{data}">
                    <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                      <vs-td :data="parseInt(indextr)+1">{{parseInt(indextr)+1}}</vs-td>
                      <vs-td :data="data[indextr].name">{{data[indextr].name}}</vs-td>
                      <vs-td :data="data[indextr].started_date">
                        <vs-chip
                          color="success"
                        >{{data[indextr].started_date | moment('MMMM-YYYY')}}</vs-chip>
                      </vs-td>
                      <vs-td :data="data[indextr].ended_date">
                        <vs-chip color="warning">{{data[indextr].ended_date | moment('MMMM-YYYY')}}</vs-chip>
                      </vs-td>
                    </vs-tr>
                  </template>
                </vs-table>
              </div>
            </div>

            <div class="row mx-0">
              <div class="col-lg-12 px-0"></div>
            </div>
          </div>
          <div
            class="edit vs-con-loading__container"
            v-if="edit_PS"
            id="loading6"
            style="overflow: visible;"
          >
            <div class="row open">
              <div class="col-lg-12">
                <div
                  class="row mx-0 open"
                  v-for="(previous_school,n) in previous_schools_data"
                  v-bind:key="previous_school.id"
                >
                  <div class="col-lg-6">
                    <vs-input
                      class="edit2_btns"
                      label="School Name"
                      v-model="previous_school.name"
                    />
                  </div>
                  <div class="col-lg-6 flex-y-end">
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
                    <vs-button
                      size="20px"
                      color="danger"
                      icon="icon-minus"
                      class="deleteWork"
                      icon-pack="feather"
                      @click="deletePS(n)"
                      type="flat"
                      radius
                    ></vs-button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- End Of Address Section  -->
        </vs-tab>
      </vs-tabs>
    </div>
    <upload :active="active" @close="active = false" :type="type" @saved_file="saved_file" />
  </div>
</template>

<script>
import upload from "../../components/upload";
import countries from "../../assets/Json/countries.json";
import Delete from "../../components/delete.vue";

export default {
  props: {
    mobile: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      position: "top",
      active: false,
      colorx: "primary",
      Delete_type: "Student",
      delete_active: false,
      delete_id: [],
      countries: countries,
      edit_PS: false,
      previous_schools_data: [{}],
      edit_emergency: false,
      emergency_data: {
        emergency_contact_person: "",
        emergency_contact_relationship: "",
        emergency_contact_person_phone: ""
      },
      edit_mother: false,
      mother_data: {
        first_name: "",
        last_name: "",
        id: "",
        NRC: "",
        email: "",
        phone: "",
        occupation: "",
        company: ""
      },
      edit_father: false,
      father_data: {
        first_name: "",
        last_name: "",
        id: "",
        NRC: "",
        email: "",
        phone: "",
        occupation: "",
        company: ""
      },
      edit_address: false,
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
        medical_history: "",
        gender: "",
        employee_language: []
      },
      profile: false,
      edit_general: false,
      general_data: {
        first_name: "",
        last_name: "",
        nick_name: "",
        phone: "",
        classroom: {
          id: null
        },
        gender: "",
        date_of_birth: ""
      },
      editing: false,
      errorMessage: "",
      student: {
        address: {},
        father: {},
        mother: {},
        classroom: {
          grade: ""
        }
      },
      classrooms: {},
      languages: {},
      relationships: {},
      student_file: "",
      student_url: null,
      student_srcList: [],
      father_file: "",
      father_url: null,
      father_srcList: [],
      mother_file: "",
      mother_url: null,
      mother_srcList: [],
      type: ""
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
    this.getStudent(id);
    if (this.mobile) {
      this.position = "top";
    }
  },
  methods: {
    successDelete() {
      this.$router.push({ name: "Students" });
    },
    setDelete(id, type) {
      this.delete_id.push(id);
      this.delete_active = true;
    },
    addPS() {
      var array = {
        name: "",
        started_date: "",
        ended_date: ""
      };
      this.previous_schools_data.push(array);
    },
    clearPS() {
      this.previous_schools_data = [];
      this.edit_PS = false;
    },
    deletePS(data) {
      this.previous_schools_data.splice(data, 1);
    },
    savePS() {
      this.$vs.loading({
        container: "#loading6",
        background: "rgba(var(--vs-theme),0.4)",
        color: "primary",
        type: "border",
        scale: 0.6
      });
      axios
        .post(`/web/updateStudentPrevious`, {
          data: this.previous_schools_data,
          id: this.student.id
        })
        .then(response => {
          this.$message({
            message: "Successfully upaded!",
            type: "success"
          });
          this.student.previous_schools = this.previous_schools_data;
          this.previous_schools_data = [];
          localStorage.removeItem("student");
          let parsed = JSON.stringify(this.student);
          localStorage.setItem("student", parsed);
          this.$vs.loading.close("#loading6 > .con-vs-loading");
          this.edit_PS = false;
        })
        .catch(error => {
          this.$vs.loading.close("#loading6 > .con-vs-loading");
          this.$message({
            message: "Oops! There is an error while updating.",
            type: "danger"
          });
        });
    },
    setPS() {
      this.edit_PS = true;
      var data = JSON.parse(localStorage.getItem("student"));
      this.previous_schools_data = data.previous_schools;
    },
    saveEmergency() {
      this.$vs.loading({
        container: "#loading5",
        background: "rgba(var(--vs-theme),0.4)",
        color: "primary",
        type: "border",
        scale: 0.6
      });
      axios
        .post(`/web/updateStudentEmergency`, {
          data: this.emergency_data,
          id: this.student.id
        })
        .then(response => {
          this.$message({
            message: "Successfully upaded!",
            type: "success"
          });
          this.student.emergency_contact_person = this.emergency_data.emergency_contact_person;
          this.student.emergency_contact_relationship = this.emergency_data.emergency_contact_relationship;
          this.student.emergency_contact_person_phone = this.emergency_data.emergency_contact_person_phone;
          this.$vs.loading.close("#loading5 > .con-vs-loading");
          this.edit_emergency = false;
        })
        .catch(error => {
          this.$vs.loading.close("#loading5 > .con-vs-loading");
          this.$message({
            message: "Oops! There is an error while updating.",
            type: "danger"
          });
        });
    },
    setEmergency() {
      this.edit_emergency = true;
      this.getRelationships();
      this.emergency_data.emergency_contact_person = this.student.emergency_contact_person;
      this.emergency_data.emergency_contact_relationship = this.student.emergency_contact_relationship;
      this.emergency_data.emergency_contact_person_phone = this.student.emergency_contact_person_phone;
    },
    clearEmergency() {
      this.edit_emergency = false;
    },
    setFather() {
      this.edit_father = true;
      this.father_data.id = this.student.father.id;
      this.father_data.first_name = this.student.father.first_name;
      this.father_data.last_name = this.student.father.last_name;
      this.father_data.NRC = this.student.father.NRC;
      this.father_data.email = this.student.father.email;
      this.father_data.phone = this.student.father.phone;
      this.father_data.occupation = this.student.father.occupation;
      this.father_data.company = this.student.father.company;
    },
    clearFather() {
      this.edit_father = false;
      this.father_url = this.student.father.profile_image;
      this.father_srcList.push(this.father_url);
    },
    saveFather() {
      this.$vs.loading({
        container: "#loading2",
        background: "rgba(var(--vs-theme),0.4)",
        color: "primary",
        type: "border",
        scale: 0.6
      });
      const config = {
        headers: { "content-type": "multipart/form-data" }
      };
      let formData = new FormData();
      if (this.father_file) {
        formData.append("file", this.father_file);
      }
      formData.append("data", JSON.stringify(this.father_data));
      formData.append("id", this.student.father.id);

      axios
        .post("web/updateStudentFather", formData, config)
        .then(response => {
          this.$vs.loading.close();
          this.$message({
            message: "Successfully upaded!",
            type: "success"
          });
          this.student.father = this.father_data;
          this.$vs.loading.close("#loading2 > .con-vs-loading");
          this.edit_father = false;
        })
        .catch(error => {
          this.$vs.loading.close("#loading2 > .con-vs-loading");
          this.$message({
            message: "Oops! There is an error while updating.",
            type: "danger"
          });
          const key = Object.keys(error.response.data.errors)[0];
          this.errorMessage = error.response.data.errors[key][0];
        });
    },
    setMother() {
      this.edit_mother = true;
      this.mother_data.id = this.student.mother.id;
      this.mother_data.first_name = this.student.mother.first_name;
      this.mother_data.last_name = this.student.mother.last_name;
      this.mother_data.NRC = this.student.mother.NRC;
      this.mother_data.email = this.student.mother.email;
      this.mother_data.phone = this.student.mother.phone;
      this.mother_data.occupation = this.student.mother.occupation;
      this.mother_data.company = this.student.mother.company;
    },
    clearMother() {
      this.edit_mother = false;
      this.mother_url = this.student.mother.profile_image;
      this.mother_srcList.push(this.mother_url);
    },
    saveMother() {
      this.$vs.loading({
        container: "#loading3",
        background: "rgba(var(--vs-theme),0.4)",
        color: "primary",
        type: "border",
        scale: 0.6
      });
      const config = {
        headers: { "content-type": "multipart/form-data" }
      };
      let formData = new FormData();
      if (this.mother_file) {
        formData.append("file", this.mother_file);
      }
      formData.append("data", JSON.stringify(this.mother_data));
      formData.append("id", this.student.mother.id);

      axios
        .post("web/updateStudentMother", formData, config)
        .then(response => {
          this.$vs.loading.close();
          this.$message({
            message: "Successfully upaded!",
            type: "success"
          });
          this.student.mother = this.mother_data;
          this.$vs.loading.close("#loading3 > .con-vs-loading");
          this.edit_mother = false;
        })
        .catch(error => {
          this.$vs.loading.close("#loading3 > .con-vs-loading");
          this.$message({
            message: "Oops! There is an error while updating.",
            type: "danger"
          });
          const key = Object.keys(error.response.data.errors)[0];
          this.errorMessage = error.response.data.errors[key][0];
        });
    },
    saveAddress() {
      this.$vs.loading({
        container: "#loading4",
        background: "rgba(var(--vs-theme),0.4)",
        color: "primary",
        type: "border",
        scale: 0.6
      });
      axios
        .post(`/web/updateStudentAddress`, {
          data: this.student.address,
          id: this.student.id,
          address_id: this.student.address_id
        })
        .then(response => {
          this.$message({
            message: "Successfully upaded!",
            type: "success"
          });
          this.$vs.loading.close("#loading4 > .con-vs-loading");
          this.edit_address = false;
        })
        .catch(error => {
          this.$vs.loading.close("#loading4 > .con-vs-loading");
          this.$message({
            message: "Oops! There is an error while updating.",
            type: "danger"
          });
        });
    },
    setAddress() {
      this.edit_address = true;
      this.address_data.street_line_1 = this.student.address.street_line_1;
      this.address_data.street_line_2 = this.student.address.street_line_2;
      this.address_data.township = this.student.address.township;
      this.address_data.postal_code = this.student.address.postal_code;
      this.address_data.city = this.student.address.city;
      this.address_data.country = this.student.address.country;
    },
    clearAddress() {
      this.edit_address = false;
    },
    upload(type) {
      this.active = true;
      this.type = type;
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
    saveGeneral() {
      this.general = false;
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
      if (this.student_file) {
        formData.append("file", this.student_file);
      }
      formData.append("data", JSON.stringify(this.general_data));
      formData.append("id", this.student.id);

      axios
        .post("web/updateStudentGeneral", formData, config)
        .then(response => {
          this.$vs.loading.close();
          this.$message({
            message: "Successfully upaded!",
            type: "success"
          });
          this.student.first_name = this.general_data.first_name;
          this.student.last_name = this.general_data.last_name;
          this.student.nick_name = this.general_data.nick_name;
          this.student.classroom.id = this.general_data.classroom.id;
          this.student.date_of_birth = this.general_data.date_of_birth;
          this.student.NRC = this.general_data.NRC;
          this.student.gender = this.general_data.gender;
          this.$vs.loading.close("#loading > .con-vs-loading");
          this.edit_general = false;
        })
        .catch(error => {
          this.$vs.loading.close("#loading > .con-vs-loading");
          this.$message({
            message: "Oops! There is an error while updating.",
            type: "danger"
          });
        });
    },
    clearGeneral() {
      this.edit_general = false;
      this.student_url = this.student.profile_image;
      this.student_srcList.push(this.student_url);
    },
    setGeneral() {
      this.getClassrooms();
      this.edit_general = true;
      this.general_data.first_name = this.student.first_name;
      this.general_data.last_name = this.student.last_name;
      this.general_data.nick_name = this.student.nick_name;
      this.general_data.classroom.id = this.student.classroom.id;
      this.general_data.date_of_birth = this.student.date_of_birth;
      this.general_data.NRC = this.student.NRC;
      this.general_data.gender = this.student.gender;
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
    },
    getStudent(id) {
      axios
        .get(`web/student_Detail/${id}`)
        .then(response => {
          this.student = response.data;
          this.student_url = this.student.profile_image;
          this.student_srcList.push(this.student_url);
          this.father_url = this.student.father.profile_image;
          this.father_srcList.push(this.father_url);
          this.mother_url = this.student.mother.profile_image;
          this.mother_srcList.push(this.mother_url);
        })
        .catch(error => {});
    }
  }
};
</script>
