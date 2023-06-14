<template>
    <div class="row ">
        <div class="col-md-6 offset-md-3 bg-content">
            <form @submit.prevent="saveData">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">পদবী নামঃ</label>
                            <input type="text" name="name" v-model="designation.name" id="name"
                                class="form-control shadow-none" autocomplete="off" />
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
        <div class="col-md-6 mt-3 offset-md-3 bg-content" v-if="designations.length > 0">
            <table class="table table-hover table-sm">
                <thead>
                    <tr>
                        <th>নং</th>
                        <th>পদবী নাম</th>
                        <th class="text-center">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in designations" :key="index">
                        <td v-html="index + 1"></td>
                        <td v-html="item.name"></td>
                        <td class="text-center">
                            <a style="cursor: pointer;" class="me-1" @click="editData(item)"><i style="font-size: 16px;"
                                    class="fa fa-edit text-primary"></i></a>
                            <a style="cursor: pointer;" class="me-1" @click="deleteData(item)"><i style="font-size: 16px;"
                                    class="fa fa-trash text-danger"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- <vue-good-table :columns="columns" :rows="designations" /> -->
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            columns: [
                { label: 'Name', field: 'name' },
                { label: "Action", field: "before" }
            ],
            designation: {
                id: '',
                name: '',
            },
            designations: [],
        }
    },
    created() {
        this.getDesignation();
    },
    methods: {
        getDesignation() {
            axios.get('/get-designation').then(res => {
                this.designations = res.data;
            })
        },

        saveData(event) {
            if (this.designation.name == '') {
                alert("Designation name required");
                return;
            }
            let formdata = new FormData(event.target);
            formdata.append('id', this.designation.id);
            let url;
            if (this.designation.id == '') {
                url = '/designation'
            } else {
                url = '/update-designation'
            }
            axios.post(url, formdata).then(res => {
                if (res.data.status) {
                    this.$moshaToast(res.data.msg,);
                    this.clearData();
                } else {
                    console.log(res.data.msg);
                }
                this.getDesignation();
            })
        },

        editData(item) {
            this.designation = {
                id: item.id,
                name: item.name
            }
        },

        deleteData(id) {
            if (confirm("Are you sure !!")) {
                axios.post('/delete-designation', { id: id }).then(res => {
                    if (res.data.status) {
                        this.$moshaToast(res.data.msg,);
                        this.clearData();
                    } else {
                        console.log(res.data.msg);
                    }
                    this.getDesignation();
                })
            }
        },

        clearData() {
            this.designation = {
                id: '',
                name: '',
            }
        },
    },
}
</script>
