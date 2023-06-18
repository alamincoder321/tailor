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
            <vue-good-table :columns="columns" :rows="designations" :fixed-header="false" :pagination-options="{
                enabled: true,
                perPage: 10,
            }" :search-options="{ enabled: true }" :line-numbers="true" styleClass="vgt-table condensed"
                max-height="550px">
                <template #table-row="props">
                    <span class="d-flex" style="justify-content: space-around;" v-if="props.column.field == 'before'">
                        <a href="" title="edit" @click.prevent="editData(props.row)">
                            <i class="fas fa-edit text-info"></i>
                        </a><br>
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
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: res.data.msg,
                    })
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
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: res.data.msg,
                        })
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
