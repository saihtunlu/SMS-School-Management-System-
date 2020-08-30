<template>
  <div class="row" v-if="$gate.isAdmin()">
    <div class="col-12 px-0 todo_content">
      <vs-sidebar
        id="todo_menu"
        color="primary"
        class="sidebarx"
        spacer
        :click-not-close="true"
        default-index="1"
        hidden-background
        v-model="active"
      >
        <div class="header-sidebar flex-align-center" style=" width: 100%;" slot="header">
          <vs-button
            color="primary"
            @click="add_active=!add_active"
            icon="icon-edit"
            icon-pack="feather"
          >Compose</vs-button>
        </div>
        <vs-sidebar-item index="1" @click="filterAll()" icon="icon-layers" icon-pack="feather">All</vs-sidebar-item>
        <vs-sidebar-item
          index="2"
          @click="filter('starred','boolean')"
          icon="icon-star"
          icon-pack="feather"
        >Starred</vs-sidebar-item>
        <!-- <vs-sidebar-item
          index="3"
          icon="icon-edit-2"
          @click="filter('Draft','boolean')"
          icon-pack="feather"
        >Draft</vs-sidebar-item>-->
        <vs-sidebar-item
          index="5"
          icon="icon-trash"
          @click="filter('deleted_at','trashed')"
          icon-pack="feather"
        >Trashed</vs-sidebar-item>
        <vs-divider />

        <h3 class="text-lg text-bold todo_title">Labels</h3>

        <vs-sidebar-item
          :index="n+6"
          class="todo_categories"
          @click="filter(category.name,'category')"
          v-for="(category,n) in categories"
          :key="n"
          icon-pack="feather"
        >
          <div>
            <vs-icon
              icon="fiber_manual_record"
              class="text-bold"
              size="16px"
              :color="category.color"
            />
            {{category.name}}
          </div>

          <vs-icon
            @click="removeCat(category.id)"
            icon="icon-x"
            icon-pack="feather"
            class="delete_cat float-right"
          />
        </vs-sidebar-item>
      </vs-sidebar>
      <slide-y-down-transition>
        <div class="todo_lists" :class="{FullWidth: !active}">
          <vs-sidebar
            id="view_body"
            color="primary"
            class="sidebarx"
            :click-not-close="true"
            spacer
            position-right
            hidden-background
            v-model="view_mail"
          >
            <div class="header-sidebar flex-between-center" style=" width: 100%;" slot="header">
              <div class="flex-align-center">
                <vs-icon
                  color="primary"
                  size="18px"
                  icon="icon-arrow-left"
                  icon-pack="feather"
                  class="back_to_mails pointer custom_icon"
                  @click="view_mail=!view_mail"
                />
                <div class="mail_detail flex flex-column ml-3">
                  <h5 class="text-bold mail_subject mb0">{{mail.subject}}</h5>
                </div>
              </div>
              <div class="mail_right flex-end flex-column">
                <div class="flex-end mb-1">
                  <vs-dropdown>
                    <vs-icon icon="icon-tag" size="18px" class="custom_icon" icon-pack="feather"></vs-icon>

                    <vs-dropdown-menu>
                      <vs-dropdown-item
                        v-for="cate in categories"
                        :key="cate.id"
                        @click="choose(cate)"
                      >
                        <vs-icon
                          icon="fiber_manual_record"
                          class="text-bold mr-1"
                          size="12px"
                          :color="cate.color"
                        />
                        {{cate.name}}
                      </vs-dropdown-item>
                    </vs-dropdown-menu>
                  </vs-dropdown>
                  <vs-icon
                    icon="icon-star "
                    class="ml-3 pointer custom_icon"
                    size="18px"
                    icon-pack="feather"
                    @click="starred(mail.id)"
                    :color="mail.starred ? 'warning': 'dark'"
                  />
                  <vs-icon
                    icon="icon-trash"
                    v-if="mail.deleted_at === null"
                    @click="deleteMail(mail.id)"
                    icon-pack="feather"
                    size="18px"
                    class="pointer ml-3 custom_icon"
                  />
                  <vs-icon
                    v-if="mail.deleted_at !== null"
                    icon="icon-rotate-ccw"
                    @click="restoreMail(mail.id)"
                    icon-pack="feather"
                    size="18px"
                    class="pointer ml-3 custom_icon"
                  />
                </div>
              </div>
            </div>
            <div class="row mx-0">
              <div class="col-12 padding">
                <div class="mail_categories flex-align-center mb-3">
                  <vs-chip
                    class="mr-3 calendar_cat text-md"
                    v-for="(data,n) in mail.categories"
                    :key="n"
                    closable
                    @click="removeMailCat(data.id,n)"
                  >
                    <vs-icon
                      icon="fiber_manual_record"
                      class="text-bold mr-1"
                      size="12px"
                      :color="data.category.color"
                    />
                    {{data.category.name}}
                  </vs-chip>
                </div>
                <div class="mail_body padding div_shadow">
                  <div class="mail_user_details flex-between-center">
                    <div class="flex-align-center">
                      <vs-avatar
                        class="mail_avator"
                        :src="mail.to[0].user.profile_image"
                        v-if="mail.to[0].user  !== null"
                      />
                      <vs-avatar class="mail_avator" v-if="mail.to[0].user === null" />

                      <div class="mail_detail flex flex-column ml-lg-3 ml-md-2 ml-sm-2">
                        <p class="header-text mb0 text-md" v-if="mail.to[0].user  !== null">
                          {{mail.to[0].user.name}}
                          <span
                            class="text-small"
                            v-if="mail.to.length > 1"
                          >, +{{mail.to.length-1}} more</span>
                        </p>
                        <p class="header-text mb0 text-md" v-if="mail.to[0].user  === null">
                          Unknown User
                          <span
                            class="text-small"
                            v-if="mail.to.length > 1"
                          >, +{{mail.to.length-1}} more</span>
                        </p>
                        <el-popover
                          placement="bottom"
                          id="mail_more_details"
                          width="250"
                          trigger="click"
                        >
                          <div class="mail_more_details">
                            <p>
                              <span class="text-bold text-small text-md">From :</span>
                              <a :href="'mailto:'+ mail.from">{{mail.from}}</a>
                            </p>
                            <p>
                              <span class="text-bold text-small text-md">To :</span>
                              <span v-for="(to,n) in mail.to" :key="to.id">
                                <span v-if="n > 0">,</span>
                                <a :href="'mailto:'+ to.email">{{to.email}}</a>
                              </span>
                            </p>
                            <p>
                              <span class="text-bold text-small text-md">Cc :</span>
                              <span v-for="(cc,n) in mail.cc" :key="cc.id">
                                <span v-if="n > 0">,</span>
                                <a :href="'mailto:'+ cc.email">{{cc.email}}</a>
                              </span>
                            </p>
                            <p>
                              <span class="text-bold text-small text-md">Bcc :</span>
                              <span v-for="(bcc,n) in mail.bcc" :key="bcc.id">
                                <span v-if="n > 0">,</span>
                                <a :href="'mailto:'+ bcc.email">{{bcc.email}}</a>
                              </span>
                            </p>
                          </div>
                          <span slot="reference" class="mb0 text-small pointer">
                            {{mail.to[0].email}}
                            <vs-icon icon="icon-chevron-down" icon-pack="feather" />
                          </span>
                        </el-popover>
                      </div>
                    </div>
                    <div class="right_mail_header flex-y-end flex-column">
                      <p
                        class="mb-1 text-md text-right"
                      >{{ $moment(mail.created_at, "YYYY-MM-DDTHH:mm:ss.SSS").format('HH:mm a')}}</p>
                      <p
                        class="mb0 text-md text-right"
                      >{{$moment(mail.created_at, "YYYY-MM-DDTHH:mm:ss.SSS").format('LL')}}</p>
                    </div>
                  </div>
                  <div id="mail_message_body" v-html="mail.message"></div>
                  <div class="attachment_title" v-if="mail.attachment">
                    <p class="text-lg text-bold">
                      <vs-button
                        radius
                        color="primary"
                        type="flat"
                        icon="icon-paperclip"
                        icon-pack="feather"
                      />Attachment
                    </p>
                  </div>
                  <div class="attachment_file open flex-between">
                    <div class="flex attachment_file_detail">
                      <a :href="mail.attachment" target="_blank">
                        <img src="/storage/image/file.png" alt />
                      </a>
                      <div id="file_detail">
                        <p class="text-md truncate">
                          <span class="text-bold">Name :</span>
                          {{mail.file_name}}
                        </p>
                        <p class="text-md">
                          <span class="text-bold">Size :</span>
                          {{mail.file_size}} MB
                        </p>
                      </div>
                    </div>
                    <!-- <div class="attachment_file_action">
                      <vs-icon
                        icon="icon-x"
                        icon-pack="feather"
                        @click="clear_file()"
                        class="delete_cat pointer"
                      />
                    </div>-->
                  </div>
                </div>
              </div>
            </div>
          </vs-sidebar>
          <div class="todo_header">
            <vs-input
              icon="icon-search"
              icon-pack="feather"
              :icon-no-border="true"
              placeholder="Search"
              v-model="query"
            />
            <vs-icon
              v-if="mobile"
              @click="active=!active"
              class="mr-3 pointer"
              icon="icon-menu"
              icon-pack="feather"
            ></vs-icon>
          </div>
          <div class="todo_body">
            <div v-if="no_data" class="no_data">
              <div class="flex-align-center flex-column">
                <img src="/storage/image/empty.png" style="width:120px;" alt />
                <p class="text-sm text-bold mt-3">No Data available!</p>
              </div>
            </div>
            <ul v-if="!no_data">
              <slide-y-down-transition group>
                <li class="todo_items" v-for="(mail,n) in mails" :key="n" @click="view(mail)">
                  <div class="todo_list pointer">
                    <div class="flex-between-center">
                      <div class="mail_left flex-align-center" v-if="mail.to[0].user !== null">
                        <vs-avatar
                          size="45px"
                          class="mail_profile"
                          :src="mail.to[0].user.profile_image"
                        />
                        <div class="mail_detail flex flex-column ml-lg-3 ml-md-2 ml-sm-2">
                          <p class="header-text mb-2">{{mail.to[0].user.name}}</p>
                          <p class="mb-2 text-md truncate">{{mail.subject}}</p>
                        </div>
                      </div>
                      <div class="mail_left flex-align-center" v-if="mail.to[0].user === null">
                        <vs-avatar size="45px" class="mail_profile" />
                        <div class="mail_detail flex flex-column ml-lg-3 ml-md-2 ml-sm-2">
                          <p class="header-text mb-2">{{mail.to[0].email}}</p>
                          <p class="mb-2 text-md truncate">{{mail.subject}}</p>
                        </div>
                      </div>
                      <div class="mail_right flex-end flex-column">
                        <div class="flex-end mb-1">
                          <vs-icon
                            icon="icon-star"
                            class="ml-3"
                            size="18px"
                            icon-pack="feather"
                            :color="mail.starred ? 'warning': 'dark'"
                          />
                          <vs-icon
                            icon="icon-trash"
                            class="ml-3"
                            size="18px"
                            icon-pack="feather"
                            :color="mail.deleted_at !==null ? 'danger': 'dark'"
                          />
                        </div>
                        <p class="mb0 mt-2 flex-align-center">
                          <vs-icon
                            icon="fiber_manual_record"
                            class="text-bold mr-2"
                            size="14px"
                            :color="mail.categories[0].category.color"
                          />
                          {{ $moment(mail.created_at, "YYYY-MM-DDTHH:mm:ss.SSS").format('DD/MM/YYYY')}}
                        </p>
                      </div>
                    </div>
                  </div>
                </li>
              </slide-y-down-transition>
            </ul>
          </div>
        </div>
      </slide-y-down-transition>
    </div>

    <!-- Add New  -->
    <vs-prompt class="add-task" title="Compose" :active.sync="add_active">
      <div class="row email_composer vs-con-loading__container" id="loading">
        <div class="col-lg-6">
          <vs-input class="edit2_btns" label="From*" v-model="email.from" required />
        </div>
        <div class="col-lg-6">
          <div style="animation:open 0.3s;" class="custom_input">
            <label for class="vs-input--label" style="display:block;">Send Options</label>
            <el-select v-model="email.send_option" filterable>
              <el-option
                v-for="option in options"
                :key="option.value"
                :label="option.name"
                :value="option.value"
              ></el-option>
            </el-select>
          </div>
        </div>
        <div class="col-lg-6" v-if="email.send_option === '1'">
          <div style="animation:open 0.3s;" class="custom_input">
            <label for class="vs-input--label" style="display:block;">To*</label>
            <el-select
              v-model="email.to"
              filterable
              multiple
              collapse-tags
              allow-create
              default-first-option
            >
              <el-option v-for="email in emails" :key="email" :label="email" :value="email"></el-option>
            </el-select>
          </div>
        </div>
        <div class="col-lg-6" v-if="email.send_option === '2'">
          <div style="animation:open 0.3s;" class="custom_input">
            <label for class="vs-input--label" style="display:block;">Select group</label>
            <el-cascader
              :options="group_options"
              @change="getData"
              v-model="selectedGroup"
              clearable
            ></el-cascader>
          </div>
        </div>
        <div class="col-lg-6">
          <div style="animation:open 0.3s;" class="custom_input">
            <label for class="vs-input--label" style="display:block;">Label</label>
            <el-select
              v-model="email.categories"
              collapse-tags
              filterable
              multiple
              allow-create
              default-first-option
              placeholder="Choose a category"
            >
              <el-option
                v-for="category in categories"
                :key="category.name"
                :label="category.name"
                :value="category.name"
              ></el-option>
            </el-select>
          </div>
        </div>
        <div class="col-lg-6">
          <div style="animation:open 0.3s;" class="custom_input">
            <label for class="vs-input--label" style="display:block;">CC*</label>
            <el-select
              v-model="email.cc"
              collapse-tags
              filterable
              multiple
              allow-create
              default-first-option
            >
              <el-option v-for="email in emails" :key="email" :label="email" :value="email"></el-option>
            </el-select>
          </div>
        </div>
        <div class="col-lg-6">
          <div style="animation:open 0.3s;" class="custom_input">
            <label for class="vs-input--label" style="display:block;">BCC*</label>
            <el-select
              v-model="email.bcc"
              collapse-tags
              filterable
              multiple
              allow-create
              default-first-option
            >
              <el-option v-for="email in emails" :key="email" :label="email" :value="email"></el-option>
            </el-select>
          </div>
        </div>
        <div class="col-lg-12">
          <vs-input class="edit2_btns" label="Subject*" v-model="email.subject" />
        </div>
        <div class="col-lg-12 mt-3">
          <label
            for
            class="vs-input--label"
            style="margin-bottom:2px !important;display:block;"
          >Message Body</label>
          <yimo-vue-editor v-model="email.message" />
        </div>
        <div class="col-lg-12 flex-align-center mt-base attachment_preview" v-if="show_preview">
          <div class="attachment_file open flex-between">
            <div class="flex attachment_file_detail">
              <a :href="FileDetails.file_path" target="_blank">
                <img src="/storage/image/file.png" alt />
              </a>
              <div id="file_detail">
                <p class="text-md">
                  <span class="text-bold truncate">Name :</span>
                  {{FileDetails.file_name}}
                </p>
                <p class="text-md">
                  <span class="text-bold">Size :</span>
                  {{FileDetails.file_size}} MB
                </p>
              </div>
            </div>
            <div class="attachment_file_action">
              <vs-icon
                icon="icon-x"
                icon-pack="feather"
                @click="clear_file()"
                class="delete_cat pointer"
              />
            </div>
          </div>
        </div>
        <div class="col-lg-12 flex-align-center mt-base mail_file" v-if="!show_preview">
          <input type="file" ref="fileInput" id="change_pic" hidden v-on:change="onFileChangeEdit" />
          <button class="add_invoice_logo" @click="trigger()">Attachments</button>
        </div>

        <div class="col-lg-12 flex-end email_btns">
          <vs-button color="primary" class="edit_btn" @click="send()" type="filled">Send</vs-button>
          <vs-button
            color="warning"
            class="edit_btn"
            type="border"
            @click="email={};add_active=false"
          >Cancel</vs-button>
        </div>
      </div>
    </vs-prompt>
  </div>
</template>

<script>
import upload from "../../components/upload";
import YimoVueEditor from "../../components/yimo-vue-editor.js";

export default {
  props: {
    auth: {},
    mobile: false
  },
  data() {
    return {
      newcategories: [],
      current_year_id: null,
      view_mail: false,
      options: [
        {
          value: "1",
          name: "Menual add email"
        },
        {
          value: "2",
          name: "Send by group"
        }
      ],
      group_options: [
        {
          value: "all",
          label: "All"
        },
        {
          value: "roles",
          label: "Roles",
          children: [
            {
              value: "All",
              label: "All"
            }
          ]
        },
        {
          value: "parents",
          label: "Parents",
          children: [
            {
              value: "All",
              label: "All"
            }
          ]
        },

        {
          value: "students",
          label: "Students",
          children: [
            {
              value: "All",
              label: "All"
            }
          ]
        }
      ],
      data: [],
      selectedGroup: [],
      emails: [],
      group_type: "",
      active: true,
      query: null,
      no_data: false,
      add_active: false,
      email: {
        send_option: "1",
        to: []
      },
      file: "",
      FileDetails: {
        file_name: "",
        file_size: "",
        file_path: "",
        file_type: ""
      },
      show_preview: false,
      mails: [],
      mail: {
        to: [{ user: {} }],
        cc: [{ user: {} }],
        bcc: [{ user: {} }],
        categories: []
      },
      categories: {}
    };
  },
  components: {
    upload,
    YimoVueEditor
  },
  created() {
    var check = localStorage.getItem("current_year_id");
    if (check) {
      this.current_year_id = check;
    }
    var index = this.$route.meta.index;
    this.$emit("index", index);
    this.email.from = this.auth.email;
    this.active = !this.mobile;
  },
  mounted() {
    if (this.$gate.isUser_Read()) {
      this.getCategories();
      this.getmails();
      this.getuser();
      this.getClassrooms(this.current_year_id);
      this.getRoles();
    }
  },
  watch: {
    query: function(newVal, oldVal) {
      this.search(newVal);
    }
  },
  methods: {
    choose(data) {
      var array = {};
      var check = this.mail.categories.filter(category => {
        return category.category.id === data.id;
      });

      if (check.length === 0) {
        array.category = data;
        this.mail.categories.push(array);
        axios
          .post(`web/addMailCat`, { data: data, mail_id: this.mail.id })
          .then(response => {
            this.getmails();
          })
          .catch(error => {});
      }
    },
    removeMailCat(id, index) {
      axios
        .delete(`web/removeMailCat/${id}`)
        .then(response => {
          this.mail.categories.splice(index, 1);
        })
        .catch(error => {});
    },
    starred(id) {
      axios
        .post(`web/starredMail`, { id: id })
        .then(response => {
          var index = this.data
            .map(x => {
              return x.id;
            })
            .indexOf(id);
          this.data[index].starred = response.data;
          this.mail.starred = response.data;
        })
        .catch(error => {});
    },
    deleteMail(id) {
      axios
        .delete(`web/mail/${id}`)
        .then(response => {
          var index = this.data
            .map(x => {
              return x.id;
            })
            .indexOf(id);
          this.data[index].deleted_at = response.data;
          this.mail.deleted_at = response.data;
        })
        .catch(error => {});
    },
    restoreMail(id) {
      axios
        .get(`web/mail/${id}`)
        .then(response => {
          var index = this.data
            .map(x => {
              return x.id;
            })
            .indexOf(id);
          this.data[index].deleted_at = null;
          this.mail.deleted_at = null;
        })
        .catch(error => {});
    },
    view(data) {
      this.mail = data;
      this.newcategories = [];
      this.mail.categories.forEach(data => {
        this.newcategories.push(data.category.name);
      });
      var array = this.mail.attachment.split("/");
      this.mail.file_name = array.slice(-1)[0];
      this.view_mail = !this.view_mail;
    },
    filterAll() {
      if (this.mobile) {
        this.active = !this.active;
      }
      this.mails = this.data;
    },
    filter(value, type) {
      if (this.mobile) {
        this.active = !this.active;
      }
      this.view_mail = false;
      var data = this.data;
      if (type === "boolean") {
        if (value === "starred") {
          this.mails = data.filter(function(data) {
            return data.starred === 1;
          });
        }
      }
      if (type === "trashed") {
        this.mails = data.filter(function(data) {
          return data.deleted_at !== null;
        });
      }
      if (type === "category") {
        this.mails = [];
        data.forEach(data => {
          var todos = data.categories.filter(data => {
            return data.category.name === value;
          });

          if (todos.length !== 0) {
            this.mails.push(data);
          }
        });
      }
    },
    search(query) {
      var array = this.data.filter(data =>
        data.subject.toLowerCase().includes(query.toLowerCase())
      );
      if (array.length != 0) {
        this.no_data = false;
        this.mails = array;
      } else {
        this.no_data = true;
      }
    },
    getData() {
      var type = this.selectedGroup[0];
      console.log("type: ", type);

      if (type === "parents") {
        var grade = this.selectedGroup[1];
        console.log("grade: ", grade);
        axios
          .get(`web/getParentsMail`)
          .then(response => {
            this.data = response.data;
            this.email.to = [];
            if (grade !== "All") {
              var data = this.data.filter(data => {
                var children = data.children.filter(children => {
                  return parseInt(children.classroom_id) === parseInt(grade);
                });
                return children.length !== 0;
              });
              console.log("data: ", data);

              data.forEach(email => {
                this.email.to.push(email.email);
              });
              console.log(" this.email.to: ", this.email.to);
            }
            if (grade === "All") {
              this.data.forEach(data => {
                this.email.to.push(data.email);
              });
            }
          })
          .catch(error => {});
      }

      if (type === "roles") {
        var role = this.selectedGroup[1];
        axios
          .get(`web/allUsers`)
          .then(response => {
            this.data = response.data;
            this.email.to = [];
            if (role !== "All") {
              var data = this.data.filter(data => {
                return data.user_role.role_id === role;
              });
              data.forEach(data => {
                this.email.to.push(data.email);
              });
            }
            if (role === "All") {
              this.data.forEach(data => {
                this.email.to.push(data.email);
              });
            }
          })
          .catch(error => {});
      }
      if (type === "students") {
        var grade = this.selectedGroup[1];
        axios
          .get(`web/getStudentsMail`)
          .then(response => {
            this.data = response.data;
            this.email.to = [];
            if (grade !== "All") {
              var data = this.data.filter(data => {
                return data.students.classroom_id === grade;
              });
              data.forEach(data => {
                this.email.to.push(data.email);
              });
            }
            if (grade === "All") {
              this.data.forEach(data => {
                this.email.to.push(data.email);
              });
            }
          })
          .catch(error => {});
      }
      if (type === "all") {
        this.email.to = this.emails;
      }
    },
    clear_file() {
      this.file = "";
      this.url = null;
      this.srcList = [];
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
    send() {
      this.add_active = false;
      this.email.file_size = this.FileDetails.file_size;
      this.$vs.loading({
        background: "rgba(var(--vs-theme),0.4)",
        color: "primary",
        type: "border",
        scale: 0.6
      });
      const config = {
        headers: { "content-type": "multipart/form-data" }
      };
      let formData = new FormData();
      formData.append("email", JSON.stringify(this.email));
      formData.append("file", this.file);
      axios
        .post("web/sendEmail", formData, config)
        .then(response => {
          this.$vs.loading.close();
          this.$message({
            message: "Congratulation! Email has been sent succefully.",
            type: "success"
          });
          this.getmails();
          this.getCategories();
        })
        .catch(err => {
          this.$vs.loading.close();
          this.$message({
            message: "Oops! There is an error while sending.",
            type: "error"
          });
        });
    },
    clear_file() {
      this.show_preview = false;
      this.file = "";
      this.FileDetails = {};
    },
    trigger() {
      this.$refs.fileInput.click();
    },
    onFileChangeEdit(e) {
      this.file = "";
      this.url = null;
      this.file = e.target.files[0];
      this.url = URL.createObjectURL(this.file);
      var getType = this.file.type.split("/")[0];

      this.FileDetails.file_size = (this.file.size / 1000000).toFixed(2); //in MB
      this.FileDetails.file_path = URL.createObjectURL(this.file);
      this.FileDetails.file_name = this.file.name;
      this.FileDetails.file_type = getType;
      this.show_preview = true;
    },
    getClassrooms(id) {
      axios.get(`/web/classrooms`).then(response => {
        var classrooms = response.data;
        var data = classrooms.filter(data => {
          return data.academic_year_id === parseInt(id);
        });
        var array = {};
        data.forEach(data => {
          array.value = data.id;
          array.label = data.grade;
          this.group_options[2].children.push(array);
          this.group_options[3].children.push(array);
          array = {};
        });
      });
    },
    getCategories() {
      axios
        .get(`web/getMailCategories`)
        .then(response => {
          this.categories = response.data;
        })
        .catch(error => {});
    },
    getmails() {
      axios
        .get(`web/mails`)
        .then(response => {
          this.data = response.data;
          this.mails = this.data;
        })
        .catch(error => {});
    },
    getRoles() {
      axios.get(`/web/roles`).then(response => {
        var data = response.data;
        var array = {};
        data.forEach(data => {
          array.value = data.id;
          array.label = data.name;
          this.group_options[1].children.push(array);
          array = {};
        });
      });
    },
    getuser() {
      axios
        .get(`web/allUsers`)
        .then(response => {
          var data = response.data;
          data.forEach(data => {
            this.emails.push(data.email);
          });
        })
        .catch(error => {});
    },
    removeCat(id) {
      axios
        .delete(`web/MailCategory/${id}`)
        .then(response => {
          this.getmails();
          this.getCategories();
        })
        .catch(error => {});
    }
  }
};
</script>

<style lang="scss" scoped>
</style>