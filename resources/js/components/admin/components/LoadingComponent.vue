<template>
    <VueElementLoading 
        spinner="bar-fade-scale" 
        :color="setting.theme_primary_color || '#ff7043'"
        :active="props && props.isActive" 
        :background-color="isDark ? 'rgba(17, 24, 39, 0.75)' : 'rgba(255, 255, 255, 0.75)'"
        :is-full-screen="props && props.isFullScreen === true" 
    />
</template>

<script>
import VueElementLoading from 'vue-element-loading';

export default {
    name: "LoadingComponent",
    components: { VueElementLoading },
    props: ['props'],
    computed: {
        setting() {
            return this.$store.getters['frontendSetting/lists'] || {};
        },
        isDark() {
            return document.documentElement.classList.contains('dark') || localStorage.getItem('rms_theme') === 'dark';
        }
    }
}
</script>
