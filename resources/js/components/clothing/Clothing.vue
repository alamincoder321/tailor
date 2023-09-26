<template>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <form @submit.prevent="getOrder">
                        <div class="row">
                            <div class="col-6 col-md-3">
                                <div class="form-group m-0">
                                    <v-select id="customers" :options="customers" v-model="selectedCustomer" label="name" placeholder="Select Customer"></v-select>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="form-group m-0">
                                    <input type="date" class="form-control" v-model="filter.dateFrom" />
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="form-group m-0">
                                    <input type="date" class="form-control" v-model="filter.dateTo" />
                                </div>
                            </div>
                            <div class="col-6 col-md-1">
                                <div class="form-group m-0">
                                    <button type="submit" class="btn btn-info btn-sm shadow-none px-3">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body" style="overflow-x: auto;" :style="{ display: orders.length > 0 ? '' : 'none' }">
                    <table class="table table-bordered m-0" v-if="recordType == 'with'" style="display:none"
                        :style="{ display: recordType == 'with' ? '' : 'none' }">
                        <thead style="background: #59d9ff">
                            <tr>
                                <th style="text-align: center; width: 8%;color:white;"> Sl </th>
                                <th style="text-align: center; width: 10%;color:white;"> #Invoice </th>
                                <th style="text-align: center;color:white;width: 12%;"> Date </th>
                                <th style="text-align: center;color:white;"> Customer </th>
                                <th style="text-align: center;color:white;">Status</th>
                                <th style="text-align: center;color:white;">Product</th>
                                <th style="text-align: center;color:white;">Quantity</th>
                                <th style="text-align: center;color:white;">Price</th>
                                <th style="text-align: center;color:white;">Total</th>
                                <th style="text-align: center;color:white;">Tailor</th>
                                <th style="text-align: center;color:white;">Status</th>
                                <th style="text-align: center; width: 5%;color:white;"> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="(item, index) in orders">
                                <tr>
                                    <td class="text-center" style="width: 5%"> {{ index + 1 }} </td>
                                    <td class="text-center"> {{ item.order_code }} </td>
                                    <td class="text-center"> {{ formatDate(item.orderDate) }} </td>
                                    <td class="text-center"> {{ item.name }} </td>
                                    <td class="text-center text-capitalize" v-html="statusText(item.status)"></td>
                                    <td class="text-center">{{ item.orderItem[0].product.name }}</td>
                                    <td class="text-center">{{ item.orderItem[0].quantity }}</td>
                                    <td class="text-center">{{ item.orderItem[0].tailor_price }}</td>
                                    <td class="text-center">{{ parseFloat(item.orderItem[0].tailor_price * item.orderItem[0].quantity).toFixed(2)}}</td>
                                    <td class="text-center">{{ item.orderItem[0].tailor ? item.orderItem[0].tailor.name : 'N/A' }}</td>
                                    <td class="text-center" v-html="statusText(item.orderItem[0].status)"></td>
                                    <td>
                                        <button v-if="item.orderItem[0].status != 'complete'"
                                            @click="modalShow(item.orderItem[0])" type="button"
                                            class="btn btn-danger btn-sm shadow-none fas fa-user"></button>
                                    </td>
                                </tr>
                                <tr v-for="(product, sl) in item.orderItem.slice(1)">
                                    <td colspan="5" :rowspan="item.orderItem.length - 1" v-if="sl == 0"></td>
                                    <td class="text-center">{{ product.name }}</td>
                                    <td class="text-center">{{ product.quantity }}</td>
                                    <td class="text-center">{{ product.tailor_price }}</td>
                                    <td class="text-center">{{ parseFloat(product.tailor_price * product.quantity).toFixed(2)}}</td>
                                    <td class="text-center">{{ product.tailor ? product.tailor.name : 'N/A' }}</td>
                                    <td class="text-center" v-html="statusText(product.status)"></td>
                                    <td>
                                        <button v-if="product.status != 'complete'"
                                            @click="modalShow(product)" type="button"
                                            class="btn btn-danger btn-sm shadow-none fas fa-user"></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="13">
                                        <div class="devider"></div>
                                    </td>
                                </tr>
                                <!-- <tr style="font-weight:bold;">
                                    <td colspan="6" style="font-weight:normal;"><strong>Note: </strong>{{ item.note }}</td>
                                    <td style="text-align:center;">Total Quantity<br>{{ item.orderItem.reduce((prev,
                                        curr) => { return prev + parseFloat(curr.quantity) }, 0) }}</td>
                                    <td style="text-align:left;">
                                        Total: 0<br>
                                        Paid: 0<br>
                                    </td>
                                </tr> -->
                            </template>
                        </tbody>
                    </table>
                </div>
                <div class="card-body" :style="{ display: orders.length > 0 ? 'none' : '' }">
                    <p class="m-0 text-center">Not Found Data in Table</p>
                </div>
            </div>
        </div>


        <!-- modal for assign order -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header d-flex justify-content-between">
                        <h5 class="modal-title" id="staticBackdropLabel">Order Assign On Tailor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <!-- {{ modalData }} -->
                        <table class="table table-bordered m-0">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>{{ modalData.product ? modalData.product.name : '' }}</td>
                                    <td>{{ modalData.quantity }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group mt-3">
                            <label for="tailors">Worker</label>
                            <v-select id="tailors" :options="tailors" v-model="selectedTailor" label="name"  placeholder="Select Tailor"></v-select>
                        </div>
                        <div class="form-group mt-3">
                            <button @click="assignTailor" type="button" class="btn btn-info shadow-none w-100">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
export default {
    data() {
        return {
            searchBy: "",
            recordType: 'with',
            filter: {
                dateFrom: "",
                dateTo: "",
            },
            orders: [],
            customers: [],
            selectedCustomer: null,
            tailors: [],
            selectedTailor: null,

            modalData: {},
        };
    },

    created() {
        this.getCustomer();
        this.getOrder();
    },

    methods: {
        statusText(status) {
            let texT = "";
            if (status == 'pending') {
                texT = "<span class='badge bg-danger'>Pending</span>"
            }
            if (status == 'proccess') {
                texT = "<span class='badge bg-warning'>Proccessing</span>"
            }
            if (status == 'complete') {
                texT = "<span class='badge bg-success'>Completed</span>"
            }
            return texT;
        },

        getCustomer() {
            axios.get("/get-customer").then((res) => {
                this.customers = res.data.customers
            });
        },
        getTailor() {
            axios.get("/get-tailor").then((res) => {
                this.tailors = res.data
            });
        },
        getOrder() {
            axios.post("/get-order", this.filter).then((res) => {
                this.orders = res.data.orders.filter(order => order.status != 'cancel');
            });
        },

        modalShow(product) {
            $('#staticBackdrop').modal('show');
            this.getTailor();
            this.modalData = product;
            this.selectedTailor = null;
            if (product.tailor_id) {
                this.selectedTailor = {
                    id: product.tailor_id,
                    name: product.tailor.name
                }
            }
        },

        assignTailor() {
            let filter = {
                id: this.modalData.id,
                tailor_id: this.selectedTailor == null ? null : this.selectedTailor.id
            }
            axios.post("/clothing", filter).then((res) => {
                this.$moshaToast(res.data.msg);
                this.getOrder();
                this.selectedTailor = null;
                $('#staticBackdrop').modal('hide');
            });
        },

        formatDate(date) {
            return moment(date).format("DD-MM-YYYY");
        },
    },
};
</script>

<style>
#thanas [role="combobox"] {
    padding: 0 !important;
}

.devider {
    width: 15px;
    height: 10px;
    border: 1px solid #59d9ff;
    margin: 0 auto;
    border-top-left-radius: 50px;
    border-bottom-right-radius: 50px;
}

.devider::before {
    content: "";
    border: 1px dashed #59d9ff;
    position: absolute;
    left: 23px;
    width: 450px;
    height: 8px;
}

.devider::after {
    content: "";
    border: 1px dashed #59d9ff;
    position: absolute;
    right: 23px;
    width: 450px;
    height: 8px;
}</style>