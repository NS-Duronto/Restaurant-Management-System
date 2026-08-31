<template>
    <div class="db-breadcrumb" v-if="breadcrumbs && breadcrumbs.length > 0">
        <ul class="db-breadcrumb-list">
            <li v-if="authDefaultPermission && Object.keys(authDefaultPermission).length > 0" class="db-breadcrumb-item">
                <router-link class="db-breadcrumb-link" :to="'/admin/'+authDefaultPermission.url">
                    {{ $t('menu.'+authDefaultPermission.name) }}
                </router-link>
            </li>
            <li class="db-breadcrumb-item" v-for="(val, key) of breadcrumbs" :key="key">
                <span v-if="val && val.meta && key !== breadcrumbs.length - 1">
                    <router-link class="db-breadcrumb-link" :to="val.path">
                        {{ $t('menu.'+val.meta.breadcrumb) }}
                    </router-link>
                </span>
                <span v-else-if="val && val.meta" class="text-heading dark:text-gray-200 font-semibold">
                    {{ $t('menu.'+val.meta.breadcrumb) }}
                </span>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: "BreadcrumbComponent",
    data() {
        return {
            breadcrumbs: []
        }
    },
    computed: {
        authDefaultPermission: function () {
            return this.$store.getters.authDefaultPermission;
        }
    },
    watch: {
        $route() {
            this.route();
        }
    },
    created() {
        this.route();
    },
    methods: {
        route: function () {
            let routeArray = [], filterBreadCrumbs = this.$route.matched || [];
            for (let i = 0; i < filterBreadCrumbs.length; i++) {
                if (filterBreadCrumbs[i] && filterBreadCrumbs[i].meta && filterBreadCrumbs[i].meta.breadcrumb) {
                    routeArray.push(filterBreadCrumbs[i]);
                }
            }
            this.breadcrumbs = routeArray;
        }
    }
}
</script>

<style scoped>

</style>
