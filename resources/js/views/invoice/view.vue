<template>
  <div>
    <!-- breadcrumb -->
    <div class="row">
      <nav aria-label="breadcrumb" class="vs-breadcrumb vs-align-left">
        <ol class="vs-breadcrumb--ol">
          <h3 class="BC_header">Invoice</h3>
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
            <router-link :to="{ name: 'Invoices' }">Invoices</router-link>
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
            <a class="vs-breadcrumb--link">{{invoice.invoice_no}}</a>
          </li>
        </ol>
      </nav>
    </div>

    <div class="row flex-between mb-base">
      <div class="send_mail flex">
        <vs-input id="invoice_email" v-model="send_email" />
        <vs-button
          id="send_invoice"
          color="primary"
          type="border"
          class="send_btn pointer"
          @click="send()"
        >Send Invoice</vs-button>
      </div>
      <div class="flex-align-center">
        <el-select
          class="invoice_status_select mr-3"
          v-model="invoice.status"
          @change="setToData()"
          default-first-option
          placeholder="Choose a person or create new"
        >
          <el-option v-for="data in status" :key="data.name" :label="data.name" :value="data.value"></el-option>
        </el-select>
        <!-- <vs-button
          @click="DownloadInvoice()"
          color="primary"
          class="mr-3"
          type="border"
          style="padding: 0.5rem 2rem !important; min-width: 130px;"
          icon-pack="feather"
          icon="icon-download"
        >Download</vs-button>-->
        <vs-button
          @click="printInvoice()"
          color="primary"
          type="filled"
          style="padding: 0.5rem 2rem !important;min-width: 200px;"
          icon-pack="feather"
          icon="icon-printer"
        >Print Or Download</vs-button>
      </div>
    </div>
    <!--End of breadcrumb -->
    <div id="invoice" class="row">
      <div class="col-lg-12 invoice_div open div_shadow invoice-box vs-con-loading__container">
        <div class="row mb-5">
          <div class="col-12 mb-base flex-end">
            <h1 class="mb0 text-bold text-uppercase">Invoice</h1>
          </div>
          <div class="col-6 flex">
            <div class="flex-align-center flex-column">
              <img :src="invoice.logo" id="invoice_logo" alt />
            </div>
          </div>
          <div class="col-6 flex-y-end flex-column">
            <h5 class="text-md text-bold mb0 text-uppercase">Invoice No.</h5>
            <p class="text-md mt-2 mb0">{{invoice.invoice_no}}</p>
            <h5 class="text-md text-bold text-uppercase mt-3 mb0">Create Date</h5>
            <p class="text-md mt-2 mb0">{{invoice.create_date | moment("L")}}</p>
            <h5 class="text-md mt-3 text-uppercase text-bold mb0">Due Date</h5>
            <p class="text-md mt-2 mb0">{{invoice.due_date | moment("L")}}</p>
          </div>
        </div>
        <div class="row mb-5">
          <div class="col-6">
            <h3 class="header-text text-uppercase">From</h3>
            <p class="text-md mb-3">
              <vs-icon icon="el-icon-school" icon-pack="el-icon" size="14px" />
              : {{invoice.from_name}}
            </p>
            <p class="text-md mb-3">
              <vs-icon icon="icon-phone" icon-pack="feather" size="14px" />
              :
              {{invoice.from_phone}}
            </p>
            <p class="text-md mb-3">
              <vs-icon icon="icon-mail" icon-pack="feather" size="14px" />
              :
              {{invoice.from_email}}
            </p>
            <p class="text-md mb-3">
              <vs-icon icon="icon-map-pin" icon-pack="feather" size="14px" />
              :
              {{invoice.from_address}}
            </p>
          </div>
          <div class="col-6 flex-y-end flex-column">
            <h3 class="header-text text-uppercase">To</h3>
            <p class="text-md mb-3">
              <vs-icon icon="icon-user" icon-pack="feather" size="14px" />
              : {{invoice.to_name}}
            </p>
            <p class="text-md mb-3">
              <vs-icon icon="icon-phone" icon-pack="feather" size="14px" />
              :
              {{invoice.to_phone}}
            </p>
            <p class="text-md mb-3">
              <vs-icon icon="icon-mail" icon-pack="feather" size="14px" />
              :
              {{invoice.to_email}}
            </p>
            <p class="text-md mb-3">
              <vs-icon icon="el-icon-suitcase" icon-pack="el-icon" size="14px" />
              :
              {{invoice.to_job}}
            </p>
          </div>
        </div>
        <div class="row mb-5">
          <div class="col-12">
            <h3 class="header-text text-uppercase">Student Information</h3>
          </div>
          <div class="col-6">
            <p class="text-md mb-3">
              <vs-icon icon="icon-user" icon-pack="feather" size="14px" />
              : {{invoice.student_name}}
            </p>
            <p class="text-md mb-3">
              <vs-icon icon="icon-book" icon-pack="feather" size="14px" />
              : {{invoice.student_grade}}
            </p>
          </div>
        </div>
        <div class="row mb0">
          <div class="col-12 mb-3">
            <vs-table hover-flat class="invoice_table" :data="invoice.items">
              <template slot="thead">
                <vs-th class="text-uppercase">Description</vs-th>

                <vs-th class="text-uppercase">Quantity/Unit</vs-th>
                <vs-th class="text-uppercase">Price</vs-th>
                <vs-th class="text-uppercase">Total</vs-th>
              </template>

              <template slot-scope="{data}">
                <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td :data="data[indextr].description">{{data[indextr].description}}</vs-td>

                  <vs-td :data="data[indextr].quantity">{{ data[indextr].quantity}}</vs-td>
                  <vs-td :data="data[indextr].price">{{ data[indextr].price}}Ks</vs-td>
                  <vs-td :data="data[indextr].total">{{ data[indextr].total}}Ks</vs-td>
                </vs-tr>
              </template>
            </vs-table>
          </div>
        </div>
        <div class="row mb-5 pb-5">
          <div class="col-12 flex-end">
            <table style="width:35%;border: 1px solid #efefef;">
              <tr>
                <td class="detail_p p-3 text-md text-bold text-uppercase">Sub Total :</td>
                <td class="text-right text-md p-2">{{invoice.sub_total}}Ks</td>
              </tr>
              <tr>
                <td class="detail_p p-3 text-md text-bold text-uppercase">Discount % :</td>
                <td class="text-right text-md p-2">{{ invoice.discount}}%</td>
              </tr>
              <tr>
                <td class="detail_p p-3 text-md text-bold text-uppercase">Grand Total :</td>
                <td class="text-right text-md p-2">{{ invoice.grand_total }}Ks</td>
              </tr>
            </table>
          </div>
        </div>
        <div class="row pt-5">
          <div class="col-6">
            <h3 class="header-text text-uppercase">Bank Information</h3>
            <p class="text-md mb-3">
              <vs-icon icon="el-icon-office-building" icon-pack="el-icon" size="14px" />
              : {{invoice.bank_name}}
            </p>
            <p class="text-md mb-3">
              <vs-icon icon="icon-user" icon-pack="feather" size="14px" />
              : {{invoice.bank_holder_name}}
            </p>
            <p class="text-md mb-3">
              <vs-icon icon="el-icon-bank-card" icon-pack="el-icon" size="14px" />
              : {{invoice.bank_no}}
            </p>
          </div>
          <div class="col-6 flex-end flex-y-end">
            <div class="flex-align-center flex-column">
              <img :src="invoice.signature" style="width:150px;height:auto;" alt />

              <p class="text-md pt-3">Signature</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      status: [
        {
          name: "Pending",
          value: "Pending"
        },
        {
          name: "Paid",
          value: "Paid"
        },
        {
          name: "Post Due",
          value: "Post_Due"
        }
      ],
      send_email: "",
      invoice: {}
    };
  },
  created() {
    //set localstorage for route refreshing
    var check = JSON.parse(localStorage.getItem("invoice"));
    var data = this.$route.params.data;
    var index = this.$route.meta.index;
    this.$emit("index", index);

    if (data) {
      if (check) {
        if (check.id == data.id) {
          this.invoice = data;
        } else {
          localStorage.removeItem("invoice");
          this.invoice = data;
          let parsed = JSON.stringify(this.invoice);
          localStorage.setItem("invoice", parsed);
        }
      } else {
        this.invoice = data;
        let parsed = JSON.stringify(this.invoice);
        localStorage.setItem("invoice", parsed);
      }
    } else {
      this.invoice = check;
    }
    this.send_email = this.invoice.to_email;
  },
  methods: {
    setToData() {
      axios
        .post(`web/updateInvoiceStatus/`, {
          id: this.invoice.id,
          status: this.invoice.status
        })
        .then(response => {
          console.log(response.data);
        })
        .catch(error => {
          console.log("error: ", error);
        });
    },
    printInvoice() {
      window.print();
    },
    send() {
      this.$vs.loading({
        background: "rgba(255,255,255,0.4)",
        color: "primary",
        type: "border",
        scale: 0.6
      });
      axios
        .post(`web/sendInvoice`, {
          data: this.invoice,
          send_email: this.send_email
        })
        .then(response => {
          this.$vs.loading.close();
          this.$message({
            message: "Success!",
            type: "success"
          });
        })
        .catch(error => {
          this.$vs.loading.close();
          this.$message({
            message: "Error!",
            type: "danger"
          });
        });
    }
    // DownloadInvoice() {
    //   this.$vs.loading({
    //     background: "rgba(255,255,255,0.4)",
    //     color: "primary",
    //     type: "border",
    //     scale: 0.6
    //   });
    //   axios
    //     .post(`web/DownloadInvoice/${Math.random()}`, { data: this.invoice })
    //     .then(response => {
    //       this.$vs.loading.close();
    //     })
    //     .catch(error => {
    //       console.log("error: ", error);
    //       this.$vs.loading.close();
    //     });
    // }
  }
};
</script>

<style lang="scss" scoped>
</style>