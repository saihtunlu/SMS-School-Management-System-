<template>
  <div id="app" class="container-fluid">
    <vm-back-top :bottom="30" :duration="1000" :timing="'ease'">
      <vs-button
        color="rgba(var(--vs-primary),0.7)"
        id="back_ro_top"
        type="filled"
        size="38px"
        icon-pack="feather"
        icon="icon-chevron-up"
      ></vs-button>
    </vm-back-top>

    <SideBar
      :logo="general_detail.logo"
      :title="general_detail.title"
      :active="showSidebar"
      :mobile="mobile"
      :noti="noti"
      :event="event"
      :todo_noti="todo_noti"
      v-if="auth"
      :index="index"
      @reduce="reduce_sidebar()"
      @close="show_sidebar()"
    />
    <div class="content_body" :class="{ reduce: reduce == true, guest: !auth,full_width:mobile }">
      <div class="stickey_header scrolled" v-if="auth" :class="{sticky: sticky }">
        <NavBar :mobile="mobile" :noti="noti" :auth="auth" @show_sidebar="show_sidebar" />
      </div>
      <vue-page-transition :name="animation">
        <router-view
          :current_year_id="current_year_id"
          @read="getNoti"
          :mobile="mobile"
          @index="setIndex"
          @event="calendar_event"
          :auth="auth"
          class="router_view"
          @show_sidebar="show_sidebar"
        />
      </vue-page-transition>
      <footer v-if="showFooter" style="margin-bottom:-15px; margin-top:1.5rem;">
        <div class="flex-between-center">
          <p class="text-lg">
            COPYRIGHT ©2020
            <a
              style="color:rgb(var(--vs-primary));"
              href="#"
            >{{general_detail.title}}</a>, All rights Reserved
          </p>
          <p class="text-lg right_footer_text">
            Hand-crafted & Made with
            <i
              class="feather icon-heart"
              style="color:rgb(var(--vs-warning));"
            ></i>
          </p>
        </div>
      </footer>
      <Theme @Sticky="ChangeSticky" @footer="changeFooter" @animation="setAni" />
    </div>
    <v-tour name="myTour" :steps="steps"></v-tour>
  </div>
</template>
<script>
import SideBar from "./components/SideBar";
import Theme from "./components/themeCustomizer";
import NavBar from "./components/navbar.vue";
import { isMobile } from "mobile-device-detect";
import { isTablet } from "mobile-device-detect";

export default {
  data() {
    return {
      steps: [
        {
          target: "#v-step-0", // We're using document.querySelector() under the hood
          content: `Toggle Collapse Sidebar.`
        },
        {
          target: ".v-step-1",
          content:
            "Create your own bookmarks. You can also re-arrange them using drag & drop."
        },
        {
          target: '[data-v-step="2"]',
          content: "Customize template based on your preference.",
          params: {
            placement: "left" // Any valid Popper.js placement. See https://popper.js.org/popper-documentation.html#Popper.placements
          }
        }
      ],
      isMobile: isMobile,
      isTablet: isTablet,
      mobile: false,
      noti: 0,
      todo_noti: 0,
      showFooter: true,
      animation: "zoom-in-out",
      sticky: true,
      index: 1,
      reduce: false,
      auth: null,
      event: null,
      showSidebar: true,
      general_detail: {},
      colors: {},
      current_year_id: null,
      calendarEvent: null
    };
  },
  created() {
    this.getGeneral();
    this.getYear();
    this.togoNoti();
    var ani = localStorage.getItem("animation");
    if (ani) {
      this.animation = ani;
    }
    this.setAuth();

    if (this.isMobile || this.isTablet) {
      this.showSidebar = false;
      this.mobile = true;
    } else {
      this.showSidebar = true;
      this.mobile = false;
    }
  },
  mounted() {
    if (this.auth) {
      this.listenForChanges();
      this.listen();
      this.getNoti();
      this.EchoMessage();
    }
    if (!this.auth) {
      this.showFooter = false;
    }
    if (!this.mobile && this.auth) {
      this.$tours["myTour"].start();
    }
  },
  components: {
    NavBar,
    SideBar,
    Theme
  },
  methods: {
    getGeneral() {
      axios
        .get(`web/getGeneral`)
        .then(response => {
          this.general_detail = response.data;
        })
        .catch(error => {});
    },
    show_sidebar() {
      this.showSidebar = !this.showSidebar;
    },
    listen() {
      Echo.join("App")
        .here(users => {})
        .joining(user => {
          axios.post("/web/online/", { id: user.id });
        })
        .leaving(user => {
          axios.post("/web/offline/", { id: user.id });
        });
    },
    listenForChanges() {
      Echo.channel("Add_student").listen("AddStudent", data => {
        Notification.requestPermission(permission => {
          let notification = new Notification("Adding New Student!", {
            body: data.data.Auth + " has added " + data.data.Student + ".", // content for the alert
            icon: "http://127.0.0.1:8000/" + data.data.Image
            // optional image url
          });
          notification.onclick = () => {
            window.open(window.location.href);
          };
        });
      });
    },
    changeFooter(data) {
      this.showFooter = data;
    },
    ChangeSticky(data) {
      this.sticky = data;
    },
    setAni(data) {
      this.animation = data;
    },
    EchoMessage() {
      Echo.private(`messages.${this.auth.id}`).listen("NewMessage", e => {
        axios
          .post(`web/deliveredMessage`, { message: e.message })
          .then(response => {})
          .catch(error => {});
        this.getNoti();
        Notification.requestPermission(permission => {
          let notification = new Notification(e.message.from_contact.name, {
            body: e.message.text, // message body
            icon:
              "http://127.0.0.1:8000/" + e.message.from_contact.profile_image
            // optional image url
          });
          notification.onclick = () => {
            window.open(window.location.href);
          };
        }); //Push Notification
      });
    },
    getNoti() {
      axios
        .get(`web/getNoti`)
        .then(response => {
          this.noti = response.data;
        })
        .catch(error => {});
    },
    setIndex(data) {
      this.index = data;
    },
    setAuth() {
      if (localStorage.getItem("Auth")) {
        this.auth = JSON.parse(localStorage.getItem("Auth"));
        this.role = this.auth.user_role.role.name;
      }
    },
    reduce_sidebar() {
      this.reduce = !this.reduce;
    },
    calendar_event(data) {
      this.event = data;
      console.log("this.event: ", this.event);
    },
    getYear() {
      axios
        .get(`web/currentYear/`)
        .then(response => {
          var data = response.data;
          const today = new Date();
          var tomorrow = new Date(today);
          tomorrow.setDate(tomorrow.getDate() - 1);
          tomorrow = this.$moment(tomorrow).format("YYYY-MM-DD HH:mm:ss");
          this.current_year_id = data.filter(data => {
            var start = this.$moment(data.start).format("YYYY-MM-DD HH:mm:ss");
            var end = this.$moment(data.end).format("YYYY-MM-DD HH:mm:ss");

            return (
              this.$moment(today).isSameOrAfter(start) &&
              this.$moment(tomorrow).isSameOrBefore(end)
            );
          })[0].id;
          console.log(" this.current_year_id: ", this.current_year_id);

          var check = localStorage.getItem("current_year_id");
          if (!check) {
            localStorage.setItem("current_year_id", this.current_year_id);
          }
          // this.getClassrooms(this.year.id);
        })
        .catch(error => {});
    },
    togoNoti() {
      axios
        .get(`web/todoNoti`)
        .then(response => {
          console.log(response.data);
          this.todo_noti = response.data;
        })
        .catch(error => {
          console.log("error: ", error);
        });
    }
  }
};
</script>
