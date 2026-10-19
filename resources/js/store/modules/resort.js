import axios from "axios";

export const resort = {
    namespaced: true,
    state: {
        landingContent: {},
        settings: {},
        sections: [],
        sliders: [],
        galleries: [],
        events: [],
        teams: [],
        plans: [],
        partners: [],
        bookings: [],
        websiteOrders: [],
        temp: {
            temp_id: null,
            isEditing: false,
        },
    },
    getters: {
        landingContent: (state) => state.landingContent,
        settings: (state) => state.settings,
        sections: (state) => state.sections,
        sliders: (state) => state.sliders,
        galleries: (state) => state.galleries,
        events: (state) => state.events,
        teams: (state) => state.teams,
        plans: (state) => state.plans,
        partners: (state) => state.partners,
        bookings: (state) => state.bookings,
        websiteOrders: (state) => state.websiteOrders,
        temp: (state) => state.temp,
    },
    mutations: {
        setLandingContent: (state, payload) => {
            state.landingContent = payload;
        },
        setSettings: (state, payload) => {
            state.settings = payload;
        },
        setSections: (state, payload) => {
            state.sections = payload;
        },
        setSliders: (state, payload) => {
            state.sliders = payload;
        },
        setGalleries: (state, payload) => {
            state.galleries = payload;
        },
        setEvents: (state, payload) => {
            state.events = payload;
        },
        setTeams: (state, payload) => {
            state.teams = payload;
        },
        setPlans: (state, payload) => {
            state.plans = payload;
        },
        setPartners: (state, payload) => {
            state.partners = payload;
        },
        setBookings: (state, payload) => {
            state.bookings = payload;
        },
        setWebsiteOrders: (state, payload) => {
            state.websiteOrders = payload;
        },
        temp: (state, payload) => {
            state.temp.temp_id = payload.temp_id;
            state.temp.isEditing = payload.isEditing;
        },
        reset: (state) => {
            state.temp.temp_id = null;
            state.temp.isEditing = false;
        },
    },
    actions: {
        // Public Landing
        fetchLandingContent(context) {
            return new Promise((resolve, reject) => {
                axios.get("frontend/resort/content").then((res) => {
                    context.commit("setLandingContent", res.data.data);
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },
        submitBooking(context, payload) {
            return new Promise((resolve, reject) => {
                axios.post("frontend/resort/booking", payload).then((res) => {
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },

        // Admin Settings
        getSettings(context) {
            return new Promise((resolve, reject) => {
                axios.get("admin/resort/settings").then((res) => {
                    context.commit("setSettings", res.data.data);
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },
        updateSettings(context, payload) {
            return new Promise((resolve, reject) => {
                axios.post("admin/resort/settings", payload).then((res) => {
                    context.commit("setSettings", res.data.data);
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },

        // Sections
        getSections(context) {
            return new Promise((resolve, reject) => {
                axios.get("admin/resort/sections").then((res) => {
                    context.commit("setSections", res.data.data);
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },
        updateSectionsOrder(context, payload) {
            return new Promise((resolve, reject) => {
                axios.post("admin/resort/sections/reorder", { sections: payload }).then((res) => {
                    context.commit("setSections", res.data.data);
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },

        // Sliders
        getSliders(context) {
            return new Promise((resolve, reject) => {
                axios.get("admin/resort/sliders").then((res) => {
                    context.commit("setSliders", res.data.data);
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },
        saveSlider(context, payload) {
            return new Promise((resolve, reject) => {
                let method = axios.post;
                let url = "admin/resort/sliders";
                if (context.state.temp.isEditing) {
                    url = `admin/resort/sliders/${context.state.temp.temp_id}`;
                }
                method(url, payload, { headers: { 'Content-Type': 'multipart/form-data' } }).then((res) => {
                    context.dispatch("getSliders");
                    context.commit("reset");
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },
        destroySlider(context, id) {
            return new Promise((resolve, reject) => {
                axios.delete(`admin/resort/sliders/${id}`).then((res) => {
                    context.dispatch("getSliders");
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },
        updateSlidersOrder(context, payload) {
            return new Promise((resolve, reject) => {
                axios.post("admin/resort/sliders/reorder", { sliders: payload }).then((res) => {
                    context.commit("setSliders", res.data.data);
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },

        // Galleries
        getGalleries(context) {
            return new Promise((resolve, reject) => {
                axios.get("admin/resort/galleries").then((res) => {
                    context.commit("setGalleries", res.data.data);
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },
        saveGallery(context, payload) {
            return new Promise((resolve, reject) => {
                let url = "admin/resort/galleries";
                if (context.state.temp.isEditing) {
                    url = `admin/resort/galleries/${context.state.temp.temp_id}`;
                }
                axios.post(url, payload, { headers: { 'Content-Type': 'multipart/form-data' } }).then((res) => {
                    context.dispatch("getGalleries");
                    context.commit("reset");
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },
        destroyGallery(context, id) {
            return new Promise((resolve, reject) => {
                axios.delete(`admin/resort/galleries/${id}`).then((res) => {
                    context.dispatch("getGalleries");
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },

        // Events
        getEvents(context) {
            return new Promise((resolve, reject) => {
                axios.get("admin/resort/events").then((res) => {
                    context.commit("setEvents", res.data.data);
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },
        saveEvent(context, payload) {
            return new Promise((resolve, reject) => {
                let url = "admin/resort/events";
                if (context.state.temp.isEditing) {
                    url = `admin/resort/events/${context.state.temp.temp_id}`;
                }
                axios.post(url, payload, { headers: { 'Content-Type': 'multipart/form-data' } }).then((res) => {
                    context.dispatch("getEvents");
                    context.commit("reset");
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },
        destroyEvent(context, id) {
            return new Promise((resolve, reject) => {
                axios.delete(`admin/resort/events/${id}`).then((res) => {
                    context.dispatch("getEvents");
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },

        // Teams
        getTeams(context) {
            return new Promise((resolve, reject) => {
                axios.get("admin/resort/teams").then((res) => {
                    context.commit("setTeams", res.data.data);
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },
        saveTeam(context, payload) {
            return new Promise((resolve, reject) => {
                let url = "admin/resort/teams";
                if (context.state.temp.isEditing) {
                    url = `admin/resort/teams/${context.state.temp.temp_id}`;
                }
                axios.post(url, payload, { headers: { 'Content-Type': 'multipart/form-data' } }).then((res) => {
                    context.dispatch("getTeams");
                    context.commit("reset");
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },
        destroyTeam(context, id) {
            return new Promise((resolve, reject) => {
                axios.delete(`admin/resort/teams/${id}`).then((res) => {
                    context.dispatch("getTeams");
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },
        updateTeamsOrder(context, payload) {
            return new Promise((resolve, reject) => {
                axios.post("admin/resort/teams/reorder", { teams: payload }).then((res) => {
                    context.commit("setTeams", res.data.data);
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },

        // Plans
        getPlans(context) {
            return new Promise((resolve, reject) => {
                axios.get("admin/resort/plans").then((res) => {
                    context.commit("setPlans", res.data.data);
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },
        savePlan(context, payload) {
            return new Promise((resolve, reject) => {
                let method = axios.post;
                let url = "admin/resort/plans";
                if (context.state.temp.isEditing) {
                    method = axios.put;
                    url = `admin/resort/plans/${context.state.temp.temp_id}`;
                }
                method(url, payload).then((res) => {
                    context.dispatch("getPlans");
                    context.commit("reset");
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },
        destroyPlan(context, id) {
            return new Promise((resolve, reject) => {
                axios.delete(`admin/resort/plans/${id}`).then((res) => {
                    context.dispatch("getPlans");
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },

        // Partners
        getPartners(context) {
            return new Promise((resolve, reject) => {
                axios.get("admin/resort/partners").then((res) => {
                    context.commit("setPartners", res.data.data);
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },
        savePartner(context, payload) {
            return new Promise((resolve, reject) => {
                let url = "admin/resort/partners";
                if (context.state.temp.isEditing) {
                    url = `admin/resort/partners/${context.state.temp.temp_id}`;
                }
                axios.post(url, payload, { headers: { 'Content-Type': 'multipart/form-data' } }).then((res) => {
                    context.dispatch("getPartners");
                    context.commit("reset");
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },
        destroyPartner(context, id) {
            return new Promise((resolve, reject) => {
                axios.delete(`admin/resort/partners/${id}`).then((res) => {
                    context.dispatch("getPartners");
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },
        updatePartnersOrder(context, payload) {
            return new Promise((resolve, reject) => {
                axios.post("admin/resort/partners/reorder", { partners: payload }).then((res) => {
                    context.commit("setPartners", res.data.data);
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },

        // Bookings
        getBookings(context) {
            return new Promise((resolve, reject) => {
                axios.get("admin/resort/bookings").then((res) => {
                    context.commit("setBookings", res.data.data);
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },
        updateBookingStatus(context, payload) {
            return new Promise((resolve, reject) => {
                axios.patch(`admin/resort/bookings/${payload.id}/status`, { status: payload.status }).then((res) => {
                    context.dispatch("getBookings");
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },
        destroyBooking(context, id) {
            return new Promise((resolve, reject) => {
                axios.delete(`admin/resort/bookings/${id}`).then((res) => {
                    context.dispatch("getBookings");
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },

        // Website Orders
        getWebsiteOrders(context) {
            return new Promise((resolve, reject) => {
                axios.get("admin/resort/website-orders").then((res) => {
                    context.commit("setWebsiteOrders", res.data.data);
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },
        downloadDbBackup(context) {
            return new Promise((resolve, reject) => {
                axios.get("admin/backup/database", { responseType: 'blob' }).then((res) => {
                    resolve(res);
                }).catch((err) => reject(err));
            });
        },
    },
};
