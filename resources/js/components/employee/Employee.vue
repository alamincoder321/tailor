<template>
    <div class="row ">
        <div class="col-md-10 offset-md-1 bg-content">
            <form @submit.prevent="saveData">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="name">এমপ্লয়ী নামঃ</label>
                                    <input type="text" name="name" v-model="employee.name" id="name"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="name">এমপ্লয়ী মোবাইলঃ</label>
                                    <input type="text" name="name" v-model="employee.name" id="name"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="name">এমপ্লয়ী ইমেইলঃ</label>
                                    <input type="text" name="name" v-model="employee.name" id="name"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="name">এমপ্লয়ী লিঙ্গ</label>
                                    <select type="text" name="name" v-model="employee.name" id="name"
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
                                    <label for="name">এমপ্লয়ী ইমেইলঃ</label>
                                    <input type="date" name="name" v-model="employee.name" id="name"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="name">এমপ্লয়ী নামঃ</label>
                                    <input type="text" name="name" v-model="employee.name" id="name"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="name">এমপ্লয়ী মোবাইলঃ</label>
                                    <input type="text" name="name" v-model="employee.name" id="name"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="name">এমপ্লয়ী ইমেইলঃ</label>
                                    <input type="text" name="name" v-model="employee.name" id="name"
                                        class="form-control shadow-none" autocomplete="off" />
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
export default {
    data() {
        return {
            employee: {
                id: '',
                name: '',
            },
            employees: [],
        }
    },
    methods: {
        getEmployee() {
            axios.get('/get-employee').then(res => {
                this.employees = res.data;
            })
        },

        saveData(event) {
            if (this.employee.name == '') {
                alert("Employee name required");
                return;
            }
            let formdata = new FormData(event.target);
            formdata.append('id', this.employee.id);
            let url;
            if (this.employee.id == '') {
                url = '/employee'
            } else {
                url = '/update-employee'
            }
            axios.post(url, formdata).then(res => {
                if (res.data.status) {
                    this.$moshaToast(res.data.msg,);
                    this.clearData();
                } else {
                    console.log(res.data.msg);
                }
            })
        },

        clearData() {
            this.employee = {
                id: '',
                name: '',
            }
        },
    },
}
</script>
