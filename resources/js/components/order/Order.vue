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
                                    <div class="position-relative">
                                        <input type="text" v-model="order.customer_name" name="customer_name"
                                            id="customer_name" class="form-control shadow-none" autocomplete="off" />
                                        <ul class="position-absolute p-0 m-0"
                                            style="list-style:none;width:100%;z-index:9999;">
                                            <li @click="CustomerName(item)"
                                                style="background: rgb(241 241 241);padding: 3px 5px;border-bottom: 1px solid rgba(255, 0, 0, 0.221);cursor:pointer;font-weight:500;"
                                                v-for="(item, index) in customers" :key="index">{{ item.name }}</li>
                                        </ul>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="customer_mobile">মোবাইল নংঃ</label>
                                    <input type="text" v-model="order.customer_mobile" name="customer_mobile"
                                        id="customer_mobile" class="form-control shadow-none" autocomplete="off"
                                        @input="mobileCheck" />
                                    <span id="errorMsg" class="text-danger"></span>
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
                                <input v-if="selectProduct != null" type="number" min="0" v-model="selectProduct.quantity"
                                    @input="productTotal" name="quantity" id="quantity" class="form-control shadow-none"
                                    autocomplete="off">
                                <input v-else type="number" min="0" name="quantity" id="quantity"
                                    class="form-control shadow-none" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3" v-if="category != '' && category == '2'">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="long">লম্বাঃ</label>
                                <input type="text" v-model="jama.long" name="long" id="long"
                                    class="form-control shadow-none" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="body">বডিঃ</label>
                                <input type="text" v-model="jama.body" name="body" id="body"
                                    class="form-control shadow-none" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="tira">তিরাঃ</label>
                                <input type="text" v-model="jama.tira" name="tira" id="tira"
                                    class="form-control shadow-none" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="hata">হাতাঃ</label>
                                <input type="text" v-model="jama.hata" name="hata" id="hata"
                                    class="form-control shadow-none" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="mohori">মোহরীঃ</label>
                                <input type="text" v-model="jama.mohori" name="mohori" id="mohori"
                                    class="form-control shadow-none" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="gola">গলাঃ</label>
                                <input type="text" v-model="jama.gola" name="gola" id="gola"
                                    class="form-control shadow-none" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="gher">ঘেরঃ</label>
                                <input type="text" v-model="jama.gher" name="gher" id="gher"
                                    class="form-control shadow-none" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="plate">প্লেটঃ</label>
                                <input type="text" v-model="jama.plate" name="plate" id="plate"
                                    class="form-control shadow-none" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="mora">মোড়াঃ</label>
                                <input type="text" v-model="jama.mora" name="mora" id="mora"
                                    class="form-control shadow-none" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="ghari">ঘাড়ী</label>
                                <input type="text" v-model="jama.ghari" name="ghari" id="ghari"
                                    class="form-control shadow-none" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="peter_map">পেটের মাপঃ</label>
                                <input type="text" v-model="jama.peter_map" name="peter_map" id="peter_map"
                                    class="form-control shadow-none" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3" v-if="category != '' && category == '1'">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="long">লম্বাঃ</label>
                                <input type="text" v-model="payjama.long" name="long" id="long"
                                    class="form-control shadow-none" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="komor">কোমরঃ</label>
                                <input type="text" v-model="payjama.komor" name="komor" id="komor"
                                    class="form-control shadow-none" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="mohori">মোহরীঃ</label>
                                <input type="text" v-model="payjama.mohori" name="mohori" id="mohori"
                                    class="form-control shadow-none" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="high">হাইঃ</label>
                                <input type="text" v-model="payjama.high" name="high" id="high"
                                    class="form-control shadow-none" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="ran">রানঃ</label>
                                <input type="text" v-model="payjama.ran" name="ran" id="ran"
                                    class="form-control shadow-none" autocomplete="off" />
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
                            <tr v-for="(item, index) in carts" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ item.product_name }}</td>
                                <td>{{ item.quantity }}</td>
                                <td>{{ item.retail_price }}</td>
                                <td>{{ item.total }}</td>
                                <td>
                                    <i style="font-size: 15px;cursor: pointer;" @click="editCart(index)"
                                        class="text-primary fa fa-edit me-2"></i>
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
                            <button @click="saveOrder" type="button" class="btn btn-silver shadow-none px-5 me-2 mt-4">
                                Confirm Order
                            </button>
                            <button type="button" @click="clearOrder" class="btn btn-silver shadow-none px-3 me-2 mt-4">
                                Reset
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

export default {
    props: ['id'],
    data() {
        return {
            order: {
                id: '',
                order_code: '',
                orderDate: moment().format('YYYY-MM-DD'),
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
            category: 1,

            carts: [],

            customers: [],
            customer: "",
        }
    },

    async created() {
        this.getCategory();
        this.getOrder();
        await this.getProduct(this.category)
    },

    methods: {
        getCategory() {
            axios.get('/get-category').then(res => {
                this.categories = res.data;
            })
        },
        async getProduct(catId) {
            await axios.get('/get-product').then(res => {
                this.products = res.data.products.filter(p => p.category_id == catId);
            })
        },

        onChangeCategory(catId) {
            this.selectProduct = '';
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
            let cartIndex = this.carts.findIndex(p => p.product_id == cart.product_id);
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

        editCart(index) {
            let cart = this.carts[index];
            this.category = cart.category_id
            this.selectProduct = {
                id:cart.product_id,
                name: cart.product_name,
                quantity: cart.quantity,
                retail_price: cart.retail_price,
                tailor_price: cart.tailor_price
            }
            if (this.category == 2) {
                this.jama = {
                    long: cart.jama.long,
                    body: cart.jama.body,
                    tira: cart.jama.tira,
                    hata: cart.jama.hata,
                    mohori: cart.jama.mohori,
                    gola: cart.jama.gola,
                    gher: cart.jama.gher,
                    plate: cart.jama.plate,
                    mora: cart.jama.mora,
                    ghari: cart.jama.ghari,
                    peter_map: cart.jama.peter_map,
                };
            } else {
                this.payjama = {
                    long: cart.payjama.long,
                    komor: cart.payjama.komor,
                    mohori: cart.payjama.mohori,
                    high: cart.payjama.high,
                    ran: cart.payjama.ran,
                    pocket_chain: cart.payjama.pocket_chain,
                    good_runner: cart.payjama.good_runner,
                    back_pocket: cart.payjama.back_pocket,
                    pocket_chain_one: cart.payjama.pocket_chain_one,
                    fitha: cart.payjama.fitha,
                    rabar: cart.payjama.rabar,
                }
            }
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
            this.selectProduct = '';
            this.category = 1;
        },

        saveOrder() {
            if (this.carts.length == 0) {
                alert("Cart is empty");
                return
            }
            if (this.order.orderDate == '') {
                alert("Order date required");
                return
            }
            if (this.order.deliveryDate == '') {
                alert("delivery date required");
                return
            }
            if (this.order.customer_name == '') {
                alert("Customer name required");
                return
            }
            if (this.order.customer_mobile == '') {
                alert("Customer mobile required");
                return
            } else if (this.order.customer_mobile.length != 11) {
                alert("Customer number must be 11 digit");
                return;
            }
            let data = {
                order: this.order,
                carts: this.carts,
                customer: this.customer
            }
            let url;
            if (this.id != '') {
                url = '/update-order'
            } else {
                url = '/order'
            }
            axios.post(url, data)
                .then(res => {
                    if(res.data.status){
                        this.$moshaToast(res.data.msg);
                        if(confirm("Are you sure want print")){
                            window.open(`/order-invoice/${res.data.id}`, '_blank');
                        }
                        if (this.id != '') {
                            setTimeout(() => {
                                location.href = '/order'
                            }, 1000)
                        }
                        this.clearOrder();
                        this.getOrder();
                    }else{
                        console.log(res.data.msg)
                    }
                })
        },

        clearOrder() {
            this.carts = [];
            this.order = {
                id: '',
                order_code: '',
                orderDate: moment().format('YYYY-MM-DD'),
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
            let data = { fromOrder: 'yes' }
            if (this.id != '') {
                data = { id: this.id }
            }
            axios.post('/get-order', data)
                .then(res => {
                    if (this.id != '') {
                        let order = res.data.orders[0]
                        let orderItem = order.orderItem
                        this.order = {
                            id: order.id,
                            order_code: order.order_code,
                            orderDate: order.orderDate,
                            deliveryDate: order.deliveryDate,
                            customer_name: order.name,
                            customer_mobile: order.phone,
                            refer: order.refer,
                            subtotal: order.subtotal,
                            discount: order.discount,
                            total: order.total,
                            advance: order.advance,
                            due: order.due,
                            tailor_slip_one: order.tailor_slip_one,
                            tailor_slip_two: order.tailor_slip_two,
                        }

                        this.customer = { id: order.customer_id }

                        orderItem.forEach(item => {
                            let cart = {
                                category_id: item.product.category_id,
                                product_id: item.product_id,
                                product_name: item.product.name,
                                quantity: item.quantity,
                                retail_price: item.retail_price,
                                tailor_price: item.tailor_price,
                                total: item.total,
                                payjama: item.product.category_id == 1 ? item.payjama : '',
                                jama: item.product.category_id == 2 ? item.jama : '',
                            }

                            this.carts.push(cart)
                        })
                    } else {
                        this.order.order_code = res.data.orderCode;
                    }


                })
        },

        mobileCheck(event) {
            var phoneno = "(?:\\+88|88)?(01[3-9]\\d{8})";
            this.customer = '';
            if (this.order.customer_mobile.match(phoneno) && this.order.customer_mobile.length == 11) {
                this.order.customer_name = "";
                document.querySelector('#errorMsg').innerHTML = '';
                document.querySelector('#customer_mobile').style = 'border-color:green;';
                axios.post('/get-mobile', { 'phone': this.order.customer_mobile }).then(res => {
                    this.customers = res.data;
                })
            } else {
                this.order.customer_name = "";
                document.querySelector('#customer_mobile').style = 'border-color:red;';
                document.querySelector('#errorMsg').innerHTML = 'Not Valid Number';
                this.customers = [];
            }
        },

        CustomerName(item) {
            this.order.customer_name = item.name;
            this.customer = item;
            this.customers = [];
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