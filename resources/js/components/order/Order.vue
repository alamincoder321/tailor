<template>
    <div class="row">
        <div class="col-md-10 offset-md-1 bg-content py-4">
            <form @submit.prevent="">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="order_code">অর্ডার নংঃ</label>
                                    <input type="text" v-model="order.order_code" name="order_code" id="order_code"
                                        class="form-control shadow-none" autocomplete="off" readonly />
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="orderDate">অর্ডার তারিখঃ</label>
                                    <input type="date" v-model="order.orderDate" name="orderDate" id="orderDate"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="deliveryDate">ডেলিভারি তারিখঃ</label>
                                    <input type="date" v-model="order.deliveryDate" name="deliveryDate" id="deliveryDate"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="customer_name">কাস্টমার নামঃ</label>
                                    <input type="text" v-model="order.customer_name" name="customer_name" id="customer_name"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="customer_mobile">মোবাইল নংঃ</label>
                                    <input type="text" v-model="order.customer_mobile" name="customer_mobile"
                                        id="customer_mobile" class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="refer">রেফারঃ</label>
                                    <input type="text" v-model="order.refer" name="refer" id="refer"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-md-10 offset-md-1 py-4">
            <div class="row">
                <div class="col-md-7 bg-content">
                    <div class="row">
                        <div class="col-md-3" v-for="(item, index) in categories" :key="index">
                            <div class="input-group">
                                <input type="radio" v-model="category" :value="item.id" @change="onChangeCategory(item.id)"
                                    name="category_type" :id="index" class="me-2">
                                <label :for="index">{{ item.name }}</label>
                            </div>
                        </div>
                    </div>
                    <hr class="m-0">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="product_name">প্রডাক্ট নামঃ</label>
                                <v-select v-model="selectProduct" :options="products" label="name" id="product"
                                    placeholder="---প্রোডাক্ট নাম বাছাই করুন---"></v-select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quantity">কোয়ান্টিটিঃ</label>
                                <input type="number" min="0" v-model="selectProduct.quantity" @input="productTotal"
                                    name="quantity" id="quantity" class="form-control shadow-none">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3" v-if="category != '' && category == '2'">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="long">লম্বাঃ</label>
                                <input type="text" v-model="jama.long" name="long" id="long"
                                    class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="body">বডিঃ</label>
                                <input type="text" v-model="jama.body" name="body" id="body"
                                    class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="tira">তিরাঃ</label>
                                <input type="text" v-model="jama.tira" name="tira" id="tira"
                                    class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="hata">হাতাঃ</label>
                                <input type="text" v-model="jama.hata" name="hata" id="hata"
                                    class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="mohori">মোহরীঃ</label>
                                <input type="text" v-model="jama.mohori" name="mohori" id="mohori"
                                    class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="gola">গলাঃ</label>
                                <input type="text" v-model="jama.gola" name="gola" id="gola"
                                    class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="gher">ঘেরঃ</label>
                                <input type="text" v-model="jama.gher" name="gher" id="gher"
                                    class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="plate">প্লেটঃ</label>
                                <input type="text" v-model="jama.plate" name="plate" id="plate"
                                    class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="mora">মোড়াঃ</label>
                                <input type="text" v-model="jama.mora" name="mora" id="mora"
                                    class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="ghari">ঘাড়ী</label>
                                <input type="text" v-model="jama.ghari" name="ghari" id="ghari"
                                    class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="peter_map">পেটের মাপঃ</label>
                                <input type="text" v-model="jama.peter_map" name="peter_map" id="peter_map"
                                    class="form-control shadow-none">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3" v-if="category != '' && category == '1'">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="long">লম্বাঃ</label>
                                <input type="text" v-model="payjama.long" name="long" id="long"
                                    class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="komor">কোমরঃ</label>
                                <input type="text" v-model="payjama.komor" name="komor" id="komor"
                                    class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="mohori">মোহরীঃ</label>
                                <input type="text" v-model="payjama.mohori" name="mohori" id="mohori"
                                    class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="high">হাইঃ</label>
                                <input type="text" v-model="payjama.high" name="high" id="high"
                                    class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="ran">রানঃ</label>
                                <input type="text" v-model="payjama.ran" name="ran" id="ran"
                                    class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <div class="row">
                                <div class="col-md-1 col-3">
                                    <input type="checkbox" v-model="payjama.pocket_chain" class="form-check"
                                        id="pocket_chain" true-value="true" false-value="false" />
                                </div>
                                <div class="col-md-3 col-9 px-md-0">
                                    <label for="pocket_chain">পকেট চেইন</label>
                                </div>
                                <div class="col-md-1 col-3">
                                    <input type="checkbox" class="form-check" id="good_runner" v-model="payjama.good_runner"
                                        true-value="true" false-value="false" />
                                </div>
                                <div class="col-md-3 col-9 px-md-0">
                                    <label for="good_runner">ভালো রানার হবে</label>
                                </div>
                                <div class="col-md-1 col-3">
                                    <input type="checkbox" v-model="payjama.back_pocket" class="form-check" id="back_pocket"
                                        true-value="true" false-value="false" />
                                </div>
                                <div class="col-md-3 col-9 px-md-0">
                                    <label for="back_pocket">পিছনে পকেট</label>
                                </div>
                                <div class="col-md-1 col-3">
                                    <input type="checkbox" v-model="payjama.pocket_chain_one" class="form-check"
                                        id="pocket_chain_one" true-value="true" false-value="false" />
                                </div>
                                <div class="col-md-3 col-9 px-md-0">
                                    <label for="pocket_chain_one">পকেট চেইন ১টি</label>
                                </div>
                                <div class="col-md-1 col-3">
                                    <input type="checkbox" v-model="payjama.fitha" class="form-check" id="fitha"
                                        true-value="true" false-value="false" />
                                </div>
                                <div class="col-md-3 col-9 px-md-0">
                                    <label for="fitha">ফিতা হবে</label>
                                </div>
                                <div class="col-md-1 col-3">
                                    <input type="checkbox" v-model="payjama.rabar" class="form-check" id="rabar"
                                        true-value="true" false-value="false" />
                                </div>
                                <div class="col-md-3 col-9 px-md-0">
                                    <label for="rabar">চিকন রাবার</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-end">
                        <button @click="addToCart" type="button" class="btn btn-silver shadow-none px-3 me-2 mt-4">
                            কার্ডে যুক্ত করুন
                        </button>
                    </div>
                </div>
                <!-- middle section -->
                <div class="col-md-1 my-3"></div>
                <!-- middle section end -->

                <!-- total section -->
                <div class="col-md-4 bg-content">
                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <label for="subtotal">মোট টাকাঃ</label>
                        </div>
                        <div class="col-md-5 mb-3">
                            <input type="number" v-model="order.subtotal" name="subtotal" class="form-control shadow-none"
                                id="subtotal" autocomplete="off" disabled />
                        </div>
                        <div class="col-md-7 mb-3">
                            <label for="discount">ডিস্কাউন্ট টাকাঃ</label>
                        </div>
                        <div class="col-md-5 mb-3">
                            <input type="number" v-model="order.discount" @input="calculateTotal" name="discount"
                                class="form-control shadow-none" id="discount" autocomplete="off" />
                        </div>
                        <div class="col-md-7 mb-3">
                            <label for="total">মোট পাওনা টাকাঃ</label>
                        </div>
                        <div class="col-md-5 mb-3">
                            <input type="number" v-model="order.total" name="total" class="form-control shadow-none"
                                id="total" autocomplete="off" disabled />
                        </div>
                        <div class="col-md-7 mb-3">
                            <label for="advance">অগ্রীম টাকাঃ</label>
                        </div>
                        <div class="col-md-5 mb-3">
                            <input type="number" v-model="order.advance" @input="calculateTotal" name="advance"
                                class="form-control shadow-none" id="advance" autocomplete="off" />
                        </div>
                        <div class="col-md-7 mb-3">
                            <label for="due">বাকী টাকাঃ</label>
                        </div>
                        <div class="col-md-5 mb-3">
                            <input type="number" v-model="order.due" name="due" class="form-control shadow-none" id="due"
                                autocomplete="off" disabled />
                        </div>
                    </div>
                </div>
                <!-- total section end -->

                <div class="col-md-7 bg-content mt-2" v-if="carts.length > 0">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <td>ক্রমিক</td>
                                <td>প্রোডাক্ট নাম</td>
                                <td>কোয়ান্টিটি</td>
                                <td>একক টাকা</td>
                                <td>মোট টাকা</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in carts">
                                <td>{{ index + 1 }}</td>
                                <td>{{ item.product_name }}</td>
                                <td>{{ item.quantity }}</td>
                                <td>{{ item.retail_price }}</td>
                                <td>{{ item.total }}</td>
                                <td>
                                    <i style="font-size: 15px;cursor: pointer;" @click="removeCart(index)"
                                        class="text-danger fa fa-trash"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-md-12 bg-content mt-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tailor_slip_one">কারিগর স্লিপ-১</label>
                                        <input type="text" v-model="order.tailor_slip_one" class="form-control shadow-none"
                                            name="tailor_slip_one" id="tailor_slip_one">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tailor_slip_two">কারিগর স্লিপ-2</label>
                                        <input type="text" v-model="order.tailor_slip_two" class="form-control shadow-none"
                                            name="tailor_slip_two" id="tailor_slip_two">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <button @click="saveOrder" type="button" class="btn btn-silver shadow-none px-3 me-2 mt-4">
                                Save
                            </button>
                            <button type="button" class="btn btn-silver shadow-none px-3 me-2 mt-4">
                                Update
                            </button>
                            <button type="button" class="btn btn-silver shadow-none px-3 me-2 mt-4">
                                Delete
                            </button>
                            <button type="button" class="btn btn-silver shadow-none px-3 me-2 mt-4">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import Swal from 'sweetalert2'

export default {
    data() {
        return {
            order: {
                order_code: '',
                orderDate: '',
                deliveryDate: '',
                customer_name: '',
                customer_mobile: '',
                refer: '',
                subtotal: 0,
                discount: 0,
                total: 0,
                advance: 0,
                due: 0,
                tailor_slip_one: '',
                tailor_slip_two: '',
            },
            jama: {
                long: '',
                body: '',
                tira: '',
                hata: '',
                mohori: '',
                gola: '',
                gher: '',
                plate: '',
                mora: '',
                ghari: '',
                peter_map: '',
            },
            payjama: {
                long: '',
                komor: '',
                mohori: '',
                high: '',
                ran: '',
                pocket_chain: 'false',
                good_runner: 'false',
                back_pocket: 'false',
                pocket_chain_one: 'false',
                fitha: 'false',
                rabar: 'false',
            },
            categories: [],
            products: [],
            selectProduct: '',
            category: 2,

            carts: [],
        }
    },

    created() {
        this.getCategory();
        this.getOrder();
    },

    methods: {
        getCategory() {
            axios.get('/get-category').then(res => {
                this.categories = res.data;
            })
        },
        getProduct(catId) {
            axios.get('/get-product').then(res => {
                this.products = res.data.products.filter(p => p.category_id == catId);
                this.products.unshift({ id: '', name: '---প্রডাক্ট নাম বাছাই করুন---' });
            })
        },

        onChangeCategory(catId) {
            this.getProduct(catId);
        },

        productTotal() {
            this.selectProduct.total = parseFloat(this.selectProduct.retail_price * this.selectProduct.quantity).toFixed(2);
        },

        addToCart() {
            if (this.selectProduct == '' || this.selectProduct.id == '') {
                alert("Product name required");
                document.querySelector("#product [type='search']").focus();
                return
            }
            if (this.selectProduct.quantity == undefined || this.selectProduct.quantity == 0) {
                alert("Quantity is empty");
                return
            }
            let cart = {
                category_id: this.category,
                product_id: this.selectProduct.id,
                product_name: this.selectProduct.name,
                quantity: this.selectProduct.quantity,
                retail_price: this.selectProduct.retail_price,
                tailor_price: this.selectProduct.tailor_price,
                total: this.selectProduct.total,
                payjama: this.category == 1 ? this.payjama : '',
                jama: this.category == 2 ? this.jama : '',
            }
            let cartIndex = this.carts.findIndex(p => cart.product_id == this.selectProduct.id);
            if (cartIndex >= 0) {
                this.carts.splice(cartIndex, 1);
            }
            this.carts.push(cart);
            this.calculateTotal();
            this.clearData();
        },

        removeCart(index) {
            this.carts.splice(index, 1);
            this.calculateTotal();
        },

        calculateTotal() {
            if (this.carts.length == 0) {
                this.order.discount = 0.00
                this.order.advance = 0.00
            }
            this.order.subtotal = this.carts.reduce((acc, pre) => {
                return acc + +parseFloat(pre.total)
            }, 0).toFixed(2);
            this.order.total = parseFloat(this.order.subtotal - this.order.discount).toFixed(2);
            this.order.due = parseFloat(this.order.total - this.order.advance).toFixed(2);
        },

        clearData() {
            this.jama = {
                long: '',
                body: '',
                tira: '',
                hata: '',
                mohori: '',
                gola: '',
                gher: '',
                plate: '',
                mora: '',
                ghari: '',
                peter_map: '',
            }
            this.payjama = {
                long: '',
                komor: '',
                mohori: '',
                high: '',
                ran: '',
                pocket_chain: 'false',
                good_runner: 'false',
                back_pocket: 'false',
                pocket_chain_one: 'false',
                fitha: 'false',
                rabar: 'false',
            }
            this.selectProduct = ''
        },

        saveOrder() {
            let data = {
                order: this.order,
                carts: this.carts
            }

            axios.post('/order', data)
                .then(res => {
                    this.$moshaToast(res.data.msg);
                    this.clearOrder();
                    this.getOrder();
                })
        },

        clearOrder() {
            this.carts = [];
            this.order = {
                order_code: '',
                orderDate: '',
                deliveryDate: '',
                customer_name: '',
                customer_mobile: '',
                refer: '',
                subtotal: 0,
                discount: 0,
                total: 0,
                advance: 0,
                due: 0,
                tailor_slip_one: '',
                tailor_slip_two: '',
            }
        },

        getOrder() {
            axios.post('/get-order', { fromOrder: 'yes' })
                .then(res => {
                    this.order.order_code = res.data.orderCode;
                })
        },
    },
}
</script>

<style>
#product [role='combobox'] {
    height: 32px !important;
}

#product .vs__clear {
    margin-right: 0;
    padding: 0px 8px !important;
}
</style>