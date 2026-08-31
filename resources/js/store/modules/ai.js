import axios from 'axios'

export const ai = {
    namespaced: true,
    state: {
        status: false,
        name: null,
        description: null,
        caution: null,
        chatHistory: [],
    },
    getters: {
        status: (state) => state.status,
        name: (state) => state.name,
        description: (state) => state.description,
        caution: (state) => state.caution,
        chatHistory: (state) => state.chatHistory,
    },
    actions: {
        status: function (context) {
            return new Promise((resolve, reject) => {
                axios.get('admin/ai/status').then((res) => {
                    context.commit('status', res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        name: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.post('admin/ai/name', payload).then((res) => {
                    context.commit('name', res.data.data.response);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        description: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.post('admin/ai/description', payload).then((res) => {
                    context.commit('description', res.data.data.response);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        caution: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.post('admin/ai/caution', payload).then((res) => {
                    context.commit('caution', res.data.data.response);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        chatHistory: function (context) {
            return new Promise((resolve, reject) => {
                axios.get('admin/ai/chat-history').then((res) => {
                    context.commit('chatHistory', res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        sendChatMessage: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.post('admin/ai/chat', {name: payload}).then((res) => {
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        setChatResponse: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.post(`admin/ai/chat-response/${payload.id}`, {name: payload.name}).then((res) => {
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        }
    },
    mutations: {
        status: function (state, payload) {
            state.status = payload;
        },
        name: function (state, payload) {
            state.name = payload;
        },
        description: function (state, payload) {
            state.description = payload;
        },
        caution: function (state, payload) {
            state.caution = payload;
        },
        chatHistory: function (state, payload) {
            state.chatHistory = payload;
        }
    }
}
