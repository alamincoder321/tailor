<template>
    <div class="row ">
        <div class="col-md-10 offset-md-1 bg-content py-4">
            <form @submit.prevent="saveData">
                <div class="row">
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="customer_code">কাস্টমার কোডঃ</label>
                                    <input type="text" name="customer_code" v-model="customer.customer_code" id="customer_code"
                                        class="form-control shadow-none" autocomplete="off" readonly />
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="name">কাস্টমার নামঃ</label>
                                    <input type="text" name="name" v-model="customer.name" id="name"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="phone">মোবাইলঃ</label>
                                    <input type="text" name="phone" v-model="customer.phone" id="phone"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="email">ইমেইলঃ</label>
                                    <input type="email" name="email" v-model="customer.email" id="email"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="address">ঠিকানাঃ</label>
                                    <textarea name="address" v-model="customer.address" id="address"
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
        <div class="col-md-12 mt-3 bg-content" v-if="customers.length > 0">
            <vue-good-table :columns="columns" :rows="customers" :fixed-header="false" :pagination-options="{
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
    props: ['id'],
    data() {
        return {
            columns: [
                { label: 'Code', field: 'customer_code' },
                { label: 'Customer Name', field: 'name' },
                { label: 'Email', field: 'email' },
                { label: 'Phone', field: 'phone' },
                { label: 'Address', field: 'address' },
                { label: "Action", field: "before" }
            ],
            customer: {
                id: '',
                customer_code: '',
                name: '',
                email: '',
                phone: '',
                address: '',
                image: '',
            },
            customers: [],

            imageSrc: "/noImage.png",
        }
    },
    created() {
        this.getCustomer();
    },
    methods: {
        saveData(event) {
            let formdata = new FormData(event.target);
            formdata.append('id', this.customer.id);
            formdata.append('image', this.customer.image);
            let url;
            if (this.customer.id == '') {
                url = '/customer'
            } else {
                url = '/update-customer'
            }
            axios.post(url, formdata).then(res => {
                if (res.data.status) {
                    this.$moshaToast(res.data.msg,);
                    this.clearData();
                    this.getCustomer();
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
            this.customer = {
                id: item.id,
                customer_code: item.customer_code,
                name: item.name,
                email: item.email,
                phone: item.phone,
                address: item.address,
                image: item.image == null ? "":item.image
            };
            this.imageSrc = item.image == null ? '/noImage.png':'/'+item.image;
        },

        deleteData(id) {
            if (confirm("Are you sure !!")) {
                axios.post('/delete-customer', { id: id }).then(res => {
                    if (res.data.status) {
                        this.$moshaToast(res.data.msg,);
                        this.clearData();
                    } else {
                        console.log(res.data.msg);
                    }
                    this.getCustomer();
                })
            }
        },

        clearData() {
            this.customer = {
                id: '',
                customer_code: this.customer.customer_code,
                name: '',
                email: '',
                phone: '',
                address: '',
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
                        this.customer.image = event.target.files[0];
                    } else {
                        alert(`This image ${img.width} X ${img.width} but require image 200 X 200`);
                    }
                }
            }
        },

        getCustomer() {
            axios.get('/get-customer').then(res => {
                let r = res.data;
                this.customers = res.data.customers
                this.customer.customer_code = r.customer_code
            })
        },
    },
}
</script>
