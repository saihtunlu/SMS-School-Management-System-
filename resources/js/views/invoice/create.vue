<template>
  <div>
    <!-- alert -->
    <vs-prompt title="Send Invoice" id="send_invoice" :active.sync="active">
      <div class="row">
        <div class="col-lg-12 mt-base">
          <p class="text-md text-center">An Invoice has been successfully created!</p>
          <p class="text-md text-center">Do you also want to send invoice to {{invoice.to_email}}?</p>
        </div>

        <div class="col-lg-12 flex-end">
          <vs-button color="primary" class="edit_btn" @click="sendInvoice()" type="filled">Yes</vs-button>
          <vs-button color="warning" class="edit_btn" type="border" @click="active=false">No</vs-button>
        </div>
      </div>
    </vs-prompt>

    <!-- breadcrumb -->
    <div class="row">
      <nav aria-label="breadcrumb" class="vs-breadcrumb vs-align-left">
        <ol class="vs-breadcrumb--ol">
          <h3 class="BC_header">Create Invoice</h3>
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
            <a class="vs-breadcrumb--link">Create</a>
          </li>
        </ol>
      </nav>
    </div>
    <!--End of breadcrumb -->
    <div id="invoice" class="row">
      <div class="col-lg-12 invoice_div open div_shadow invoice-box vs-con-loading__container">
        <div class="row mb-base">
          <div class="col-12 mb-base flex-end">
            <h1 class="mb0 text-bold text-uppercase">Invoice</h1>
          </div>
          <div class="col-6 flex">
            <div class="flex-align-center flex-column">
              <input
                type="file"
                ref="fileInput"
                id="change_pic"
                hidden
                v-on:change="onFileChangeEdit"
              />
              <img :src="url" v-if="url" class="pointer" id="invoice_logo" @click="trigger()" alt />
              <button class="add_invoice_logo" v-if="!url" @click="trigger()">
                <vs-icon icon="icon-plus" icon-pack="feather" size="25px" />
              </button>
              <p class="text-md pt-3" v-if="!url">Add Logo</p>
            </div>
          </div>
          <div class="col-6 flex-y-end flex-column">
            <!-- <h5 class="text-md text-bold mb0 text-uppercase">Invoice No.</h5>
            <el-input placeholder="Please input" class="invoice_el_input" v-model="invoice_no"></el-input>-->
            <h5 class="text-md text-bold mt-3 mb0 text-uppercase">Create Date</h5>
            <el-date-picker
              v-model="invoice.create_date"
              type="date"
              class="invoice_el_input"
              placeholder="Pick a day"
            ></el-date-picker>
            <h5 class="text-md mt-3 text-bold mb0 text-uppercase">Due Date</h5>
            <el-date-picker
              v-model="invoice.due_date"
              type="date"
              class="invoice_el_input"
              placeholder="Pick a day"
            ></el-date-picker>
          </div>
        </div>
        <div class="row mb-base">
          <div class="col-6">
            <h3 class="header-text text-uppercase">From</h3>
            <el-input
              placeholder="School Name"
              class="invoice_el_input_sec"
              v-model="invoice.from_name"
            ></el-input>
            <el-input
              placeholder="Mobile Phone"
              class="invoice_el_input_sec"
              v-model="invoice.from_phone"
            ></el-input>
            <el-input
              type="email"
              placeholder="Email Address"
              class="invoice_el_input_sec"
              v-model="invoice.from_email"
            ></el-input>
            <el-input
              placeholder="Address"
              class="invoice_el_input_sec"
              v-model="invoice.from_address"
            ></el-input>
          </div>
          <div class="col-6 flex-y-end flex-column">
            <h3 class="header-text text-uppercase">To</h3>
            <el-select
              v-model="to_data_NRC"
              @change="setToData()"
              filterable
              allow-create
              class="invoice_el_input_sec"
              default-first-option
              placeholder="Choose a person or create new"
            >
              <el-option
                v-for="data in to_data"
                :key="data.first_name"
                :label="data.first_name+' '+data.last_name"
                :value="data.NRC"
              ></el-option>
            </el-select>

            <el-input
              placeholder="Mobile Phone"
              class="invoice_el_input_sec"
              v-model="invoice.to_phone"
            ></el-input>
            <el-input
              type="email"
              placeholder="Email Address"
              class="invoice_el_input_sec"
              v-model="invoice.to_email"
            ></el-input>
            <el-input placeholder="Job Title" class="invoice_el_input_sec" v-model="invoice.to_job"></el-input>
          </div>
        </div>
        <div class="row mb-base">
          <div class="col-12">
            <h3 class="header-text text-uppercase">Student Information</h3>
          </div>
          <div class="col-6">
            <el-select
              v-model="student_id"
              @change="setStudent()"
              filterable
              allow-create
              class="invoice_el_input_sec"
              default-first-option
              placeholder="Choose a student"
            >
              <el-option
                v-for="student in students"
                :key="student.first_name"
                :label="student.first_name+' '+student.last_name"
                :value="student.id"
              ></el-option>
            </el-select>
            <el-input
              placeholder="Grade"
              class="invoice_el_input_sec"
              v-model="invoice.student_grade"
            ></el-input>
          </div>
        </div>
        <div class="row mb-base">
          <div class="col-12 mb-3">
            <vs-table hover-flat class="invoice_table" :data="invoice.items">
              <template slot="thead">
                <vs-th class="text-uppercase">Description</vs-th>

                <vs-th class="text-uppercase">Quantity/Unit</vs-th>
                <vs-th class="text-uppercase">Price</vs-th>
                <vs-th class="text-uppercase">Total</vs-th>
                <vs-th class="text-uppercase">Actions</vs-th>
              </template>

              <template slot-scope="{data}">
                <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td :data="data[indextr].description">
                    <el-input placeholder="Name" v-model="data[indextr].description"></el-input>
                  </vs-td>

                  <vs-td :data="data[indextr].quantity">
                    <el-input
                      type="number"
                      min="1"
                      placeholder="Quantity/Unit"
                      v-model="data[indextr].quantity"
                      @change="sum_total(indextr)"
                    ></el-input>
                  </vs-td>
                  <vs-td :data="data[indextr].price">
                    <el-input
                      min="0"
                      type="number"
                      placeholder="Price"
                      @change="sum_total(indextr)"
                      v-model="data[indextr].price"
                    ></el-input>
                  </vs-td>
                  <vs-td :data="data[indextr].total">
                    <el-input type="number" placeholder="Total" v-model="data[indextr].total"></el-input>
                  </vs-td>
                  <vs-td :data="data[indextr]" class="action_table">
                    <vs-icon
                      icon="icon-x"
                      class="removeItem_btn mt-0"
                      icon-pack="feather"
                      @click="removeItem(indextr)"
                    />
                  </vs-td>
                </vs-tr>
              </template>
            </vs-table>
          </div>
          <div class="col-12 flex-end">
            <vs-button
              color="primary"
              class="edit_btns"
              icon="icon-plus"
              icon-pack="feather"
              type="filled"
              @click="add_item()"
            >Add Item</vs-button>
          </div>
        </div>
        <div class="row mb-base">
          <div class="col-12 flex-end">
            <table style="width:350px;">
              <tr>
                <td class="detail_p p-3 text-md text-right text-uppercase text-bold">Sub Total :</td>
                <td class="text-right text-md p-3">
                  <animated-number
                    :value="invoice.sub_total"
                    :formatValue="format"
                    :duration="300"
                  />
                </td>
              </tr>
              <tr>
                <td class="detail_p p-3 text-md text-right text-uppercase text-bold">Discount % :</td>
                <td class="p-3">
                  <el-input
                    type="number"
                    min="0"
                    max="100"
                    placeholder="Discount"
                    @change="AddGrandTotal()"
                    v-model="invoice.discount"
                  ></el-input>
                </td>
              </tr>
              <tr>
                <td class="detail_p p-3 text-md text-right text-uppercase text-bold">Grand Total :</td>
                <td class="text-right text-md p-3">
                  <animated-number
                    :value="invoice.grand_total"
                    :formatValue="format"
                    :duration="300"
                  />
                </td>
              </tr>
            </table>
          </div>
        </div>
        <div class="row mb-base">
          <div class="col-6">
            <h3 class="header-text text-uppercase">Bank Information</h3>
            <el-select
              v-model="invoice.bank_name"
              filterable
              class="invoice_el_input_sec"
              allow-create
              default-first-option
              placeholder="Choose a bank"
            >
              <el-option
                v-for="bank in banks"
                :key="bank.name"
                :label="bank.name"
                :value="bank.name"
              ></el-option>
            </el-select>

            <el-input
              placeholder="Account Holder Name"
              class="invoice_el_input_sec"
              v-model="invoice.bank_holder_name"
            ></el-input>
            <el-input
              placeholder="Bank Account No."
              class="invoice_el_input_sec"
              v-model="invoice.bank_no"
            ></el-input>
          </div>
          <div class="col-6 flex-end flex-y-end">
            <div class="flex-align-center flex-column">
              <input
                type="file"
                ref="signFile"
                id="change_pic"
                hidden
                v-on:change="onFileChangeFile"
              />
              <img
                :src="SignUrl"
                v-if="SignUrl"
                class="pointer"
                style="width:150px;height:auto;"
                @click="triggerSign()"
                alt
              />
              <button class="add_invoice_logo" v-if="!SignUrl" @click="triggerSign()">
                <vs-icon icon="icon-plus" icon-pack="feather" size="25px" />
              </button>
              <p class="text-md pt-3">Signature</p>
            </div>
          </div>
        </div>
        <div class="row mt-base">
          <div class="col-12 flex-end">
            <vs-button
              color="primary"
              id="loading"
              class="add_user_btn vs-con-loading__container mr-3"
              @click="save()"
              type="filled"
            >Save</vs-button>
            <vs-button
              color="warning"
              class="add_user_btn"
              style="margin-right:0px !important;"
              @click="Goback()"
              type="border"
            >Cancel</vs-button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import AnimatedNumber from "animated-number-vue";

export default {
  data() {
    return {
      invoice_no: "#" + Math.floor(Math.random() * 90000),
      current_year_id: 0,
      active: false,
      student_id: "",
      student_data: {},
      students: {},
      invoice: {
        create_date: "",
        due_date: "",
        invoice_no: "",
        from_name: "",
        from_phone: "",
        from_address: "",
        from_email: "",
        to_email: "",
        to_name: "",
        to_phone: "",
        to_job: "",
        student_name: "",
        student_grade: "",
        bank_name: "",
        bank_holder_name: "",
        bank_no: "",
        items: [
          {
            description: "",
            quantity: "",
            price: "",
            total: ""
          }
        ],
        sub_total: "",
        discount: "",
        grand_total: ""
      },
      disabled: false,
      url: "",
      file: "",
      SignUrl: "",
      Signfile: "",
      to_data_NRC: "",
      to_data: [
        {
          first_name: "",
          last_name: ""
        }
      ],
      banks: {}
    };
  },
  created() {
    var check = localStorage.getItem("current_year_id");
    if (check) {
      this.current_year_id = check;
    }
    var index = this.$route.meta.index;
    this.$emit("index", index);
    this.getBanks();
    this.getDefault();
    this.getItems();
  },
  mounted() {
    this.getStudent();
  },
  components: {
    AnimatedNumber
  },
  methods: {
    format(value) {
      return value.toFixed(0);
    },
    setToData() {
      var NRC = this.to_data_NRC;
      var data = this.to_data.filter(function(data) {
        if (data.NRC === NRC) return true;
      });
      this.invoice.to_name = data[0].first_name + " " + data[0].last_name;
      this.invoice.to_email = data[0].email;
      this.invoice.to_phone = data[0].phone;
      this.invoice.to_job = data[0].company;
    },
    setStudent() {
      this.to_data = [];
      var id = this.student_id;
      var data = this.students.filter(function(student) {
        if (student.id === id) return true;
      });
      this.invoice.student_name = data[0].first_name + " " + data[0].last_name;
      this.invoice.student_grade = data[0].classroom.grade;
      this.to_data.push(data[0].father);
      this.to_data.push(data[0].mother);
    },
    getStudent() {
      axios
        .get(`/web/studentsByYear/${this.current_year_id}`)
        .then(response => {
          console.log(response.data);
          this.students = response.data;
          console.log(" this.students: ", this.students);
        })
        .catch(error => {
          const key = Object.keys(error.response.data.errors)[0];
          console.log("key: ", key);
        });
    },
    Goback() {
      this.$router.go(-1);
    },
    AddGrandTotal() {
      this.invoice.grand_total =
        parseInt(this.invoice.sub_total) -
        parseInt(this.invoice.sub_total) *
          (parseInt(this.invoice.discount) / 100);
    },
    sum_total(data) {
      if (
        this.invoice.items[data].quantity != "" ||
        this.invoice.items[data].price != ""
      ) {
        this.invoice.items[data].total =
          parseInt(this.invoice.items[data].quantity) *
          parseInt(this.invoice.items[data].price);

        this.invoice.items[data].primary_total =
          parseInt(this.invoice.items[data].quantity) *
          parseInt(this.invoice.items[data].primary_price);

        var values = this.invoice.items.map(item => Number(item.total));
        if (!values.every(value => isNaN(value))) {
          this.invoice.sub_total = values.reduce((prev, curr) => {
            const value = Number(curr);
            if (!isNaN(value)) {
              return prev + curr;
            } else {
              return prev;
            }
          }, 0);
        } else {
          this.invoice.sub_total = "N/A";
        }

        var primary_values = this.invoice.items.map(item =>
          Number(item.primary_total)
        );
        if (!primary_values.every(value => isNaN(value))) {
          this.invoice.primary_total = primary_values.reduce((prev, curr) => {
            const value = Number(curr);
            if (!isNaN(value)) {
              return prev + curr;
            } else {
              return prev;
            }
          }, 0);
        } else {
          this.invoice.primary_total = "N/A";
        }
        console.log("this.invoice: ", this.invoice);

        if (this.invoice.discount != "") {
          this.invoice.grand_total =
            parseInt(this.invoice.sub_total) -
            parseInt(this.invoice.sub_total) *
              (parseInt(this.invoice.discount) / 100);
        }
      }
    },
    removeItem(data) {
      this.invoice.items.splice(data, 1);
    },
    add_item() {
      var array = {
        description: "",
        date: "",
        quantity: "",
        price: "",
        total: ""
      };
      this.invoice.items.push(array);
    },
    clear_file() {
      this.file = "";
      this.url = null;
    },
    triggerSign() {
      this.$refs.signFile.click();
    },
    onFileChangeFile(e) {
      this.Signfile = "";
      this.SignUrl = null;
      this.Signfile = e.target.files[0];
      this.SignUrl = URL.createObjectURL(this.Signfile);
    },
    clear_sign() {
      this.Signfile = "";
      this.SignUrl = null;
    },
    trigger() {
      this.$refs.fileInput.click();
    },
    onFileChangeEdit(e) {
      this.file = "";
      this.url = null;
      this.file = e.target.files[0];
      this.url = URL.createObjectURL(this.file);
    },
    save() {
      this.$vs.loading({
        container: "#loading",
        background: "rgba(var(--vs-primary),1)",
        color: "light",
        type: "border",
        scale: 0.4
      });
      const config = {
        headers: { "content-type": "multipart/form-data" }
      };
      let formData = new FormData();
      formData.append("invoice", JSON.stringify(this.invoice));
      formData.append("logo", this.file);
      formData.append("sign", this.Signfile);

      axios
        .post("web/createInvoice", formData, config)
        .then(response => {
          this.$vs.loading.close("#loading > .con-vs-loading");
          this.active = true;
          this.invoice.id = response.data;
          this.$message({
            message:
              "Congratulation! You have successfully created an invoice.",
            type: "success"
          });
        })
        .catch(error => {
          this.$message({
            message: "Oops! There is an error while creating new invoice.",
            type: "error"
          });
          console.log("error: ", error);
          this.$vs.loading.close("#loading > .con-vs-loading");
        });
    },
    getBanks() {
      axios.get(`/web/Bank`).then(response => {
        this.banks = response.data;
        console.log(" this.banks: ", this.banks);
      });
    },
    getDefault() {
      axios
        .get(`web/defaultInvoice`)
        .then(response => {
          console.log(response.data);
          var invoice = response.data;
          this.invoice.invoice_no = invoice.number;
          this.invoice.from_name = invoice.name;
          this.invoice.from_phone = invoice.phone;
          this.invoice.from_email = invoice.email;
          this.invoice.from_address = invoice.address;
          this.invoice.bank_name = invoice.bank_name;
          this.invoice.bank_holder_name = invoice.account_holder;
          this.invoice.bank_no = invoice.account_number;
          this.invoice.logo = invoice.logo;
          this.invoice.signature = invoice.sign;
          this.url = this.invoice.logo;
          this.SignUrl = this.invoice.signature;
        })
        .catch(error => {
          const key = Object.keys(error.response.data.errors)[0];
          this.errorMessage = error.response.data.errors[key][0];
        });
    },
    getItems() {
      axios
        .get(`web/GetInvoiceItems`)
        .then(response => {
          console.log(response.data);
          this.invoice.items = response.data;
        })
        .catch(error => {
          console.log("error: ", error);
        });
    },
    sendInvoice() {
      this.$vs.loading({
        background: "rgba(var(--vs-theme),0.2)",
        color: "primary",
        type: "border",
        scale: 0.6
      });
      this.active = false;
      var send_email = this.invoice.to_email;
      axios
        .post(`web/sendInvoice`, {
          data: this.invoice,
          send_email: send_email
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
  }
};
</script>

