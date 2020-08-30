<template>
  <div class="row vs-con-loading__container" style="overflow: visible;" id="permission_loading">
    <div class="col-lg-12 mb-base">
      <div class="flex-between">
        <h4 class="header-text flex-align-center" style="margin-bottom:0.6rem !important;">
          <vs-button
            radius
            size="40px"
            color="primary"
            icon-pack="feather"
            icon="icon-globe"
            type="flat"
          ></vs-button>General
        </h4>
      </div>
      <vs-divider class="mt-0" color="rgb(var(--vs-gray2),0.5)" />
      <div class="row">
        <div class="col-lg-6 edit1 flex-align-center flex-center">
          <div class="flex-align-center flex-column">
            <input
              type="file"
              ref="fileInput"
              id="change_pic"
              hidden
              v-on:change="onFileChangeEdit"
            />
            <img
              :src="data.logo"
              v-if="data.logo"
              class="pointer"
              id="invoice_logo"
              @click="trigger()"
              alt
            />
            <button class="add_invoice_logo" v-if="!data.logo" @click="trigger()">
              <vs-icon icon="icon-plus" icon-pack="feather" size="25px" />
            </button>
            <p class="text-md pt-3">Logo</p>
          </div>
        </div>
        <div class="col-lg-6">
          <vs-input class="edit2_btns" label="Title" @change="setDetail()" v-model="data.title" />
          <div class="custom_input">
            <label for class="vs-input--label" style="display:block;">Page Transition</label>
            <el-select
              v-model="data.transition"
              @change="setDetail()"
              filterable
              default-first-option
              placeholder="Choose a page transition"
            >
              <el-option
                v-for="ani in animations"
                :key="ani.name"
                :label="ani.name"
                :value="ani.value"
              ></el-option>
            </el-select>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12">
      <div class="flex-between">
        <h4 class="header-text flex-align-center" style="margin-bottom:0.6rem !important;">
          <vs-button
            radius
            size="40px"
            color="primary"
            icon-pack="feather"
            icon="icon-sliders"
            type="flat"
          ></vs-button>Common Colors
        </h4>
        <vs-icon
          icon="icon-rotate-ccw"
          @click="reset()"
          icon-pack="feather"
          size="18px"
          class="pointer custom_icon ml-2"
        />
      </div>
      <vs-divider class="mt-0" color="rgb(var(--vs-gray2),0.5)" />
      <div class="row theme_colors">
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker v-model="data.primary" @change="color()" color-format="rgb"></el-color-picker>
            <p>Primary Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker v-model="data.light_primary" @change="color()" color-format="rgb"></el-color-picker>
            <p>Light Primary Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker v-model="data.success" @change="color()" color-format="rgb"></el-color-picker>
            <p>Success Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker v-model="data.danger" @change="color()" color-format="rgb"></el-color-picker>
            <p>Danger Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker v-model="data.warning" @change="color()" color-format="rgb"></el-color-picker>
            <p>Warning Color</p>
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
      default_themes: [
        {
          dark: "rgb(65,65,65)",
          gray: "rgb(239, 242, 247)",
          gray2: "rgb(239, 242, 247)",
          input: "rgb(255, 255, 255)",
          main_bg: "rgb(239, 238, 253)",
          mode: "light",
          id: 1,
          semi_dark: "rgb(65,65,65)",
          semi_theme: "rgb(255,255,255)",
          theme_1: "rgb(255,255,255)",
          theme_2: "rgb(255,255,255)",
          theme_3: "rgb(255,255,255)"
        },
        {
          dark: "rgb(225,225,225)",
          gray: "rgb(38, 44, 73)",
          gray2: "rgb(24, 31, 68)",
          input: "rgb(38, 44, 73)",
          main_bg: "rgb(38, 44, 73)",
          mode: "dark",
          id: 2,
          semi_dark: "rgb(225,225,225)",
          semi_theme: "rgb(16, 22, 58)",
          theme_1: "rgb(16, 22, 58)",
          theme_2: "rgb(24, 31, 68)",
          theme_3: "rgb(24, 31, 68)"
        },
        {
          dark: "rgb(65,65,65)",
          gray: "rgb(239, 242, 247)",
          gray2: "rgb(195, 195, 195)",
          input: "rgb(255, 255, 255)",
          main_bg: "rgb(239, 238, 253)",
          mode: "semi_dark",
          id: 3,
          semi_dark: "rgb(225,225,225)",
          semi_theme: "rgb(16, 22, 58)",
          theme_1: "rgb(255,255,255)",
          theme_2: "rgb(245, 245, 245)",
          theme_3: "rgb(255, 255, 255)"
        }
      ],

      file: "",
      data: {},

      animations: [
        {
          value: "zoom-in-out",
          name: "Default"
        },
        {
          value: "fade-in-right",
          name: "Fade In Right"
        },
        {
          value: "fade-in-left",
          name: "Fade In Left"
        },
        {
          value: "fade-in-up",
          name: "Fade In Up"
        },
        {
          value: "zoom",
          name: "Zoom"
        },
        {
          value: "flip-x",
          name: "Flip-X"
        },
        {
          value: "flip-y",
          name: "Flip-Y"
        },
        {
          value: "overlay-right",
          name: "Overlay Right"
        },
        {
          value: "overlay-left",
          name: "Overlay Left"
        },
        {
          value: "overlay-down",
          name: "Overlay Down"
        },
        {
          value: "overlay-up",
          name: "Overlay Up"
        },
        {
          value: "overlay-right-full",
          name: "Overlay Right Full"
        },
        {
          value: "overlay-left-full",
          name: "Overlay Left Full"
        },
        {
          value: "overlay-down-full",
          name: "Overlay Down Full"
        },
        {
          value: "overlay-up-full",
          name: "Overlay Up Full"
        },
        {
          value: "overlay-left-right",
          name: "Overlay Left Right"
        },
        {
          value: "overlay-up-down",
          name: "Overlay Up Down"
        }
      ]
    };
  },
  created() {
    this.getGeneral();
  },
  mounted() {},
  methods: {
    reset() {
      this.data.primary = "rgb(180, 122, 243)";
      this.data.light_primary = "rgb(130, 122, 243)";
      this.data.success = "rgb(40, 199, 111)";
      this.data.warning = "rgb(255, 159, 67)";
      this.data.danger = "rgb(242, 19, 93)";
      this.color();
    },
    clear_file() {
      this.file = "";
      this.data.logo = null;
    },
    trigger() {
      this.$refs.fileInput.click();
    },
    onFileChangeEdit(e) {
      this.file = "";
      this.data.logo = null;
      this.file = e.target.files[0];

      this.$vs.loading({
        background: "rgba(var(--vs-theme),0.2)",
        color: "dark",
        type: "border",
        scale: 0.6
      });
      const config = {
        headers: { "content-type": "multipart/form-data" }
      };
      let formData = new FormData();
      formData.append("logo", this.file);
      formData.append("id", this.data.id);

      axios
        .post("web/setLogo", formData, config)
        .then(response => {
          this.$vs.loading.close();
          this.data.logo = URL.createObjectURL(this.file);
        })
        .catch(error => {
          this.$vs.loading.close();
        });
    },
    getGeneral() {
      axios
        .get(`web/getGeneral`)
        .then(response => {
          this.data = response.data;
        })
        .catch(error => {});
    },
    color() {
      axios
        .post(`web/setColor`, {
          primary: this.data.primary,
          light_primary: this.data.light_primary,
          success: this.data.success,
          danger: this.data.danger,
          warning: this.data.warning,
          id: this.data.id
        })
        .then(response => {
          this.getGeneral();
        })
        .catch(error => {});
    },
    setDetail() {
      axios
        .post(`web/setDetail`, {
          transition: this.data.transition,
          title: this.data.title,
          id: this.data.id
        })
        .then(response => {
          this.getGeneral();
        })
        .catch(error => {});
    }
  }
};
</script>

