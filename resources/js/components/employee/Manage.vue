<template>
    <div class="row ">
        <div class="col-md-12 bg-content">
            <vue-good-table :columns="columns" :rows="employees" :fixed-header="false" :pagination-options="{
                enabled: true,
                perPage: 10,
            }" :search-options="{ enabled: true }" :line-numbers="true" styleClass="vgt-table condensed"
                max-height="550px">
                <template #table-row="props">
                    <span class="d-flex" style="justify-content: space-around;" v-if="props.column.field == 'before'">
                        <a title="edit" :href="`employee/${props.row.id}`">
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
export default {
    data() {
        return {
            columns: [
                { label: 'Name', field: 'name' },
                { label: 'Mobile', field: 'mobile' },
                { label: 'Email', field: 'email' },
                { label: 'Gender', field: 'gender' },
                { label: 'DOB', field: 'birth_date' },
                { label: 'Salary', field: 'salary_range' },
                { label: 'Join Date', field: 'join_date' },
                { label: 'Father', field: 'father_name' },
                { label: 'Mother', field: 'mother_name' },
                { label: "Action", field: "before" }
            ],
            designation: {
                id: '',
                name: '',
            },
            employees: [],
        }
    },
    created() {
        this.getEmployee();
    },
    methods: {
        getEmployee() {
            axios.post('/get-employee', {id: ''}).then(res => {
                this.employees = res.data;
            })
        },

        deleteData(id) {
            if (confirm("Are you sure !!")) {
                axios.post('/delete-employee', { id: id }).then(res => {
                    if (res.data.status) {
                        this.$moshaToast(res.data.msg,);
                    } else {
                        console.log(res.data.msg);
                    }
                    this.getEmployee();
                })
            }
        },
    },
}
</script>
