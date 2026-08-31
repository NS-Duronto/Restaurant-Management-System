<template>
    <div dir="ltr">
        <TailwindPagination
            :data="pagination"
            @pagination-change-page="page"
            :active-classes="activeClass"
            :limit="1"
        />
    </div>
</template>

<script>

import { TailwindPagination } from 'laravel-vue-pagination';

export default {
    name: "PaginationBox",
    components: {TailwindPagination},
    props: {
        pagination: {type: Object},
        method: {type: Function}
    },
    data() {
        return {
            activeClass : [
                "!bg-primary",
                "!border-primary",
                "!text-white",
                "font-semibold",
                "shadow-sm"
            ]
        }
    },
    methods: {
        page: function (page = 1) {
            this.method(page);
        }
    }
}
</script>
