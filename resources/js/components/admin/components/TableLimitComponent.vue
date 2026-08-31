<template>
    <div class="dropdown-group" v-if="page && page.total > 10">
        <button class="db-card-filter-btn dropdown-btn">
            <span>{{ pageSearch.per_page }}</span>
            <i class="lab lab-arrow-down-2 lab-font-size-17"></i>
        </button>
        <ul
            class="p-2 rounded-xl shadow-2xl absolute top-9 ltr:right-0 rtl:left-0 z-10 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 transition-all duration-300 origin-top scale-y-0 dropdown-list">
            <li class="flex items-center gap-2 py-1 px-2.5 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800"
                v-for="option in perPageOptions" :key="option" @click="selectPerPage(option)">
                <span class="text-heading dark:text-gray-200 capitalize text-sm">{{ option }}</span>
            </li>
        </ul>
    </div>
</template>


<script>

export default {
    name: "TableLimitComponent",
    props: {
        page: { type: Object },
        search: { type: Object },
        method: { type: Function }
    },
    data() {
        return {
            perPageOptions: [10, 25, 50, 100, 500, 1000],
            pageSearch: {
                per_page: 10
            }
        };
    },
    methods: {
        limitChange: function () {
            this.method();
        },
        selectPerPage(value) {
            this.pageSearch.per_page = value;
            this.search.per_page = value;
            this.limitChange();
        },
    }
}
</script>