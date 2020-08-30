<template>
  <div class="row">
    <div class="col-12 px-0 todo_content">
      <vs-sidebar
        :click-not-close="true"
        id="todo_menu"
        color="primary"
        class="sidebarx"
        spacer
        default-index="1"
        hidden-background
        v-model="active"
      >
        <div class="header-sidebar flex-align-center" style=" width: 100%;" slot="header">
          <vs-button color="primary" @click="add_active=!add_active">Add Task</vs-button>
        </div>
        <vs-sidebar-item index="1" @click="filterAll()" icon="icon-layers" icon-pack="feather">All</vs-sidebar-item>
        <vs-divider />
        <h3 class="text-lg text-bold todo_title">Filter</h3>
        <vs-sidebar-item
          index="2"
          @click="filter('starred','boolean')"
          icon="icon-star"
          icon-pack="feather"
        >Starred</vs-sidebar-item>
        <vs-sidebar-item
          index="3"
          icon="icon-alert-circle"
          @click="filter('important','boolean')"
          icon-pack="feather"
        >Important</vs-sidebar-item>
        <vs-sidebar-item
          index="4"
          icon="icon-check"
          @click="filter('completed','boolean')"
          icon-pack="feather"
        >Completed</vs-sidebar-item>
        <vs-sidebar-item
          index="5"
          icon="icon-trash"
          @click="filter('deleted_at','trashed')"
          icon-pack="feather"
        >Trashed</vs-sidebar-item>
        <vs-divider />

        <h3 class="text-lg text-bold todo_title">Categories</h3>

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
                <li class="todo_items" v-for="(todo,n) in todos" :key="n" @click="view(todo)">
                  <div
                    class="todo_list pointer"
                    :class="{expired: todo.expired && !todo.completed}"
                  >
                    <div class="flex-between-center">
                      <div class="todo_left flex-align-center">
                        <vs-checkbox size="12px" class="mr-1" v-model="todo.completed" />
                        <p
                          class="text-md mb0 mr-2 text-bold"
                          :class="{line_through: todo.completed}"
                        >{{todo.title}}</p>
                        <vs-chip
                          transparent
                          color="dark"
                          class="category_chip mr-1"
                          v-for="(category,n) in todo.categories"
                          :key="n"
                        >
                          <vs-icon
                            icon="fiber_manual_record"
                            class="text-bold"
                            size="12px"
                            :color="category.category.color"
                          />
                          {{category.category.name}}
                        </vs-chip>
                      </div>
                      <div class="todo_right flex-end">
                        <vs-icon
                          icon="icon-alert-circle"
                          icon-pack="feather"
                          size="18px"
                          class="ml-3"
                          :color="todo.important ? 'success': 'dark'"
                        />
                        <vs-icon
                          icon="icon-star"
                          class="ml-3"
                          size="18px"
                          icon-pack="feather"
                          :color="todo.starred ? 'warning': 'dark'"
                        />
                        <vs-icon
                          icon="icon-trash"
                          class="ml-3"
                          size="18px"
                          icon-pack="feather"
                          :color="todo.deleted_at !==null ? 'danger': 'dark'"
                        />
                      </div>
                    </div>
                    <p
                      class="text-md truncate mb0 mt-1"
                      :class="{line_through: todo.completed}"
                    >{{todo.description}}</p>
                  </div>
                </li>
              </slide-y-down-transition>
            </ul>
          </div>
        </div>
      </slide-y-down-transition>
    </div>

    <!-- Add New  -->
    <vs-prompt class="add-task" title="Add Task" :active.sync="add_active">
      <div class="row">
        <div class="flex-end col-12">
          <vs-icon
            icon="icon-alert-circle"
            icon-pack="feather"
            size="18px"
            @click="add.important=!add.important"
            class="pointer custom_icon"
            :color="add.important ? 'success': 'dark'"
          />
          <vs-icon
            icon="icon-star"
            @click="add.starred=!add.starred"
            icon-pack="feather"
            size="18px"
            class="pointer custom_icon ml-2"
            :color="add.starred ? 'warning': 'dark'"
          />
        </div>
        <div class="col-lg-6">
          <vs-input class="edit2_btns" label="Title" v-model="add.title" />
        </div>
        <div class="col-lg-6">
          <div style="animation:open 0.3s;" class="custom_input">
            <label for class="vs-input--label" style="display:block;">Category</label>
            <el-select
              v-model="add.categories"
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
        <div class="col-lg-12">
          <div style="animation:open 0.3s;" class="custom_input">
            <label for class="vs-input--label" style="display:block;">Assign People</label>
            <el-select
              v-model="add.assigned"
              filterable
              multiple
              allow-create
              default-first-option
              placeholder="Choose an employee"
            >
              <el-option
                v-for="employee in employees"
                :key="employee.id"
                :label="employee.name"
                :value="employee.id"
              ></el-option>
            </el-select>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="custom_input flex-between-center">
            <div class="custom_col mr-1">
              <label for class="vs-input--label" style="display:block;">Started Date</label>
              <el-date-picker v-model="add.started_date" type="date" format="dd/MM/yyyy"></el-date-picker>
            </div>
            <div class="custom_col ml-1">
              <label for class="vs-input--label" style="display:block;">Ended Date</label>
              <el-date-picker
                v-model="add.ended_date"
                type="date"
                @change="calculate()"
                format="dd/MM/yyyy"
              ></el-date-picker>
            </div>
          </div>
        </div>
        <div class="col-lg-12">
          <vs-textarea class="edit2_btns" label="Add description" v-model="add.description" />
        </div>
        <div class="col-lg-12 flex-end">
          <vs-button color="primary" class="edit_btn" @click="addTask()" type="filled">Add Task</vs-button>
          <vs-button color="warning" class="edit_btn" type="border" @click="add_active=false">Cancel</vs-button>
        </div>
      </div>
    </vs-prompt>

    <!-- Edit or view -->
    <vs-prompt class="add-task" :title="todo.title" :active.sync="view_active">
      <div class="row">
        <div class="col-12 flex-between-center">
          <vs-checkbox size="12px" class="mr-1" v-model="todo.completed">Completed</vs-checkbox>
          <div class="flex-end">
            <vs-icon
              icon="icon-alert-circle"
              icon-pack="feather"
              size="18px"
              @click="todo.important=!todo.important"
              class="pointer custom_icon"
              :color="todo.important ? 'success': 'dark'"
            />
            <vs-icon
              icon="icon-star"
              @click="todo.starred=!todo.starred"
              icon-pack="feather"
              size="18px"
              class="pointer custom_icon ml-2"
              :color="todo.starred ? 'warning': 'dark'"
            />
            <vs-icon
              icon="icon-trash"
              v-if="todo.deleted_at === null"
              @click="deleteTodo(todo.id)"
              icon-pack="feather"
              size="18px"
              class="pointer custom_icon ml-2"
            />
            <vs-icon
              v-if="todo.deleted_at !== null"
              icon="icon-rotate-ccw"
              @click="restoreTodo(todo.id)"
              icon-pack="feather"
              size="18px"
              class="pointer custom_icon ml-2"
            />
          </div>
        </div>
        <div class="col-lg-6">
          <vs-input class="edit2_btns" label="Title" v-model="todo.title" />
        </div>
        <div class="col-lg-6">
          <div style="animation:open 0.3s;" class="custom_input">
            <label for class="vs-input--label" style="display:block;">Category</label>
            <el-select
              v-model="editcategories"
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
        <div class="col-lg-12">
          <div style="animation:open 0.3s;" class="custom_input">
            <label for class="vs-input--label" style="display:block;">Assign People</label>
            <el-select
              v-model="editassigned"
              filterable
              multiple
              allow-create
              default-first-option
              placeholder="Choose an employee"
            >
              <el-option
                v-for="employee in employees"
                :key="employee.id"
                :label="employee.name"
                :value="employee.id"
              ></el-option>
            </el-select>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="custom_input flex-between-center">
            <div class="custom_col mr-1">
              <label for class="vs-input--label" style="display:block;">Started Date</label>
              <el-date-picker v-model="todo.started_date" type="date" format="dd/MM/yyyy"></el-date-picker>
            </div>
            <div class="custom_col ml-1">
              <label for class="vs-input--label" style="display:block;">Ended Date</label>
              <el-date-picker
                v-model="todo.ended_date"
                type="date"
                @change="calculate()"
                format="dd/MM/yyyy"
              ></el-date-picker>
            </div>
          </div>
        </div>
        <div class="col-lg-12">
          <vs-textarea class="edit2_btns" label="Add description" v-model="todo.description" />
        </div>
        <div class="col-lg-12 flex-end">
          <vs-button color="primary" class="edit_btn" @click="UpdateTask()" type="filled">Submit</vs-button>
          <vs-button
            color="warning"
            class="edit_btn"
            type="border"
            @click="view_active=false"
          >Cancel</vs-button>
        </div>
      </div>
    </vs-prompt>
  </div>
</template>

<script>
export default {
  props: {
    auth: {},
    mobile: false
  },
  data() {
    return {
      add: {
        assigned: [],
        categories: [],
        starred: false,
        important: false,
        started_date: "",
        ended_date: ""
      },
      view_active: false,
      data: [],
      todos: {},
      todo: {},
      editassigned: [],
      editcategories: [],
      employees: {},
      add_active: false,
      completed: true,
      active: true,
      no_data: false,
      query: null,
      categories: []
    };
  },
  components: {},
  created() {
    var index = this.$route.meta.index;
    this.$emit("index", index);
    this.active = !this.mobile;
  },
  mounted() {
    this.getEmployee();
    this.getCategories();
    this.getTodos();
  },
  watch: {
    query: function(newVal, oldVal) {
      this.search(newVal);
    }
  },
  methods: {
    filterAll() {
      if (this.mobile) {
        this.active = !this.active;
      }
      this.todos = this.data;
    },
    filter(value, type) {
      if (this.mobile) {
        this.active = !this.active;
      }
      var data = this.data;
      if (type === "boolean") {
        if (value === "starred") {
          this.todos = data.filter(function(data) {
            return data.starred === 1;
          });
        }
        if (value === "important") {
          this.todos = data.filter(function(data) {
            return data.important === 1;
          });
        }
        if (value === "completed") {
          this.todos = data.filter(function(data) {
            return data.completed === 1;
          });
        }
      }
      if (type === "trashed") {
        this.todos = data.filter(function(data) {
          return data.deleted_at !== null;
        });
      }
      if (type === "category") {
        this.todos = [];
        data.forEach(data => {
          var todos = data.categories.filter(data => {
            return data.category.name === value;
          });

          if (todos.length !== 0) {
            this.todos.push(data);
          }
        });
      }
    },
    view(data) {
      var assigned = [];
      data.assigned.forEach(data => {
        var id = data.user_id;
        assigned.push(id);
      });
      var categories = [];
      data.categories.forEach(data => {
        var name = data.category.name;
        categories.push(name);
      });
      this.todo = data;
      this.editassigned = assigned;
      this.editcategories = categories;
      this.view_active = true;
    },
    removeCat(id) {
      axios
        .delete(`web/TodoCategory/${id}`)
        .then(response => {
          this.getTodos();
          this.getCategories();
        })
        .catch(error => {});
    },
    deleteTodo(id) {
      axios
        .delete(`web/todo/${id}`)
        .then(response => {
          this.view_active = false;
          this.getTodos();
        })
        .catch(error => {});
    },
    restoreTodo(id) {
      axios
        .get(`web/todo/${id}`)
        .then(response => {
          this.getTodos();
          this.view_active = false;
        })
        .catch(error => {});
    },
    calculate() {
      var started = this.$moment(this.add.started_date);
      var ended = this.$moment(this.add.ended_date);
      this.add.day = ended.diff(started, "days");
    },
    search(query) {
      var array = this.data.filter(data =>
        data.title.toLowerCase().includes(query.toLowerCase())
      );
      if (array.length != 0) {
        this.no_data = false;
        this.todos = array;
      } else {
        this.no_data = true;
      }
    },
    getTodos() {
      axios
        .get(`web/todo`)
        .then(response => {
          this.data = response.data;
          this.todos = this.data;
        })
        .catch(error => {});
    },
    getCategories() {
      axios
        .get(`web/getCategories`)
        .then(response => {
          this.categories = response.data;
        })
        .catch(error => {});
    },
    getEmployee() {
      axios
        .get(`web/getUserEmployees`)
        .then(response => {
          this.employees = response.data;
        })
        .catch(error => {});
    },
    addTask() {
      this.add_active = false;
      this.$vs.loading({
        background: "rgba(var(--vs-theme),0.4)",
        color: "primary",
        type: "border",
        scale: 0.6
      });
      axios
        .post(`web/addTodo`, { data: this.add })
        .then(response => {
          this.getTodos();
          this.getCategories();
          this.$vs.loading.close();
          this.$message({
            message: "Congratulation! You have successfully added a task.",
            type: "success"
          });
        })
        .catch(error => {
          this.$vs.loading.close();
          this.$message({
            message: "Oops! There is an error while adding a task.",
            type: "danger"
          });
        });
    },
    UpdateTask() {
      this.todo.editassigned = this.editassigned;
      this.todo.editcategories = this.editcategories;
      axios
        .post(`web/updateTodo`, { data: this.todo })
        .then(response => {
          this.getTodos();
          this.getCategories();
          this.view_active = false;
          this.$message({
            message: "Congratulation! You have successfully added a task.",
            type: "success"
          });
        })
        .catch(error => {
          this.$message({
            message: "Oops! There is an error while adding a task.",
            type: "danger"
          });
        });
    }
  }
};
</script>

