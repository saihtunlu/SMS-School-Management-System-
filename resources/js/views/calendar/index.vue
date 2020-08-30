<template>
  <div class="row">
    <div class="col-lg-12 padding div_shadow">
      <full-calendar
        @dayClick="dayClick"
        :eventLimit="2"
        @eventClick="eventClick"
        :events="fcEvents"
        locale="en"
      >
        <div slot="fc-header-left">
          <vs-button
            color="primary"
            class="add_new_event"
            icon="icon-plus"
            @click="add_active=true"
            icon-pack="feather"
          >Add</vs-button>
        </div>
        <div slot="fc-header-right">
          <vs-chip
            class="mr-3 calendar_cat text-md mt-2"
            v-for="(data,n) in category"
            @click="removeCat(data.id,n)"
            :key="n"
            closable
          >
            <vs-icon
              icon="fiber_manual_record"
              class="text-bold mr-1"
              size="12px"
              :color="data.color"
            />
            {{data.name}}
          </vs-chip>
        </div>
      </full-calendar>
    </div>

    <!-- Add New  -->
    <vs-prompt class="add-event" title="Add Event" :active.sync="add_active">
      <div class="row">
        <div class="col-lg-12">
          <vs-input class="edit2_btns" label="Title" v-model="add.title" />
        </div>
        <div class="col-lg-12">
          <div style="animation:open 0.3s;" class="custom_input">
            <label for class="vs-input--label" style="display:block;">Category</label>
            <el-select
              v-model="add.category.name"
              @change="checkcat()"
              filterable
              allow-create
              default-first-option
              placeholder="Choose a category"
            >
              <el-option
                v-for="data in category"
                :key="data.name"
                :label="data.name"
                :value="data.name"
              ></el-option>
            </el-select>
          </div>
        </div>
        <div class="col-12 mt-4" v-if="show_color">
          <label for class="vs-input--label" style="display:block;">Choose a color for new category</label>
          <div class="flex-align-center">
            <vs-button
              class="theme_color"
              :class="{ 'active_color': add.category.color == 'primary' }"
              style="background:rgb(115, 103, 240) !important;"
              @click="add.category.color='primary' ;add.cssClass ='primary'"
            ></vs-button>

            <vs-button
              class="theme_color"
              :class="{ 'active_color': add.category.color == 'danger' }"
              style="background:rgb(255, 71, 87) !important;"
              @click="add.category.color='danger' ;add.cssClass ='danger'"
            ></vs-button>

            <vs-button
              class="theme_color"
              :class="{ 'active_color': add.category.color == 'success' }"
              style="background:rgb(70, 201, 58) !important;"
              @click="add.category.color='success';add.cssClass ='success'"
            ></vs-button>

            <vs-button
              class="theme_color"
              :class="{ 'active_color': add.category.color == 'warning' }"
              style="background:rgb(255, 186, 0) !important;"
              @click="add.category.color='warning' ;add.cssClass ='warning'"
            ></vs-button>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="custom_input flex-between-center">
            <div class="custom_col mr-1">
              <label for class="vs-input--label" style="display:block;">Started Date</label>
              <el-date-picker v-model="add.start" type="date"></el-date-picker>
            </div>
            <div class="custom_col ml-1">
              <label for class="vs-input--label" style="display:block;">Ended Date</label>
              <el-date-picker v-model="add.end" type="date"></el-date-picker>
            </div>
          </div>
        </div>
        <div class="col-lg-12">
          <vs-textarea class="edit2_btns" label="Add description" v-model="add.description" />
        </div>
        <div class="col-lg-12 flex-end">
          <vs-button color="primary" class="edit_btn" @click="addEvent()" type="filled">Add Event</vs-button>
          <vs-button color="warning" class="edit_btn" type="border" @click="add_active=false">Cancel</vs-button>
        </div>
      </div>
    </vs-prompt>

    <!-- view_event -->
    <vs-prompt class="add-event" :title="event.title" :active.sync="view_active">
      <div class="row">
        <div class="col-12 flex-end">
          <vs-icon
            icon="icon-trash"
            @click="removeEvent(event.id)"
            icon-pack="feather"
            size="18px"
            class="pointer ml-3 custom_icon"
          />
        </div>
        <div class="col-lg-12">
          <vs-input class="edit2_btns" label="Title" v-model="event.title" />
        </div>
        <div class="col-lg-12">
          <div style="animation:open 0.3s;" class="custom_input">
            <label for class="vs-input--label" style="display:block;">Category</label>
            <el-select
              v-model="editcategory.name"
              @change="checkcatView()"
              filterable
              allow-create
              default-first-option
              placeholder="Choose a category"
            >
              <el-option
                v-for="data in category"
                :key="data.id"
                :label="data.name"
                :value="data.name"
              ></el-option>
            </el-select>
          </div>
        </div>
        <div class="col-12 mt-4" v-if="show_color">
          <label for class="vs-input--label" style="display:block;">Choose a color for new category</label>
          <div class="flex-align-center">
            <vs-button
              class="theme_color"
              :class="{ 'active_color': editcategory.color == 'primary' }"
              style="background:rgb(115, 103, 240) !important;"
              @click="editcategory.color='primary' ;editcssClass ='primary'"
            ></vs-button>

            <vs-button
              class="theme_color"
              :class="{ 'active_color': editcategory.color == 'danger' }"
              style="background:rgb(255, 71, 87) !important;"
              @click="editcategory.color='danger' ;editcssClass ='danger'"
            ></vs-button>

            <vs-button
              class="theme_color"
              :class="{ 'active_color': editcategory.color == 'success' }"
              style="background:rgb(70, 201, 58) !important;"
              @click="editcategory.color='success';editcssClass ='success'"
            ></vs-button>

            <vs-button
              class="theme_color"
              :class="{ 'active_color': editcategory.color == 'warning' }"
              style="background:rgb(255, 186, 0) !important;"
              @click="editcategory.color='warning' ;editcssClass ='warning'"
            ></vs-button>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="custom_input flex-between-center">
            <div class="custom_col mr-1">
              <label for class="vs-input--label" style="display:block;">Started Date</label>
              <el-date-picker v-model="event.start" type="date"></el-date-picker>
            </div>
            <div class="custom_col ml-1">
              <label for class="vs-input--label" style="display:block;">Ended Date</label>
              <el-date-picker v-model="event.end" type="date"></el-date-picker>
            </div>
          </div>
        </div>
        <div class="col-lg-12">
          <vs-textarea class="edit2_btns" label="Add description" v-model="event.description" />
        </div>
        <div class="col-lg-12 flex-end">
          <vs-button color="primary" class="edit_btn" @click="updateEvent()" type="filled">Submit</vs-button>
          <vs-button
            color="warning"
            class="edit_btn"
            type="border"
            @click="event={};view_active=false"
          >Cancel</vs-button>
        </div>
      </div>
    </vs-prompt>
  </div>
</template>

<script>
export default {
  data() {
    return {
      category: [],
      fcEvents: [],
      add: {
        category: {
          name: "",
          color: ""
        },
        cssClass: "",
        title: "",
        start: "",
        end: ""
      },
      event: {
        cssClass: "",
        title: "",
        start: "",
        end: ""
      },
      editcategory: {
        name: "",
        color: ""
      },
      editcssClass: "",
      view_active: false,
      data: [],
      show_color: false,
      add_active: false
    };
  },
  created() {
    var index = this.$route.meta.index;
    this.$emit("index", index);
  },
  components: {
    "full-calendar": require("../../components/vue-fullcalendar.js")
  },
  mounted() {
    this.getEvents();
    this.getCategories();
  },
  methods: {
    checkcat() {
      var check = this.category.filter(data => {
        return data.name === this.add.category.name;
      });
      if (check.length === 0) {
        this.show_color = true;
      } else {
        this.add.category = check[0];
        this.add.cssClass = this.add.category.color;
      }
    },
    checkcatView() {
      var check = this.category.filter(data => {
        return data.name === this.editcategory.name;
      });

      if (check.length === 0) {
        this.show_color = true;
      } else {
        this.editcategory = check[0];
        this.event.cssClass = this.editcategory.color;
      }
    },
    dayClick(data) {
      this.add.start = data;
      this.add_active = true;
    },
    eventClick(data) {
      this.event = data;
      console.log(" this.event: ", this.event);
      this.view_active = true;
      if (data.categories) {
        this.editcategory = data.categories.category;
      }
    },
    addEvent() {
      console.log("this.add: ", this.add);
      this.add.start = this.$moment(this.add.start).format(
        "YYYY-MM-DD HH:mm:ss"
      );
      this.add.end = this.$moment(this.add.start).format("YYYY-MM-DD HH:mm:ss");

      axios
        .post(`web/addEvent`, { data: this.add })
        .then(response => {
          this.fcEvents.push(response.data);
          this.getCategories();
          this.$message({
            message: "Congratulation! You have successfully added a task.",
            type: "success"
          });
          this.add_active = false;
          this.show_color = false;
        })
        .catch(error => {
          this.$message({
            message: "Oops! There is an error while adding an event.",
            type: "danger"
          });
        });
    },
    updateEvent() {
      this.event.category = this.editcategory;
      if (this.event.cssClass === "") {
        this.event.cssClass = this.editcssClass;
      }
      this.event.start = this.$moment(this.event.start).format(
        "YYYY-MM-DD HH:mm:ss"
      );
      this.event.end = this.$moment(this.event.end).format(
        "YYYY-MM-DD HH:mm:ss"
      );
      axios
        .post(`web/updateEvent`, { data: this.event })
        .then(response => {
          var id = response.data.id;
          var index = this.fcEvents
            .map(x => {
              return x.id;
            })
            .indexOf(id);
          this.fcEvents[index] = response.data;
          this.getCategories();
          this.$message({
            message: "Congratulation! You have successfully added a task.",
            type: "success"
          });
          this.view_active = false;
        })
        .catch(error => {
          this.$message({
            message: "Oops! There is an error while adding an event.",
            type: "danger"
          });
        });
    },
    getEvents() {
      axios
        .get(`web/events`)
        .then(response => {
          this.data = response.data;
          this.fcEvents = this.data;
        })
        .catch(error => {});
    },
    getCategories() {
      axios
        .get(`web/getCalendarCategories`)
        .then(response => {
          this.category = response.data;
        })
        .catch(error => {});
    },
    removeCat(id, index) {
      axios
        .delete(`web/removeCalendarCat/${id}`)
        .then(response => {
          this.category.splice(index, 1);
        })
        .catch(error => {});
    },
    removeEvent(id, index) {
      axios
        .delete(`web/event/${id}`)
        .then(response => {
          var index = this.fcEvents
            .map(x => {
              return x.id;
            })
            .indexOf(id);
          this.fcEvents.splice(index, 1);
          this.view_active = false;
        })
        .catch(error => {});
    }
  }
};
</script>

<style lang="scss" scoped>
</style>