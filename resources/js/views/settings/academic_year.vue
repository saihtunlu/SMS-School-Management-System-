<template>
  <div class="row vs-con-loading__container" style="overflow: visible;" id="permission_loading">
    <vs-prompt id="send_invoice" title="Adding New Academic Year" :active.sync="active">
      <div class="row">
        <div class="col-lg-12">
          <vs-input class="edit2_btns" label="Title" v-model="year.title" />
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">From</label>
            <el-date-picker
              v-model="year.from"
              format="dd/MM/yyyy"
              value-format="dd/MM/yyyy"
              type="date"
              placeholder="Pick a year"
            ></el-date-picker>
          </div>
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">To</label>
            <el-date-picker
              v-model="year.to"
              format="dd/MM/yyyy"
              value-format="dd/MM/yyyy"
              type="date"
              placeholder="Pick a year"
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
          <vs-input class="edit2_btns" label="Title" v-model="year.title" />

          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Start</label>
            <el-date-picker
              v-model="year.from"
              format="dd/MM/yyyy"
              value-format="dd/MM/yyyy"
              type="date"
              placeholder="Pick a date"
            ></el-date-picker>
          </div>
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">From</label>
            <el-date-picker
              v-model="year.to"
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
            @click="editactive=false;year={}"
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
          icon="icon-clock"
          type="flat"
        ></vs-button>Academic Years
      </h4>
      <vs-button
        color="primary"
        type="filled"
        class="add_new"
        icon-pack="feather"
        icon="icon-plus"
        @click="add_new()"
      >Add</vs-button>
    </div>
    <vs-divider class="mt-0" color="rgb(var(--vs-gray2),0.5)" />

    <div class="col-lg-12 mt-4">
      <div class="default_permissions_content">
        <div style="overflow-x:auto; overflow-y:hidden;" class="vs-table--tbody">
          <table class="permisson_table" id="subject-table" cellspacing="10">
            <thead>
              <tr class="table_header">
                <th class="px-3 text-center py-2">No.</th>
                <th class="px-3 text-center py-2">Title</th>
                <th class="px-3 text-center py-2">From</th>
                <th class="px-3 text-center py-2">To</th>
                <th class="px-3 text-center py-2">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr class="table_content" v-for="(year,index) in years" v-bind:key="index">
                <td
                  style="border-top-left-radius: .5rem;border-bottom-left-radius: .5rem;"
                >{{parseInt(index)+1}}</td>
                <td>{{year.title}}</td>
                <td>{{year.from}}</td>
                <td>{{year.to}}</td>

                <td
                  class="flex-center"
                  style="border-top-right-radius: .5rem;border-bottom-right-radius: .5rem;"
                >
                  <vs-button
                    color="dark"
                    type="flat"
                    icon-pack="feather"
                    class="mr-1"
                    @click="editYear(year,index)"
                    icon="icon-edit"
                  ></vs-button>
                  <vs-button
                    color="dark"
                    type="flat"
                    @click="deleteYear(year.id,index)"
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
      years: [],
      data: [{}],
      year: {},
      edit_index: 0
    };
  },
  created() {
    this.getYears();
  },
  mounted() {},
  methods: {
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
        .post(`web/updateYear`, { data: this.year })
        .then(response => {
          this.$message({
            message: "Successfully updated!",
            type: "success"
          });
          var index = this.years
            .map(x => {
              return x.id;
            })
            .indexOf(this.year.id);
          this.years[index] = this.year;
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
    editYear(data, index) {
      this.year = data;
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
        .post("/web/createYear", {
          data: this.year
        })
        .then(response => {
          this.$message({
            message: "Successfully added!",
            type: "success"
          });
          this.years.push(this.year);
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

    getYears() {
      axios
        .get(`web/years`)
        .then(response => {
          console.log(response.data);
          this.years = response.data;
        })
        .catch(error => {
          console.log("error: ", error);
        });
    },
    deleteYear(id) {
      axios
        .delete(`web/removeYear/${id}`)
        .then(response => {
          this.getYears();
        })
        .catch(error => {});
    }
  }
};
</script>

