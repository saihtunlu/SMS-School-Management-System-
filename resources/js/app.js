require("./bootstrap");

import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import Gate from "./gate";

//form wizard
import VueFormWizard from "vue-form-wizard";
import "vue-form-wizard/dist/vue-form-wizard.min.css";
Vue.use(VueFormWizard);



//Vue Cropper
import VueCropper from "vue-cropper";
Vue.use(VueCropper);

//Vue Moment
Vue.use(require("vue-moment"));

// //Timetable
// import Schedule from 'vue-schedule'
// Vue.use(Schedule)

//Vue2-Transition
import Transitions from 'vue2-transitions'
Vue.use(Transitions)

//BacktoTop
import VmBackTop from "vue-multiple-back-top";
Vue.component(VmBackTop.name, VmBackTop);

//Vuesax_UI
import Vuesax from "vuesax";
import "vuesax/dist/vuesax.css";
import "material-icons/iconfont/material-icons.css";
Vue.use(Vuesax);

//Element-UI
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import locale from "element-ui/lib/locale/lang/en";
Vue.use(ElementUI, { locale });

//CustomCSS & Animation
import "./assets/CustomCSS.css";
import "./assets/animation.css";
import "./assets/css/iconfont.css";

//Gate
if (localStorage.getItem("Auth")) {
    var user = JSON.parse(localStorage.getItem("Auth"));
    Vue.prototype.$gate = new Gate(user);
}

// vue-tour 
import VueTour from 'vue-tour'
require('vue-tour/dist/vue-tour.css')
Vue.use(VueTour)

// //Vue-Page-transition
import VuePageTransition from "vue-page-transition";
Vue.use(VuePageTransition);

//Apex Chart
import VueApexCharts from "vue-apexcharts";
Vue.use(VueApexCharts)

Vue.component("App", App);

Vue.config.productionTip = false;
const app = new Vue({
    created() { },
    el: "#app",
    router
});