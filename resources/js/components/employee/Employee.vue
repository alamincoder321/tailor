<template>
    <div class="row ">
        <div class="col-md-10 offset-md-1 bg-content py-4">
            <form @submit.prevent="saveData">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <h4 class="m-0" style="border-bottom: 1px solid gray;">Personal Information</h4>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="name">এমপ্লয়ী নামঃ</label>
                                    <input type="text" name="name" v-model="employee.name" id="name"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="mobile">এমপ্লয়ী মোবাইলঃ</label>
                                    <input type="text" name="mobile" v-model="employee.mobile" id="mobile"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="email">এমপ্লয়ী ইমেইলঃ</label>
                                    <input type="text" name="email" v-model="employee.email" id="email"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="gender">এমপ্লয়ী লিঙ্গ</label>
                                    <select name="gender" v-model="employee.gender" id="gender"
                                        class="form-select shadow-none" autocomplete="off">
                                        <option value="">----লিঙ্গ বাছাই করুন----</option>
                                        <option value="male">পুরুষ</option>
                                        <option value="female">মহিলা</option>
                                        <option value="others">অন্যান্য</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="birth_date">জন্ম তারিখঃ</label>
                                    <input type="date" name="birth_date" v-model="employee.birth_date" id="birth_date"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <h4 class="m-0" style="border-bottom: 1px solid gray;">Company Information</h4>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="designation_id">পদবীঃ</label>
                                    <div class="input-group">
                                        <select name="designation_id" v-model="employee.designation_id" id="designation_id"
                                            class="form-select shadow-none" autocomplete="off">
                                            <option value="">---পদবী বাছাই করুন---</option>
                                            <option v-for="(item, index) in designations" :key="index" :value="item.id">{{
                                                item.name }}
                                            </option>
                                        </select>
                                        <a href="/designation" style="padding:3px 5px;" class="btn btn-danger"
                                            target="_blank"><i class="fa fa-plus text-white"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="join_date">জয়েন তারিখঃ</label>
                                    <input type="date" name="join_date" v-model="employee.join_date" id="join_date"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="salary_range">বেতনের পরিসীমাঃ</label>
                                    <input type="number" step="0.01" min="0" name="salary_range"
                                        v-model="employee.salary_range" id="salary_range" class="form-control shadow-none"
                                        autocomplete="off" />
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <div class="form-group ImageBackground">
                                    <img :src="imageSrc" class="imageShow" />
                                    <label for="image">ছবি আপলোড</label>
                                    <input type="file" id="image" class="form-control shadow-none" @change="imageUrl" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <h4 class="m-0" style="border-bottom: 1px solid gray;">Others Information</h4>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="father_name">পিতার নামঃ</label>
                                    <input type="text" name="father_name" v-model="employee.father_name" id="father_name"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="mother_name">মাতার নামঃ</label>
                                    <input type="text" name="mother_name" v-model="employee.mother_name" id="mother_name"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="present_address">বর্তমান ঠিকানাঃ</label>
                                    <textarea name="present_address" v-model="employee.present_address" id="present_address"
                                        class="form-control shadow-none" autocomplete="off"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="permanent_address">স্থায়ী ঠিকানাঃ</label>
                                    <textarea name="permanent_address" v-model="employee.permanent_address"
                                        id="permanent_address" class="form-control shadow-none"
                                        autocomplete="off"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-silver shadow-none">Save</button>
                            <button type="button" class="btn btn-silver shadow-none ms-2" @click="clearData">Reset</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import Swal from 'sweetalert2'

export default {
    props: ['id'],
    data() {
        return {
            employee: {
                id: '',
                name: '',
                mobile: '',
                email: '',
                gender: '',
                designation_id: '',
                birth_date: moment().format("YYYY-MM-DD"),
                join_date: moment().format("YYYY-MM-DD"),
                salary_range: '',
                father_name: '',
                mother_name: '',
                present_address: '',
                permanent_address: '',
                image: '',
            },
            designations: [],

            imageSrc: "/noImage.png",
        }
    },
    created() {
        this.getDesignation();
        if (this.id != '') {
            this.getEmployee();
        }
    },
    methods: {
        getDesignation() {
            axios.get('/get-designation').then(res => {
                this.designations = res.data;
            })
        },

        saveData(event) {
            if (this.employee.name == '') {
                alert("Employee name required");
                return;
            }
            let formdata = new FormData(event.target);
            formdata.append('id', this.employee.id);
            formdata.append('image', this.employee.image);
            let url;
            if (this.employee.id == '') {
                url = '/employee'
            } else {
                url = '/update-employee'
            }
            axios.post(url, formdata).then(res => {
                if (res.data.status) {
                    this.$moshaToast(res.data.msg,);
                    if (this.employee.id != '') {
                        location.href = '/manage-employee'
                    }
                    this.clearData();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: res.data.msg,
                    })
                }
            })
        },

        clearData() {
            this.employee = {
                id: '',
                name: '',
                mobile: '',
                email: '',
                gender: '',
                designation_id: '',
                birth_date: moment().format("YYYY-MM-DD"),
                join_date: moment().format("YYYY-MM-DD"),
                salary_range: '',
                father_name: '',
                mother_name: '',
                present_address: '',
                permanent_address: '',
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
                        this.employee.image = event.target.files[0];
                    } else {
                        alert(`This image ${img.width} X ${img.width} but require image 200 X 200`);
                    }
                }
            }
        },

        getEmployee() {
            axios.post('/get-employee', { id: this.id }).then(res => {
                let r = res.data;
                this.employee = {
                    id: r.id,
                    name: r.name,
                    mobile: r.mobile,
                    email: r.email,
                    gender: r.gender,
                    designation_id: r.designation_id,
                    birth_date: moment(r.birth_date).format("YYYY-MM-DD"),
                    join_date: moment(r.join_date).format("YYYY-MM-DD"),
                    salary_range: r.salary_range,
                    father_name: r.father_name,
                    mother_name: r.mother_name,
                    present_address: r.present_address,
                    permanent_address: r.permanent_address,
                    image: r.image == null ? '':r.image,
                }
                this.imageSrc = r.image == null ? '/noImage.png' : '/'+r.image;
            })
        },
    },
}
</script>
