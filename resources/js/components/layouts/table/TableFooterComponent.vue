<template>
    <LoadingComponent :props="loading" />

    <footer class="bg-primary">
        <div class="container">
            <div class="flex flex-col-reverse flex-col md:flex-row items-center justify-between gap-5 py-8">
                <p class="text-sm text-white">{{ setting.site_copyright }}</p>
                <nav class="flex flex-wrap items-center gap-4 sm:gap-6">
                    <router-link :to="{ name: 'resort.landing' }" class="text-sm font-semibold capitalize text-white hover:underline flex items-center gap-1.5">
                        <i class="lab lab-site text-xs"></i>
                        <span>{{ $t('menu.resort_website') }}</span>
                    </router-link>
                    <router-link :to="logged ? '/admin/dashboard' : '/login'" class="text-sm font-semibold capitalize text-white hover:underline flex items-center gap-1.5">
                        <i :class="logged ? 'lab lab-dashboard' : 'lab lab-lock'" class="text-xs"></i>
                        <span>{{ logged ? $t('menu.dashboard') : ($t('label.login') || 'Login') }}</span>
                    </router-link>
                    <router-link v-for="page in pages" :key="page.id" class="text-sm capitalize text-white hover:underline"
                        :to="{ name: 'table.page', params: { slug: this.$route.params.slug, pageSlug: page.slug } }">
                        {{ page.title }}
                    </router-link>
                </nav>
            </div>
        </div>
    </footer>
</template>


<script>
import statusEnum from "../../../enums/modules/statusEnum";
import menuSectionEnum from "../../../enums/modules/menuSectionEnum";
import LoadingComponent from "../../frontend/components/LoadingComponent";

export default {
    name: "TableFooterComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            }
        }
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        pages: function () {
            return this.$store.getters['frontendPage/lists'];
        },
        logged: function () {
            return !!this.$store.getters.authStatus;
        }
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch("frontendPage/lists", {
            paginate: 0,
            order_column: "id",
            order_type: "asc",
            menu_section_id: menuSectionEnum.FOOTER,
            status: statusEnum.ACTIVE
        }).then(res => {
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });
    }
}
</script>