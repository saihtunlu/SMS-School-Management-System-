<template>
  <div id="parentx">
    <vs-sidebar
      id="Main_sidebar"
      :reduce="reduce"
      :reduce-not-hover-expand="notExpand"
      parent="body"
      :default-index="index"
      color="primary"
      class="sidebarx"
      spacer
      :click-not-close="true"
      v-model="active"
    >
      <div class="header_sidebar flex-between-center" style="width:100%;" slot="header">
        <div class="flex-align-center">
          <img class="logo-nav" style="width:40px;" :src="logo" alt />
          <span class="logo">{{title}}</span>
        </div>
        <span class="reduce_btn" v-if="mobile" @click="close_sidebar()">
          <vs-icon icon="icon-x" size="24px" icon-pack="feather" />
        </span>
        <span class="reduce_btn" v-if="!mobile " id="v-step-0" @click="reduce_sidebar()">
          <svg
            viewBox="0 0 24 24"
            width="20"
            height="20"
            stroke="currentColor"
            stroke-width="2"
            fill="none"
            stroke-linecap="round"
            stroke-linejoin="round"
            class="css-i6dzq1"
          >
            <circle cx="12" cy="12" r="10" />
            <circle v-if="reduce == false" cx="12" cy="12" r="3" />
          </svg>
        </span>
      </div>

      <vs-sidebar-item
        @click="close_sidebar()"
        index="1"
        to="/"
        icon-pack="feather"
        :class="{'vs-sidebar-item-active' : index===1 ,'vs-sidebar-item-deactive': index !== 1}"
        icon="icon-home"
      >Dashboard</vs-sidebar-item>
      <span class="custom_sidebar_divider">People</span>
      <vs-sidebar-item
        @click="close_sidebar()"
        index="2"
        to="/users"
        :class="{'vs-sidebar-item-active' : index===2 ,'vs-sidebar-item-deactive': index !== 2}"
        icon-pack="feather"
        icon="icon-user"
      >Users</vs-sidebar-item>
      <vs-sidebar-item
        @click="close_sidebar()"
        v-if="reduce"
        index="3.1"
        to="/employees"
        :class="{'vs-sidebar-item-active' : index===3.1 ,'vs-sidebar-item-deactive': index !== 3.1}"
        icon-pack="el-icon"
        icon="el-icon-suitcase"
      >Employees</vs-sidebar-item>
      <vs-sidebar-item
        @click="close_sidebar()"
        v-if="reduce"
        index="4.1"
        to="/students"
        :class="{'vs-sidebar-item-active' : index === 4.1 ,'vs-sidebar-item-deactive': index !== 4.1}"
        icon-pack="feather"
        icon="icon-users"
      >Students</vs-sidebar-item>

      <vs-sidebar-group v-if="!reduce" class="employees el-icon" title="Employees">
        <vs-sidebar-item
          @click="close_sidebar()"
          index="3.1"
          to="/employees"
          :class="{'vs-sidebar-item-active' : index===3.1 ,'vs-sidebar-item-deactive': index !== 3.1}"
          icon-pack="feather"
          icon="icon-circle"
        >Lists</vs-sidebar-item>
        <vs-sidebar-item
          @click="close_sidebar()"
          index="3.2"
          v-if="$gate.isEmployee_Create()"
          to="/add_employee"
          :class="{'vs-sidebar-item-active' : index===3.2 ,'vs-sidebar-item-deactive': index !== 3.2}"
          icon-pack="feather"
          icon="icon-circle"
        >Add New</vs-sidebar-item>
      </vs-sidebar-group>

      <vs-sidebar-group class="students_sidebar" v-if="!reduce" title="Students">
        <vs-sidebar-item
          @click="close_sidebar()"
          index="4.1"
          to="/students"
          :class="{'vs-sidebar-item-active' : index === 4.1 ,'vs-sidebar-item-deactive': index !== 4.1}"
          icon-pack="feather"
          icon="icon-circle"
        >Lists</vs-sidebar-item>
        <vs-sidebar-item
          @click="close_sidebar()"
          index="4.2"
          :class="{'vs-sidebar-item-active' : index===4.2 ,'vs-sidebar-item-deactive': index !== 4.2}"
          to="/add_student"
          icon-pack="feather"
          icon="icon-circle"
        >Add New</vs-sidebar-item>
      </vs-sidebar-group>
      <span class="custom_sidebar_divider">School</span>
      <vs-sidebar-item
        @click="close_sidebar()"
        index="14"
        :class="{'vs-sidebar-item-active' : index === 14 ,'vs-sidebar-item-deactive': index !== 14}"
        icon="icon-credit-card"
        to="/timetable"
        icon-pack="feather"
      >Timetable</vs-sidebar-item>

      <span class="custom_sidebar_divider">App</span>
      <vs-sidebar-item
        @click="close_sidebar()"
        v-if="reduce && $gate.isInvoice_Read()"
        index="5.1"
        to="/invoices"
        :class="{'vs-sidebar-item-active' : index === 5.1 ,'vs-sidebar-item-deactive': index !== 5.1}"
        icon-pack="feather"
        icon="icon-file-text"
      >Invoices</vs-sidebar-item>

      <vs-sidebar-group
        class="invoices_sidebar"
        v-if="!reduce && !mobile && $gate.isInvoice_Read()"
        title="Invoices"
      >
        <vs-sidebar-item
          @click="close_sidebar() "
          v-if="$gate.isInvoice_Read()"
          index="5.1"
          to="/invoices"
          :class="{'vs-sidebar-item-active' : index === 5.1 ,'vs-sidebar-item-deactive': index !== 5.1}"
          icon-pack="feather"
          icon="icon-circle"
        >Lists</vs-sidebar-item>
        <vs-sidebar-item
          @click="close_sidebar()"
          v-if="$gate.isInvoice_Create()"
          index="5.2"
          :class="{'vs-sidebar-item-active' : index===5.2 ,'vs-sidebar-item-deactive': index !== 5.2}"
          to="/create_invoice"
          icon-pack="feather"
          icon="icon-circle"
        >Add New</vs-sidebar-item>
      </vs-sidebar-group>

      <vs-sidebar-item
        @click="close_sidebar()"
        index="7"
        :class="{'vs-sidebar-item-active' : index === 7 ,'vs-sidebar-item-deactive': index !== 7}"
        icon="icon-message-square"
        to="/chat"
        icon-pack="feather"
      >
        Chat
        <vs-chip color="danger" v-if="noti!==0" class="sidebar_badge">{{noti}}</vs-chip>
      </vs-sidebar-item>
      <vs-sidebar-item
        @click="close_sidebar()"
        index="9"
        v-if="$gate.isAdmin()"
        :class="{'vs-sidebar-item-active' : index === 9 ,'vs-sidebar-item-deactive': index !== 9}"
        icon="icon-mail"
        to="/mail"
        icon-pack="feather"
      >Mail</vs-sidebar-item>
      <vs-sidebar-item
        @click="close_sidebar()"
        index="10"
        :class="{'vs-sidebar-item-active' : index === 10 ,'vs-sidebar-item-deactive': index !== 10}"
        icon="icon-check-square"
        to="/todo"
        icon-pack="feather"
      >
        Todo
        <vs-chip color="warning" v-if="todo_noti!==0" class="sidebar_badge">{{todo_noti}}</vs-chip>
      </vs-sidebar-item>
      <vs-sidebar-item
        @click="close_sidebar()"
        index="12"
        :class="{'vs-sidebar-item-active' : index === 12 ,'vs-sidebar-item-deactive': index !== 12}"
        icon="icon-calendar"
        to="/calendar"
        icon-pack="feather"
      >
        Calendar
        <vs-chip color="primary" v-if="event" class="sidebar_badge">{{event}}</vs-chip>
      </vs-sidebar-item>
      <vs-sidebar-item
        @click="close_sidebar()"
        index="11"
        to="/settings"
        :class="{'vs-sidebar-item-active' : index === 11 ,'vs-sidebar-item-deactive': index !==11}"
        icon-pack="feather"
        icon="icon-settings"
      >Settings</vs-sidebar-item>
    </vs-sidebar>
  </div>
</template>
<script>
export default {
  props: {
    logo: "",
    event: null,
    todo_noti: 0,
    title: "",
    index: "",
    noti: 0,
    active: false,
    mobile: false
  },
  data: () => ({
    notExpand: false,
    reduce: false
  }),
  created() {
    if (this.mobile) {
      this.reduce = false;
    }
  },
  watch: {
    index: function(newVal, oldVal) {
      // watch it
    }
  },
  methods: {
    close_sidebar() {
      if (this.mobile) {
        this.$emit("close");
      }
    },
    reduce_sidebar() {
      this.reduce = !this.reduce;
      this.$emit("reduce");
    }
  }
};
</script>
<style></style>
