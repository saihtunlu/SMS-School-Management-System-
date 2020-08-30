<template>
  <div class="row float_nav" :class="{ guest_Nav: !auth}" v-if="auth">
    <div class="nav_bar">
      <div v-if="searching" class="nav_search">
        <vs-input
          icon="icon-search"
          icon-pack="feather"
          id="main_search"
          class="main_search"
          ref="main_search"
          v-model="value1"
          icon-no-border
          :autofocus="true"
        />
        <vs-icon
          class="close_search"
          icon="icon-x"
          icon-pack="feather"
          size="22px"
          color="dark"
          @click="searching = !searching"
        />
      </div>
      <div v-if="!searching" class="nav_items">
        <div class="nav_left ml-2" v-if="show_fav">
          <draggable
            id="favs"
            v-model="favs"
            class="flex-center"
            @start="drag = true"
            @end="drag = false"
            @change="change_fav"
          >
            <div class="openicon" v-for="(fav, n) in favs" :key="n">
              <router-link :to="favs[n].link">
                <vs-icon
                  transparent
                  class="nav_icon custom_icon"
                  :icon="fav.icon"
                  icon-pack="feather"
                  size="18px"
                />
              </router-link>
              <!-- <el-tooltip class="item" effect="dark" :content="fav.content" placement="bottom"> -->

              <!-- </el-tooltip> -->
            </div>
          </draggable>

          <el-dropdown trigger="click" class="v-step-1">
            <vs-icon
              class="pointer"
              icon="icon-star"
              icon-pack="feather"
              size="24px"
              color="warning"
            />
            <el-dropdown-menu slot="dropdown" class="select_fav_menu">
              <el-dropdown-item class="select_fav">
                <span @click="changeTodo">
                  <vs-icon
                    class="select_fav_icon"
                    icon="icon-check-square"
                    icon-pack="feather"
                    size="18px"
                    color="dark"
                  />Todo
                </span>
                <vs-icon icon="icon-star" icon-pack="feather" size="18px" :color="todocolor" />
              </el-dropdown-item>
              <el-dropdown-item class="select_fav">
                <span @click="changeChat">
                  <vs-icon
                    class="select_fav_icon"
                    icon="icon-message-square"
                    icon-pack="feather"
                    size="18px"
                    color="dark"
                  />Chat
                </span>
                <vs-icon icon="icon-star" icon-pack="feather" size="18px" :color="chatcolor" />
              </el-dropdown-item>
              <el-dropdown-item class="select_fav">
                <span @click="changeMail">
                  <vs-icon
                    class="select_fav_icon"
                    icon="icon-mail"
                    icon-pack="feather"
                    size="18px"
                    color="dark"
                  />E-mail
                </span>
                <vs-icon icon="icon-star" icon-pack="feather" size="18px" :color="mailcolor" />
              </el-dropdown-item>
              <el-dropdown-item class="select_fav">
                <span @click="changeCalendar">
                  <vs-icon
                    class="select_fav_icon"
                    icon="icon-calendar"
                    icon-pack="feather"
                    size="18px"
                    color="dark"
                  />Calendar
                </span>
                <vs-icon icon="icon-star" icon-pack="feather" size="18px" :color="calendarcolor" />
              </el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </div>
        <div class="nav_left ml-2" v-if="show_menu_btn">
          <vs-icon
            class="nav_icon"
            icon="icon-menu"
            @click="$emit('show_sidebar')"
            icon-pack="feather"
            size="21px"
            color="dark"
          />
        </div>
        <div class="nav_right">
          <vs-icon
            class="nav_icon flex-align-center flex-center"
            icon="icon-search"
            icon-pack="feather"
            size="24px"
            color="dark"
            @click="searching=!searching"
          />

          <vs-avatar
            class="nav_icon"
            color="transparent"
            text-color="dark"
            icon="icon-bell"
            icon-pack="feather"
            size="24px"
            badge-color="primary"
            :badge="noti"
          />

          <div class="auth_info openicon" v-if="!mobile">
            <span class="auth_name">{{ auth.name }}</span>
            <span class="auth_type">
              <vs-icon
                v-if="auth.user_role.role.name =='Administrator'"
                size="10px"
                color="success"
                icon="fiber_manual_record"
              ></vs-icon>
              <vs-icon
                v-if=" auth.user_role.role.name =='Manager'"
                size="10px"
                color="primary"
                icon="fiber_manual_record"
              ></vs-icon>
              <vs-icon
                v-if="auth.user_role.role.name =='Teacher'"
                size="10px"
                color="warning"
                icon="fiber_manual_record"
              ></vs-icon>
              {{ auth.user_role.role.name }}
            </span>
          </div>
          <vs-dropdown vs-trigger-click color="none">
            <el-avatar class="shadow-md openicon" :size="40" :src="auth.profile_image"></el-avatar>
            <vs-dropdown-menu class="nav_dropdown">
              <!-- <vs-dropdown-item class="nav_dropdown_item">
                <vs-icon icon-pack="feather" icon="icon-user" />My Account
              </vs-dropdown-item>
              <vs-dropdown-item class="nav_dropdown_item">
                <vs-icon icon-pack="feather" icon="icon-user" />option 2
              </vs-dropdown-item>-->

              <vs-dropdown-item divider class="nav_dropdown_item" @click="logout()">
                <vs-icon icon-pack="feather" icon="icon-log-out" />Sign Out
              </vs-dropdown-item>
            </vs-dropdown-menu>
          </vs-dropdown>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import draggable from "vuedraggable";

export default {
  props: {
    auth: {},
    noti: 0,
    mobile: false
  },
  data() {
    return {
      favs: [
        {
          id: 1,
          icon: "icon-check-square",
          content: "Todo",
          link: "/todo"
        },
        {
          id: 2,
          icon: "icon-message-square",
          content: "Chat",
          link: "/chat"
        },
        {
          id: 3,
          icon: "icon-mail",
          content: "Mail",
          link: "/mail"
        },
        {
          id: 4,
          icon: "icon-calendar",
          content: "Calendar",
          link: "/calendar"
        }
      ],
      show_fav: true,
      show_menu_btn: false,
      todo: true,
      chat: true,
      mail: true,
      calendar: true,
      todocolor: "warning",
      chatcolor: "warning",
      mailcolor: "warning",
      calendarcolor: "warning",
      searching: false,
      query: null,
      show: false,
      reduce: false,
      role: "",
      active: false
    };
  },
  created() {
    if (this.mobile) {
      this.show_menu_btn = true;
      this.show_fav = false;
    }
    var checkFav = localStorage.getItem("favs");
    if (checkFav) {
      this.favs = JSON.parse(localStorage.getItem("favs"));
      var todo = this.favs.find(fav => fav.id === 1);
      var chat = this.favs.find(fav => fav.id === 2);
      var mail = this.favs.find(fav => fav.id === 3);
      var calendar = this.favs.find(fav => fav.id === 4);

      if (todo) {
        this.todocolor = "warning";
      } else {
        this.todocolor = "dark";
        this.todo = false;
      }
      if (chat) {
        this.chatcolor = "warning";
      } else {
        this.chatcolor = "dark";
        this.chat = false;
      }
      if (mail) {
        this.mailcolor = "warning";
      } else {
        this.mailcolor = "dark";
        this.mail = false;
      }
      if (calendar) {
        this.calendarcolor = "warning";
      } else {
        this.calendarcolor = "dark";
        this.calendar = false;
      }
    }
  },

  components: {
    draggable
  },
  methods: {
    change_fav() {
      if (localStorage.getItem("favs")) {
        localStorage.removeItem("favs");
      }
      let parsed = JSON.stringify(this.favs);
      localStorage.setItem("favs", parsed);
    },

    changeTodo() {
      this.todo = !this.todo;
      console.log("this.todo: ", this.todo);
      if (this.todo === false) {
        const filtered = this.favs.filter(fav => fav.id !== 1);
        this.favs = filtered;
        if (localStorage.getItem("favs")) {
          localStorage.removeItem("favs");
        }
        let parsed = JSON.stringify(this.favs);
        localStorage.setItem("favs", parsed);
        this.todocolor = "dark";
      } else {
        var todo = {
          id: 1,
          icon: "icon-check-square",
          content: "Todo",
          link: "/todo"
        };
        this.favs.push(todo);
        if (localStorage.getItem("favs")) {
          localStorage.removeItem("favs");
        }
        let parsed = JSON.stringify(this.favs);
        localStorage.setItem("favs", parsed);
        this.todocolor = "warning";
      }
    },
    changeChat() {
      this.chat = !this.chat;
      if (this.chat === false) {
        const filtered = this.favs.filter(fav => fav.id !== 2);
        this.favs = filtered;
        if (localStorage.getItem("favs")) {
          localStorage.removeItem("favs");
        }
        let parsed = JSON.stringify(this.favs);
        localStorage.setItem("favs", parsed);
        this.chatcolor = "dark";
      } else {
        var chat = {
          id: 2,
          icon: "icon-message-square",
          content: "Chat",
          link: "/chat"
        };
        this.favs.push(chat);
        if (localStorage.getItem("favs")) {
          localStorage.removeItem("favs");
        }
        let parsed = JSON.stringify(this.favs);
        localStorage.setItem("favs", parsed);
        this.chatcolor = "warning";
      }
    },
    changeMail() {
      this.mail = !this.mail;
      if (this.mail === false) {
        const filtered = this.favs.filter(fav => fav.id !== 3);
        this.favs = filtered;
        if (localStorage.getItem("favs")) {
          localStorage.removeItem("favs");
        }
        let parsed = JSON.stringify(this.favs);
        localStorage.setItem("favs", parsed);
        this.mailcolor = "dark";
      } else {
        var mail = {
          id: 3,
          icon: "icon-mail",
          content: "Mail",
          link: "/mail"
        };
        this.favs.push(mail);
        if (localStorage.getItem("favs")) {
          localStorage.removeItem("favs");
        }
        let parsed = JSON.stringify(this.favs);
        localStorage.setItem("favs", parsed);
        this.mailcolor = "warning";
      }
    },
    changeCalendar() {
      this.calendar = !this.calendar;
      if (this.calendar === false) {
        const filtered = this.favs.filter(fav => fav.id !== 4);
        this.favs = filtered;
        if (localStorage.getItem("favs")) {
          localStorage.removeItem("favs");
        }
        let parsed = JSON.stringify(this.favs);
        localStorage.setItem("favs", parsed);
        this.calendarcolor = "dark";
      } else {
        var calendar = {
          id: 4,
          icon: "icon-calendar",
          content: "Calendar",
          link: "/calendar"
        };
        this.favs.push(calendar);
        if (localStorage.getItem("favs")) {
          localStorage.removeItem("favs");
        }
        let parsed = JSON.stringify(this.favs);
        localStorage.setItem("favs", parsed);
        this.calendarcolor = "warning";
      }
    },
    logout() {
      axios.post("/logout").then(response => {
        localStorage.removeItem("isLoggedIn");
        localStorage.removeItem("Auth");
        window.location.reload();
      });
    }
  }
};
</script>

<style lang="scss" scoped>
</style>