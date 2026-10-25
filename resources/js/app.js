/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import {createApp} from 'vue';
import DefaultComponent from "./components/DefaultComponent.vue";
import router from './router';
import store from './store';
import axios from 'axios';
import i18n from "./i18n";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import VueSimpleAlert from "vue3-simple-alert";
import VueNextSelect from 'vue-next-select';
import 'vue-next-select/dist/index.css';
import ENV from './config/env';
import 'sweetalert2/dist/sweetalert2.min.css';

/* Start tooltip alert code */
const options = {
    timeout: 2000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: false,
    closeButton: "button",
    icon: true,
    rtl: false
};
/* End tooltip alert code */


/* Start axios code*/
const API_URL = ENV.API_URL;
const API_KEY = ENV.API_KEY;

axios.defaults.baseURL = API_URL + '/api';
axios.interceptors.request.use(
    config => {
        if (API_KEY) {
            config.headers['x-api-key'] = API_KEY;
        }
        if (localStorage.getItem('vuex')) {
            const vuex = JSON.parse(localStorage.getItem('vuex'));
            const token = vuex.auth.authToken;
            const language = vuex.globalState.lists.language_code;
            config.headers['Authorization'] = token ? `Bearer ${token}` : '';
            config.headers['x-localization'] = language;
        }
        return config;
    },
    error => Promise.reject(error),
);
axios.interceptors.response.use(
    response => {
        if (store.getters.authStatus) {
            store.commit('updateActivity');
        }
        return response;
    },
    error => {
        if (error.response && error.response.status === 401) {
            store.commit('authLogout');
            if (window.location.pathname !== '/login') {
                window.location.href = '/login';
            }
        }
        return Promise.reject(error);
    }
);

let activityThrottle = 0;
const registerUserActivity = () => {
    const now = Date.now();
    if (now - activityThrottle > 10000) {
        activityThrottle = now;
        if (store.getters.authStatus) {
            store.commit('updateActivity');
        }
    }
};
window.addEventListener('click', registerUserActivity, { passive: true });
window.addEventListener('keydown', registerUserActivity, { passive: true });
window.addEventListener('touchstart', registerUserActivity, { passive: true });
/* End axios code */

const app = createApp(DefaultComponent);
app.component('vue-select', VueNextSelect)
app.use(router)
app.use(store)
app.use(VueSimpleAlert)
app.use(Toast, options)
app.use(i18n)
app.mount('#app');
