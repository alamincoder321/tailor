<template>
    <div style="width:80%;margin:auto;">
        <div class="container text-end">
            <button class="btn btn-warning btn-sm text-white shadow-none px-4 mb-3" @click="PrintInvoice"><i class="bi bi-printer"></i> Print</button>
        </div>
        <div class="container" id="invoice">
            <div class="row">
                <div class="col-12 mb-3">
                    <h6 class="text-center m-0"
                        style="border-top: 1px dashed gray;border-bottom: 1px dashed gray;text-transform: uppercase;padding: 8px;">
                        Order Invoice</h6>
                </div>
                <div class="col-6 mb-3" style="line-height: 20px;">
                    <span style="font-weight: 500;">Customer Id: </span>{{
                        order.customer_code
                    }}<br>
                    <span style="font-weight: 500;">Customer Name: </span>{{ order.name }}<br>
                    <span style="font-weight: 500;">Customer Mobile: </span>{{ order.phone }}<br>
                    <span style="font-weight: 500;">Customer Address: </span>{{ order.address }}<br>
                    <span style="font-weight: 500;">Refer: </span>{{ order.refer }}
                </div>
                <div class="col-6 mb-3">
                    <div class="text-end">
                        <span style="font-weight: 500;">Order By: </span>{{ order.addby }}<br>
                        <span style="font-weight: 500;">Invoice: </span>{{ order.order_code }}<br>
                        <span style="font-weight: 500;">Order Date:</span> {{ formatDate(order.orderDate) }}<br>
                        <span style="font-weight: 500;">Delivery Date:</span> {{ formatDate(order.deliveryDate) }}
                    </div>
                </div>
                <!-- product details -->
                <div class="col-12 mb-3">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-center" style="width: 5%;">Sl</th>
                                <th class="text-uppercase text-center" style="width: 50%;">Description</th>
                                <th class="text-uppercase text-center" style="width: 10%;">Qty</th>
                                <th class="text-uppercase text-center" style="width: 10%;">Price</th>
                                <th class="text-uppercase text-end" style="width: 15%;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in orderItem" :key="index">
                                <td class="text-center">{{ index + 1 }}</td>
                                <td>{{ item.product.name }}</td>
                                <td class="text-center">{{ item.quantity }}</td>
                                <td class="text-center">{{ item.retail_price }}</td>
                                <td class="text-end">{{ item.total }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-7">
                    <!-- <table style="line-height: 22px;width: 275px;">
                        <tr>
                            <td style="font-weight: 600;">Previous Due</td>
                            <td>:</td>
                            <td style="text-align: right;">{{ sales.previous_due }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600;">Current Due</td>
                            <td>:</td>
                            <td style="text-align: right;">{{ sales.due }}</td>
                        </tr>
                        <tr style="border-top: 1px dashed gray;">
                            <td style="font-weight: 600;">Total Due</td>
                            <td>:</td>
                            <td style="text-align: right;">
                                {{ (parseFloat(sales.previous_due) + parseFloat(sales.due)).toFixed(2) }}</td>
                        </tr>
                    </table> -->
                </div>
                <div class="col-5">
                    <table style="line-height: 23px;width: 100%;">
                        <tr>
                            <td style="font-weight: 600;width: 130px;">SubTotal</td>
                            <td>:</td>
                            <td style="text-align: right;">{{ order.subtotal }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600;width: 130px;">Discount</td>
                            <td>:</td>
                            <td style="text-align: right;">{{ order.discount }}</td>
                        </tr>
                        <tr style="border-top: 1px dashed gray;">
                            <td style="font-weight: 600;width: 130px;">Total</td>
                            <td>:</td>
                            <td style="text-align: right;">{{ order.total }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600;width: 130px;">Advance</td>
                            <td>:</td>
                            <td style="text-align: right;">{{ order.advance }}</td>
                        </tr>
                        <tr style="border-top: 1px dashed gray;">
                            <td style="font-weight: 600;width: 130px;">Due</td>
                            <td>:</td>
                            <td style="text-align: right;">{{ order.due }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import moment from 'moment'
export default {
    props: ['id'],
    data() {
        return {
            company: {},
            order: {},
            orderItem: [],
        }
    },

    created() {
        this.getOrder()
        this.getCompany()
    },
    methods: {
        getCompany() {
            axios.get("/get-setting").then((res) => {
                this.company = res.data;
            });
        },
        getOrder() {
            axios.post("/get-order", { id: this.id }).then((res) => {
                this.order = res.data.orders[0]
                this.orderItem = res.data.orderItem
            });
        },
        formatDate(date) {
            return moment(date).format("DD/MM/YYYY");
        },

        async PrintInvoice() {
            var myWindow = window.open('', '', `width=${window.screen.width},height=${window.screen.height}`);

            myWindow.document.write(`
                    <html>
                        <head>
                            <title>Sales Invoice</title>
                            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
                            <style>    
                                .table thead tr th {
                                    font-size: 12px;
                                }
    
                                .table tbody tr td {
                                    font-size: 12px;
                                }
    
                                img {
                                    width: 100px;
                                    height: 100px;
                                    border: 1px solid #c9c9c9;
                                }
    
                                h2 {
                                    color: #939393 !important;
                                }
    
                                @media print {
                                    img {
                                        width: 100px;
                                        height: 100px;
                                        border: 1px solid #c9c9c9;
                                    }
    
                                    h2 {
                                        color: #939393 !important;
                                    }
                                }
                            </style>
                        </head>
                        <body>
                            <div class='container'>
                                <table style="width:100%;">
                                    <thead>
                                        <tr>
                                            <td>
                                                <div class="row mb-2">
                                                    <div class="col-12 text-center">
                                                        <img src="${this.company.logo ? "/" + this.company.logo : '/noImage.png'}" />
                                                        <h2 class="text-uppercase m-0">${this.company.company_name}</h2>
                                                        <p class="m-0 text-uppercase">${this.company.mobile}</p>
                                                        <address style="margin:0;">${this.company.address}</address>
                                                    </div>
                                                </div>
                                            </td>    
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>${document.querySelector("#invoice").innerHTML}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td style="height:30px;position:fixed;bottom:0;left:0;"></td>
                                        </tr>
                                    </tfoot>   
                                </table>
                                <div class="row px-0" style='width:100%;position: fixed;bottom: 0;left: 0;'>
                                    <div class="col-4"> <span style='text-decoration:dashed overline;'>Received By</span></div>
                                    <div class="col-4 text-center">Print Date: ${moment().format("DD-MM-YYYY")}</div>
                                    <div class="col-4 text-end pe-0"><span style='text-decoration:dashed overline;'>Authorized Signature</span></div>
                                </div>
                                
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