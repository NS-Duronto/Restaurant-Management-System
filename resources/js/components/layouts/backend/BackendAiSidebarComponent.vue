<template>
    <LoadingContentComponent :props="loading" />
    <div v-if="status" class="ai-move shadow-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800" @click.prevent="openCanvas('ai-sidebar')">
        <span class="mb-1">
            <i class="lab-fill-ai lab-font-size-24 text-orange-500"></i>
        </span>
        <span class="text-xs font-bold leading-none">
            <span class="text-orange-500">{{ $t('label.ai') }}&nbsp;</span>
            <span class="text-slate-900 dark:text-gray-100">{{ $t('label.helper') }}</span>
        </span>
    </div>

    <div v-if="status" id="ai-sidebar" @click="closeBackdrop"
        class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm duration-500 transition-all invisible opacity-0">
        <div
            class="w-full max-w-xl h-dvh bg-white dark:bg-gray-900 ms-auto ltr:translate-x-full rtl:-translate-x-full flex flex-col shadow-2xl transition-all duration-300 border-l border-gray-200 dark:border-gray-800">
            <div class="flex items-center justify-between p-6 border-b border-slate-100 dark:border-gray-800 flex-shrink-0 bg-white dark:bg-gray-900 z-10">
                <h3 class="text-xl font-bold text-slate-800 dark:text-gray-100">{{ $t('label.ai_assistant') }}</h3>
                <button class="lab lab-close lab-font-size-24 text-slate-500 dark:text-gray-400 hover:text-red-500 transition-colors"
                    @click="reset"></button>
            </div>

            <div class="flex-1 overflow-y-auto thin-scrolling p-6 bg-[#F7F7FC] dark:bg-gray-950" ref="chatContainer">
                <div class="space-y-6">
                    <div v-for="message in messages" :key="message.id" class="w-full">
                        <div
                            class="ml-auto flex w-fit justify-end bg-orange-500 text-white rounded-tl-none max-w-[85%] rounded-2xl p-4 text-sm font-medium leading-relaxed shadow-sm relative group">
                            {{ message.message }}
                        </div>

                        <div v-if="message.response"
                            class="flex w-full justify-start bg-white dark:bg-gray-800 text-slate-800 dark:text-gray-200 border border-slate-100 dark:border-gray-700 rounded-tr-none mt-6 max-w-[85%] rounded-2xl p-4 text-sm font-medium leading-relaxed shadow-sm relative group">
                            <div v-html="message.response"></div>
                        </div>
                    </div>
                    <div v-if="isTyping" class="flex justify-start">
                        <div
                            class="bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 rounded-2xl rounded-tl-none p-4 text-sm border border-slate-100 dark:border-gray-700">
                            <div class="flex gap-1">
                                <span class="w-2 h-2 bg-orange-500 rounded-full animate-bounce delay-0"></span>
                                <span class="w-2 h-2 bg-orange-500 rounded-full animate-bounce delay-0"></span>
                                <span class="w-2 h-2 bg-orange-500 rounded-full animate-bounce delay-0"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-white dark:bg-gray-900 border-t border-slate-100 dark:border-gray-800 flex-shrink-0">
                <form @submit.prevent="sendMessage" class="relative flex items-center">
                    <input v-model="chatInput" type="text" :placeholder="$t('label.type_your_message')"
                        :disabled="isTyping"
                        class="w-full pl-5 pr-12 py-4 rounded-xl border border-slate-200 dark:border-gray-800 bg-slate-50 dark:bg-gray-950 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 text-sm text-slate-700 dark:text-gray-200 placeholder:text-slate-400 dark:placeholder:text-gray-500 transition-all shadow-sm disabled:opacity-50">
                    <button type="submit" :disabled="!chatInput.trim() || isTyping"
                        class="absolute right-3 p-2 text-slate-400 hover:text-orange-500 transition-colors rounded-lg hover:bg-slate-100 dark:hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="lab-send-2 lab-font-size-24 leading-[30px]"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingContentComponent from "../../admin/components/LoadingContentComponent.vue";
import { useCanvas } from "../../../composables/canvas.js";
import alertService from "../../../services/alertService.js";

export default {
    name: "BackendAiSidebarComponent",
    components: { LoadingContentComponent },
    setup() {
        const { openCanvas, closeCanvas, closeBackdrop } = useCanvas();
        return { openCanvas, closeCanvas, closeBackdrop };
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            messages: [],
            chatInput: "",
            chatInputItem: "",
            isTyping: false
        }
    },
    computed: {
        status: function () {
            return this.$store.getters['ai/status'];
        }
    },
    mounted() {
        this.loadHistory();
    },
    methods: {
        loadHistory: function () {
            this.$store.dispatch('ai/status').then((res) => {
                if (res.data.data) {
                    this.loading.isActive = true;
                    this.$store.dispatch('ai/chatHistory').then((res) => {
                        this.loading.isActive = false;
                        if (res.data.data && res.data.data.length > 0) {
                            this.messages = res.data.data;
                        }
                        this.$nextTick(() => {
                            this.scrollToBottom();
                        });
                    }).catch((err) => {
                        this.loading.isActive = false;
                    });
                }
            }).catch((err) => {
                alertService.error(this.$t('message.something_wrong'));
            });
        },
        sendMessage: function () {
            this.$nextTick(() => {
                this.scrollToBottom();
            });
            this.$store.dispatch('ai/sendChatMessage', this.chatInput.trim()).then((res) => {
                if (res.data.data) {
                    this.messages.push(res.data.data);
                    this.$nextTick(() => {
                        this.scrollToBottom();
                    });
                    this.chatInputItem = this.chatInput;
                    this.chatInput = "";
                    this.isTyping = true;
                    this.$store.dispatch('ai/setChatResponse', { id: res.data.data.id, name: this.chatInputItem }).then((res) => {
                        this.isTyping = false;
                        this.messages.map((item, index) => {
                            if (index === this.messages.length - 1 && !item.response) {
                                item.response = res.data.data.response;
                            }
                        });
                        this.$nextTick(() => {
                            this.scrollToBottom();
                        });
                    }).catch((err) => {
                        this.isTyping = false;
                        alertService.error(this.$t('message.something_wrong'));
                    })
                }
            }).catch((err) => {
                this.isTyping = false;
                alertService.error(err.response?.data?.message || err.message);
            });
        },
        scrollToBottom: function () {
            const container = this.$refs.chatContainer;
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        },
        reset: function () {
            useCanvas().closeCanvas('ai-sidebar');
        }
    }
}
</script>
