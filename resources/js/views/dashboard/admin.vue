<template>
  <div class="row" style="margin:0px -30px;">
    <div class="col-lg-3 col-6 mb-base col-md-3">
      <div class="summery classrooms">
        <div class="flex-between-center">
          <h5>Total Classrooms</h5>
          <vs-dropdown vs-trigger-click class="contact_details">
            <vs-icon icon="info" color="rgb(var(--vs-dark),0.3)" size="14px" />
            <vs-dropdown-menu class="nav_dropdown" id="chat_dropdown">
              <vs-dropdown-item
                class="nav_dropdown_item"
                v-for="year in years"
                @click="getTotalClassroom(year)"
                :key="year.id"
              >
                <vs-icon icon-pack="feather" icon="icon-calendar" />
                {{year.title}}
              </vs-dropdown-item>
            </vs-dropdown-menu>
          </vs-dropdown>
        </div>
        <div class="flex" style="justify-content: space-between;align-items: flex-end;">
          <vs-icon class="icon_1 openicon" icon-pack="el-icon" icon="el-icon-collection"></vs-icon>
          <p class="text-md mb-0">
            <animated-number :value="totalClassroom" :formatValue="format" :duration="300" />
          </p>

          <vs-icon class="icon_2" color="primary" icon="bar_chart" />
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-6 col-md-3 mb-base">
      <div class="summery teachers">
        <div class="flex-between-center">
          <h5>Total Teachers</h5>
        </div>
        <div class="flex" style="justify-content: space-between;align-items: flex-end;">
          <vs-icon class="icon_1 openicon" icon-pack="feather" icon="icon-user"></vs-icon>
          <p class="text-md mb-0">
            <animated-number :value="totalTeacher" :formatValue="format" :duration="300" />
          </p>
          <vs-icon class="icon_2" color="danger" icon="bar_chart" />
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-6 col-md-3 mb-base">
      <div class="summery students">
        <div class="flex-between-center">
          <h5>Total Students</h5>
          <vs-dropdown vs-trigger-click class="contact_details">
            <vs-icon icon="info" color="rgb(var(--vs-dark),0.3)" size="14px" />
            <vs-dropdown-menu class="nav_dropdown" id="chat_dropdown">
              <vs-dropdown-item
                class="nav_dropdown_item"
                v-for="year in years"
                @click="getTotalStudents(year)"
                :key="year.id"
              >
                <vs-icon icon-pack="feather" icon="icon-calendar" />
                {{year.title}}
              </vs-dropdown-item>
            </vs-dropdown-menu>
          </vs-dropdown>
        </div>
        <div class="flex" style="justify-content: space-between;align-items: flex-end;">
          <vs-icon class="icon_1 openicon" icon="mood"></vs-icon>
          <p class="text-md mb-0">
            <animated-number :value="totalStudent" :formatValue="format" :duration="300" />
          </p>
          <vs-icon class="icon_2" color="success" icon="bar_chart" />
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-6 col-md-3 mb-base">
      <div class="summery parents">
        <div class="flex-between-center">
          <h5>Total Parents</h5>
        </div>
        <div class="flex" style="justify-content: space-between;align-items: flex-end;">
          <vs-icon class="icon_1 openicon" icon-pack="feather" icon="icon-users"></vs-icon>
          <p class="text-md mb-0">
            <animated-number :value="totalParent" :formatValue="format" :duration="300" />
          </p>
          <vs-icon class="icon_2" color="warning" icon="bar_chart" />
        </div>
      </div>
    </div>

    <div class="col-lg-7 col-mg-7">
      <div
        id="invoice_loading"
        class="mb-base padding div_shadow radius-1 vs-con-loading__container"
        style="padding-bottom:0px !important;"
      >
        <div class="flex-between-center">
          <h5>Invoice Status ({{invoice_year}})</h5>

          <vs-dropdown vs-trigger-click class="contact_details">
            <vs-icon icon="more_horiz" />
            <vs-dropdown-menu class="nav_dropdown" id="chat_dropdown">
              <vs-dropdown-item
                v-for="(n,index) in 5"
                :key="index"
                @click="getInvoice(parseInt(new Date().getFullYear())-index)"
                class="nav_dropdown_item"
              >
                <vs-icon icon-pack="feather" icon="icon-calendar" />
                {{parseInt(new Date().getFullYear())-index}}
              </vs-dropdown-item>
            </vs-dropdown-menu>
          </vs-dropdown>
        </div>
        <div id="Chart1" class="flex-align-center flex-center"></div>
      </div>
    </div>
    <div class="col-lg-5 col-mg-5">
      <div class="mb-base padding div_shadow radius-1 vs-con-loading__container" id="pie_chat">
        <div class="flex-between-center">
          <h5>Students ({{current_year.title}} )</h5>
          <vs-dropdown vs-trigger-click class="contact_details">
            <vs-icon icon="more_horiz" />
            <vs-dropdown-menu class="nav_dropdown" id="chat_dropdown">
              <vs-dropdown-item
                class="nav_dropdown_item"
                v-for="year in years"
                @click="getStudents(year)"
                :key="year.id"
              >
                <vs-icon icon-pack="feather" icon="icon-calendar" />
                {{year.title}}
              </vs-dropdown-item>
            </vs-dropdown-menu>
          </vs-dropdown>
        </div>
        <div id="Chart2" class="flex-align-center flex-center"></div>
        <div class="flex-between-center mb-3">
          <div class="left_progress">
            <vs-progress :height="7" :percent="male_percent" color="primary"></vs-progress>
            <p class="text-md mb-1">Male Students</p>
            <h5 class="text-md mb-0">
              <animated-number :value="male" :formatValue="format" :duration="300" />
            </h5>
          </div>
          <div class="right_progress">
            <vs-progress :height="7" :percent="female_percent" color="danger"></vs-progress>
            <p class="text-md mb-1">Female Students</p>
            <h5 class="text-md mb-0">
              <animated-number :value="female" :formatValue="format" :duration="300" />
            </h5>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-7 mb-base col-md-7">
      <div class="padding div_shadow">
        <div class="flex-between-center">
          <h5>Today Events ({{new Date() | moment('DD/MM/YYYY')}} )</h5>
          <vs-dropdown vs-trigger-click class="contact_details">
            <vs-icon icon="more_horiz" />
            <vs-dropdown-menu class="nav_dropdown" id="chat_dropdown">
              <vs-dropdown-item class="nav_dropdown_item" to="/calendar">
                <vs-icon icon-pack="feather" icon="icon-eye" />View
              </vs-dropdown-item>
            </vs-dropdown-menu>
          </vs-dropdown>
        </div>
        <div style="height:288px; overflow-y:auto;">
          <div
            v-if="calendar.length === 0"
            style="height: 100%;"
            class="flex-align-center flex-center"
          >
            <div class="flex-align-center flex-column">
              <img src="/storage/image/noEvent.png" style="width:120px;" alt />
              <p
                class="text-small text-center mt-3"
                style="color:rgb(var(--vs-dark),0.5)"
              >No Calendar Event Today!</p>
            </div>
          </div>
          <div v-if="calendar.length !== 0">
            <div
              class="today_event flex-align-center mt-2"
              v-for="event in calendar"
              :key="event.id"
            >
              <vs-icon icon="icon-check" :color="event.cssClass" icon-pack="feather" class="mr-3" />
              <div class="event_details" style="max-width: 90%;">
                <p class="text-md mb-1 text-bold">{{event.title}}</p>
                <p class="mb0 text-small truncate">{{event.description}}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-5 mb-base col-md-7">
      <div class="padding div_shadow" style="padding-bottom:0px !important;">
        <FunctionalCalendar />
      </div>
    </div>
  </div>
</template>

<script>
import { FunctionalCalendar } from "vue-functional-calendar";
import AnimatedNumber from "animated-number-vue";

export default {
  props: {
    current_year_id: {
      type: Number,
      default: null
    }
  },
  data() {
    return {
      Revenue: [],
      Profit: [],
      students: [],
      monthNames: [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December"
      ],
      invoice_year: 0,
      years: [],
      male_percent: 0,
      female_percent: 0,
      male: 0,
      female: 0,
      year_id: null,
      current_year: {},
      calendar: [],
      totalClassroom: 0,
      totalTeacher: 0,
      totalStudent: 0,
      totalParent: 0
    };
  },
  created() {
    if (localStorage.getItem("current_year_id")) {
      this.year_id = localStorage.getItem("current_year_id");
    }
    this.getYears();
    this.getInvoice();
    this.getCalendar();
    this.getTotalTeacher();
  },
  mounted() {
    this.chart1();
  },
  components: {
    FunctionalCalendar,
    AnimatedNumber
  },
  methods: {
    format(value) {
      return value.toFixed(0);
    },
    chart1() {
      var options = {
        colors: ["#B47AF3", "#7AD3F3"],
        series: [
          {
            name: "Net Profit",
            data: this.Profit
          },
          {
            name: "Revenue",
            data: this.Revenue
          }
        ],
        chart: {
          type: "bar",
          height: 348,
          toolbar: {
            show: false
          },
          zoom: {
            enabled: false
          },
          animations: {
            enabled: true,
            easing: "linear",
            dynamicAnimation: {
              speed: 1000
            }
          }
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: "75%",
            endingShape: "rounded"
          }
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ["transparent"]
        },
        xaxis: {
          categories: this.monthNames
        },
        yaxis: {
          title: {
            // text: "$ (thousands)"
          }
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function(val) {
              return val + " Ks";
            }
          }
        }
      };

      var chart = new ApexCharts(document.getElementById("Chart1"), options);
      chart.render();
      setTimeout(() => {
        this.$vs.loading.close("#invoice_loading > .con-vs-loading");
      }, 100);
    },
    chart2() {
      var options = {
        colors: ["#B47AF3", "#F2135D"],
        series: [this.male_percent, this.female_percent],
        chart: {
          type: "donut"
        },
        labels: ["Male", "Female"],
        responsive: [
          {
            breakpoint: 480,
            options: {
              chart: {
                width: 200
              },
              legend: {
                position: "bottom"
              }
            }
          }
        ]
      };

      var chart = new ApexCharts(document.getElementById("Chart2"), options);
      chart.render();
      setTimeout(() => {
        this.$vs.loading.close("#pie_chat > .con-vs-loading");
      }, 100);
    },
    getInvoice(Year) {
      this.Profit = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
      this.Revenue = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
      if (!Year) {
        Year = new Date().getFullYear();
      }
      this.invoice_year = Year;
      axios
        .get(`web/invoiceStatus/${Year}`)
        .then(response => {
          this.$vs.loading({
            background: "rgb(var(--vs-theme),0.2)",
            color: "primary",
            container: "#invoice_loading",
            type: "border",
            scale: 0.4
          });
          document.getElementById("Chart1").innerHTML = "";

          var data = response.data;
          data.forEach(data => {
            this.Revenue[parseInt(data.months) - 1] = parseInt(data.total);
            var profit = parseInt(data.total) - parseInt(data.primary_total);
            this.Profit[parseInt(data.months) - 1] = parseInt(profit);
          });

          this.chart1();
        })
        .catch(error => {
          console.log("error: ", error);
        });
    },
    getStudents(year) {
      if (!year) {
        year = this.years[0];
      }
      this.current_year = year;
      axios
        .get(`web/studentDashboard/${year.id}`)
        .then(response => {
          this.$vs.loading({
            background: "rgb(var(--vs-theme),0.2)",
            color: "primary",
            container: "#pie_chat",
            type: "border",
            scale: 0.4
          });
          document.getElementById("Chart2").innerHTML = "";
          var data = response.data;
          if (data.length != 0) {
            this.male = data.filter(data => {
              return data.gender === "male";
            }).length;
            this.male_percent = (this.male / data.length) * 100;

            this.female = data.filter(data => {
              return data.gender === "female";
            }).length;
            this.female_percent = (this.female / data.length) * 100;
          } else {
            this.female_percent = 0;
            this.male_percent = 0;
            this.male = 0;
            this.female = 0;
          }
          this.chart2();
        })
        .catch(error => {});
    },
    getYears() {
      axios
        .get(`web/years`)
        .then(response => {
          this.years = response.data;
          this.getStudents();
          this.getTotalClassroom();
          this.getTotalStudents();
        })
        .catch(error => {});
    },
    getCalendar() {
      axios
        .get(`web/calendarDashboard`)
        .then(response => {
          console.log(response.data);
          var data = response.data;
          const today = new Date();
          var tomorrow = new Date(today);
          tomorrow.setDate(tomorrow.getDate() - 1);
          tomorrow = this.$moment(tomorrow).format("YYYY-MM-DD HH:mm:ss");
          console.log("tomorrow: ", tomorrow);
          this.calendar = data.filter(data => {
            var start = this.$moment(data.start).format("YYYY-MM-DD HH:mm:ss");
            var end = this.$moment(data.end).format("YYYY-MM-DD HH:mm:ss");

            return (
              this.$moment(today).isSameOrAfter(start) &&
              this.$moment(tomorrow).isSameOrBefore(end)
            );
            this.$emit("calendar_event", this.calendar.length);
          });

          this.$emit("calendarEvent", this.calendar.length);
        })
        .catch(error => {
          console.log("error: ", error);
        });
    },
    getTotalClassroom(year) {
      if (!year) {
        year = this.years[0];
      }
      console.log("year: ", year);

      axios
        .get(`web/classroomDashboard/${year.id}`)
        .then(response => {
          var data = response.data;
          this.totalClassroom = data.length;
        })
        .catch(error => {
          console.log("error: ", error);
        });
    },
    getTotalTeacher() {
      axios
        .get(`web/getTeachers/`)
        .then(response => {
          var data = response.data;
          this.totalTeacher = data.length;
        })
        .catch(error => {
          console.log("error: ", error);
        });
    },
    getTotalStudents(year) {
      if (!year) {
        year = this.years[0];
      }
      axios
        .get(`web/studentDashboard/${year.id}`)
        .then(response => {
          this.totalStudent = response.data.length;
          this.totalParent = parseInt(this.totalStudent) * 2;
          console.log("response.data: ", response.data);
        })
        .catch(error => {});
    }
  }
};
</script>

<style lang="scss" scoped>
</style>