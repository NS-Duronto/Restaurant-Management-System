<template>
    <div class="rms-food-page">
        <!-- TOP HEADER -->
        <header class="rms-header">
            <div class="header-container">
                <router-link to="/" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                    <span>{{ $t('resort.return_resort_home') }}</span>
                </router-link>

                <div class="brand">
                    <i class="fas fa-utensils"></i>
                    <span>{{ setting.company_name || 'Garden Palace Restaurant & Cafe' }}</span>
                </div>

                <div class="header-actions">
                    <button type="button" class="cart-trigger" @click="cartOpen = true">
                        <i class="fas fa-shopping-bag"></i>
                        <span class="cart-count" v-if="cartTotalItems > 0">{{ cartTotalItems }}</span>
                        <span class="cart-total-text" v-if="cartSubtotal > 0">৳{{ cartSubtotal }}</span>
                    </button>
                </div>
            </div>
        </header>

        <!-- HERO BANNER -->
        <div class="rms-banner">
            <div class="banner-content">
                <h2>{{ $t('resort.food_hero_title') }}</h2>
                <p>{{ $t('resort.food_hero_sub') }}</p>
                
                <div class="search-wrap">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" v-model="searchQuery" :placeholder="$t('resort.food_search_placeholder')" />
                        <button v-if="searchQuery" @click="searchQuery = ''" class="clear-btn">&times;</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="menu-container">
            <!-- CATEGORY PILLS -->
            <div class="categories-bar">
                <button type="button" class="cat-pill" :class="{ 'active': selectedCategory === 'all' }" @click="selectedCategory = 'all'">
                    <i class="fas fa-th-large"></i> {{ $t('resort.all_menus') }}
                </button>
                <button type="button" v-for="cat in categories" :key="cat.id"
                    class="cat-pill" :class="{ 'active': selectedCategory === cat.id }"
                    @click="selectedCategory = cat.id">
                    {{ cat.name }}
                </button>
            </div>

            <!-- FOOD GRID -->
            <div class="food-section-wrap">
                <div class="section-meta">
                    <h3>{{ getSelectedCategoryName() }}</h3>
                    <span class="item-count">{{ $t('resort.foods_found_count', { count: filteredItems.length }) }}</span>
                </div>

                <div class="items-grid" v-if="filteredItems.length > 0">
                    <div v-for="item in filteredItems" :key="item.id" class="menu-card">
                        <div class="card-img-wrap">
                            <img :src="item.thumb || item.cover || 'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?w=400&q=80'" :alt="item.name" />
                            <span v-if="item.is_featured" class="featured-badge">{{ $t('resort.tag_special') }}</span>
                        </div>
                        <div class="card-content">
                            <h4>{{ item.name }}</h4>
                            <p class="desc">{{ item.description || '' }}</p>
                            <div class="card-footer">
                                <span class="price">৳{{ item.price }}</span>
                                <button type="button" class="add-btn" @click="addToCart(item)">
                                    <i class="fas fa-plus"></i> {{ $t('resort.add_to_cart') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="empty-state">
                    <i class="fas fa-utensils"></i>
                    <h4>{{ $t('resort.no_food_found') }}</h4>
                    <p>{{ $t('resort.no_food_found_sub') }}</p>
                </div>
            </div>
        </div>

        <!-- FLOATING BOTTOM CART BAR (MOBILE) -->
        <div class="mobile-cart-bar" v-if="cartTotalItems > 0" @click="cartOpen = true">
            <div class="cart-info">
                <span class="badge">{{ $t('resort.cart_items_count', { count: cartTotalItems }) }}</span>
                <span class="total">৳{{ cartSubtotal }}</span>
            </div>
            <button class="view-cart-btn">
                <span>{{ $t('resort.complete_order') }}</span>
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <!-- CART & CHECKOUT DRAWER -->
        <div class="cart-drawer-overlay" v-if="cartOpen" @click="cartOpen = false">
            <div class="cart-drawer" @click.stop>
                <div class="drawer-header">
                    <div class="title">
                        <i class="fas fa-shopping-bag text-orange-500"></i>
                        <h3>{{ $t('resort.your_food_cart') }} ({{ cartTotalItems }})</h3>
                    </div>
                    <button class="close-btn" @click="cartOpen = false">&times;</button>
                </div>

                <div class="drawer-body">
                    <!-- CART ITEMS LIST -->
                    <div v-if="cart.length > 0" class="cart-items-list">
                        <div v-for="(ci, idx) in cart" :key="idx" class="cart-item">
                            <div class="item-details">
                                <h5>{{ ci.name }}</h5>
                                <span class="item-unit-price">৳{{ ci.price }}</span>
                            </div>
                            <div class="qty-controls">
                                <button type="button" @click="decreaseQty(idx)">-</button>
                                <span>{{ ci.qty }}</span>
                                <button type="button" @click="increaseQty(idx)">+</button>
                            </div>
                            <span class="item-subtotal">৳{{ (ci.price * ci.qty).toFixed(2) }}</span>
                        </div>

                        <!-- BILL BREAKDOWN -->
                        <div class="bill-summary">
                            <div class="row">
                                <span>{{ $t('resort.subtotal') }}</span>
                                <span>৳{{ cartSubtotal }}</span>
                            </div>
                            <div class="row grand-total">
                                <span>{{ $t('resort.grand_total') }}</span>
                                <span>৳{{ cartSubtotal }}</span>
                            </div>
                        </div>

                        <!-- CHECKOUT FORM -->
                        <div class="checkout-form">
                            <h4><i class="fas fa-receipt"></i> {{ $t('resort.delivery_customer_info') }}</h4>
                            <div class="input-field">
                                <label>{{ $t('resort.fullName') }} *</label>
                                <input v-model="orderForm.name" type="text" :placeholder="$t('resort.ph_customer_name')" required />
                            </div>
                            <div class="input-field">
                                <label>{{ $t('resort.phone') }} *</label>
                                <input v-model="orderForm.phone" type="tel" :placeholder="$t('resort.phPhone')" required />
                            </div>
                            <div class="input-field">
                                <label>{{ $t('resort.room_table_address') }} *</label>
                                <input v-model="orderForm.address" type="text" :placeholder="$t('resort.ph_room_table_address')" required />
                            </div>
                            <div class="input-field">
                                <label>{{ $t('resort.specialNote') }}</label>
                                <textarea v-model="orderForm.instruction" rows="2" :placeholder="$t('resort.ph_food_instruction')"></textarea>
                            </div>
                            <div class="input-field">
                                <label>{{ $t('resort.payment_method') }}</label>
                                <select v-model="orderForm.payment_method">
                                    <option value="cash">{{ $t('resort.cod_room_pay') }}</option>
                                    <option value="digital">{{ $t('resort.online_payment_option') }}</option>
                                </select>
                            </div>

                            <button type="button" class="place-order-btn" @click="placeOrder" :disabled="isPlacingOrder">
                                <span v-if="!isPlacingOrder">
                                    <i class="fas fa-check-circle"></i> {{ $t('resort.confirm_order_btn') }} (৳{{ cartSubtotal }})
                                </span>
                                <span v-else>{{ $t('resort.processing') }}</span>
                            </button>
                        </div>
                    </div>

                    <div v-else class="empty-cart">
                        <i class="fas fa-shopping-basket"></i>
                        <h4>{{ $t('resort.cart_is_empty') }}</h4>
                        <p>{{ $t('resort.cart_is_empty_sub') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- SUCCESS MODAL -->
        <div v-if="orderSuccessModal" class="success-modal-overlay">
            <div class="success-box">
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <h3>{{ $t('resort.order_success_title') }}</h3>
                <p>{{ $t('resort.order_success_sub') }}</p>
                <div class="order-id-badge" v-if="lastOrderId">
                    {{ $t('resort.order_tracking_id') }}: #{{ lastOrderId }}
                </div>
                <button class="ok-btn" @click="orderSuccessModal = false">{{ $t('resort.btn_ok') }}</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "RmsFoodComponent",
    data() {
        return {
            selectedCategory: "all",
            searchQuery: "",
            cartOpen: false,
            cart: [],
            isPlacingOrder: false,
            orderSuccessModal: false,
            lastOrderId: null,
            orderForm: {
                name: "",
                phone: "",
                address: "",
                instruction: "",
                payment_method: "cash",
            },
        };
    },
    computed: {
        setting() { return this.$store.getters['frontendSetting/lists'] || {}; },
        items() { return this.$store.getters['resort/landingContent'].food_items || []; },
        categories() { return this.$store.getters['tableItemCategory/lists'] || []; },
        filteredItems() {
            let list = this.items;
            if (this.selectedCategory !== "all") {
                list = list.filter(i => i.item_category_id === this.selectedCategory);
            }
            if (this.searchQuery.trim()) {
                const q = this.searchQuery.toLowerCase();
                list = list.filter(i => (i.name && i.name.toLowerCase().includes(q)) || (i.description && i.description.toLowerCase().includes(q)));
            }
            return list;
        },
        cartTotalItems() {
            return this.cart.reduce((sum, item) => sum + item.qty, 0);
        },
        cartSubtotal() {
            return this.cart.reduce((sum, item) => sum + (parseFloat(item.price) * item.qty), 0).toFixed(2);
        },
    },
    mounted() {
        this.$store.dispatch('resort/fetchLandingContent');
        this.$store.dispatch('tableItemCategory/lists');
    },
    methods: {
        getSelectedCategoryName() {
            if (this.selectedCategory === "all") return this.$t('resort.all_popular_foods');
            const cat = this.categories.find(c => c.id === this.selectedCategory);
            return cat ? cat.name : this.$t('resort.menu_items');
        },
        addToCart(item) {
            const existing = this.cart.find(ci => ci.id === item.id);
            if (existing) {
                existing.qty++;
            } else {
                this.cart.push({
                    id: item.id,
                    name: item.name,
                    price: item.price,
                    qty: 1,
                });
            }
            this.cartOpen = true;
        },
        increaseQty(idx) {
            this.cart[idx].qty++;
        },
        decreaseQty(idx) {
            if (this.cart[idx].qty > 1) {
                this.cart[idx].qty--;
            } else {
                this.cart.splice(idx, 1);
            }
        },
        placeOrder() {
            if (!this.orderForm.name || !this.orderForm.phone || !this.orderForm.address) {
                alert(this.$t('resort.fill_required_order_fields'));
                return;
            }
            if (this.cart.length === 0) return;

            this.isPlacingOrder = true;
            // Simulated order submission to Sohoj RMS
            setTimeout(() => {
                this.lastOrderId = Math.floor(100000 + Math.random() * 900000);
                this.cart = [];
                this.cartOpen = false;
                this.isPlacingOrder = false;
                this.orderSuccessModal = true;
                this.orderForm = { name: "", phone: "", address: "", instruction: "", payment_method: "cash" };
            }, 800);
        }
    }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap');

.rms-food-page {
    font-family: 'Hind Siliguri', sans-serif;
    background: #f8f6f0;
    min-height: 100vh;
    color: #2b2b2b;
    padding-bottom: 90px;
}

/* HEADER */
.rms-header {
    background: #0f1a12;
    color: #fff;
    padding: 14px 5%;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}
.header-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.back-link {
    color: #d0c8b0;
    text-decoration: none;
    font-size: 0.88rem;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: 0.2s;
}
.back-link:hover { color: #c9a34e; }
.brand {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #c9a34e;
    font-size: 1.15rem;
    font-weight: 700;
}
.cart-trigger {
    background: #c9a34e;
    color: #0f1a12;
    border: none;
    padding: 8px 16px;
    border-radius: 25px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: 0.2s;
}
.cart-trigger:hover { background: #d8b45f; }
.cart-count {
    background: #0f1a12;
    color: #fff;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* BANNER */
.rms-banner {
    background: linear-gradient(135deg, #0f1a12 0%, #1e3324 100%);
    color: #fff;
    padding: 50px 5% 45px;
    text-align: center;
}
.banner-content {
    max-width: 700px;
    margin: 0 auto;
}
.banner-content h2 {
    font-size: 2rem;
    color: #c9a34e;
    margin-bottom: 10px;
    font-weight: 700;
}
.banner-content p {
    color: #d0c8b0;
    font-size: 1rem;
    margin-bottom: 25px;
}
.search-wrap {
    max-width: 550px;
    margin: 0 auto;
}
.search-box {
    position: relative;
    background: #fff;
    border-radius: 30px;
    display: flex;
    align-items: center;
    padding: 6px 20px;
    box-shadow: 0 6px 25px rgba(0,0,0,0.2);
}
.search-box i {
    color: #c9a34e;
    font-size: 1.1rem;
    margin-right: 12px;
}
.search-box input {
    border: none;
    outline: none;
    width: 100%;
    font-size: 0.95rem;
    font-family: inherit;
    padding: 8px 0;
}
.clear-btn {
    background: transparent;
    border: none;
    font-size: 1.3rem;
    color: #999;
    cursor: pointer;
}

/* MENU CONTAINER */
.menu-container {
    max-width: 1200px;
    margin: 30px auto;
    padding: 0 5%;
}
.categories-bar {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding-bottom: 12px;
    margin-bottom: 30px;
    scrollbar-width: thin;
}
.cat-pill {
    background: #fff;
    border: 1px solid #e0d8c8;
    color: #444;
    padding: 8px 18px;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
    transition: 0.2s;
    display: flex;
    align-items: center;
    gap: 6px;
}
.cat-pill.active, .cat-pill:hover {
    background: #0f1a12;
    color: #c9a34e;
    border-color: #0f1a12;
}

/* ITEMS GRID */
.section-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.section-meta h3 {
    font-size: 1.3rem;
    color: #1e2b1e;
    font-weight: 700;
}
.item-count {
    font-size: 0.85rem;
    color: #888;
}
.items-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 25px;
}
.menu-card {
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.04);
    border: 1px solid #ebe4d5;
    transition: 0.3s;
    display: flex;
    flex-direction: column;
}
.menu-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
}
.card-img-wrap {
    position: relative;
    height: 170px;
}
.card-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.featured-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #c9a34e;
    color: #0f1a12;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 10px;
}
.card-content {
    padding: 16px;
    display: flex;
    flex-direction: column;
    flex: 1;
}
.card-content h4 {
    font-size: 1.05rem;
    font-weight: 700;
    color: #1e2b1e;
    margin-bottom: 6px;
}
.card-content .desc {
    color: #777;
    font-size: 0.84rem;
    line-height: 1.5;
    margin-bottom: 15px;
    flex: 1;
}
.card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid #f2ece0;
    padding-top: 12px;
}
.card-footer .price {
    font-size: 1.25rem;
    font-weight: 700;
    color: #c9a34e;
}
.add-btn {
    background: #0f1a12;
    color: #fff;
    border: none;
    padding: 7px 15px;
    border-radius: 20px;
    font-size: 0.82rem;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
    display: flex;
    align-items: center;
    gap: 6px;
}
.add-btn:hover {
    background: #c9a34e;
    color: #0f1a12;
}

/* MOBILE CART BAR */
.mobile-cart-bar {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background: #0f1a12;
    color: #fff;
    padding: 14px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 99;
    box-shadow: 0 -4px 20px rgba(0,0,0,0.2);
    cursor: pointer;
}
.cart-info {
    display: flex;
    align-items: center;
    gap: 12px;
}
.cart-info .badge {
    background: #c9a34e;
    color: #0f1a12;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 700;
}
.cart-info .total {
    font-size: 1.15rem;
    font-weight: 700;
    color: #fff;
}
.view-cart-btn {
    background: transparent;
    border: none;
    color: #c9a34e;
    font-weight: 700;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

/* CART DRAWER */
.cart-drawer-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    z-index: 1000;
    display: flex;
    justify-content: flex-end;
}
.cart-drawer {
    background: #fff;
    width: 100%;
    max-width: 440px;
    height: 100%;
    display: flex;
    flex-direction: column;
    box-shadow: -6px 0 25px rgba(0,0,0,0.2);
}
.drawer-header {
    background: #0f1a12;
    color: #fff;
    padding: 18px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.drawer-header .title {
    display: flex;
    align-items: center;
    gap: 10px;
}
.drawer-header h3 {
    font-size: 1.1rem;
    font-weight: 700;
    margin: 0;
}
.drawer-header .close-btn {
    background: transparent;
    border: none;
    color: #fff;
    font-size: 1.6rem;
    cursor: pointer;
}
.drawer-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
}
.cart-items-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}
.cart-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 12px;
    border-bottom: 1px solid #f0ebe0;
}
.item-details h5 {
    font-size: 0.92rem;
    font-weight: 600;
    color: #1e2b1e;
    margin-bottom: 3px;
}
.item-unit-price {
    font-size: 0.8rem;
    color: #888;
}
.qty-controls {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f4efe4;
    padding: 2px 8px;
    border-radius: 15px;
}
.qty-controls button {
    background: transparent;
    border: none;
    font-weight: 700;
    color: #0f1a12;
    cursor: pointer;
    font-size: 1rem;
    padding: 2px 6px;
}
.item-subtotal {
    font-weight: 700;
    color: #c9a34e;
    font-size: 0.95rem;
}
.bill-summary {
    background: #fbf9f4;
    padding: 15px;
    border-radius: 12px;
    border: 1px dashed #e8dfcf;
    margin: 15px 0;
}
.bill-summary .row {
    display: flex;
    justify-content: space-between;
    font-size: 0.88rem;
    margin-bottom: 6px;
    color: #555;
}
.bill-summary .grand-total {
    font-size: 1.1rem;
    font-weight: 700;
    color: #0f1a12;
    border-top: 1px solid #e8dfcf;
    padding-top: 8px;
    margin-top: 8px;
}
.checkout-form {
    margin-top: 20px;
    border-top: 2px solid #0f1a12;
    padding-top: 18px;
}
.checkout-form h4 {
    font-size: 1rem;
    font-weight: 700;
    color: #0f1a12;
    margin-bottom: 12px;
}
.input-field {
    margin-bottom: 12px;
}
.input-field label {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    color: #444;
    margin-bottom: 4px;
}
.input-field input, .input-field select, .input-field textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-family: inherit;
    font-size: 0.88rem;
}
.place-order-btn {
    width: 100%;
    background: #c9a34e;
    color: #0f1a12;
    padding: 13px;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    margin-top: 15px;
    transition: 0.2s;
}
.place-order-btn:hover { background: #d8b45f; }

/* SUCCESS MODAL */
.success-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.7);
    z-index: 2000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.success-box {
    background: #fff;
    max-width: 400px;
    width: 100%;
    padding: 35px 25px;
    border-radius: 20px;
    text-align: center;
}
.success-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: #e8f5e9;
    color: #2e7d32;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin: 0 auto 15px;
}
.success-box h3 {
    font-size: 1.25rem;
    color: #1e2b1e;
    margin-bottom: 8px;
}
.success-box p {
    font-size: 0.88rem;
    color: #666;
    margin-bottom: 18px;
    line-height: 1.5;
}
.order-id-badge {
    background: #f4efe4;
    color: #0f1a12;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.88rem;
    margin-bottom: 20px;
    display: inline-block;
}
.ok-btn {
    width: 100%;
    background: #0f1a12;
    color: #fff;
    border: none;
    padding: 12px;
    border-radius: 12px;
    font-weight: 700;
    cursor: pointer;
}
</style>
