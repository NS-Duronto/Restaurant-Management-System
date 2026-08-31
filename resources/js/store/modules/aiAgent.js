import axios from 'axios'
import appService from "../../services/appService.js";

export const aiAgent = {
    namespaced: true,
    state: {
        lists: []
    },
    getters: {
        lists: function (state) {
            return state.lists;
        }
    },
    actions: {
        fetch: function (context, payload = {}) {
            return new Promise((resolve, reject) => {
                let url = 'admin/setting/ai-agent';
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    context.commit("lists", res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        save: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.post(`/admin/setting/ai-agent/update`, payload.form).then(res => {
                    context.dispatch("fetch", payload.search).then().catch();
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        }
    },
    mutations: {
        lists: function (state, payload) {
            state.lists = payload
        }
    },
}
