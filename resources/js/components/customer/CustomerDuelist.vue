<style>
#customers [role='combobox'] {
    height: 32px !important;
}

#customers .vs__clear {
    margin-right: 0;
    padding: 0px 8px !important;
}
</style>

<template>
    <div class="row">
        <div class="col-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <form @submit.prevent="getCustomerDue">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group m-0">
                                    <v-select :options="customers" id="customers" v-model="selectedCustomer"
                                        label="name" placeholder="কাস্টমার বাছাই করুন"></v-select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group m-0">
                                    <button type="submit" class="btn btn-info btn-sm text-white shadow-none px-3">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body" style="overflow-x:auto;" v-if="customerdues.length > 0">
                    <button class="btn btn-warning text-white btn-sm shadow-none" @click="print" v-if="customerdues.length > 0"><i
                            class="bi bi-printer" style="font-size: 15px;"></i> Print</button>
                    <table class="table table-sm table-hover table-bordered" id="customerdues">
                        <thead style="background: #897800;">
                            <tr class="text-center text-white" style="font-size: 12px;">
                                <th>Sl</th>
                                <th>Customer Code</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th class="text-end">Due</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in customerdues" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ item.customer_code }}</td>
                                <td>{{ item.name }}</td>
                                <td>{{ item.address }}</td>
                                <td class="text-end">{{ item.due }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4">Total</th>
                                <th class="text-end">{{ customerdues.reduce((acc, pre) => {return acc + +parseFloat(pre.due)}, 0).toFixed(2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-body text-center" v-else>
                    Not found data in Table
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
            customers: [],
            selectedCustomer: null,
            customerdues: [],
            previousDue: 0,
            company: {},
        };
    },
    created() {
        this.getCustomer();
        this.getCompany();
    },
    methods: {
        getCompany() {
            axios.get("/get-setting").then((res) => {
                this.company = res.data;
            });
        },
        getCustomer() {
            axios.get("/get-customer").then((res) => {
                this.customers = res.data.customers;
            });
        },
        getCustomerDue() {
            let data = {
                id: this.selectedCustomer != null ? this.selectedCustomer.id : "",
            };

            axios.post("/get-customer-due", data).then((res) => {
                this.customerdues = res.data
            });
        },

        formatDate(date) {
            return moment(date).format("DD-MM-YYYY");
        },

        async print() {
            var myWindow = window.open('', '', `width=${window.screen.width},height=${window.screen.height}`);
            myWindow.document.write(`
                     <html>
                         <head>
                             <title>Customer Ledger</title>
                             <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
                             <style> 
                                 h2 {
                                     color: #939393 !important;
                                 }    
                                 @media print {
                                     *{
                                         margin:0 auto;
                                     }
                                     .table thead {
                                         background: #897800 !important;
                                         color: white !important;
                                     }
                                     #customers{
                                         page-break-inside:avoid;
                                         page-break-after:always;
                                     }
                                 }
                             </style>
                         </head>
                         <body>
                             <div class='container'>
                                 <div class="row">
                                     <div class="col-12 text-center" style="border-bottom:1.5px dashed gray;">
                                         <h2 class="text-uppercase m-0">${this.company.company_name}</h2>
                                         <p class="m-0 text-uppercase">${this.company.mobile}</p>
                                         <address style="margin:0;">${this.company.address}</address>
                                     </div>
                                 </div>
                                 <div class="row">
                                     <h2 style="margin:0;text-align:center">Customer Due</h2>
                                     <table class="table table-sm table-bordered m-0">
                                         ${document.querySelector("#customerdues").innerHTML}
                                     </table>
                                 </div>
                                 <div style='width:100%;position:fixed;bottom:0;left:0;text-align:center;padding-top:8px;font-style:italic;'>Print Date: ${moment().format("DD-MM-YYYY")}<div>
                             </div>
                         </body>
                     </html>
                 `);
            myWindow.focus();
            await new Promise(resolve => setTimeout(resolve, 1000));
            myWindow.print();
            myWindow.close();
        },
    },
}
</script>
