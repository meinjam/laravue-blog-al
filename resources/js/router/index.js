import Vue from "vue";
import VueRouter from "vue-router";
import Home from "../views/Home.vue";
import Tags from "../views/Tags.vue";
import Category from "../views/Category.vue";

Vue.use(VueRouter);

const routes = [{
        path: "/",
        name: "Home",
        component: Home
    },
    {
        path: "/tags",
        name: "Tags",
        component: Tags
    },
    {
        path: "/categories",
        name: "Category",
        component: Category
    }
];

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;
