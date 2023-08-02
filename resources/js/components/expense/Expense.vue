<template>
    <div class="row ">
        <div class="col-md-8 offset-md-2 bg-content py-4">
            <form @submit.prevent="saveData">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="expense_code">খরচের কোডঃ</label>
                                    <input type="text" name="expense_code" v-model="expense.expense_code" id="expense_code"
                                        class="form-control shadow-none" autocomplete="off" readonly />
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="date">তারিখঃ</label>
                                    <input type="date" name="date" v-model="expense.date" id="date"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="description">বর্ননাঃ</label>
                                    <textarea name="description" v-model="expense.description" id="description"
                                        class="form-control shadow-none" autocomplete="off"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="amount">খরচঃ</label>
                                    <input type="number" step="0.01" min="0" name="amount" v-model="expense.amount" id="amount"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mt-4 text-end">
                            <button type="submit" class="btn btn-silver shadow-none">Save</button>
                            <button type="button" class="btn btn-silver shadow-none ms-2" @click="clearData">Reset</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-12 mt-3 bg-content" v-if="expenses.length > 0">
            <vue-good-table :columns="columns" :rows="expenses" :fixed-header="false" :pagination-options="{
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
                { label: 'Code', field: 'expense_code' },
                { label: 'Date', field: 'date' },
                { label: 'Description', field: 'description' },
                { label: 'Amount', field: 'amount' },
                { label: "Action", field: "before" }
            ],
            expense: {
                id: '',
                expense_code: '',
                date: '',
                description: '',
                amount: '',
            },
            expenses: [],
        }
    },
    created() {
        this.getExpense();
    },
    methods: {
        saveData(event) {
            let formdata = new FormData(event.target);
            formdata.append('id', this.expense.id);
            let url;
            if (this.expense.id == '') {
                url = '/expense'
            } else {
                url = '/update-expense'
            }
            axios.post(url, formdata).then(res => {
                if (res.data.status) {
                    this.$moshaToast(res.data.msg,);
                    this.clearData();
                    this.getExpense();
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
            this.expense = {
                id: item.id,
                expense_code: item.expense_code,
                date: item.date,
                description: item.description,
                amount: item.amount,
            };
        },

        deleteData(id) {
            if (confirm("Are you sure !!")) {
                axios.post('/delete-expense', { id: id }).then(res => {
                    if (res.data.status) {
                        this.$moshaToast(res.data.msg,);
                        this.clearData();
                    } else {
                        console.log(res.data.msg);
                    }
                    this.getExpense();
                })
            }
        },

        clearData() {
            this.expense = {
                id: '',
                expense_code: this.expense.expense_code,
                date: '',
                description: '',
                amount: '',
            };
        },

        getExpense() {
            axios.get('/get-expense').then(res => {
                let r = res.data;
                this.expenses = res.data.expenses
                this.expense.expense_code = r.expense_code
            })
        },
    },
}
</script>
