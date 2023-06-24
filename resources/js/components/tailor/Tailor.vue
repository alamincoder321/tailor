<template>
    <div class="row ">
        <div class="col-md-12 bg-content py-4">
            <form @submit.prevent="saveData">
                <div class="row">
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="name">নামঃ</label>
                                    <input type="text" name="name" v-model="tailor.name" id="name"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="username">ইউজার নামঃ</label>
                                    <input type="text" name="username" v-model="tailor.username" id="username"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="email">ইমেইলঃ</label>
                                    <input type="text" name="email" v-model="tailor.email" id="email"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="password">পাসওয়ার্ডঃ</label>
                                    <input type="password" name="password" v-model="tailor.password" id="password"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="mobile">মোবাইলঃ</label>
                                    <input type="mobile" name="mobile" v-model="tailor.mobile" id="mobile"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="address">ঠিকানাঃ</label>
                                    <textarea name="address" v-model="tailor.address" id="address"
                                        class="form-control shadow-none" autocomplete="off"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="experience">যোগ্যতাঃ</label>
                                    <textarea name="experience" v-model="tailor.experience" id="experience"
                                        class="form-control shadow-none" autocomplete="off"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="col-md-12 mb-2">
                            <div class="form-group ImageBackground">
                                <img :src="imageSrc" class="imageShow" />
                                <label for="image">ছবি আপলোড</label>
                                <input type="file" id="image" class="form-control shadow-none" @change="imageUrl" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-10 text-end">
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-silver shadow-none">Save</button>
                            <button type="button" class="btn btn-silver shadow-none ms-2" @click="clearData">Reset</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-12 mt-3 bg-content" v-if="tailors.length > 0">
            <vue-good-table :columns="columns" :rows="tailors" :fixed-header="false" :pagination-options="{
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
                { label: 'Name', field: 'name' },
                { label: 'User Name', field: 'username' },
                { label: 'Email', field: 'email' },
                { label: 'Mobile', field: 'mobile' },
                { label: 'Address', field: 'address' },
                { label: "Action", field: "before" }
            ],
            tailor: {
                id: '',
                name: '',
                username: '',
                email: '',
                mobile: '',
                password: '',
                address: '',
                image: '',
            },
            tailors: [],
            roles: [],
            imageSrc: "/noImage.png",
        }
    },
    created() {
        this.getTailor();
    },
    methods: {
        getTailor() {
            axios.get('/get-tailor').then(res => {
                this.tailors = res.data;
            })
        },

        saveData(event) {
            if (this.tailor.name == '') {
                alert("Tailor name required");
                return;
            }
            let formdata = new FormData(event.target);
            formdata.append('id', this.tailor.id);
            formdata.append('image', this.tailor.image);
            let url;
            if (this.tailor.id == '') {
                url = '/tailor'
            } else {
                url = '/update-tailor'
            }
            axios.post(url, formdata).then(res => {
                if (res.data.status) {
                    this.$moshaToast(res.data.msg,);
                    this.clearData();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: res.data.msg,
                    })
                }
                this.getTailor();
            })
        },

        editData(item) {
            this.tailor = {
                id: item.id,
                name: item.name,
                username: item.username,
                email: item.email,
                mobile: item.mobile,
                address: item.address,
                experience: item.experience,
                image: item.image
            };
            this.imageSrc = item.image == null ? '/noImage.png':'/'+item.image;
        },

        deleteData(id) {
            if (confirm("Are you sure !!")) {
                axios.post('/delete-tailor', { id: id }).then(res => {
                    if (res.data.status) {
                        this.$moshaToast(res.data.msg,);
                        this.clearData();
                    } else {
                        console.log(res.data.msg);
                    }
                    this.getTailor();
                })
            }
        },

        clearData() {
            this.tailor = {
                id: '',
                name: '',
                username: '',
                email: '',
                mobile: '',
                password: '',
                image: '',
            };

            this.imageSrc = '/noImage.png';
        },

        imageUrl(event) {
            if (event.target.files[0]) {
                let img = new Image()
                img.src = window.URL.createObjectURL(event.target.files[0]);
                img.onload = () => {
                    if (img.width === 200 && img.height === 200) {
                        this.imageSrc = window.URL.createObjectURL(event.target.files[0]);
                        this.tailor.image = event.target.files[0];
                    } else {
                        alert(`This image ${img.width} X ${img.width} but require image 200 X 200`);
                    }
                }
            }
        },
    },
}
</script>
