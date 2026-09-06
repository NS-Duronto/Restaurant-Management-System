import axios from 'axios';

export const posPaymentSetting = {
    namespaced: true,
    state: {
        lists: {
            order_setup_pos_card_types: ['Visa', 'Mastercard', 'Takapay', 'Nexuspay'],
            order_setup_pos_mfs_types: ['bKash', 'Rocket', 'Nagad', 'Ucash']
        },
    },
    getters: {
        lists: function (state) {
            return state.lists;
        }
    },
    actions: {
        lists: function (context) {
            return new Promise((resolve, reject) => {
                axios.get('admin/setting/pos-payment').then((res) => {
                    context.commit('lists', res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        save: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.put('admin/setting/pos-payment', payload).then(res => {
                    context.commit('lists', res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        }
    },
    mutations: {
        lists: function (state, payload) {
            state.lists = payload;
        }
    },
};
