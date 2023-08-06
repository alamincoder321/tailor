<template>
    <div class="row ">
        <div class="col-md-12 p-md-0">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-2">
                            <select class="form-control shadow-none" v-model="searchBy">
                                <option value="">All</option>
                                <option value="tailor">By Tailor</option>
                            </select>
                        </div>
                        <div class="col-md-3" :class="searchBy == 'tailor' ? '' : 'd-none'">
                            <v-select :options="tailors" v-model="selectTailor" label="name" id="tailor"></v-select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control shadow-none" v-model="dateFrom">
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control shadow-none" v-model="dateTo">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-silver" @click="getClothing">Search</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-12 bg-content">
            <div class="text-end" :class="clothing.length > 0 ? '' : 'd-none'">
                <a href="" @click.prevent="print"><i class="fa fa-print"></i></a>
            </div>
            <div class="clothingDaTa" :class="clothing.length > 0 ? '' : 'd-none'">
                <table class="table table-striped table-sm">
                    <thead class="text-white bg-dark">
                        <tr>
                            <th>Sl</th>
                            <th>Date</th>
                            <th>Tailor Name</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Due</th>
                            <th>Note</th>
                            <th>Status</th>
                            <th class="text-center action">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in clothing" :key="index">
                            <td>{{ index + 1 }}</td>
                            <td>{{ item.date }}</td>
                            <td>{{ item.name }}</td>
                            <td>{{ item.total }}</td>
                            <td>{{ item.paid }}</td>
                            <td>{{ item.due }}</td>
                            <td>{{ item.note }}</td>
                            <td>
                                <span class="badge bg-danger" v-if="item.status == 'p'">Pending</span>
                                <span class="badge bg-secondary" v-if="item.status == 'pr'">Processing</span>
                                <span class="badge bg-warning" v-if="item.status == 'c'">Completed</span>
                                <span class="badge bg-success" v-if="item.status == 'a'">Delivered</span>
                            </td>
                            <td style="width: 10%;text-align: center;" class="action">
                                <a href="#"><i style="font-size: 18px;" class="fa fa-file"></i></a>
                                <a :href="`/clothing/${item.id}`"><i style="font-size: 18px;" class="fa fa-edit mx-2"></i></a>
                                <a href="#" @click.prevent="deleteData(item.id)"><i style="font-size: 18px;" class="fa fa-trash text-danger"></i></a>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">Total</th>
                            <th>{{ clothing.reduce((acc, pre) => {return acc + +parseFloat(pre.total)}, 0).toFixed(2) }}</th>
                            <th>{{ clothing.reduce((acc, pre) => {return acc + +parseFloat(pre.paid)}, 0).toFixed(2) }}</th>
                            <th>{{ clothing.reduce((acc, pre) => {return acc + +parseFloat(pre.due)}, 0).toFixed(2) }}</th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="text-center" :class="clothing.length == 0 ? '' : 'd-none'">Data Not Found</div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';

export default {
    data() {
        return {
            searchBy: '',
            dateFrom: moment().format('YYYY-MM-DD'),
            dateTo: moment().format('YYYY-MM-DD'),
            tailors: [],
            selectTailor: null,
            clothing: [],
        }
    },
    created(){
        this.getClothing();
        this.getTailor();
    },
    methods: {
        getTailor() {
            axios.get('/get-tailor').then(res => {
                this.tailors = res.data
            })
        },
        getClothing() {
            let data = {
                dateFrom: this.dateFrom,
                dateTo: this.dateTo,
                tailorId: this.searchBy =='tailor' ? this.selectTailor.id : ''

            }
            axios.post('/get-clothing', data).then(res => {
                this.clothing = res.data.clothing;
            })
        },

        deleteData(id) {
            if (confirm("Are you sure !!")) {
                axios.post('/delete-clothing', { id: id }).then(res => {
                    if (res.data.status) {
                        this.$moshaToast(res.data.msg,);
                    } else {
                        console.log(res.data.msg);
                    }
                    this.getEmployee();
                })
            }
        },

        async print(){
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
                                <h3 class="m-0 text-center bg-primary text-white text-uppercase">Clothing Records ${this.searchBy == 'tailor' ? 'of '+this.selectTailor.name: ''}</h3>
                                <div class="row">
                                    <div class="col-12">
                                        ${document.querySelector('.clothingDaTa').innerHTML}
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
#tailor #vs1__combobox{
    padding: 0;
    height: 32px;
}
#tailor .vs__search{
    margin: 0 !important;
}
#tailor .vs__actions{
    padding: 0 2px;
}
#tailor .vs__clear{
    margin: 0;
    padding: 0px 8px !important;
}
#tailor .vs__selected{
    margin: 0 !important;
    padding: 0 !important;
}

</style>
