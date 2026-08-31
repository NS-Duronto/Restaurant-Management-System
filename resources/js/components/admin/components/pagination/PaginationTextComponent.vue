<template>
    <div v-if="pageData && pageData.total > 0">
        <p class="text-sm text-gray-700 dark:text-gray-300">
            {{
                $t("message.pagination_label", {
                    from: pageData.from || 0,
                    to: pageData.to || 0,
                    total: pageData.total || 0
                })
            }}
        </p>
    </div>
</template>

<script>
export default {
    name: "PaginationTextComponent",
    props: ['props', 'page'],
    computed: {
        pageData() {
            return (this.props && this.props.page) ? this.props.page : this.page;
        }
    }
}
</script>
