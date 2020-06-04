require("./bootstrap");
window.Vue = require("vue");
import router from "./router/index";
import ViewUI from "view-design";
import "view-design/dist/styles/iview.css";

Vue.use(ViewUI);

Vue.component("mainapp", require("./App.vue").default);

const app = new Vue({
    el: "#app",
    router
});
