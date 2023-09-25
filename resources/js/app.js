
import './bootstrap';
import { createApp } from 'vue';
//toast
import moshaToast from 'mosha-vue-toastify'
import 'mosha-vue-toastify/dist/style.css'
//vue good table
import VueGoodTablePlugin from 'vue-good-table-next'; 
import 'vue-good-table-next/dist/vue-good-table-next.css'

const app = createApp({});
app.use(moshaToast);
app.use(VueGoodTablePlugin);

import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';
app.component('v-select', vSelect)




import Dashboard from './components/Dashboard.vue';
import Setting from './components/Setting.vue';
import Role from './components/role/Role.vue';
import User from './components/user/User.vue';
import Customer from './components/customer/Customer.vue';
import CustomerLedger from './components/customer/CustomerLedger.vue';
import CustomerDuelist from './components/customer/CustomerDuelist.vue';
import CustomerPayment from './components/customerpayment/CustomerPayment.vue';
import Brand from './components/brand/Brand.vue';
import Category from './components/category/Category.vue';
import Designation from './components/designation/Designation.vue';
import Employee from './components/employee/Employee.vue';
import Product from './components/product/Product.vue';
import EmployeeManage from './components/employee/Manage.vue';
import Tailor from './components/tailor/Tailor.vue';
import TailorLedger from './components/tailor/TailorLedger.vue';
import TailorPayment from './components/tailorpayment/TailorPayment.vue';
import Order from './components/order/Order.vue';
import OrderManage from './components/order/Manage.vue';
import OrderInvoice from './components/order/Invoice.vue';
import Expense from './components/expense/Expense.vue';
import Clothing from './components/clothing/Clothing.vue';
import ClothingManage from './components/clothing/Manage.vue';
app.component('dashboard', Dashboard);
app.component('setting', Setting);
app.component('role', Role);
app.component('user', User);
app.component('customer', Customer);
app.component('customer-ledger', CustomerLedger);
app.component('customer-duelist', CustomerDuelist);
app.component('customer-payment', CustomerPayment);
app.component('brand', Brand);
app.component('category', Category);
app.component('designation', Designation);
app.component('employee', Employee);
app.component('product', Product);
app.component('tailor', Tailor);
app.component('tailor-payment', TailorPayment);
app.component('tailor-ledger', TailorLedger);
app.component('employee-manage', EmployeeManage);
app.component('order', Order);
app.component('order-manage', OrderManage);
app.component('order-invoice', OrderInvoice);
app.component('expense', Expense);
app.component('clothing', Clothing);
app.component('clothing-manage', ClothingManage);


app.mount('#layoutSidenav');
