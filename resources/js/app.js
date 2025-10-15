import "./bootstrap";
import "../css/app.css";
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap";
import "popper.js";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
// import 'jquery';

import "admin-lte";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import "@bhplugin/vue3-datatable/dist/style.css";
import "@bhplugin/vue3-datatable/dist/style.css";
const appName = import.meta.env.VITE_APP_NAME || "NWSDB";

createInertiaApp({
    title: (title) => ` ${appName} - ${title} `,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(Toast, {
                    transition: "Vue-Toastification__fade",
                    maxToasts: 20,
                    newestOnTop: true,
                })
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
