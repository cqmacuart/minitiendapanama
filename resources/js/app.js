/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");

import ElementUI from "element-ui";
import "element-ui/lib/theme-chalk/index.css";

window.Vue.use(ElementUI);

Vue.component("App", require("./components/App.vue").default);
Vue.component("Admin", require("./components/Admin.vue").default);
Vue.component("Auth", require("./components/Auth.vue").default);

import router from "./routes";
import VueSocialSharing from "vue-social-sharing";

window.Vue.use(VueSocialSharing);

router.beforeEach((to, from, next) => {
    if (to.path == "/admin") {
        next({ name: "admin.home" });
    }
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!sessionStorage.getItem("currCustomer")) {
            next({ name: "inicio" });
        } else {
            next();
        }
    } else {
        next();
    }

    // IF ADMIN
    if (to.matched.some(record => record.meta.requiresAuthAdmin)) {
        if (!sessionStorage.getItem("userAdmin")) {
            console.log("usuario no existe");
            next({ name: "admin.login" });
        } else {
            next();
        }
    } else {
        next();
    }
});

const app = new Vue({
    el: "#app",
    router
});
