import axios from 'axios';
import appService from "../../services/appService";

export const itemIngredient = {
    namespaced: true,
    state: {
        lists: [],
        page: {},
        pagination: [],
        show: {},
        summary: {},
        temp: {
            temp_id: null,
            isEditing: false,
        },
    },
    getters: {
        lists: function (state) {
            return state.lists;
        },
        pagination: function (state) {
            return state.pagination;
        },
        page: function (state) {
            return state.page;
        },
        show: function (state) {
            return state.show;
        },
        summary: function (state) {
            return state.summary;
        },
        temp: function (state) {
            return state.temp;
        }
    },
    actions: {
        lists: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = `admin/item/ingredient/${payload.id}`;
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    if (typeof payload.vuex === "undefined" || payload.vuex === true) {
                        context.commit('lists', res.data.data);
                        context.commit('page', res.data.meta);
                        context.commit('pagination', res.data);
                    }
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        summary: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.get(`admin/item/ingredient/${payload}/summary`).then((res) => {
                    context.commit('summary', res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        save: function (context, payload) {
            return new Promise((resolve, reject) => {
                let method = axios.post;
                let url = `/admin/item/ingredient/${payload.id}`;
                if (context.state.temp.isEditing) {
                    method = axios.put;
                    url = `/admin/item/ingredient/${payload.id}/${context.state.temp.temp_id}`;
                }
                method(url, payload.form).then(res => {
                    context.dispatch('lists', payload.search).then().catch();
                    context.dispatch('summary', payload.id).then().catch();
                    context.commit('reset');
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        edit: function (context, payload) {
            context.commit('temp', payload);
        },
        destroy: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.delete(`admin/item/ingredient/${payload.item}/${payload.id}`).then((res) => {
                    context.dispatch('lists', payload.search).then().catch();
                    context.dispatch('summary', payload.item).then().catch();
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        show: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.get(`admin/item/ingredient/${payload.item}/show/${payload.id}`).then((res) => {
                    context.commit('show', res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        reset: function (context) {
            context.commit('reset');
        },
    },
    mutations: {
        lists: function (state, payload) {
            state.lists = payload;
        },
        summary: function (state, payload) {
            state.summary = payload;
        },
        pagination: function (state, payload) {
            state.pagination = payload;
        },
        page: function (state, payload) {
            if (typeof payload !== "undefined" && payload !== null) {
                state.page = {
                    from: payload.from,
                    to: payload.to,
                    total: payload.total
                };
            }
        },
        show: function (state, payload) {
            state.show = payload;
        },
        temp: function (state, payload) {
            state.temp.temp_id = payload;
            state.temp.isEditing = true;
        },
        reset: function (state) {
            state.temp.temp_id = null;
            state.temp.isEditing = false;
        }
    },
};
