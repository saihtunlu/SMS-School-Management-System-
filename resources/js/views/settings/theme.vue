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
            icon="icon-sun"
            type="flat"
          ></vs-button>Light Theme
        </h4>
        <vs-icon
          icon="icon-rotate-ccw"
          @click="reset('light')"
          icon-pack="feather"
          size="18px"
          class="pointer custom_icon ml-2"
        />
      </div>
      <vs-divider class="mt-0" color="rgb(var(--vs-gray2),0.5)" />
      <div class="row theme_colors">
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker v-model="light.theme_1" @change="update('light')" color-format="rgb"></el-color-picker>
            <p>Theme 1 Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker v-model="light.theme_2" @change="update('light')" color-format="rgb"></el-color-picker>
            <p>Theme 2 Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker v-model="light.theme_3" @change="update('light')" color-format="rgb"></el-color-picker>
            <p>Theme 3 Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker
              v-model="light.semi_theme"
              @change="update('light')"
              color-format="rgb"
            ></el-color-picker>
            <p>Semi Theme Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker v-model="light.semi_dark" @change="update('light')" color-format="rgb"></el-color-picker>
            <p>Semi Dark Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker v-model="light.dark" @change="update('light')" color-format="rgb"></el-color-picker>
            <p>Dark Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker v-model="light.input" @change="update('light')" color-format="rgb"></el-color-picker>
            <p>Input Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker v-model="light.main_bg" @change="update('light')" color-format="rgb"></el-color-picker>
            <p>Main Background Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker v-model="light.gray" @change="update('light')" color-format="rgb"></el-color-picker>
            <p>Gray Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker v-model="light.gray2" @change="update('light')" color-format="rgb"></el-color-picker>
            <p>Gray 2 Color</p>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12 mb-base">
      <div class="flex-between">
        <h4 class="header-text flex-align-center" style="margin-bottom:0.6rem !important;">
          <vs-button
            radius
            size="40px"
            color="primary"
            icon-pack="feather"
            icon="icon-moon"
            type="flat"
          ></vs-button>Dark Theme
        </h4>
        <vs-icon
          icon="icon-rotate-ccw"
          @click="reset('dark')"
          icon-pack="feather"
          size="18px"
          class="pointer custom_icon ml-2"
        />
      </div>
      <vs-divider class="mt-0" color="rgb(var(--vs-gray2),0.5)" />
      <div class="row theme_colors">
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker v-model="dark.theme_1" @change="update('dark')" color-format="rgb"></el-color-picker>
            <p>Theme 1 Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker v-model="dark.theme_2" @change="update('dark')" color-format="rgb"></el-color-picker>
            <p>Theme 2 Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker v-model="dark.theme_3" @change="update('dark')" color-format="rgb"></el-color-picker>
            <p>Theme 3 Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker v-model="dark.semi_theme" @change="update('dark')" color-format="rgb"></el-color-picker>
            <p>Semi Theme Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker v-model="dark.semi_dark" @change="update('dark')" color-format="rgb"></el-color-picker>
            <p>Semi Dark Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker v-model="dark.dark" @change="update('dark')" color-format="rgb"></el-color-picker>
            <p>Dark Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker v-model="dark.input" @change="update('dark')" color-format="rgb"></el-color-picker>
            <p>Input Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker v-model="dark.main_bg" @change="update('dark')" color-format="rgb"></el-color-picker>
            <p>Main Background Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker v-model="dark.gray" @change="update('dark')" color-format="rgb"></el-color-picker>
            <p>Gray Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker v-model="dark.gray2" @change="update('dark')" color-format="rgb"></el-color-picker>
            <p>Gray 2 Color</p>
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
            icon="icon-sunset"
            type="flat"
          ></vs-button>Semi Dark Theme
        </h4>
        <vs-icon
          icon="icon-rotate-ccw"
          @click="reset('semi_dark')"
          icon-pack="feather"
          size="18px"
          class="pointer custom_icon ml-2"
        />
      </div>
      <vs-divider class="mt-0" color="rgb(var(--vs-gray2),0.5)" />
      <div class="row theme_colors">
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker
              v-model="semi_dark.theme_1"
              @change="update('semi_dark')"
              color-format="rgb"
            ></el-color-picker>
            <p>Theme 1 Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker
              v-model="semi_dark.theme_2"
              @change="update('semi_dark')"
              color-format="rgb"
            ></el-color-picker>
            <p>Theme 2 Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker
              v-model="semi_dark.theme_3"
              @change="update('semi_dark')"
              color-format="rgb"
            ></el-color-picker>
            <p>Theme 3 Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker
              v-model="semi_dark.semi_theme"
              @change="update('semi_dark')"
              color-format="rgb"
            ></el-color-picker>
            <p>Semi Theme Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker
              v-model="semi_dark.semi_dark"
              @change="update('semi_dark')"
              color-format="rgb"
            ></el-color-picker>
            <p>Semi Dark Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker
              v-model="semi_dark.dark"
              @change="update('semi_dark')"
              color-format="rgb"
            ></el-color-picker>
            <p>Dark Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker
              v-model="semi_dark.input"
              @change="update('semi_dark')"
              color-format="rgb"
            ></el-color-picker>
            <p>Input Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker
              v-model="semi_dark.main_bg"
              @change="update('semi_dark')"
              color-format="rgb"
            ></el-color-picker>
            <p>Main Background Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker
              v-model="semi_dark.gray"
              @change="update('semi_dark')"
              color-format="rgb"
            ></el-color-picker>
            <p>Gray Color</p>
          </div>
        </div>
        <div class="col-lg-20 col-6">
          <div class="color_box">
            <el-color-picker
              v-model="semi_dark.gray2"
              @change="update('semi_dark')"
              color-format="rgb"
            ></el-color-picker>
            <p>Gray 2 Color</p>
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
      light: {},
      dark: {},
      semi_dark: {}
    };
  },
  created() {
    this.getThemes();
  },
  mounted() {},
  methods: {
    reset(mode) {
      if (mode === "light") {
        this.light = this.default_themes[0];
        this.update("light");
      }
      if (mode === "dark") {
        this.dark = this.default_themes[1];
        this.update("dark");
      }
      if (mode === "semi_dark") {
        this.semi_dark = this.default_themes[2];
        this.update("semi_dark");
      }
    },
    getThemes() {
      axios
        .get(`web/getTheme`)
        .then(response => {
          this.light = response.data.filter(data => {
            return data.mode === "light";
          })[0];
          this.dark = response.data.filter(data => {
            return data.mode === "dark";
          })[0];
          this.semi_dark = response.data.filter(data => {
            return data.mode === "semi_dark";
          })[0];
        })
        .catch(error => {});
    },
    update(mode) {
      var data = {};
      if (mode === "light") {
        data = this.light;
      }
      if (mode === "dark") {
        data = this.dark;
      }
      if (mode === "semi_dark") {
        data = this.semi_dark;
      }
      axios
        .post(`web/updateTheme`, { data: data })
        .then(response => {
          this.getThemes();
        })
        .catch(error => {});
    }
  }
};
</script>

