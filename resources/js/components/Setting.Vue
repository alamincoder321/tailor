<template>
    <div class="row ">
        <div class="col-md-8 offset-md-2 bg-content">
            <form @submit.prevent="saveData">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label for="company_name">কোম্পানি নামঃ</label>
                            <input type="text" name="company_name" v-model="setting.company_name" id="company_name"
                                class="form-control shadow-none" autocomplete="off" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label for="mobile">মোবাইলঃ</label>
                            <input type="text" name="mobile" v-model="setting.mobile" id="mobile"
                                class="form-control shadow-none" autocomplete="off" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label for="email">ইমেইলঃ</label>
                            <input type="text" name="email" v-model="setting.email" id="email"
                                class="form-control shadow-none" autocomplete="off" />
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label for="address">অ্যাড্রেসঃ</label>
                            <textarea name="address" v-model="setting.address" id="address" class="form-control shadow-none"
                                autocomplete="off"> </textarea>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6 col-6">
                                <div class="form-group ImageBackground">
                                    <img :src="imageSrc" class="imageShow" />
                                    <label for="logo">লোগো</label>
                                    <input type="file" id="logo" class="form-control shadow-none" @change="imageUrl" />
                                </div>
                            </div>
                            <div class="col-md-6 col-6">
                                <div class="form-group ImageBackground">
                                    <img :src="imageSrcTwo" class="imageShow" />
                                    <label for="navicon">ন্যাভ আইকোন</label>
                                    <input type="file" id="navicon" class="form-control shadow-none"
                                        @change="imageUrlTwo" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-silver shadow-none px-3">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import Swal from 'sweetalert2'
export default {
    data() {
        return {
            setting: {
                company_name: '',
                mobile: '',
                email: '',
                address: '',
                logo: '',
                navicon: '',
            },
            imageSrc: "/noImage.png",
            imageSrcTwo: "/noImage.png",
        }
    },
    created() {
        this.getSetting()
    },
    methods: {
        getSetting() {
            axios.get('/get-setting').then(res => {
                let r = res.data;
                this.setting = {
                    company_name: r.company_name,
                    mobile: r.mobile,
                    email: r.email,
                    address: r.address,
                    logo: r.logo,
                    navicon: r.navicon,
                }
                this.imageSrc = r.logo != null ? r.logo : "/noImage.png"
                this.imageSrcTwo = r.navicon != null ? r.navicon : "/noImage.png"
            })
        },

        saveData(event) {
            if (this.setting.company_name == '') {
                alert("Company name required");
                return;
            }
            let formdata = new FormData(event.target);
            formdata.append('logo', this.setting.logo);
            formdata.append('navicon', this.setting.navicon);
            axios.post('/setting', formdata).then(res => {
                if (res.data.status) {
                    this.$moshaToast(res.data.msg,);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: res.data.msg,
                    })
                }
                this.getSetting();
            })
        },

        imageUrl(event) {
            if (event.target.files[0]) {
                let img = new Image()
                img.src = window.URL.createObjectURL(event.target.files[0]);
                img.onload = () => {
                    if (img.width === 200 && img.height === 200) {
                        this.imageSrc = window.URL.createObjectURL(event.target.files[0]);
                        this.setting.logo = event.target.files[0];
                    } else {
                        alert(`This image ${img.width} X ${img.width} but require image 200 X 200`);
                    }
                }
            }
        },

        imageUrlTwo(event) {
            if (event.target.files[0]) {
                let img = new Image()
                img.src = window.URL.createObjectURL(event.target.files[0]);
                img.onload = () => {
                    if (img.width === 200 && img.height === 200) {
                        this.imageSrcTwo = window.URL.createObjectURL(event.target.files[0]);
                        this.setting.navicon = event.target.files[0];
                    } else {
                        alert(`This image ${img.width} X ${img.width} but require image 200 X 200`);
                    }
                }
            }
        },
    },
}
</script>
