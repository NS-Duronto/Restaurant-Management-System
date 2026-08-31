import axios from 'axios';
import appService from "../../services/appService";

export const sendToKitchen = {
    namespaced: true,
    state: {
        lists: [],
        page: {},
        pagination: [],
        show: {},
        temp: {
            temp_id: null,
            isEditing: false,
        },
    },
    getters: {
        lists: (state) => state.lists,
        pagination: (state) => state.pagination,
        page: (state) => state.page,
        show: (state) => state.show,
        temp: (state) => state.temp,
    },
    actions: {
        lists: (context, payload) => {
            return new Promise((resolve, reject) => {
                let url = 'admin/send-to-kitchen';
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    if (typeof payload?.vuex === "undefined" || payload.vuex === true) {
                        context.commit('lists', res.data.data);
                        context.commit('page', res.data.meta);
                        context.commit('pagination', res.data);
                    }
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },
        save: (context, payload) => {
            return new Promise((resolve, reject) => {
                axios.post('/admin/send-to-kitchen', payload.form).then(res => {
                    context.dispatch('lists', payload.search).then().catch();
                    context.commit('reset');
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },
        destroy: (context, payload) => {
            return new Promise((resolve, reject) => {
                axios.delete(`admin/send-to-kitchen/${payload.id}`).then((res) => {
                    context.dispatch('lists', payload.search).then().catch();
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },
        show: (context, payload) => {
            return new Promise((resolve, reject) => {
                axios.get(`admin/send-to-kitchen/show/${payload}`).then((res) => {
                    context.commit('show', res.data.data);
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },
        reset: (context) => context.commit('reset'),
    },
    mutations: {
        lists: (state, payload) => state.lists = payload,
        pagination: (state, payload) => state.pagination = payload,
        page: (state, payload) => {
            if (typeof payload !== "undefined" && payload !== null) {
                state.page = {
                    from: payload.from,
                    to: payload.to,
                    total: payload.total
                }
            }
        },
        show: (state, payload) => state.show = payload,
        temp: (state, payload) => {
            state.temp.temp_id = payload;
            state.temp.isEditing = true;
        },
        reset: (state) => {
            state.temp.temp_id = null;
            state.temp.isEditing = false;
        }
    },
};
