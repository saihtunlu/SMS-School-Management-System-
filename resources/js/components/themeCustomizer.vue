<template>
  <div>
    <vs-button
      class="float_btn flex-center"
      color="primary"
      type="filled"
      icon-pack="feather"
      icon="icon-settings"
      data-v-step="2"
      @click="Right_sidebar=!Right_sidebar"
    ></vs-button>
    <vs-sidebar
      hidden-background
      position-right
      :click-not-close="true"
      id="setting"
      parent="body"
      default-index="1"
      color="primary"
      class="sidebarx setting"
      spacer
      v-model="Right_sidebar"
    >
      <div class="header-sidebar pt-4 pb-0" slot="header">
        <h4 class="header-text" style="color:rgb(var(--vs-dark));">
          THEME CUSTOMIZER
          <span id="close_setting" @click="Right_sidebar=false">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="20px"
              height="20px"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="feather feather-x"
            >
              <line x1="18" y1="6" x2="6" y2="18" />
              <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
          </span>
        </h4>
      </div>
      <vs-divider />
      <div class="color_btn mt-3 pb-4">
        <h5 class="text-lg text-bold">Page Transitions</h5>
        <div class="custom_input">
          <el-select
            v-model="animation"
            @change="setAnimation()"
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
      <div class="mt-4 color_btn pb-3">
        <h5 class="text-lg text-bold">Theme Color</h5>
        <p class="text-md mb0 mt-1">Primary Color</p>
        <div class="color_div">
          <vs-button
            class="theme_color"
            :class="{ 'active_color': themecolor == 'rgb(130, 122, 243)' }"
            style="background:rgb(130, 122, 243) !important;"
            @click="color_btn('rgb(130, 122, 243)')"
          ></vs-button>

          <vs-button
            class="theme_color"
            :class="{ 'active_color': themecolor == 'rgb(255, 71, 87)' }"
            style="background:rgb(255, 71, 87) !important;"
            @click="color_btn('rgb(255, 71, 87)')"
          ></vs-button>

          <vs-button
            class="theme_color"
            :class="{ 'active_color': themecolor == 'rgb(70, 201, 58)' }"
            style="background:rgb(70, 201, 58) !important;"
            @click="color_btn('rgb(70, 201, 58)')"
          ></vs-button>

          <vs-button
            class="theme_color"
            :class="{ 'active_color': themecolor == 'rgb(107, 68, 204)' }"
            style="background:rgb(107, 68, 204) !important;"
            @click="color_btn('rgb(107, 68, 204)')"
          ></vs-button>

          <vs-button
            class="theme_color"
            :class="{ 'active_color': themecolor == 'rgb(255, 186, 0)' }"
            style="background:rgb(255, 186, 0) !important;"
            @click="color_btn('rgb(255, 186, 0)')"
          ></vs-button>
          <el-color-picker class="theme_color" v-model="themecolor" @change="change_color()"></el-color-picker>
        </div>

        <p class="text-md mb0 mt-1">Light Primary Color</p>
        <div class="color_div">
          <vs-button
            class="theme_color"
            :class="{ 'active_color': Secthemecolor == 'rgb(180, 122, 243)' }"
            style="background:rgb(180, 122, 243) !important;"
            @click="Seccolor_btn('rgb(180, 122, 243)')"
          ></vs-button>

          <vs-button
            class="theme_color"
            :class="{ 'active_color': Secthemecolor == 'rgb(255, 91, 87)' }"
            style="background:rgb(255, 91, 87) !important;"
            @click="Seccolor_btn('rgb(255, 91, 87)')"
          ></vs-button>

          <vs-button
            class="theme_color"
            :class="{ 'active_color': Secthemecolor == 'rgb(120, 201, 58)' }"
            style="background:rgb(120, 201, 58) !important;"
            @click="Seccolor_btn('rgb(120, 201, 58)')"
          ></vs-button>

          <vs-button
            class="theme_color"
            :class="{ 'active_color': Secthemecolor == 'rgb(157, 68, 204)' }"
            style="background:rgb(157, 68, 204) !important;"
            @click="Seccolor_btn('rgb(157, 68, 204)')"
          ></vs-button>

          <vs-button
            class="theme_color"
            :class="{ 'active_color': Secthemecolor == 'rgb(255, 236, 0)' }"
            style="background:rgb(255, 236, 0) !important;"
            @click="Seccolor_btn('rgb(255, 236, 0)')"
          ></vs-button>
          <el-color-picker class="theme_color" v-model="Secthemecolor" @change="change_Seccolor()"></el-color-picker>
        </div>
      </div>
      <div class="color_btn mt-4 pb-3">
        <h5 class="text-lg text-bold">Theme Mode</h5>
        <vs-radio
          class="mode"
          v-model="theme_mode"
          vs-name="theme_mode"
          v-on:change="themeFilter()"
          vs-value="dark"
        >Dark</vs-radio>

        <vs-radio
          class="mode"
          v-model="theme_mode"
          vs-name="theme_mode"
          v-on:change="themeFilter()"
          vs-value="light"
        >Light</vs-radio>

        <vs-radio
          class="mode"
          v-model="theme_mode"
          vs-name="theme_mode"
          v-on:change="themeFilter()"
          vs-value="semi_dark"
        >Semi-dark</vs-radio>
      </div>
      <div class="mt-4 color_btn pb-3">
        <h5 class="text-lg text-bold">Sticky Header</h5>
        <vs-switch
          style="margin: 10px 10px 10px 0px;"
          v-model="sticky"
          @change="changeSticky()"
          icon-pack="feather"
          vs-icon-off="icon-x"
          vs-icon-on="icon-check"
        />
      </div>
      <div class="mt-4 color_btn pb-3">
        <h5 class="text-lg text-bold">Show Footer</h5>
        <vs-switch
          style="margin: 10px 10px 10px 0px;"
          v-model="show_footer"
          @change="changeFooter()"
          icon-pack="feather"
          vs-icon-off="icon-x"
          vs-icon-on="icon-check"
        />
      </div>
    </vs-sidebar>
  </div>
</template>

<script>
export default {
  data() {
    return {
      show_footer: true,
      sticky: true,
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
      ],
      animation: "zoom-in-out",
      Right_sidebar: false,
      themes: [],
      theme_mode: "light",
      theme: [],
      general_detail: {},
      color: "",
      Seccolor: "",
      themecolor: "#827AF3",
      Secthemecolor: "#B47AF3"
    };
  },
  mounted() {},
  created() {
    this.getGeneral();
    var theme_mode = localStorage.getItem("theme");
    if (theme_mode) {
      this.theme_mode = theme_mode;
    }
    this.getThemes();
  },
  methods: {
    getGeneral() {
      axios
        .get(`web/getGeneral`)
        .then(response => {
          this.general_detail = response.data;
          this.$vs.theme({
            primary: this.general_detail.primary,
            light_primary: this.general_detail.light_primary,
            success: this.general_detail.success,
            danger: this.general_detail.danger,
            warning: this.general_detail.warning
          });
          this.animation = this.general_detail.transition;

          this.started_action();
        })
        .catch(error => {});
    },
    started_action() {
      var ani = localStorage.getItem("animation");
      if (ani) {
        this.animation = ani;
      }
      //Theme
      var color = localStorage.getItem("color");
      var Seccolor = localStorage.getItem("Seccolor");

      var themecolor = localStorage.getItem("themecolor");
      var Secthemecolor = localStorage.getItem("Secthemecolor");
      var logo = localStorage.getItem("logo");
      var title = localStorage.getItem("title");
      if (logo !== this.general_detail.logo) {
        localStorage.removeItem("logo");
        localStorage.setItem("logo", this.general_detail.logo);
      }
      if (title !== this.general_detail.title) {
        localStorage.removeItem("title");
        localStorage.setItem("title", this.general_detail.title);
      }

      if (!logo) {
        localStorage.setItem("logo", this.general_detail.logo);
      }
      if (!title) {
        localStorage.setItem("title", this.general_detail.title);
      }
      if (themecolor) {
        this.themecolor = "#" + themecolor;
      }
      if (Secthemecolor) {
        this.Secthemecolor = "#" + Secthemecolor;
      }
      if (color) {
        this.themecolor = color;
        window.addEventListener("beforeunload", this.theme_color(color));
      }
      if (Seccolor) {
        this.Secthemecolor = Seccolor;
        window.addEventListener("beforeunload", this.theme_Seccolor(Seccolor));
      }
    },
    changeFooter() {
      this.$emit("footer", this.show_footer);
    },
    changeSticky() {
      this.$emit("Sticky", this.sticky);
    },
    setAnimation() {
      if (localStorage.getItem("animation")) {
        localStorage.removeItem("animation");
      }
      this.$emit("animation", this.animation);
      localStorage.setItem("animation", this.animation);
    },
    theme_color(default_color) {
      this.$vs.theme({
        primary: default_color
      });
    },
    theme_Seccolor(default_color) {
      this.$vs.theme({
        light_primary: default_color
      });
    },
    change_color() {
      var color = this.themecolor;
      color = color.toString().replace("#", "");
      let r = parseInt(color.substring(0, 2), 16);
      let g = parseInt(color.substring(2, 4), 16);
      let b = parseInt(color.substring(4, 6), 16);
      let result = "rgb(" + r + "," + g + "," + b + ")";
      this.$vs.theme({
        primary: result // my new color
      });
      localStorage.setItem("color", result);
      localStorage.setItem("themecolor", color);
    },
    change_Seccolor() {
      var color = this.Secthemecolor;
      color = color.toString().replace("#", "");
      let r = parseInt(color.substring(0, 2), 16);
      let g = parseInt(color.substring(2, 4), 16);
      let b = parseInt(color.substring(4, 6), 16);
      let result = "rgb(" + r + "," + g + "," + b + ")";
      this.$vs.theme({
        light_primary: result // my new color
      });
      localStorage.setItem("Seccolor", result);
      localStorage.setItem("Secthemecolor", color);
    },
    color_btn(e) {
      this.themecolor = e;
      this.$vs.theme({
        primary: e
      });
      localStorage.setItem("color", e);
    },
    Seccolor_btn(e) {
      this.Secthemecolor = e;
      this.$vs.theme({
        light_primary: e
      });
      localStorage.setItem("Seccolor", e);
    },
    dark_theme() {
      this.$vs.theme({
        theme: "rgb(16, 22, 58)",
        semi_theme: "rgb(16, 22, 58)",
        theme2: "rgb(24, 31, 68)",
        theme3: "rgb(24, 31, 68)",
        gray2: "rgb(24, 31, 68)",
        gray: "rgb(38, 44, 73)",
        semi_gray: "rgb(38, 44, 73)",
        main_bg: "rgb(38, 44, 73)",
        input: "rgb(38, 44, 73)",

        dark: "rgb(225,225,225)",
        semi_dark: "rgb(225,225,225)",
        dark2: "rgb(225,225,225)",
        light: "rgb(65,65,65)"
      });
      localStorage.setItem("theme", "dark");
      this.radios1 = "dark";
    },
    semi_dark_theme() {
      this.$vs.theme({
        semi_theme: "rgb(16, 22, 58)",
        semi_dark: "rgb(225,225,225)",
        semi_gray: "rgb(38, 44, 73)",
        theme: "rgb(255,255,255)",
        theme3: "rgb(245, 245, 245)",
        theme2: "rgb(255, 255, 255)",
        gray2: "rgb(195, 195, 195)",
        main_bg: "rgb(239, 238, 253)",
        gray: "rgb(239, 242, 247)",
        input: "rgb(255, 255, 255)",
        dark: "rgb(65,65,65)",
        dark2: "rgb(89,89,89)",
        light: "rgb(255,255,255)"
      });
      localStorage.setItem("theme", "semi_dark");
      this.radios1 = "semi_dark";
    },
    light_theme() {
      this.$vs.theme({
        theme: "rgb(255,255,255)",
        semi_theme: "rgb(255,255,255)",
        theme3: "rgb(245, 245, 245)",
        theme2: "rgb(255, 255, 255)",
        semi_gray: "rgb(239, 242, 247)",
        gray2: "rgb(239, 242, 247)",
        main_bg: "rgb(239, 238, 253)",
        gray: "rgb(239, 242, 247)",
        input: "rgb(255, 255, 255)",
        dark: "rgb(65,65,65)",
        semi_dark: "rgb(65,65,65)",
        dark2: "rgb(89,89,89)",
        light: "rgb(255,255,255)"
      });
    },
    themeFilter() {
      var theme = this.theme_mode;
      console.log("theme: ", theme);
      var data = this.themes.filter(data => {
        return data.mode === theme;
      });
      this.theme = data[0];
      this.$vs.theme({
        theme: this.theme.theme_1,
        theme2: this.theme.theme_2,
        theme3: this.theme.theme_3,
        gray2: this.theme.gray2,
        gray: this.theme.gray,
        main_bg: this.theme.main_bg,
        input: this.theme.input,
        dark: this.theme.dark,
        semi_dark: this.theme.semi_dark,
        semi_theme: this.theme.semi_theme
      });

      localStorage.setItem("theme", theme);
      this.radios1 = theme;
    },
    getThemes() {
      axios
        .get(`web/getTheme`)
        .then(response => {
          this.themes = response.data;
          this.themeFilter();
        })
        .catch(error => {});
    }
  }
};
</script>
