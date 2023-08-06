<template>
    <div class="row">
        <div class="col-md-10 offset-md-1 bg-content py-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="date">তারিখঃ</label>
                                <input type="date" v-model="clothing.date" name="date" id="date"
                                    class="form-control shadow-none" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="tailor_id">কারিগর নামঃ</label>
                                <select v-model="clothing.tailor_id" name="tailor_id" id="tailor_id"
                                    class="form-select shadow-none">
                                    <option value="">---কারিগর নাম বাছাই করুন---</option>
                                    <option v-for="(item, index) in tailors" :key="index" :value="item.id">{{ item.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                            <label for="total">মোট টাকাঃ</label>
                        </div>
                        <div class="col-md-5 mb-3">
                            <input type="number" v-model="clothing.total" name="total" class="form-control shadow-none"
                                id="total" autocomplete="off" disabled />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <label for="paid">পেমেন্টঃ</label>
                        </div>
                        <div class="col-md-5 mb-3">
                            <input type="number" v-model="clothing.paid" name="paid" class="form-control shadow-none"
                                id="paid" autocomplete="off" @input="calculateTotal" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <label for="due">বাকি টাকাঃ</label>
                        </div>
                        <div class="col-md-5 mb-3">
                            <input type="number" v-model="clothing.due" name="due" class="form-control shadow-none"
                                id="due" autocomplete="off" disabled />
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
                                <td>{{ item.tailor_price }}</td>
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
                            <div class="form-group">
                                <label for="note">ক্লোথিং নোটঃ</label>
                                <textarea v-model="clothing.note" class="form-control shadow-none"
                                    name="note" id="note"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <button @click="saveClothing" type="button" class="btn btn-silver shadow-none px-3 me-2 mt-4">
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

export default {
    props: ['id'],
    data() {
        return {
            clothing: {
                id: '',
                date: moment().format('YYYY-MM-DD'),
                tailor_id: '',
                total: 0,
                paid: 0,
                due: 0,
                note: '',
            },
            categories: [],
            products: [],
            selectProduct: '',
            category: 1,

            carts: [],

            tailors: [],
        }
    },

    created() {
        this.getCategory();
        this.getTailor();
        if (this.id != '') {
            this.getClothing();
        }
    },

    methods: {
        getCategory() {
            axios.get('/get-category').then(res => {
                this.categories = res.data;
            })
        },
        getTailor() {
            axios.get('/get-tailor').then(res => {
                this.tailors = res.data;
            })
        },
        getProduct(catId) {
            axios.get('/get-product').then(res => {
                this.products = res.data.products.filter(p => p.category_id == catId);
            })
        },

        onChangeCategory(catId) {
            this.selectProduct = '';
            this.getProduct(catId);
        },

        productTotal() {
            this.selectProduct.total = parseFloat(this.selectProduct.tailor_price * this.selectProduct.quantity).toFixed(2);
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
                tailor_price: this.selectProduct.tailor_price,
                total: this.selectProduct.total,
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
            this.clothing.total = this.carts.reduce((acc, pre) => {
                return acc + +parseFloat(pre.total)
            }, 0).toFixed(2);
            this.clothing.due = parseFloat(parseFloat(this.clothing.total) - parseFloat(this.clothing.paid)).toFixed(2);
        },

        clearData() {
            this.selectProduct = ''
        },

        saveClothing() {
            if (this.carts.length == 0) {
                alert("Carts empty");
                return
            }
            if (this.clothing.date == '') {
                alert("Clothing date required");
                return
            }
            if (this.clothing.tailor_id == '') {
                alert("Tailor name required");
                return
            }

            let data = {
                clothing: this.clothing,
                carts: this.carts,
            }

            let url;
            if (this.id != '') {
                url = '/update-clothing'
            } else {
                url = '/clothing'
            }
            axios.post(url, data)
                .then(res => {
                    this.$moshaToast(res.data.msg);
                    this.clearClothing();
                    if (this.id != '') {
                        setTimeout(() => {
                            location.href = '/clothing'
                        }, 500)
                    }
                    this.getClothing();
                })
        },

        clearClothing() {
            this.carts = [];
            this.clothing = {
                id: '',
                date: moment().format('YYYY-MM-DD'),
                tailor_id: '',
                total: 0,
                paid: 0,
                due: 0,
                note:'',
            }
        },

        getClothing() {
            let data = { id: this.id }
            axios.post('/get-clothing', data)
                .then(res => {
                    let clothing = res.data.clothing[0]
                    let clothingItem = res.data.clothingItem
                    this.clothing = {
                        id       : clothing.id,
                        date     : clothing.date,
                        tailor_id: clothing.tailor_id,
                        total    : clothing.total,
                        paid     : clothing.paid,
                        due      : clothing.due,
                        note     : clothing.note
                    }

                    clothingItem.forEach(item => {
                        let cart = {
                            category_id : item.product.category_id,
                            product_id  : item.product_id,
                            product_name: item.product.name,
                            quantity    : item.quantity,
                            tailor_price: item.tailor_price,
                            total       : item.total,
                        }

                        this.carts.push(cart)
                    })


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