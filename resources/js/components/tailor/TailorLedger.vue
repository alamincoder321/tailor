<style>
#tailors [role='combobox'] {
    height: 32px !important;
}

#tailors .vs__clear {
    margin-right: 0;
    padding: 0px 8px !important;
}
</style>

<template>
    <div class="row">
        <div class="col-12 col-lg-12">
            <div class="card">
                <div class="card-header" style="background:linear-gradient(45deg, #bb3a87, #000000d1);">
                    <form @submit.prevent="getLedger">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group m-0">
                                    <v-select :options="tailors" id="tailors" v-model="selectedTailor"
                                        label="name"></v-select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group m-0">
                                    <input type="date" class="form-control shadow-none" v-model="dateFrom" />
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group m-0">
                                    <input type="date" class="form-control shadow-none" v-model="dateTo" />
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
                <div class="card-body" style="overflow-x:auto;" v-if="ledgers.length > 0">
                    <button class="btn btn-warning text-white btn-sm shadow-none" @click="print" v-if="ledgers.length > 0"><i
                            class="bi bi-printer" style="font-size: 15px;"></i> Print</button>
                    <table class="table table-sm table-hover table-bordered" id="ledgers">
                        <thead style="background: #897800;">
                            <tr class="text-center text-white" style="font-size: 12px;">
                                <th>Sl</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Bill</th>
                                <th>Paid</th>
                                <th>Due</th>
                                <th class="text-end">CurrentDue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th colspan="6" style="padding-left:44px;">Previous Due</th>
                                <td class="text-end">{{ parseFloat(previousDue).toFixed(2) }}</td>
                            </tr>
                            <template v-for="(item, index) in ledgers">
                                <tr>
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ item.description }}</td>
                                    <td>{{ formatDate(item.date) }}</td>
                                    <td>{{ item.billAmount }}</td>
                                    <td>{{ item.paidAmount }}</td>
                                    <td>{{ item.dueAmount }}</td>
                                    <td align="right">{{ (item.due).toFixed(2) }}</td>
                                </tr>
                            </template>
                        </tbody>
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
            dateFrom: moment().format("YYYY-MM-DD"),
            dateTo: moment().format("YYYY-MM-DD"),
            tailors: [],
            selectedTailor: null,
            ledgers: [],
            previousDue: 0,
            company: {},
        };
    },
    created() {
        this.getTailor();
        this.getCompany();
    },
    methods: {
        getCompany() {
            axios.get("/get-setting").then((res) => {
                this.company = res.data;
            });
        },
        getTailor() {
            axios.get("/get-tailor").then((res) => {
                this.tailors = res.data;
            });
        },
        getLedger() {
            if (this.selectedTailor == null) {
                alert("Select first Tailor");
                document.querySelector("#tailors [type='search']").focus();
                return;
            }

            let data = {
                id: this.selectedTailor != null ? this.selectedTailor.id : "",
                dateFrom: this.dateFrom,
                dateTo: this.dateTo,
            };

            axios.post("/get-tailor-ledger", data).then((res) => {
                this.ledgers = res.data.ledger.filter(ledger => {
                    return ledger.date >= this.dateFrom && ledger.date <= this.dateTo;
                });
                this.previousDue = res.data.previousDue;
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
                             <title>Tailor Ledger</title>
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
                                     #ledgers{
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
                                     <h2 style="margin:0;text-align:center">Tailor Ledger of ${this.selectedTailor.name}</h2>
                                     <table class="table table-sm table-bordered m-0">
                                         ${document.querySelector("#ledgers").innerHTML}
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
