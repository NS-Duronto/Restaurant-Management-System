<template>
    <LoadingComponent :props="loading" />
    <PoscustomerComponent v-on:onCustomverCreate="onCustomverCreate" />

    <div class="md:w-[calc(100%-340px)] lg:w-[calc(100%-320px)] xl:w-[calc(100%-377px)]">
        <!-- Editing Order Alert Banner -->
        <div v-if="isEditingOrder" class="mb-4 p-3.5 rounded-2xl bg-gradient-to-r from-amber-500 to-orange-500 text-white flex items-center justify-between shadow-lg shadow-orange-500/20">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fa-solid fa-pen-to-square text-base animate-pulse"></i>
                </div>
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider">{{ $t('label.editing_order') || 'Editing Order' }}</h4>
                    <p class="text-sm font-bold">#{{ editingOrderSerialNo }}</p>
                </div>
            </div>
            <button type="button" @click="cancelEditOrder" class="px-3 py-1.5 bg-white text-orange-600 rounded-xl text-xs font-black hover:bg-orange-50 transition shadow-sm flex items-center gap-1">
                <i class="fa-solid fa-xmark"></i>
                <span>{{ $t('button.cancel_edit') || 'Cancel Edit' }}</span>
            </button>
        </div>

        <!-- Top Sticky Live Dining Table Strip -->
        <div class="mb-4 bg-white dark:bg-gray-900 p-3 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm" v-if="diningtables.length > 0">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-chair text-orange-500 text-sm"></i>
                    <h3 class="text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">{{ $t('label.dining_table_strip') }}</h3>
                    <button type="button" @click="refreshTables" :title="$t('button.refresh') || 'Refresh Tables'"
                        class="w-6 h-6 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 flex items-center justify-center text-gray-400 hover:text-orange-500 transition">
                        <i class="fa-solid fa-arrows-rotate text-[11px]" :class="loading.isActive ? 'animate-spin' : ''"></i>
                    </button>
                </div>
                <div class="flex items-center gap-3 text-[11px]">
                    <span class="flex items-center gap-1.5 text-gray-500 dark:text-gray-400">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> {{ $t('label.table_available') }}
                    </span>
                    <span class="flex items-center gap-1.5 text-gray-500 dark:text-gray-400">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span> {{ $t('label.table_running') }}
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-2 overflow-x-auto pb-1 thin-scrolling">
                <button type="button" @click="clearSelectedTable"
                    class="flex-shrink-0 px-3 py-1.5 rounded-xl text-xs font-bold transition border"
                    :class="!checkoutProps.form.dining_table_id ? 'bg-orange-500 text-white border-orange-500 shadow-md shadow-orange-500/20' : 'bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-400 border-gray-200 dark:border-gray-700 hover:border-orange-400'">
                    {{ $t('label.all_tables') }}
                </button>
                <button v-for="table in diningtables" :key="table.id" type="button" @click="selectTable(table)"
                    class="flex-shrink-0 flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold transition border"
                    :class="checkoutProps.form.dining_table_id === table.id
                        ? 'bg-orange-500 text-white border-orange-500 shadow-md shadow-orange-500/20'
                        : table.dining_table_status === 2
                            ? 'bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-300 border-amber-300 dark:border-amber-700 hover:border-amber-400'
                            : 'bg-emerald-50/60 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800 hover:border-emerald-400'">
                    <span class="w-2 h-2 rounded-full"
                        :class="checkoutProps.form.dining_table_id === table.id ? 'bg-white' : table.dining_table_status === 2 ? 'bg-amber-500 animate-pulse' : 'bg-emerald-500'"></span>
                    <span>{{ table.name }}</span>
                    <span v-if="table.dining_table_status === 2 && table.active_orders && table.active_orders.length > 1" class="px-1.5 py-0.5 bg-amber-200/90 dark:bg-amber-900/60 text-amber-900 dark:text-amber-200 rounded text-[10px] font-black">
                        {{ table.active_orders.length }} {{ $t('label.orders_count') || 'orders' }}
                    </span>
                    <span v-else-if="table.dining_table_status === 2 && (table.current_order?.total || (table.active_orders && table.active_orders[0]?.total))" class="text-[10px] font-bold text-amber-700 dark:text-amber-300">
                        ({{ currencyFormat(table.current_order?.total || table.active_orders[0]?.total, setting.site_digit_after_decimal_point, setting.site_default_currency_symbol, setting.site_currency_position) }})
                    </span>
                    <span v-else class="text-[10px] opacity-75 font-normal">({{ table.capacity }} {{ $t('label.person') }})</span>
                </button>
            </div>
        </div>

        <form @submit.prevent="search"
            class="flex items-center w-full h-[38px] leading-[38px] mb-4 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800">
            <input type="text" v-model="props.search.name" :placeholder="$t('label.search_by_menu_item')"
                class="w-full px-5 rounded-tl-lg rounded-bl-lg placeholder:text-xs placeholder:font-rubik placeholder:text-[#A0A3BD] dark:text-gray-100 bg-transparent">
            <button @click="resetName" type="button" v-if="props.search.name"
                class="text-sm text-red-500 fa-regular fa-circle-xmark mr-4"></button>
            <button type="submit"
                class="flex-shrink-0 w-[38px] h-full text-center ltr:rounded-tr-lg ltr:rounded-br-lg rtl:rounded-tl-lg rtl:rounded-bl-lg bg-primary">
                <i class="lab lab-search-normal text-white"></i>
            </button>
        </form>

        <div class="swiper pos-menu-swiper mb-6" v-if="categories.length > 1">
            <Swiper dir="ltr" :speed="1000" slidesPerView="auto" :spaceBetween="16" class="menu-slides">
                <SwiperSlide class="!w-fit" v-for="(category, index) in categories" :key="category"
                    :class="category.id === props.search.item_category_id || (category.id === 0 && props.search.item_category_id === '') ? 'pos-group' : ''">
                    <router-link v-if="index === 0" to="#" @click.prevent="allCategory"
                        class="w-28 flex flex-col items-center text-center gap-4 py-4 px-3 rounded-lg border-b-2 border-transparent transition hover:bg-primary-light hover:border-primary bg-white dark:bg-gray-900 dark:hover:bg-gray-800 text-gray-800 dark:text-gray-200 shadow-sm">
                        <img class="h-7 drop-shadow-category" :src="category.thumb" alt="category">
                        <h3 class="text-xs leading-[16px] font-medium font-rubik">{{ category.name }}</h3>
                    </router-link>
                    <router-link v-else to="#" @click.prevent="setCategory(category.id)"
                        class="w-28 flex flex-col items-center text-center gap-4 py-4 px-3 rounded-lg border-b-2 border-transparent transition hover:bg-primary-light hover:border-primary bg-white dark:bg-gray-900 dark:hover:bg-gray-800 text-gray-800 dark:text-gray-200 shadow-sm">
                        <img class="h-7 drop-shadow-category" :src="category.thumb" alt="category">
                        <h3 class="text-xs leading-[16px] font-medium font-rubik">{{ category.name }}</h3>
                    </router-link>
                </SwiperSlide>
            </Swiper>
        </div>
        <ItemComponent :items="items" v-if="items.length > 0" />

        <div class="my-12" v-else-if="items.length === 0 && !props.search.name">
            <div class="max-w-[350px] mx-auto">
                <img class="w-full mb-8" :src="setting.image_order_not_found" alt="image_order_not_found">
            </div>
            <span class="w-full mb-4 text-center text-gray-800 dark:text-gray-200">{{ $t('message.no_data_available') }}</span>
        </div>
        <div class="my-12" v-else-if="items.length === 0 && props.search.name">
            <div class="max-w-[250px] mx-auto">
                <img class="w-full mb-8" :src="setting.item_not_found" alt="item_not_found">
            </div>
            <span class="w-full mb-4 text-center text-gray-800 dark:text-gray-200">{{ $t('message.no_items_found') }}</span>
        </div>
    </div>

    <div id="pos-cart"
        class="db-pos-cartDiv fixed top-0 ltr:right-0 rtl:left-0 w-full h-screen rounded-none z-50 md:z-10 md:top-[85px] ltr:md:right-5 rtl:md:left-5 md:w-[322px] lg:w-[305px] xl:w-[360px] md:h-[calc(100vh-85px)] md:rounded-lg overflow-y-auto thin-scrolling bg-white dark:bg-gray-900 border border-transparent dark:border-gray-800 shadow-xl">
        <div class="p-4">
            <div class="md:hidden text-right mb-3">
                <button class="db-pos-cartCls" @click="closePosCart('pos-cart')">
                    <i class="lab-close-circle-line font-fill-danger lab-font-size-24"></i>
                </button>
            </div>
            <!-- Cart Edit Alert -->
            <div v-if="isEditingOrder" class="mb-3 p-2.5 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-400 dark:border-amber-700 flex items-center justify-between text-xs">
                <div class="flex items-center gap-2 font-bold text-amber-700 dark:text-amber-400">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span>{{ $t('label.editing_order') || 'Editing' }} #{{ editingOrderSerialNo }}</span>
                </div>
                <button type="button" @click="cancelEditOrder" class="text-amber-600 hover:text-red-500 text-xs font-bold underline">
                    {{ $t('button.cancel') || 'Cancel' }}
                </button>
            </div>
            <div v-if="selectedTableName" class="mb-3 p-2.5 rounded-xl bg-orange-50 dark:bg-gray-800 border border-orange-500/30 flex items-center justify-between text-xs">
                <div class="flex items-center gap-2 font-bold text-orange-600 dark:text-orange-400">
                    <i class="fa-solid fa-chair"></i>
                    <span>{{ $t('label.select_table') }}: {{ selectedTableName }}</span>
                </div>
                <button type="button" @click="clearSelectedTable" class="text-gray-400 hover:text-red-500 text-xs">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="flex gap-2  mb-3">
                <vue-select
                    class="db-field-control w-full flex-auto text-sm rounded-lg appearance-none text-heading border-[#D9DBE9] dark:border-gray-700"
                    id="customer" v-model="checkoutProps.form.customer_id" :options="customers" label-by="name"
                    value-by="id" :closeOnSelect="true" :searchable="true" :clearOnClose="true"
                    :placeholder="$t('label.select_customer')" :search-placeholder="$t('label.search_customer')" />

                <button data-modal="#customerModal" @click.prevent="addCustomer" type="button"
                    class="flex items-center justify-center gap-1.5 px-3 h-10 rounded-lg text-white bg-primary">
                    <i class="lab lab-add-circle-line"></i>
                    <span class="capitalize text-sm font-bold">{{ $t('button.add') }}</span>
                </button>
            </div>
            <div class="db-field mb-3">
                <input class="db-field-control text-sm rounded-lg appearance-none text-heading border-[#D9DBE9] dark:border-gray-700" id="token"
                    v-model="checkoutProps.form.token" :placeholder="$t('label.token_no')" />
            </div>

            <div class="p-3 pt-2 rounded-lg border border-[#D9DBE9] dark:border-gray-700">
                <h4 class="text-sm font-medium mb-3 text-gray-800 dark:text-gray-200">{{ $t('label.select_order_type') }}</h4>

                <div class="db-field-radio-group gap-1 active-group">

                    <label @click="dineInOrder" ref="dineIn" for="dinein" data-dine="#dine"
                        class="!w-fit db-field-radio px-2.5 py-2 rounded-lg border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 active">
                        <div class="custom-radio sm">
                            <input ref="dineInInput" type="radio" id="dinein" name="orderType"
                                :value="orderTypeEnums.dineIn" v-model="checkoutProps.form.order_type"
                                class="custom-radio-field" />
                            <span class="custom-radio-span"></span>
                        </div>
                        <h3 class="db-field-label text-sm text-heading dark:text-gray-200">
                            {{ $t('label.dine_in') }}
                        </h3>
                    </label>
                    <label ref="takeAway" @click="takeAwayOrder" for="takeway"
                        class="!w-fit db-field-radio px-2.5 py-2 rounded-lg border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        <div class="custom-radio sm">
                            <input ref="takeAwayInput" type="radio" id="takeway" name="orderType"
                                :value="orderTypeEnums.takeAway" v-model="checkoutProps.form.order_type"
                                class="custom-radio-field" />
                            <span class="custom-radio-span"></span>
                        </div>
                        <h3 class="db-field-label text-sm text-heading dark:text-gray-200">
                            {{ $t('label.takeaway') }}
                        </h3>
                    </label>
                </div>
                <div ref="dineInDiv" id="dine" class="h-auto hidden transition">
                    <div class="mt-3">
                        <div class="db-field flex-grow">
                            <vue-select
                                class="db-field-control text-sm rounded-lg appearance-none text-heading border-[#D9DBE9] dark:border-gray-700"
                                id="diningtables" :options="diningtables" v-model="checkoutProps.form.dining_table_id"
                                value-by="id" label-by="name" :closeOnSelect="true" :searchable="true"
                                :clearOnClose="true" :placeholder="$t('label.select_table')"
                                :search-placeholder="$t('label.search_table')" />
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <table class="w-full">
            <thead class="bg-orange-50 dark:bg-gray-800">
                <tr class="h-9">
                    <th class="capitalize text-xs font-normal font-rubik text-left pl-3 text-heading dark:text-gray-200"></th>
                    <th class="capitalize text-xs font-semibold font-rubik text-left px-3 text-heading dark:text-gray-200">
                        {{ $t('label.item') }}
                    </th>
                    <th class="capitalize text-xs font-semibold font-rubik text-left px-3 text-heading dark:text-gray-200">
                        {{ $t('label.qty') }}
                    </th>
                    <th class="capitalize text-xs font-semibold font-rubik text-left px-3 text-heading dark:text-gray-200">
                        {{ $t('label.price') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(cart, index) in carts">
                    <td class="pl-3 py-3 last:pr-3 align-top border-b border-gray-200 dark:border-gray-800 rtl:pr-3">
                        <button @click.prevent="deleteCartItem(index)">
                            <i class="lab lab-trash-line-2 font-fill-danger"></i>
                        </button>
                    </td>
                    <td class="pl-3 py-3 last:pr-3 align-top border-b border-gray-200 dark:border-gray-800">
                        <h3 class="capitalize text-xs font-rubik text-gray-800 dark:text-gray-100 font-medium">{{ cart.name }}</h3>
                        <p v-if="Object.keys(cart.item_variations.variations).length !== 0">
                            <span v-for="(variation, variationName, index) in cart.item_variations.names">
                                <span class="capitalize text-[10px] leading-4 font-rubik text-heading dark:text-gray-300">{{
                                    variationName
                                    }}:
                                    &nbsp;</span>
                                <span class="capitalize text-[10px] leading-4 font-rubik text-gray-600 dark:text-gray-400">{{ variation }}
                                    <span v-if="index + 1 < cart.item_variations.names">, &nbsp;</span>
                                </span>
                            </span>
                        </p>
                        <ul v-if="cart.item_extras.extras.length > 0 || cart.instruction !== ''">
                            <li v-if="cart.item_extras.extras.length > 0" class="leading-4">
                                <span class="capitalize text-[10px] leading-4 font-rubik text-heading dark:text-gray-300">
                                    {{ $t('label.extras') }}:
                                </span>
                                <p class="capitalize text-[10px] leading-4 font-rubik text-gray-600 dark:text-gray-400">
                                    <span v-for="(extra, index) in cart.item_extras.names">
                                        {{ extra }}
                                        <span v-if="index + 1 < cart.item_extras.extras.length">, &nbsp;</span>
                                    </span>
                                </p>
                            </li>
                            <li v-if="cart.instruction !== ''" class="leading-4">
                                <span class="capitalize text-[10px] leading-4 font-rubik text-heading dark:text-gray-300">
                                    {{ $t('label.instruction') }}:
                                </span>
                                <span class="capitalize text-[10px] leading-4 font-rubik text-gray-600 dark:text-gray-400">
                                    {{ cart.instruction }}
                                </span>
                            </li>
                        </ul>
                    </td>
                    <td class="pl-3 py-3 last:pr-3 align-top border-b border-gray-200 dark:border-gray-800">
                        <div class="flex items-center indec-group">
                            <button @click.prevent="cartQuantityDecrement(index)"
                                :class="cart.quantity === 1 ? 'fa-trash-can' : 'fa-minus'"
                                class="fa-solid text-[10px] w-[18px] h-[18px] leading-4 text-center rounded-full border transition text-primary border-primary hover:bg-primary hover:text-white indec-minus"></button>
                            <input v-on:keypress="onlyNumber($event)" v-on:keyup="cartQuantityUp(index, $event)"
                                type="number" :value="cart.quantity"
                                class="text-center w-7 text-xs font-semibold text-heading dark:text-gray-100 indec-value">
                            <button @click.prevent="cartQuantityIncrement(index)"
                                class="fa-solid fa-plus text-[10px] w-[18px] h-[18px] leading4 text-center rounded-full border transition text-primary border-primary hover:bg-primary hover:text-white indec-plus"></button>
                        </div>
                    </td>
                    <td class="pl-3 py-3 last:pr-3 align-top border-b border-gray-200 dark:border-gray-800 text-xs font-rubik text-primary dark:text-orange-400 font-bold">
                        {{
                            currencyFormat(cart.total, setting.site_digit_after_decimal_point,
                                setting.site_default_currency_symbol, setting.site_currency_position)
                        }}
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="p-4">
            <div class="flex h-[38px]" v-if="carts.length > 0">
                <div class="dropdown-group">
                    <button
                        class="flex items-center justify-start w-[120px] h-full text-sm font-rubik rounded-tl rounded-bl appearance-none border pl-3 text-heading dark:text-gray-200 border-gray-200 dark:border-gray-700 dropdown-btn">
                        <span class="flex-1 text-start" v-if="discountType === discountTypeEnum.PERCENTAGE">{{
                            $t("label.percentage") }}</span>
                        <span class="flex-1 text-start" v-else>{{ $t("label.fixed") }}</span>
                        <i class="lab lab-arrow-down-2 lab-font-size-17 mx-1"></i>
                    </button>
                    <ul
                        class="p-2 rounded-lg shadow-xl absolute top-10 ltr:right-0 rtl:left-0 z-10 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 transition-all duration-300 origin-top scale-y-0 dropdown-list w-full">
                        <li class="flex items-center gap-2 py-1 px-2.5 rounded-md cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800"
                            v-for="option in [
                                { name: $t('label.percentage'), value: discountTypeEnum.PERCENTAGE },
                                { name: $t('label.fixed'), value: discountTypeEnum.FIXED }
                            ]" :key="option" @click="selectDiscount(option.value)">
                            <span class="text-heading dark:text-gray-200 capitalize text-sm">{{ option.name }}</span>

                        </li>
                    </ul>
                </div>
                <input v-on:keypress="floatNumber($event)" v-model="discount" type="text"
                    :placeholder="$t('label.add_discount')"
                    class="w-full h-full border-t border-b px-3 border-gray-200 dark:border-gray-700 dark:text-gray-100">
                <button @click.prevent="applyDiscount" type="submit"
                    class="flex-shrink-0 w-16 h-full text-sm font-medium font-rubik capitalize ltr:rounded-tr-lg ltr:rounded-br-lg rtl:rounded-tl-lg rtl:rounded-bl-lg text-white bg-primary hover:bg-primary/90 transition">
                    {{ $t('button.apply') }}
                </button>
            </div>
            <ul class="flex flex-col gap-1.5 mb-4 mt-4">
                <li class="flex items-center justify-between">
                    <span class="text-sm font-rubik capitalize leading-6 text-gray-700 dark:text-gray-300">
                        {{ $t("label.sub_total") }}
                    </span>
                    <span class="text-sm font-rubik capitalize leading-6 font-semibold text-gray-800 dark:text-gray-200">
                        {{
                            currencyFormat(subtotal, setting.site_digit_after_decimal_point,
                                setting.site_default_currency_symbol, setting.site_currency_position)
                        }}
                    </span>
                </li>
                <li class="flex items-center justify-between">
                    <span class="text-sm font-rubik capitalize leading-6 text-gray-700 dark:text-gray-300">{{ $t("label.discount") }}</span>
                    <span class="text-sm font-rubik capitalize leading-6 font-semibold text-gray-800 dark:text-gray-200">{{
                        currencyFormat(posDiscount,
                            setting.site_digit_after_decimal_point, setting.site_default_currency_symbol,
                            setting.site_currency_position)
                    }}</span>
                </li>
                <li class="flex items-center justify-between">
                    <span class="text-sm font-medium font-rubik capitalize leading-6 text-heading dark:text-gray-100 font-bold">
                        {{ $t("label.total") }}
                    </span>
                    <span class="text-base font-bold font-rubik capitalize leading-6 text-primary dark:text-orange-400">
                        {{
                            currencyFormat(subtotal - posDiscount,
                                setting.site_digit_after_decimal_point, setting.site_default_currency_symbol,
                                setting.site_currency_position)
                        }}
                    </span>
                </li>
            </ul>
            <div class="flex items-center justify-center gap-4" v-if="carts.length > 0">
                <button @click.prevent="isEditingOrder ? cancelEditOrder() : resetCart()"
                    class="capitalize text-sm font-medium leading-6 font-rubik w-full text-center rounded-3xl py-2 text-white bg-[#FB4E4E] hover:bg-[#e04545] transition">
                    {{ $t('button.cancel') }}
                </button>
                <button @click.prevent="orderSubmit"
                    class="capitalize text-sm font-medium leading-6 font-rubik w-full text-center rounded-3xl py-2 text-white transition"
                    :class="isEditingOrder ? 'bg-amber-600 hover:bg-amber-700 shadow-md shadow-amber-600/30' : 'bg-[#1AB759] hover:bg-[#169d4c]'">
                    {{ isEditingOrder ? ($t('button.update_order') || 'Update Order') : $t('button.order') }}
                </button>
            </div>
        </div>
    </div>

    <button @click="openPosCart('pos-cart')" type="button"
        class="db-pos-cartBtn fixed md:hidden bottom-0 z-10 left-0 w-full h-14 py-4 text-center flex items-center justify-center shadow-xl-top gap-3 bg-primary">
        <i class="lab lab-bag-2 lab-font-size-13 text-white"></i>
        <span class="text-base font-medium font-rubik text-white">
            {{ totalItems() }} {{ $t('label.items') }} - {{
                currencyFormat(subtotal - posDiscount,
                    setting.site_digit_after_decimal_point, setting.site_default_currency_symbol,
                    setting.site_currency_position)
            }}
        </span>
    </button>

    <!-- Running Table Action Modal -->
    <div id="runningTableModal" class="modal">
        <div class="modal-dialog max-w-[460px] w-full">
            <div class="modal-header pb-3 border-b border-[#D9DBE9] dark:border-gray-800 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-sm font-bold shadow-sm">
                        <i class="fa-solid fa-chair"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-sm text-gray-800 dark:text-gray-100">
                                {{ activeRunningTable?.name }}
                            </h3>
                            <span class="text-[11px] text-gray-400 dark:text-gray-500 font-medium">({{ activeRunningTable?.capacity || 4 }} {{ $t('label.seats') || 'Seats' }})</span>
                        </div>
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-600 dark:text-amber-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            <span v-if="activeRunningTable?.active_orders && activeRunningTable.active_orders.length > 1">
                                {{ activeRunningTable.active_orders.length }} {{ $t('label.active_orders') || 'Active Orders' }} ({{ $t('label.shared_table') || 'Shared Table' }})
                            </span>
                            <span v-else>
                                {{ $t('label.table_running') || 'Occupied / Running' }}
                            </span>
                        </span>
                    </div>
                </div>
                <button class="modal-close fa-regular fa-circle-xmark text-gray-400 hover:text-red-500 text-lg" @click="closeRunningTableModal"></button>
            </div>
            <div class="modal-body py-4" v-if="activeRunningTable">
                <!-- Case A: Multiple Active Orders on this Shared Table -->
                <div v-if="activeRunningTable.active_orders && activeRunningTable.active_orders.length > 1" class="mb-4">
                    <div class="text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 flex items-center justify-between">
                        <span><i class="fa-solid fa-receipt mr-1 text-primary"></i> {{ $t('label.active_orders') || 'Active Orders' }} ({{ activeRunningTable.active_orders.length }})</span>
                        <span class="text-[10px] font-semibold text-amber-600 bg-amber-50 dark:bg-amber-950/50 px-2 py-0.5 rounded-md border border-amber-200 dark:border-amber-800">{{ $t('label.shared_table') || 'Shared Table' }}</span>
                    </div>
                    <div class="space-y-2.5 max-h-[260px] overflow-y-auto thin-scrolling pr-1">
                        <div v-for="(orderItem, idx) in activeRunningTable.active_orders" :key="orderItem.id"
                            class="p-3 rounded-2xl bg-amber-50/70 dark:bg-gray-800/80 border border-amber-200/80 dark:border-gray-700 shadow-sm">
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-5 h-5 rounded-full bg-amber-200 dark:bg-amber-900/60 text-amber-800 dark:text-amber-200 flex items-center justify-center text-[10px] font-black">#{{ idx + 1 }}</span>
                                    <span class="text-xs font-black text-amber-700 dark:text-amber-400">
                                        #{{ orderItem.order_serial_no || orderItem.id }}
                                    </span>
                                    <span v-if="orderItem.token" class="text-[10px] text-gray-500 font-medium">({{ $t('label.token') }} #{{ orderItem.token }})</span>
                                </div>
                                <span class="text-xs font-black text-orange-600 dark:text-orange-400">
                                    {{ currencyFormat(orderItem.total || 0, setting.site_digit_after_decimal_point, setting.site_default_currency_symbol, setting.site_currency_position) }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-2.5">
                                <span class="truncate max-w-[170px] text-[11px]"><i class="fa-solid fa-user text-[10px] mr-1 text-gray-400"></i>{{ orderItem.customer_name }}</span>
                                <span class="text-[10px] text-gray-400">{{ orderItem.order_datetime ? new Date(orderItem.order_datetime).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : '' }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" @click="payOrder(orderItem.id)"
                                    class="py-1.5 px-3 rounded-xl text-white text-xs font-bold bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 transition flex items-center justify-center gap-1.5 shadow-sm shadow-emerald-500/20">
                                    <i class="fa-solid fa-cash-register text-[11px]"></i>
                                    <span>{{ $t('button.pay_bill') || 'Pay Bill' }}</span>
                                </button>
                                <button type="button" @click="editOrder(orderItem.id)"
                                    class="py-1.5 px-3 rounded-xl text-amber-800 dark:text-amber-200 text-xs font-bold bg-amber-100 hover:bg-amber-200 dark:bg-amber-950/50 dark:hover:bg-amber-900/60 transition flex items-center justify-center gap-1.5 border border-amber-300 dark:border-amber-700/60">
                                    <i class="fa-solid fa-cart-plus text-[11px]"></i>
                                    <span>{{ $t('button.edit') || 'Edit Items' }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Case B: Single Active Order on Table -->
                <div v-else class="p-3.5 rounded-2xl bg-amber-50/70 dark:bg-gray-800/80 border border-amber-200/80 dark:border-gray-700 mb-4 shadow-sm">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-semibold">{{ $t('label.order_id') || 'Order No' }}</span>
                        <span class="text-xs font-black text-amber-700 dark:text-amber-400">
                            #{{ singleRunningOrder?.order_serial_no || singleRunningOrder?.id || activeRunningTable.current_order_id }}
                            <span v-if="singleRunningOrder?.token" class="text-[10px] font-normal text-gray-500">({{ $t('label.token') }} #{{ singleRunningOrder.token }})</span>
                        </span>
                    </div>
                    <div class="flex justify-between items-center mb-2" v-if="singleRunningOrder?.customer_name">
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-semibold">{{ $t('label.customer') || 'Customer' }}</span>
                        <span class="text-xs font-bold text-gray-800 dark:text-gray-200">{{ singleRunningOrder.customer_name }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-2.5 border-t border-amber-200/60 dark:border-gray-700">
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $t('label.total') || 'Total Bill' }}</span>
                        <span class="text-lg font-black text-orange-600 dark:text-orange-400">
                            {{ currencyFormat(singleRunningOrder?.total || 0, setting.site_digit_after_decimal_point, setting.site_default_currency_symbol, setting.site_currency_position) }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 mt-3 pt-2.5 border-t border-amber-200/60 dark:border-gray-700">
                        <button type="button" @click="payOrder(singleRunningOrder?.id || activeRunningTable.current_order_id)"
                            class="py-2.5 px-3 rounded-xl text-white text-xs font-black bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 transition flex items-center justify-center gap-1.5 shadow-md shadow-emerald-500/20">
                            <i class="fa-solid fa-cash-register"></i>
                            <span>{{ $t('button.pay_and_release_table') || 'Pay Bill' }}</span>
                        </button>
                        <button type="button" @click="editOrder(singleRunningOrder?.id || activeRunningTable.current_order_id)"
                            class="py-2.5 px-3 rounded-xl text-amber-800 dark:text-amber-200 text-xs font-bold bg-amber-100/90 hover:bg-amber-200 dark:bg-amber-950/50 dark:hover:bg-amber-900/60 transition flex items-center justify-center gap-1.5 border border-amber-200 dark:border-amber-800/40">
                            <i class="fa-solid fa-cart-plus"></i>
                            <span>{{ $t('button.add_items_to_order') || 'Add Items / Edit' }}</span>
                        </button>
                    </div>
                </div>

                <!-- Table Management Actions -->
                <div class="flex flex-col gap-2 pt-2 border-t border-gray-200 dark:border-gray-800">
                    <button type="button" @click="takeSharedTableOrder(activeRunningTable)"
                        class="w-full py-2.5 px-4 rounded-xl text-orange-600 dark:text-orange-400 text-xs sm:text-sm font-bold bg-orange-50 hover:bg-orange-100 dark:bg-orange-950/30 dark:hover:bg-orange-900/40 border border-orange-200 dark:border-orange-800/60 transition flex items-center justify-center gap-2 shadow-sm">
                        <i class="fa-solid fa-users-rectangle text-sm"></i>
                        <span>{{ $t('button.take_shared_order') || 'Take New Shared Order on this Table' }}</span>
                    </button>

                    <button type="button" @click="releaseTableDirectly(activeRunningTable)"
                        class="w-full py-2 px-4 rounded-xl text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30 text-xs font-semibold transition flex items-center justify-center gap-1.5 border border-red-200 dark:border-red-900/40">
                        <i class="fa-solid fa-door-open"></i>
                        <span>{{ $t('button.release_table') || 'Direct Release Table' }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!--====================================
      PAYMENT MODAL PART START
  =====================================-->
    <PaymentComponent :props="checkoutProps" />
    <!--====================================
          PAYMENT MODAL PART END
      =====================================-->
</template>
<script>
import LoadingComponent from "../components/LoadingComponent";
import 'vue3-carousel/dist/carousel.css';
import ItemComponent from "./ItemComponent";
import sourceEnum from "../../../enums/modules/sourceEnum";
import orderTypeEnum from "../../../enums/modules/orderTypeEnum";
import isAdvanceOrderEnum from "../../../enums/modules/isAdvanceOrderEnum";
import statusEnum from "../../../enums/modules/statusEnum";
import roleEnum from "../../../enums/modules/roleEnum";
import appService from "../../../services/appService";
import discountTypeEnum from "../../../enums/modules/discountTypeEnum";
import alertService from "../../../services/alertService";
import PaymentComponent from "./PaymentComponent";
import PoscustomerComponent from './PosCustomerComponent';
import posPaymentMethodEnum from "../../../enums/modules/posPaymentMethodEnum";
import { Swiper, SwiperSlide } from 'swiper/vue';
import _ from "lodash";
import 'swiper/css';

export default {
    name: "PosComponent",
    components: {
        LoadingComponent,
        ItemComponent,
        PoscustomerComponent,
        Swiper,
        SwiperSlide,
        PaymentComponent
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            order: {},
            editingOrderId: null,
            editingOrderSerialNo: "",
            activeRunningTable: null,
            discount: null,
            checkoutProps: {
                form: {
                    branch_id: null,
                    subtotal: 0,
                    token: "",
                    customer_id: null,
                    discount: 0,
                    delivery_charge: 0,
                    delivery_time: null,
                    total: 0,
                    order_type: orderTypeEnum.POS,
                    is_advance_order: isAdvanceOrderEnum.NO,
                    pos_payment_method: posPaymentMethodEnum.CASH,
                    pos_payment_note: '',
                    source: sourceEnum.POS,
                    address_id: null,
                    items: [],
                    dining_table_id: null,
                    pos_received_amount: null,
                }
            },
            props: {
                search: {
                    paginate: 0,
                    order_column: "id",
                    order_type: "asc",
                    name: "",
                    item_category_id: "",
                    status: statusEnum.ACTIVE
                },
            },
            categoryProps: {
                paginate: 0,
                order_column: "sort",
                order_type: "asc",
                status: statusEnum.ACTIVE
            },
            settings: {
                itemsToShow: 6.2,
                wrapAround: false,
                snapAlign: "start"
            },
            breakpoints: {
                200: {
                    itemsToShow: 1.4,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                250: {
                    itemsToShow: 1.9,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                300: {
                    itemsToShow: 2.3,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                375: {
                    itemsToShow: 3,
                    wrapAround: true,
                    snapAlign: 'start',
                },
                540: {
                    itemsToShow: 4.3,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                700: {
                    itemsToShow: 5.2,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                768: {
                    itemsToShow: 3.2,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                830: {
                    itemsToShow: 3.6,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                900: {
                    itemsToShow: 4.3,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                960: {
                    itemsToShow: 5.3,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                1024: {
                    snapAlign: 'start',
                    itemsToShow: 3.5,
                    wrapAround: false,
                },
                1100: {
                    snapAlign: 'start',
                    itemsToShow: 4.1,
                    wrapAround: false,
                },
                1180: {
                    snapAlign: 'start',
                    itemsToShow: 4.8,
                    wrapAround: false,
                },
                1280: {
                    snapAlign: 'start',
                    itemsToShow: 5.2,
                    wrapAround: false,
                },
                1400: {
                    snapAlign: 'start',
                    itemsToShow: 5.8,
                    wrapAround: false,
                },
                1600: {
                    snapAlign: 'start',
                    itemsToShow: 6.8,
                    wrapAround: false,
                },
                1700: {
                    snapAlign: 'start',
                    itemsToShow: 7.8,
                    wrapAround: false,
                },
                1800: {
                    snapAlign: 'start',
                    itemsToShow: 8.8,
                    wrapAround: false,
                },
                1920: {
                    snapAlign: 'start',
                    itemsToShow: 9.8,
                    wrapAround: false,
                },
                2000: {
                    snapAlign: 'start',
                    itemsToShow: 10.8,
                    wrapAround: false,
                },
                2100: {
                    snapAlign: 'start',
                    itemsToShow: 11.8,
                    wrapAround: false,
                }
            },
            statusEnum: statusEnum,
            discountTypeEnum: discountTypeEnum,
            posPaymentMethodEnum: posPaymentMethodEnum,
            discountType: discountTypeEnum.PERCENTAGE,
            orderTypeEnums: {
                dineIn: orderTypeEnum.DINING_TABLE,
                takeAway: orderTypeEnum.TAKEAWAY
            },
        }
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        categories: function () {
            return this.$store.getters["posCategory/lists"];
        },
        items: function () {
            return this.$store.getters["item/lists"];
        },
        customers: function () {
            return this.$store.getters['user/lists'];
        },
        carts: function () {
            return this.$store.getters['posCart/lists'];
        },
        subtotal: function () {
            return this.$store.getters['posCart/subtotal'];
        },
        posDiscount: function () {
            return this.$store.getters['posCart/discount'];
        },
        diningtables: function () {
            return this.$store.getters["diningTable/lists"];
        },
        selectedTableName: function () {
            if (!this.checkoutProps.form.dining_table_id || !this.diningtables) return null;
            const table = this.diningtables.find(t => t.id === this.checkoutProps.form.dining_table_id);
            return table ? table.name : null;
        },
        isEditingOrder: function () {
            return Boolean(this.$store.getters['posOrder/temp']?.isEditing && this.editingOrderId);
        },
        singleRunningOrder: function () {
            if (!this.activeRunningTable) return null;
            if (this.activeRunningTable.active_orders && this.activeRunningTable.active_orders.length > 0) {
                return this.activeRunningTable.active_orders[0];
            }
            return this.activeRunningTable.current_order || null;
        },
    },
    mounted() {
        this.closeSidebar();
        this.$refs.dineIn.click();
        this.itemCategories();
        this.itemList();
        try {
            this.loading.isActive = true;
            this.$store.dispatch("defaultAccess/show").then((res) => {
                this.checkoutProps.form.branch_id = res.data.data.branch_id;
                this.fetchNextToken();
            }).catch((err) => {
                this.loading.isActive = false;
            });

            this.customerList();

            this.loading.isActive = true;
            this.$store.dispatch("company/lists").then((res) => {
                this.company.name = res.data.data.company_name;
                this.company.email = res.data.data.company_email;
                this.company.phone = res.data.data.company_phone;
                this.company.address = res.data.data.company_address;
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
            this.loading.isActive = true;
            this.$store.dispatch("diningTable/lists", {
                order_column: 'id',
                order_type: 'asc',
                status: statusEnum.ACTIVE,
            }).then((res) => {
                this.loading.isActive = false;
                if (this.$route.query.order_id) {
                    this.loadOrderForEdit(this.$route.query.order_id);
                }
            }).catch((err) => {
                this.loading.isActive = false;
                if (this.$route.query.order_id) {
                    this.loadOrderForEdit(this.$route.query.order_id);
                }
            });
        } catch (err) {
            this.loading.isActive = false;
        }
    },
    methods: {
        onlyNumber: function (e) {
            return appService.onlyNumber(e);
        },
        floatNumber: function (e) {
            return appService.floatNumber(e);
        },
        currencyFormat: function (amount, decimal, currency, position) {
            return appService.currencyFormat(amount, decimal, currency, position);
        },
        openPosCart: function (id) {
            return appService.openPosCart(id);
        },
        closePosCart: function (id) {
            return appService.closePosCart(id);
        },
        resetName: function () {
            this.props.search.name = "";
            this.itemList();
        },
        selectDiscount(value) {
            this.discountType = value;
        },
        search: function () {
            this.itemList();
        },
        customerList: function (id = null) {
            this.loading.isActive = true;
            this.$store.dispatch('user/lists', {
                order_column: 'id',
                order_type: 'asc',
                status: statusEnum.ACTIVE,
            }).then((res) => {
                if (!this.isEditingOrder) {
                    const walkIn = res.data?.data?.find(c => c.name?.toLowerCase().includes('walk') || c.email?.toLowerCase().includes('walk'))
                        || (res.data?.data?.length > 1 ? res.data.data[1] : res.data?.data?.[0]);
                    this.checkoutProps.form.customer_id = id === null ? (walkIn ? walkIn.id : null) : id;
                }
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        allCategory: function () {
            this.props.search.name = "";
            this.props.search.item_category_id = "";
            this.itemList();
        },
        itemCategories: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch("posCategory/lists", this.categoryProps).then((res) => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        itemList: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch("item/lists", this.props.search).then((res) => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        setCategory: function (id) {
            this.props.search.item_category_id = id;
            this.itemList();
        },
        cartQuantityUp: function (id, e) {
            if (e.target.value > 0) {
                this.$store.dispatch('posCart/quantity', { id: id, status: e.target.value }).then().catch();
            }
        },
        cartQuantityIncrement: function (id) {
            this.$store.dispatch('posCart/quantity', { id: id, status: "increment" }).then().catch();
        },
        cartQuantityDecrement: function (id) {
            this.$store.dispatch('posCart/quantity', { id: id, status: "decrement" }).then().catch();
        },
        deleteCartItem: function (id) {
            this.$store.dispatch('posCart/deleteCartItem', { id: id, status: "decrement" }).then().catch();
        },
        applyDiscount: function () {
            if (this.discountType == discountTypeEnum.FIXED) {
                if (this.subtotal < this.discount) {
                    return alertService.error(this.$t('message.discount_fixed_error_message'));
                } else {
                    this.checkoutProps.form.discount = parseFloat(+this.discount).toFixed(this.setting.site_digit_after_decimal_point);
                    this.$store.dispatch('posCart/discount', this.checkoutProps.form.discount).then().catch();
                }

            } else {
                if (this.discount > 100) {
                    return alertService.error(this.$t('message.discount_error_message'));
                } else {

                    this.checkoutProps.form.discount = parseFloat((this.subtotal * this.discount) / 100).toFixed(this.setting.site_digit_after_decimal_point);
                    this.$store.dispatch('posCart/discount', this.checkoutProps.form.discount).then().catch();

                }
            }
        },
        resetCart: function () {
            if (this.isEditingOrder) {
                this.editingOrderId = null;
                this.editingOrderSerialNo = "";
                this.$store.commit('posOrder/reset');
                if (this.$route.query.order_id) {
                    this.$router.replace({ path: '/admin/pos' });
                }
            }
            this.$store.dispatch('posCart/resetCart').then(res => {
                this.fetchNextToken();
            }).catch();
        },
        orderSubmit: function () {
            this.loading.isActive = true;
            this.checkoutProps.form.subtotal = this.subtotal;
            this.checkoutProps.form.total = parseFloat(this.subtotal - this.checkoutProps.form.discount).toFixed(this.setting.site_digit_after_decimal_point);
            this.checkoutProps.form.items = [];
            this.checkoutProps.form.pos_payment_note = this.checkoutProps.form.pos_payment_method === posPaymentMethodEnum.CASH ?
                null : this.checkoutProps.form.pos_payment_note;
            _.forEach(this.carts, (item, index) => {
                let item_variations = [];
                if (Object.keys(item.item_variations.variations).length > 0) {
                    _.forEach(item.item_variations.variations, (value, index) => {
                        item_variations.push({
                            "id": value,
                            "item_id": item.item_id,
                            "item_attribute_id": index,
                        });
                    });
                }

                if (Object.keys(item.item_variations.names).length > 0) {
                    let i = 0;
                    _.forEach(item.item_variations.names, (value, index) => {
                        item_variations[i].variation_name = index;
                        item_variations[i].name = value;
                        i++;
                    });
                }

                let item_extras = [];
                if (item.item_extras.extras.length) {
                    _.forEach(item.item_extras.extras, (value) => {
                        item_extras.push({
                            id: value,
                            item_id: item.item_id,
                        });
                    });
                }

                if (item.item_extras.names.length) {
                    let i = 0;
                    _.forEach(item.item_extras.names, (value) => {
                        item_extras[i].name = value;
                        i++;
                    });
                }

                this.checkoutProps.form.items.push({
                    item_id: item.item_id,
                    item_price: item.convert_price,
                    branch_id: this.checkoutProps.form.branch_id,
                    instruction: item.instruction,
                    quantity: item.quantity,
                    discount: item.discount,
                    total_price: item.total,
                    item_variation_total: item.item_variation_total,
                    item_extra_total: item.item_extra_total,
                    item_variations: item_variations,
                    item_extras: item_extras
                });
            });
            this.checkoutProps.form.items = JSON.stringify(this.checkoutProps.form.items);

            this.loading.isActive = false;
            if (!this.checkoutProps.form.token && !this.isEditingOrder) {
                this.fetchNextToken();
            }
            if (this.checkoutProps.form.order_type === orderTypeEnum.DINING_TABLE && !this.checkoutProps.form.dining_table_id) {
                return alertService.error(this.$t("message.table_field_required"));
            }
            appService.modalShow('#orderpayment');
        },
        totalItems: function () {
            if (this.carts.length > 0) {
                let totalItem = 0;
                this.carts.forEach(cart => {
                    totalItem += cart.quantity;
                });
                return totalItem;
            }
        },
        addCustomer: function () {
            appService.modalShow("#customerModal");
        },
        onCustomverCreate: function (customerId) {
            appService.modalHide();
            this.customerList(customerId);
        },
        closeSidebar: function () {
            this.$store.dispatch("globalState/set", { topSidebar: false });
            document?.querySelector(".db-sidebar")?.classList?.add("active");
            document?.querySelector(".db-main")?.classList?.add("expand");
        },
        dineInOrder: function () {
            this.checkoutProps.form.order_type = this.orderTypeEnums.dineIn;
            this.$refs.dineIn?.classList.add('active');
            this.$refs.dineInDiv?.classList.add('block');
            this.$refs.dineInDiv?.classList.remove('hidden');
            this.$refs.takeAway?.classList.remove('active');
        },
        takeAwayOrder: function () {
            this.checkoutProps.form.dining_table_id = null;
            this.checkoutProps.form.order_type = this.orderTypeEnums.takeAway;
            this.$refs.takeAway?.classList.add('active');
            this.$refs.dineIn?.classList.remove('active');
            this.$refs.dineInDiv?.classList.add('hidden');
            this.$refs.dineInDiv?.classList.remove('block');
        },
        selectTable: function (table) {
            if (table.dining_table_status === 2) {
                this.openRunningTableModal(table);
                return;
            }

            if (this.checkoutProps.form.dining_table_id === table.id) {
                this.checkoutProps.form.dining_table_id = null;
            } else {
                this.checkoutProps.form.dining_table_id = table.id;
                this.checkoutProps.form.order_type = this.orderTypeEnums.dineIn;
                this.$nextTick(() => {
                    if (this.$refs.dineIn) {
                        this.dineInOrder();
                    }
                });
            }
        },
        clearSelectedTable: function () {
            this.checkoutProps.form.dining_table_id = null;
        },
        openRunningTableModal: function (table) {
            this.activeRunningTable = table;
            appService.modalShow('#runningTableModal');
        },
        closeRunningTableModal: function () {
            this.activeRunningTable = null;
            appService.modalHide('#runningTableModal');
        },
        payOrder: function (orderId) {
            if (!orderId) return;
            this.closeRunningTableModal();
            this.loadOrderForEdit(orderId);
            this.$nextTick(() => {
                setTimeout(() => {
                    this.orderSubmit();
                }, 400);
            });
        },
        editOrder: function (orderId) {
            if (!orderId) return;
            this.closeRunningTableModal();
            this.loadOrderForEdit(orderId);
        },
        takeSharedTableOrder: function (table) {
            if (!table) return;
            this.closeRunningTableModal();
            this.resetCart();
            this.checkoutProps.form.order_type = this.orderTypeEnums.dineIn;
            this.checkoutProps.form.dining_table_id = table.id;
            this.$nextTick(() => {
                if (this.$refs.dineIn) {
                    this.dineInOrder();
                }
            });
            alertService.info(this.$t('message.taking_shared_order') || `Taking new shared order for ${table.name}`);
        },
        payRunningTable: function (table) {
            if (!table) return;
            const orderId = table.current_order?.id || table.current_order_id;
            this.payOrder(orderId);
        },
        addItemsToRunningTable: function (table) {
            if (!table) return;
            const orderId = table.current_order?.id || table.current_order_id;
            this.editOrder(orderId);
        },
        releaseTableDirectly: function (table) {
            if (!table) return;
            this.closeRunningTableModal();
            this.loading.isActive = true;
            this.$store.dispatch('diningTable/release', table.id).then(() => {
                this.loading.isActive = false;
                alertService.success(this.$t('message.table_released_successfully') || "Table released successfully");
                this.refreshTables();
            }).catch((err) => {
                this.loading.isActive = false;
                alertService.error(err.response?.data?.message || "Failed to release table");
            });
        },
        refreshTables: function () {
            this.loading.isActive = true;
            this.$store.dispatch("diningTable/lists", {
                order_column: 'id',
                order_type: 'asc',
                status: statusEnum.ACTIVE,
            }).then(() => {
                this.loading.isActive = false;
            }).catch(() => {
                this.loading.isActive = false;
            });
        },
        loadOrderForEdit: function (orderId) {
            if (!orderId) return;
            this.loading.isActive = true;
            this.$store.dispatch('posOrder/show', orderId).then((res) => {
                const order = res.data.data;
                if (!order) {
                    this.loading.isActive = false;
                    return;
                }
                this.editingOrderId = order.id;
                this.editingOrderSerialNo = order.order_serial_no;
                this.$store.commit('posOrder/edit', order.id);

                let cartItems = [];
                if (order.order_items && order.order_items.length) {
                    order.order_items.forEach((item) => {
                        let variationsObj = {};
                        let variationNamesObj = {};
                        if (item.item_variations && Array.isArray(item.item_variations)) {
                            item.item_variations.forEach(v => {
                                if (v.item_attribute_id && v.id) {
                                    variationsObj[v.item_attribute_id] = v.id;
                                }
                                if (v.variation_name && v.name) {
                                    variationNamesObj[v.variation_name] = v.name;
                                }
                            });
                        }
                        let extrasArr = [];
                        let extraNamesArr = [];
                        if (item.item_extras && Array.isArray(item.item_extras)) {
                            item.item_extras.forEach(e => {
                                if (e.id) extrasArr.push(e.id);
                                if (e.name) extraNamesArr.push(e.name);
                            });
                        }
                        cartItems.push({
                            item_id: item.item_id,
                            name: item.item_name,
                            image: item.item_image || '',
                            instruction: item.instruction || '',
                            quantity: Number(item.quantity) || 1,
                            discount: Number(item.discount_amount || 0),
                            convert_price: Number(item.convert_price !== undefined ? item.convert_price : 0),
                            item_variation_total: Number(item.item_variation_total || 0),
                            item_extra_total: Number(item.item_extra_total || 0),
                            total: Number(item.total_price || 0),
                            item_variations: {
                                variations: variationsObj,
                                names: variationNamesObj
                            },
                            item_extras: {
                                extras: extrasArr,
                                names: extraNamesArr
                            }
                        });
                    });
                }

                this.$store.dispatch('posCart/setCart', cartItems);

                if (order.customer_id) {
                    this.checkoutProps.form.customer_id = order.customer_id;
                }
                this.checkoutProps.form.token = order.token || "";
                this.checkoutProps.form.order_type = order.order_type;

                if (order.order_type === this.orderTypeEnums.dineIn) {
                    this.checkoutProps.form.dining_table_id = order.dining_table_id || null;
                    this.$nextTick(() => {
                        if (this.$refs.dineIn) {
                            this.dineInOrder();
                        }
                    });
                } else {
                    this.$nextTick(() => {
                        if (this.$refs.takeAway) {
                            this.takeAwayOrder();
                        }
                    });
                }

                if (order.discount && order.discount > 0) {
                    this.discount = order.discount;
                    this.discountType = this.discountTypeEnum.FIXED;
                    this.checkoutProps.form.discount = order.discount;
                    this.$store.dispatch('posCart/discount', order.discount);
                }

                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
                alertService.error(err.response?.data?.message || "Failed to load order for edit");
            });
        },
        fetchNextToken: function () {
            if (this.isEditingOrder) return;
            const branchId = this.checkoutProps.form.branch_id || null;
            this.$store.dispatch('posOrder/nextToken', branchId ? { branch_id: branchId } : {}).then((res) => {
                if (res.data?.data?.token) {
                    this.checkoutProps.form.token = res.data.data.token;
                }
            }).catch(() => {});
        },
        cancelEditOrder: function () {
            this.editingOrderId = null;
            this.editingOrderSerialNo = "";
            this.$store.commit('posOrder/reset');
            if (this.$route.query.order_id) {
                this.$router.replace({ path: '/admin/pos' });
            }
            this.resetCart();
        },
    },
    watch: {
        carts: {
            handler(newCarts) {
                if (!newCarts || newCarts.length === 0) {
                    if (!this.isEditingOrder) {
                        this.discount = null;
                        this.discountType = discountTypeEnum.PERCENTAGE;
                        this.$nextTick(() => {
                            if (this.$refs.dineIn) {
                                this.$refs.dineIn.click();
                                const walkIn = this.customers?.find(c => c.name?.toLowerCase().includes('walk') || c.email?.toLowerCase().includes('walk'))
                                    || (this.customers?.length > 1 ? this.customers[1] : this.customers?.[0]);
                                if (walkIn) {
                                    this.checkoutProps.form.customer_id = walkIn.id;
                                }
                            }
                        });
                        if (this.checkoutProps.form.branch_id) {
                            this.fetchNextToken();
                        }
                    }
                }
            },
            deep: true,
            immediate: true
        },
        '$route.query.order_id': function (newVal) {
            if (newVal) {
                this.loadOrderForEdit(newVal);
            } else if (this.isEditingOrder) {
                this.cancelEditOrder();
            }
        }
    },
}
</script>