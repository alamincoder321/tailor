<template>
    <div class="row">
        <div class="col-md-10 offset-md-1 bg-content py-4">
            <form @submit.prevent="">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="product_code">অর্ডার নংঃ</label>
                                    <input type="text" name="product_code" id="product_code"
                                        class="form-control shadow-none" autocomplete="off" readonly />
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="orderDate">অর্ডার তারিখঃ</label>
                                    <input type="date" name="orderDate" id="orderDate" class="form-control shadow-none"
                                        autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="deliveryDate">ডেলিভারি তারিখঃ</label>
                                    <input type="date" name="deliveryDate" id="deliveryDate"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="customer_name">কাস্টমার নামঃ</label>
                                    <input type="text" name="customer_name" id="customer_name"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="mobile">মোবাইল নংঃ</label>
                                    <input type="text" name="mobile" id="mobile" class="form-control shadow-none"
                                        autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="refer_no">রেফার নংঃ</label>
                                    <input type="text" name="refer_no" id="refer_no" class="form-control shadow-none"
                                        autocomplete="off" />
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
                                <input type="radio" @change="onChangeCategory(item.id)" name="category_type" :id="index" class="me-2">
                                <label :for="index">{{item.name}}</label>
                            </div>
                        </div>
                    </div>
                    <hr class="m-0">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="product_name">প্রডাক্ট নামঃ</label>
                                <select name="product_name" id="product_name" class="form-select shadow-none">
                                    <option value="">---প্রডাক্ট নাম বাছাই করুন---</option>
                                    <option v-for="(item, index) in products" :key="index" :value="item.id">{{ item.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="qunatity">কোয়ান্টিটিঃ</label>
                                <input type="number" min="0" name="qunatity" id="qunatity" class="form-control shadow-none">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1 my-3"></div>
                <div class="col-md-4 bg-content">
                    de
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Swal from 'sweetalert2'

export default {
    data() {
        return {
            categories: [],
            products: [],
        }
    },
    created() {
        this.getCategory();
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
            })
        },

        onChangeCategory(catId){
            this.getProduct(catId);
        }
    },
}
</script>
