<template>
    <div class="row ">
        <div class="col-md-8 offset-md-2 bg-content py-4">
            <form @submit.prevent="saveData">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="transaction_type">ট্রান্সজেকশন টাইপঃ</label>
                                    <select name="transaction_type" v-model="customerpayment.transaction_type"
                                        id="transaction_type" class="form-select shadow-none" autocomplete="off">
                                        <option value="CR">Receive</option>
                                        <option value="CP">Payment</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="payment_type">পেমেন্ট টাইপঃ</label>
                                    <select name="payment_type" v-model="customerpayment.payment_type" id="payment_type"
                                        class="form-select shadow-none" autocomplete="off">
                                        <option value="cash">Cash</option>
                                        <option value="bank">Bank</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="customer_id">কাস্টমার</label>
                                    <select @change="onChangeCustomer" name="customer_id"
                                        v-model="customerpayment.customer_id" id="customer_id"
                                        class="form-select shadow-none" autocomplete="off">
                                        <option value="">---কাস্টমার নাম বাছাই করুন---</option>
                                        <option v-for="(item, index) in customers" :value="item.id" :key="index">{{
                                            item.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="due">বাকিঃ</label>
                                    <input type="number" step="0.01" min="0" name="due" v-model="customerpayment.due"
                                        id="due" class="form-control shadow-none" autocomplete="off" disabled />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="date">তারিখঃ</label>
                                    <input type="date" name="date" v-model="customerpayment.date" id="date"
                                        class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="amount">পেমেন্টঃ</label>
                                    <input type="number" step="0.01" min="0" name="amount" v-model="customerpayment.amount"
                                        id="amount" class="form-control shadow-none" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="note">বর্ননাঃ</label>
                                    <textarea name="note" v-model="customerpayment.note" id="note"
                                        class="form-control shadow-none" autocomplete="off"></textarea>
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
        <div class="col-md-12 mt-3 bg-content" v-if="customerpayments.length > 0">
            <vue-good-table :columns="columns" :rows="customerpayments" :fixed-header="false" :pagination-options="{
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
import moment from 'moment';
import Swal from 'sweetalert2'

export default {
    data() {
        return {
            columns: [
                { label: 'Date', field: 'date' },
                { label: 'Customer', field: 'customer_name' },
                { label: 'TransactionType', field: 'tran_type' },
                { label: 'PaymentType', field: 'payment_type' },
                { label: 'Amount', field: 'amount' },
                { label: 'Description', field: 'note' },
                { label: "Action", field: "before" }
            ],
            customerpayment: {
                id: '',
                date: moment().format('YYYY-MM-DD'),
                transaction_type: 'CR',
                payment_type: 'cash',
                bank_id: '',
                customer_id: '',
                amount: 0,
                due: 0,
                note: '',
            },
            customerpayments: [],

            customers: [],
        }
    },
    created() {
        this.getCustomerPayment();
        this.getCustomer();
    },
    methods: {
        getCustomer() {
            axios.get('/get-customer').then(res => {
                this.customers = res.data.customers
            })
        },
        onChangeCustomer() {
            if (this.customerpayment.customer_id == "") {
                this.customerpayment.due = 0;
                return
            }
            axios.post('/get-customer-due', { id: this.customerpayment.customer_id }).then(res => {
                this.customerpayment.due = res.data[0].due;
            })
        },
        saveData(event) {
            if(parseFloat(this.customerpayment.amount) == 0 || this.customerpayment.amount == ''){
                alert('পেমেন্ট টাকা খালি রয়েছে।')
                return;
            }

            if (parseFloat(this.customerpayment.due) < parseFloat(this.customerpayment.amount)) {
                alert('কাস্টমার বাঁকি এর থেকে পেমেন্ট টাকা বেশি হয়েছে।')
                return;
            }
            let formdata = new FormData(event.target);
            formdata.append('id', this.customerpayment.id);
            formdata.append('due', this.customerpayment.due);
            let url;
            if (this.customerpayment.id == '') {
                url = '/customerpayment'
            } else {
                url = '/update-customerpayment'
            }
            axios.post(url, formdata).then(res => {
                if (res.data.status) {
                    this.$moshaToast(res.data.msg,);
                    this.clearData();
                    this.getCustomerPayment();
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
            this.customerpayment = {
                id: item.id,
                date: item.date,
                transaction_type: item.transaction_type,
                payment_type: item.payment_type,
                customer_id: item.customer_id,
                bank_id: item.bank_id,
                amount: item.amount,
                due: item.due,
                note: item.note,
            };
        },

        deleteData(id) {
            if (confirm("Are you sure !!")) {
                axios.post('/delete-customerpayment', { id: id }).then(res => {
                    if (res.data.status) {
                        this.$moshaToast(res.data.msg,);
                        this.clearData();
                    } else {
                        console.log(res.data.msg);
                    }
                    this.getCustomerPayment();
                })
            }
        },

        clearData() {
            this.customerpayment = {
                id: '',
                date: moment().format('YYYY-MM-DD'),
                transaction_type: 'CR',
                payment_type: 'cash',
                bank_id: '',
                customer_id: '',
                amount: 0,
                due: 0,
                note: '',
            };
        },

        getCustomerPayment() {
            axios.get('/get-customerpayment').then(res => {
                this.customerpayments = res.data.map(cus => {
                    cus.tran_type = cus.transaction_type == 'CR' ? "Received" : "Payment"
                    cus.customer_name = cus.customer.name
                    return cus;
                })
            })
        },
    },
}
</script>
