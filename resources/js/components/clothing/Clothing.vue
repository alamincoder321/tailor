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
                                <label for="order_id">অর্ডার লিস্টঃ</label>
                                <select @change="OrderList" v-model="clothing.order_id" name="order_id" id="order_id"
                                    class="form-select shadow-none">
                                    <option value="">---অর্ডার বাছাই করুন---</option>
                                    <option v-for="(item, index) in orders" :key="index" :value="item.id">{{ item.order_code }}-({{ item.orderDate }})-{{ item.name }}
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
                <div class="col-md-12 bg-content mt-2" v-if="carts.length > 0">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <td>ক্রমিক</td>
                                <td>কারিগর নাম</td>
                                <td>প্রোডাক্ট নাম</td>
                                <td>কোয়ান্টিটি</td>
                                <td>একক টাকা</td>
                                <td>মোট টাকা</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in carts[0].orderItem" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>
                                    <select name="tailor_id" :id="index" class="form-select shadow-none" v-model="item.tailor_id" :disabled="item.tailor_id > 0 ? true : false">
                                        <option value="0">---কারিগর নাম বাছা্ই করুন---</option>
                                        <option v-for="(tailor, sl) in tailors" :value="tailor.id" :key="sl">{{ tailor.name }}</option>
                                    </select>
                                </td>
                                <td>{{ item.product.name }}</td>
                                <td>{{ item.quantity }}</td>
                                <td>{{ item.tailor_price }}</td>
                                <td>{{ parseFloat(item.quantity * item.tailor_price).toFixed(2) }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm px-3 shadow-none" :class="item.status == 'p' ? 'btn-danger':'btn-success'">{{ item.status == 'p' ? 'Pending' : 'Processing' }}</button>
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
                                <textarea v-model="clothing.note" class="form-control shadow-none" name="note"
                                    id="note"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <button @click="saveClothing" type="button" class="btn btn-silver shadow-none px-3 me-2 mt-4">
                                Save
                            </button>
                            <button type="button" class="btn btn-silver shadow-none px-3 me-2 mt-4">
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
            clothing: {
                id: '',
                date: moment().format('YYYY-MM-DD'),
                order_id: '',
                note: '',
            },

            carts: [],

            tailors: [],
            orders: [],
        }
    },

    created() {
        this.getTailor();
        this.getOrder();
        if (this.id != '') {
            this.getClothing();
        }
    },

    methods: {
        getTailor() {
            axios.get('/get-tailor').then(res => {
                this.tailors = res.data;
            })
        },

        getOrder() {
            axios.post('/get-order', {detail: 'with'}).then(res => {
                this.orders = res.data.orders;
            })
        },

        OrderList(event){
            if(event.target.value){
                this.carts = [];
                let findIndex = this.orders.findIndex(order => order.id == event.target.value);
                let cart = this.orders[findIndex];
                this.carts.push(cart);
            }
        },

        saveClothing() {
            
            let data = {
                clothing: this.clothing,
                carts: this.carts[0].orderItem,
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
                    this.getOrder();
                })
        },

        clearClothing() {
            this.carts = [];
            this.clothing = {
                id: '',
                date: moment().format('YYYY-MM-DD'),
                order_id: 0,
                total: 0,
                paid: 0,
                due: 0,
                note: '',
            }
        },

        getClothing() {
            let data = { id: this.id }
            axios.post('/get-clothing', data)
                .then(res => {
                    let clothing = res.data.clothing[0]
                    let clothingItem = res.data.clothingItem
                    this.clothing = {
                        id: clothing.id,
                        date: clothing.date,
                        tailor_id: clothing.tailor_id,
                        total: clothing.total,
                        paid: clothing.paid,
                        due: clothing.due,
                        note: clothing.note
                    }

                    clothingItem.forEach(item => {
                        let cart = {
                            category_id: item.product.category_id,
                            product_id: item.product_id,
                            product_name: item.product.name,
                            quantity: item.quantity,
                            tailor_price: item.tailor_price,
                            total: item.total,
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