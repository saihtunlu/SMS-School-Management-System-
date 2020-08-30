<template>
  <div class="row">
    <Delete
      :active="delete_active"
      :type="Delete_type"
      :delete_id="delete_id"
      @success="successDelete"
      @error="delete_active = false"
    />
    <!-- breadcrumb -->
    <nav aria-label="breadcrumb" class="vs-breadcrumb vs-align-left">
      <ol class="vs-breadcrumb--ol">
        <h3 class="BC_header">Invoices</h3>
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
        <li class="vs-active BC_link">
          <a href="#" class="vs-breadcrumb--link">Invoices</a>
        </li>
      </ol>
    </nav>
    <!--End of breadcrumb -->

    <div class="col-lg-12 employee open padding div_shadow">
      <vs-table
        hoverFlat
        id="loading"
        class="vs-con-loading__container"
        multiple
        v-model="data_selected"
        pagination
        :max-items="max_pagination"
        search
        :data="invoices"
      >
        <template slot="header">
          <vs-dropdown vs-trigger-click>
            <vs-button
              class="pagenation"
              icon-after
              color="dark"
              type="border"
              icon-pack="feather"
              icon="icon-chevron-down"
            >1 - {{ max_pagination }} 0f {{ total }}</vs-button>
            <vs-dropdown-menu class="page_dropdown">
              <vs-dropdown-item @click="max_pagination = '10'">10</vs-dropdown-item>
              <vs-dropdown-item @click="max_pagination = '20'">20</vs-dropdown-item>
              <vs-dropdown-item @click="max_pagination = '30'">30</vs-dropdown-item>
              <vs-dropdown-item @click="max_pagination = '40'">40</vs-dropdown-item>
            </vs-dropdown-menu>
          </vs-dropdown>
          <vs-button
            class="add_new"
            color="primary"
            type="filled"
            icon-pack="feather"
            icon="icon-file-plus"
            to="/create_invoice"
          >Create New</vs-button>
          <vs-dropdown vs-trigger-click class="action">
            <div class="a-icon">
              <span>Actions</span>
              <vs-icon icon-pack="feather" icon="icon-chevron-down"></vs-icon>
            </div>

            <vs-dropdown-menu class="action_dropdown">
              <vs-dropdown-item @click="deleteInvoices()">
                <vs-icon icon-pack="feather" icon="icon-trash-2" />Move to trash
              </vs-dropdown-item>
            </vs-dropdown-menu>
          </vs-dropdown>
        </template>
        <template slot="thead">
          <vs-th sort-key="No." class="table_no">No.</vs-th>
          <vs-th sort-key="invoice_no">Invoice No</vs-th>
          <vs-th sort-key="to_name">To</vs-th>
          <vs-th sort-key="student_name">Student Name</vs-th>
          <vs-th sort-key="student_grade">Grade</vs-th>
          <vs-th sort-key="create_date">Created Date</vs-th>
          <vs-th sort-key="due_date">Due Date</vs-th>
          <vs-th sort-key="status">Status</vs-th>
          <vs-th sort-key="sent">Sent</vs-th>
          <vs-th sort-key="actions">Actions</vs-th>
        </template>

        <template slot-scope="{ data }">
          <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
            <vs-td :data="data[indextr].id">{{ parseInt(indextr)+1}}</vs-td>
            <vs-td :data="data[indextr].invoice_no">{{ data[indextr].invoice_no }}</vs-td>
            <vs-td :data="data[indextr].to_name">{{data[indextr].to_name}}</vs-td>
            <vs-td :data="data[indextr].student_name">{{ data[indextr].student_name }}</vs-td>
            <vs-td :data="data[indextr].student_grade">{{ data[indextr].student_grade }}</vs-td>
            <vs-td
              :data="data[indextr].create_date"
            >{{ data[indextr].create_date | moment("calendar") }}</vs-td>
            <vs-td :data="data[indextr].due_date">{{ data[indextr].due_date | moment("calendar") }}</vs-td>

            <vs-td :data="data[indextr].status" class="invoice_status">
              <vs-progress
                :height="6"
                :percent="50"
                v-if="data[indextr].status ==='Pending'"
                color="warning"
              ></vs-progress>

              <vs-progress
                :height="6"
                :percent="100"
                v-if="data[indextr].status ==='Paid'"
                color="success"
              ></vs-progress>

              <vs-progress
                :height="6"
                :percent="100"
                v-if="data[indextr].status ==='Post_Due'"
                color="danger"
              ></vs-progress>
            </vs-td>
            <vs-td :data="data[indextr].sent">
              <vs-chip
                transparent
                closable
                icon-pack="feather"
                v-if="data[indextr].sent"
                color="success"
                close-icon="icon-check"
              >Yes</vs-chip>
              <vs-chip
                transparent
                closable
                icon-pack="feather"
                v-if="!data[indextr].sent"
                color="warning"
                close-icon="icon-clock"
              >No</vs-chip>
            </vs-td>
            <vs-td :data="data[indextr]">
              <vs-button
                size="35px"
                radius
                class="p-1"
                color="primary"
                icon-pack="feather"
                icon="icon-chevron-right"
                type="flat"
                :to="{name: 'Invoice', params: { data: data[indextr]}}"
              ></vs-button>
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
    </div>
  </div>
</template>
<script>
import Delete from "../../components/delete.vue";

export default {
  data() {
    return {
      Delete_type: "Invoice",
      delete_active: false,
      delete_id: [],
      invoices: {},
      total: "",
      max_pagination: "10",
      data_selected: []
    };
  },
  mounted() {
    this.getInvoices();
  },
  watch: {},
  components: {
    Delete
  },
  created() {
    var index = this.$route.meta.index;
    this.$emit("index", index);
  },
  methods: {
    deleteInvoices() {
      this.delete_id = [];
      this.data_selected.forEach(data => {
        this.delete_id.push(data.id);
      });
      this.delete_active = true;
    },
    successDelete() {
      this.getInvoices();
      this.delete_active = false;
    },
    getInvoices() {
      this.$vs.loading({
        container: "#loading",
        background: "rgba(var(--vs-theme),0.4)",
        color: "primary",
        type: "border",
        scale: 0.6
      });
      axios
        .get("/web/invoices")
        .then(response => {
          this.invoices = response.data;
          this.total = this.invoices.length;
          this.$vs.loading.close("#loading > .con-vs-loading");
        })
        .catch(error => {
          const key = Object.keys(error.response.data.errors)[0];
          this.$vs.loading.close("#loading > .con-vs-loading");
        });
    }
  }
};
</script>
