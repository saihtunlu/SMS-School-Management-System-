<template>
  <div class="row vs-con-loading__container" style="overflow: visible;" id="loading">
    <div class="col-lg-12">
      <div class="flex-between">
        <h4 class="header-text flex-align-center" style="margin-bottom:0.6rem !important;">
          <vs-button
            radius
            size="40px"
            color="primary"
            icon-pack="feather"
            icon="icon-file-text"
            type="flat"
          ></vs-button>Default General Information
        </h4>
      </div>
      <vs-divider class="mt-0" color="rgb(var(--vs-gray2),0.5)" />
    </div>
    <div class="col-6 edit1" style="justify-content: center;display: flex;align-items: flex-end;">
      <div class="flex-align-center flex-column">
        <input type="file" ref="fileInput" id="change_pic" hidden v-on:change="onFileChangeEdit" />
        <img :src="url" v-if="url" class="pointer" id="invoice_logo" @click="trigger()" alt />
        <button class="add_invoice_logo" v-if="!url" @click="trigger()">
          <vs-icon icon="icon-plus" icon-pack="feather" size="25px" />
        </button>
        <p class="text-md pt-3">Logo</p>
      </div>
    </div>
    <div class="col-6 edit1" style="justify-content: center;display: flex;align-items: flex-end;">
      <div class="flex-align-center flex-column">
        <input type="file" ref="signFile" id="change_pic" hidden v-on:change="onFileChangeFile" />
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
    <div class="col-lg-6">
      <vs-input class="edit2_btns" label="Name*" v-model="invoice.name" />
      <vs-input
        class="edit2_btns"
        type="number"
        min="0"
        label="Mobile Phone*"
        v-model="invoice.phone"
      />
    </div>
    <div class="col-lg-6">
      <vs-input type="email" class="edit2_btns" label="Email*" v-model="invoice.email" />
    </div>
    <div class="col-lg-12 mt-4">
      <label for class="vs-input--label" style="display:block;">Address</label>
      <vs-textarea v-model="invoice.address" />
    </div>
    <div class="col-lg-6">
      <div class="custom_input">
        <label for class="vs-input--label" style="display:block;">Bank Name</label>
        <el-select
          v-model="invoice.bank_name"
          filterable
          allow-create
          default-first-option
          placeholder="Choose a bank"
        >
          <el-option v-for="bank in banks" :key="bank.name" :label="bank.name" :value="bank.name"></el-option>
        </el-select>
      </div>
      <vs-input class="edit2_btns" label="Account Holder Name" v-model="invoice.account_holder" />
    </div>
    <div class="col-lg-6">
      <vs-input class="edit2_btns" label="Account Number" v-model="invoice.account_number" />
    </div>
    <div class="col-lg-12 flex-end mt-3">
      <vs-button color="primary" type="filled" class="add_new" @click="saveGeneral()">Save Changes</vs-button>
    </div>
    <div class="col-lg-12 mt-base">
      <div class="flex-between">
        <h4 class="header-text flex-align-center" style="margin-bottom:0.6rem !important;">
          <vs-button
            radius
            size="40px"
            color="primary"
            icon-pack="feather"
            icon="icon-list"
            type="flat"
          ></vs-button>Invoice Items
        </h4>
        <vs-icon
          class="pointer icon_btns openicon btn_primary"
          size="18px"
          icon-pack="feather"
          icon="icon-plus"
          @click="addItem()"
        ></vs-icon>
      </div>
      <vs-divider class="mt-0" color="rgb(var(--vs-gray2),0.5)" />
    </div>
    <div class="col-lg-12 default_invoice_items" v-for="(item,n) in invoice_items" v-bind:key="n">
      <div class="flex-align-center">
        <vs-input class="edit2_btns mr-3" label="Description*" v-model="item.description" />
        <vs-input
          class="edit2_btns mr-3"
          type="number"
          min="0"
          label="Primary price*"
          v-model="item.primary_price"
        />
        <vs-input
          class="edit2_btns mr-3"
          type="number"
          min="0"
          label="Price*"
          v-model="item.price"
        />
        <vs-icon icon="icon-x" class="removeItem_btn" icon-pack="feather" @click="remove(n)" />
      </div>
      <!-- <vs-divider /> -->
    </div>
    <div class="col-lg-12 flex-end mt-4">
      <vs-button color="primary" type="filled" class="add_new" @click="saveItem()">Save Changes</vs-button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      url: "",
      file: "",
      SignUrl: "",
      Signfile: "",
      invoice: {},
      invoice_items: [{}],
      banks: {}
    };
  },
  mounted() {
    this.getBanks();
    this.getDefault();
    this.getItems();
  },
  methods: {
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
    addItem() {
      var array = {
        description: "",
        price: ""
      };
      this.invoice_items.push(array);
    },
    remove(index) {
      this.invoice_items.splice(index, 1);
    },
    getBanks() {
      axios.get(`/web/Bank`).then(response => {
        this.banks = response.data;
      });
    },
    saveGeneral() {
      this.$vs.loading({
        container: "#loading",
        background: "rgba(var(--vs-theme),0.2)",
        color: "dark",
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
        .post("web/SetInvoice", formData, config)
        .then(response => {
          this.$vs.loading.close("#loading > .con-vs-loading");
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
          this.$vs.loading.close("#loading > .con-vs-loading");
        });
    },
    getDefault() {
      axios
        .get(`web/defaultInvoice`)
        .then(response => {
          this.invoice = response.data;
          this.url = this.invoice.logo;
          this.SignUrl = this.invoice.sign;
        })
        .catch(error => {
          const key = Object.keys(error.response.data.errors)[0];
          this.errorMessage = error.response.data.errors[key][0];
        });
    },
    saveItem() {
      this.$vs.loading({
        container: "#loading",
        background: "rgba(var(--vs-theme),0.2)",
        color: "dark",
        type: "border",
        scale: 0.4
      });

      axios
        .post("web/SetInvoiceItems", { items: this.invoice_items })
        .then(response => {
          this.$vs.loading.close("#loading > .con-vs-loading");
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
          this.$vs.loading.close("#loading > .con-vs-loading");
        });
    },
    getItems() {
      axios
        .get(`web/GetInvoiceItems`)
        .then(response => {
          this.invoice_items = response.data;
        })
        .catch(error => {});
    }
  }
};
</script>
