<template>
    <div id="backup" class="db-card db-tab-div active">
        <div class="db-card-header">
            <h3 class="db-card-title">{{ $t("menu.backup") }}</h3>
        </div>
        <div class="db-card-body">
            <div class="p-6 rounded-2xl bg-gradient-to-br from-slate-50 to-emerald-50/30 dark:from-gray-800 dark:to-gray-800/80 border border-slate-200/80 dark:border-gray-700">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="max-w-xl">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                                <i class="fa-solid fa-database text-lg"></i>
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-gray-800 dark:text-gray-100">
                                    {{ $t('resort.db_backup') }}
                                </h4>
                                <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400">
                                    MySQL Full Database Dump (.sql)
                                </span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed mt-2">
                            {{ $t('resort.db_backup_desc') }}
                        </p>
                    </div>

                    <div class="flex-shrink-0">
                        <button type="button" @click="downloadBackup" :disabled="backupLoading"
                            class="db-btn text-white bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 transition-all duration-200 py-2.5 px-5 shadow-sm rounded-lg flex items-center gap-2 font-medium">
                            <i v-if="!backupLoading" class="fa-solid fa-cloud-arrow-down text-base"></i>
                            <i v-else class="fa-solid fa-circle-notch fa-spin text-base"></i>
                            <span>{{ backupLoading ? $t('resort.downloading') : $t('resort.download_db_backup') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import alertService from "../../../../services/alertService";

export default {
    name: "BackupComponent",
    data() {
        return {
            backupLoading: false,
        };
    },
    methods: {
        downloadBackup() {
            this.backupLoading = true;
            this.$store.dispatch('resort/downloadDbBackup').then((res) => {
                this.backupLoading = false;
                const blob = new Blob([res.data], { type: 'application/sql' });
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                const dateStr = new Date().toISOString().slice(0, 10);
                link.download = `sohoj_rms_db_backup_${dateStr}.sql`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                window.URL.revokeObjectURL(url);
                alertService.success(this.$t('resort.db_backup_success'));
            }).catch((err) => {
                this.backupLoading = false;
                console.error(err);
                alertService.error(this.$t('resort.db_backup_failed'));
            });
        }
    }
};
</script>
