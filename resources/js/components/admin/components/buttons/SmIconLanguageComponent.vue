<template>
    <button type="button" data-modal="#tax-translation-modal" class="db-table-action language" @click="$emit('click', $event)">
        <i class="lab lab-languages"></i>
        <span class="db-tooltip">{{ $t('label.translations') }}</span>
    </button>
</template>

<script>
export default {
    name: "SmIconLanguageComponent",
    emits: ['click']
}
</script>

<style scoped>

</style>
