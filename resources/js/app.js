
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
import Brand from './components/brand/Brand.vue';
import Category from './components/category/Category.vue';
import Designation from './components/designation/Designation.vue';
import Employee from './components/employee/Employee.vue';
import Product from './components/product/Product.vue';
import employeeManage from './components/employee/Manage.vue';
import Tailor from './components/tailor/Tailor.vue';
import Order from './components/order/Order.vue';
app.component('dashboard', Dashboard);
app.component('setting', Setting);
app.component('role', Role);
app.component('user', User);
app.component('customer', Customer);
app.component('brand', Brand);
app.component('category', Category);
app.component('designation', Designation);
app.component('employee', Employee);
app.component('product', Product);
app.component('tailor', Tailor);
app.component('employee-manage', employeeManage);
app.component('order', Order);


app.mount('#layoutSidenav');
