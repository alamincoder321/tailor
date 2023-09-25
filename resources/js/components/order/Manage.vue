<template>
    <div class="row ">
        <div class="col-md-12 p-md-0">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-2">
                            <select class="form-control shadow-none" v-model="searchBy">
                                <option value="">All</option>
                                <option value="customer">By Customer</option>
                            </select>
                        </div>
                        <div class="col-md-3" :class="searchBy == 'customer' ? '' : 'd-none'">
                            <v-select :options="customers" v-model="selectCustomer" label="name" id="customer"></v-select>
                        </div>

                        <div class="col-md-2">
                            <select class="form-control shadow-none" v-model="detail" @change="DetailChange">
                                <option value="without">Without Details</option>
                                <option value="with">With Details</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control shadow-none" v-model="dateFrom">
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control shadow-none" v-model="dateTo">
                        </div>
                        <div class="col-md-2 p-0">
                            <button type="button" class="btn btn-silver" @click="getOrder">Search</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-12 bg-content">
            <div class="text-end" :class="orders.length > 0 ? '' : 'd-none'">
                <a href="" @click.prevent="print"><i class="fa fa-print"></i></a>
            </div>
            <div class="orderDaTa" :class="orders.length > 0 ? '' : 'd-none'">
                <table class="table table-striped table-sm" v-if="showDetail == false"
                    :class="showDetail == false ? '' : 'd-none'">
                    <thead class="text-white bg-dark">
                        <tr>
                            <th>Sl</th>
                            <th>Invoice</th>
                            <th>Order Date</th>
                            <th>Delivery Date</th>
                            <th>Customer</th>
                            <th>SubTotal</th>
                            <th>Discount</th>
                            <th>Total</th>
                            <th>Advance</th>
                            <th>Due</th>
                            <th>Refer</th>
                            <th class="text-center action">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in orders" :key="index">
                            <td>{{ index + 1 }}</td>
                            <td>{{ item.order_code }}</td>
                            <td>{{ item.orderDate }}</td>
                            <td>{{ item.deliveryDate }}</td>
                            <td>{{ item.name }}</td>
                            <td>{{ item.subtotal }}</td>
                            <td>{{ item.discount }}</td>
                            <td>{{ item.total }}</td>
                            <td>{{ item.advance }}</td>
                            <td>{{ item.due }}</td>
                            <td>{{ item.refer }}</td>
                            <td style="width: 10%;text-align: center;" class="action">
                                <a :href="`/order-invoice/${item.id}`"><i style="font-size: 18px;"
                                        class="fa fa-file"></i></a>
                                <a :href="`/order/${item.id}`"><i style="font-size: 18px;" class="fa fa-edit mx-2"></i></a>
                                <a href="#" @click.prevent="deleteData(item.id)"><i style="font-size: 18px;"
                                        class="fa fa-trash text-danger"></i></a>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5">Total</th>
                            <th>{{ orders.reduce((acc, pre) => { return acc + +parseFloat(pre.subtotal) }, 0).toFixed(2) }}
                            </th>
                            <th>{{ orders.reduce((acc, pre) => { return acc + +parseFloat(pre.discount) }, 0).toFixed(2) }}
                            </th>
                            <th>{{ orders.reduce((acc, pre) => { return acc + +parseFloat(pre.total) }, 0).toFixed(2) }}
                            </th>
                            <th>{{ orders.reduce((acc, pre) => { return acc + +parseFloat(pre.advance) }, 0).toFixed(2) }}
                            </th>
                            <th>{{ orders.reduce((acc, pre) => { return acc + +parseFloat(pre.due) }, 0).toFixed(2) }}</th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table>

                <table v-else class="table table-striped table-sm" :class="this.showDetail ? '' : 'd-none'">
                    <thead class="text-white bg-dark">
                        <tr>
                            <th>Sl</th>
                            <th>Invoice</th>
                            <th>Order Date</th>
                            <th>Delivery Date</th>
                            <th>Customer</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                            <th class="text-center action">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(order, index) in orders">
                            <tr>
                                <td>{{ index + 1 }}</td>
                                <td>{{ order.order_code }}</td>
                                <td>{{ order.orderDate }}</td>
                                <td>{{ order.deliveryDate }}</td>
                                <td>{{ order.name }}</td>
                                <td>{{ order.orderItem[0].product.name }}</td>
                                <td>{{ order.orderItem[0].product.category.name }}</td>
                                <td>{{ order.orderItem[0].quantity }}</td>
                                <td>{{ order.orderItem[0].retail_price }}</td>
                                <td>{{ order.orderItem[0].total }}</td>
                                <td style="width: 10%;text-align: center;" class="action">
                                    <a :href="`/order-invoice/${order.id}`"><i style="font-size: 18px;"
                                            class="fa fa-file"></i></a>
                                    <a :href="`/order/${order.id}`"><i style="font-size: 18px;"
                                            class="fa fa-edit mx-2"></i></a>
                                    <a href="#" @click.prevent="deleteData(order.id)"><i style="font-size: 18px;"
                                            class="fa fa-trash text-danger"></i></a>
                                </td>
                            </tr>
                            <tr v-for="(item, sl) in order.orderItem.slice(1)" :key="sl">
                                <td colspan="5" :rowspan="order.orderItem.length - 1" v-if="sl == 0"></td>
                                <td>{{ item.product.name }}</td>
                                <td>{{ item.product.category.name }}</td>
                                <td>{{ item.quantity }}</td>
                                <td>{{ item.retail_price }}</td>
                                <td>{{ item.total }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="9" style="border-right: 1px solid gray;">
                                    <strong>Refer:</strong> {{ order.refer }}
                                </td>
                                <td colspan="2" class="text-end">
                                    <strong>SubTotal:</strong> {{ order.subtotal }}<br>
                                    <strong>Discount:</strong> {{ order.discount }}<br>
                                    <strong>Total:</strong> {{ order.total }}<br>
                                    <strong>Advance:</strong> {{ order.advance }}<br>
                                    <strong>Due</strong> {{ order.due }}
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5">Total</th>
                            <th>{{ orders.reduce((acc, pre) => { return acc + +parseFloat(pre.subtotal) }, 0).toFixed(2) }}
                            </th>
                            <th>{{ orders.reduce((acc, pre) => { return acc + +parseFloat(pre.discount) }, 0).toFixed(2) }}
                            </th>
                            <th>{{ orders.reduce((acc, pre) => { return acc + +parseFloat(pre.total) }, 0).toFixed(2) }}
                            </th>
                            <th>{{ orders.reduce((acc, pre) => { return acc + +parseFloat(pre.advance) }, 0).toFixed(2) }}
                            </th>
                            <th>{{ orders.reduce((acc, pre) => { return acc + +parseFloat(pre.due) }, 0).toFixed(2) }}</th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="text-center" :class="orders.length == 0 ? '' : 'd-none'">Data Not Found</div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';

export default {
    data() {
        return {
            searchBy: '',
            detail: 'without',
            dateFrom: moment().format('YYYY-MM-DD'),
            dateTo: moment().format('YYYY-MM-DD'),
            customers: [],
            selectCustomer: null,
            orders: [],
            showDetail: false,
        }
    },
    created() {
        this.getOrder();
        this.getCustomer();
    },
    methods: {
        DetailChange() {
            this.orders = [];
            if (this.detail == 'with') {
                this.showDetail = true;
            } else {
                this.showDetail = false;
            }
        },
        getCustomer() {
            axios.get('/get-customer').then(res => {
                let r = res.data;
                this.customers = res.data.customers
            })
        },
        getOrder() {
            let data = {
                dateFrom: this.dateFrom,
                dateTo: this.dateTo,
                detail: this.detail,
                customerId: this.searchBy == 'customer' ? this.selectCustomer.id : ''

            }
            axios.post('/get-order', data).then(res => {
                this.orders = res.data.orders;
            })
        },

        deleteData(id) {
            if (confirm("Are you sure !!")) {
                axios.post('/delete-order', { id: id }).then(res => {
                    if (res.data.status) {
                        this.$moshaToast(res.data.msg,);
                    } else {
                        console.log(res.data.msg);
                    }
                    this.getEmployee();
                })
            }
        },

        async print() {
            var myWindow = window.open('', '', `width=${window.screen.width},height=${window.screen.height}`);
            myWindow.document.write(`
                    <!doctype html>
                    <html lang="en">
                        <head>
                            <meta charset="utf-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1">
                            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
                            <title>Order Record</title>
                            <style>
                                @media print{
                                    .action{
                                        display:none;
                                    }
                                    body{
                                        font-size: 13px !important;
                                    }
                                }
                            </style>
                        </head>
                        <body>
                            <div class="container-fluid">
                                <h3 class="m-0 text-center bg-primary text-white text-uppercase">Order Records ${this.searchBy == 'customer' ? 'of ' + this.selectCustomer.name : ''}</h3>
                                <div class="row">
                                    <div class="col-12">
                                        ${document.querySelector('.orderDaTa').innerHTML}
                                    </div>
                                </div>
                            </div>
                        </body>
                    </html>
            `);
            myWindow.focus();
            await new Promise(resolve => setTimeout(resolve, 500));
            myWindow.print();
            myWindow.close();
        },
    },
}
</script>

<style>
#customer #vs1__combobox {
    padding: 0;
    height: 32px;
}

#customer .vs__search {
    margin: 0 !important;
}

#customer .vs__actions {
    padding: 0 2px;
}

#customer .vs__clear {
    margin: 0;
    padding: 0px 8px !important;
}

#customer .vs__selected {
    margin: 0 !important;
    padding: 0 !important;
}
</style>
