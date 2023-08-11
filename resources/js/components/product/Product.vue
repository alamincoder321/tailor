<template>
    <div class="row ">
        <div class="col-md-10 offset-md-1 bg-content py-4">
            <form @submit.prevent="saveData">
                <div class="row">
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="product_code">পণ্যের কোডঃ</label>
                                    <input type="text" name="product_code" v-model="product.product_code" id="product_code"
                                        class="form-control shadow-none" autocomplete="off" readonly />
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="category_id">ক্যাটাগরি নাম</label>
                                    <div class="input-group">
                                        <select name="category_id" v-model="product.category_id" id="category_id"
                                            class="form-select shadow-none" autocomplete="off">
                                            <option value="">----ক্যাটাগরি বাছাই করুন----</option>
                                            <option v-for="(item, index) in categories" :key="index" :value="item.id">{{
                                                item.name }}</option>
                                        </select>
                                        <a href="/category" style="padding:3px 5px;" class="btn btn-danger"
                                            target="_blank"><i class="fa fa-plus text-white"></i></a>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="name">পণ্যের নামঃ</label>
                                    <input type="text" name="name" v-model="product.name" id="name"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="discount">ডিস্কাউন্টঃ</label>
                                    <input type="number" step="0.01" min="0" name="discount" v-model="product.discount"
                                        id="discount" class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="brand_id">ব্র্যান্ডঃ</label>
                                    <div class="input-group">
                                        <select name="brand_id" v-model="product.brand_id" id="brand_id"
                                            class="form-select shadow-none" autocomplete="off">
                                            <option value="">--- ব্র্যান্ড বাছাই করুন---</option>
                                            <option v-for="(item, index) in brands" :key="index" :value="item.id">{{
                                                item.name }}
                                            </option>
                                        </select>
                                        <a href="/brand" style="padding:3px 5px;" class="btn btn-danger" target="_blank"><i
                                                class="fa fa-plus text-white"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="tailor_price">কারিগর মূল্যঃ</label>
                                    <input type="number" step="0.01" min="0" name="tailor_price"
                                        v-model="product.tailor_price" id="tailor_price" class="form-control shadow-none"
                                        autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="retail_price">খুচরা মূল্যঃ</label>
                                    <input type="number" step="0.01" min="0" name="retail_price"
                                        v-model="product.retail_price" id="retail_price" class="form-control shadow-none"
                                        autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="description">বর্ননাঃ</label>
                                    <textarea name="description" v-model="product.description" id="description"
                                        class="form-control shadow-none" autocomplete="off"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <div class="form-group ImageBackground">
                                    <img :src="imageSrc" class="imageShow" />
                                    <label for="image">ছবি আপলোড</label>
                                    <input type="file" id="image" class="form-control shadow-none" @change="imageUrl" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <div class="form-group mt-4 text-end">
                            <button type="submit" class="btn btn-silver shadow-none">Save</button>
                            <button type="button" class="btn btn-silver shadow-none ms-2" @click="clearData">Reset</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-12 mt-3 bg-content" v-if="products.length > 0">
            <vue-good-table :columns="columns" :rows="products" :fixed-header="false" :pagination-options="{
                enabled: true,
                perPage: 10,
            }" :search-options="{ enabled: true }" :line-numbers="true" styleClass="vgt-table condensed"
                max-height="550px">
                <template #table-row="props">
                    <span class="d-flex" style="justify-content: space-around;" v-if="props.column.field == 'before'">
                        <a href="" title="edit" @click.prevent="editData(props.row)">
                            <i class="fas fa-edit text-info"></i>
                        </a>
                        <a href="" title="delete" @click.prevent="deleteData(props.row.id)">
                            <i class="fas fa-trash text-danger"></i>
                        </a>
                    </span>
                </template>
            </vue-good-table>
        </div>
    </div>
</template>

<script>
import Swal from 'sweetalert2'

export default {
    data() {
        return {
            columns: [
                { label: 'Code', field: 'product_code' },
                { label: 'Product Name', field: 'name' },
                { label: 'Category', field: 'category_name' },
                { label: 'Brand', field: 'brand_name' },
                { label: 'Discount', field: 'discount' },
                { label: 'Tailor_Price', field: 'tailor_price' },
                { label: 'Retail_Price', field: 'retail_price' },
                { label: "Action", field: "before" }
            ],
            product: {
                id: '',
                product_code: '',
                name: '',
                description: '',
                category_id: '',
                tailor_price: 0.00,
                retail_price: 0.00,
                discount: 0.00,
                brand_id: '',
                image: '',
            },
            categories: [],
            brands: [],

            products: [],

            imageSrc: "/noImage.png",
        }
    },
    created() {
        this.getCategory();
        this.getBrand();
        this.getProduct();
    },
    methods: {
        getCategory() {
            axios.get('/get-category').then(res => {
                this.categories = res.data;
            })
        },
        getBrand() {
            axios.get('/get-brand').then(res => {
                this.brands = res.data;
            })
        },

        saveData(event) {
            if (this.product.name == '') {
                alert("Product name required");
                return;
            }
            let formdata = new FormData(event.target);
            formdata.append('id', this.product.id);
            formdata.append('image', this.product.image);
            let url;
            if (this.product.id == '') {
                url = '/product'
            } else {
                url = '/update-product'
            }
            axios.post(url, formdata).then(res => {
                if (res.data.status) {
                    this.$moshaToast(res.data.msg,);
                    this.clearData();
                    this.getProduct();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: res.data.msg,
                    })
                }
            })
        },

        editData(item) {
            this.product = {
                id: item.id,
                product_code: item.product_code,
                name: item.name,
                category_id: item.category_id,
                discount: item.discount,
                description: item.description,
                brand_id: item.brand_id == null ? '' : item.brand_id,
                retail_price: item.retail_price,
                tailor_price: item.tailor_price,
                image: item.image == null ? "" : item.image
            };
            this.imageSrc = item.image == null ? '/noImage.png' : '/' + item.image;
        },

        deleteData(id) {
            if (confirm("Are you sure !!")) {
                axios.post('/delete-product', { id: id }).then(res => {
                    if (res.data.status) {
                        this.$moshaToast(res.data.msg,);
                        this.clearData();
                    } else {
                        console.log(res.data.msg);
                    }
                    this.getProduct();
                })
            }
        },

        clearData() {
            this.product = {
                id: '',
                product_code: this.product.product_code,
                name: '',
                description: '',
                category_id: '',
                tailor_price: 0.00,
                retail_price: 0.00,
                discount: 0.00,
                brand_id: '',
                image: '',
            };
            this.imageSrc = "/noImage.png"
        },

        imageUrl(event) {
            if (event.target.files[0]) {
                let img = new Image()
                img.src = window.URL.createObjectURL(event.target.files[0]);
                img.onload = () => {
                    if (img.width === 200 && img.height === 200) {
                        this.imageSrc = window.URL.createObjectURL(event.target.files[0]);
                        this.product.image = event.target.files[0];
                    } else {
                        alert(`This image ${img.width} X ${img.width} but require image 200 X 200`);
                    }
                }
            }
        },

        getProduct() {
            axios.get('/get-product').then(res => {
                let r = res.data;
                this.products = res.data.products.map(p => {
                    p.category_name = p.category.name
                    p.brand_name = p.brand == null ? '' : p.brand.name
                    return p;
                });
                this.product.product_code = r.product_code
            })
        },
    },
}
</script>
