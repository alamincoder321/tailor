
import './bootstrap';
import { createApp } from 'vue';
//toast
import moshaToast from 'mosha-vue-toastify'
import 'mosha-vue-toastify/dist/style.css'

const app = createApp({});
app.use(moshaToast)




import Dashboard from './components/Dashboard.vue';
import Brand from './components/brand/Brand.vue';
import Category from './components/category/Category.vue';
import Designation from './components/designation/Designation.vue';
app.component('dashboard', Dashboard);
app.component('brand', Brand);
app.component('category', Category);
app.component('designation', Designation);


app.mount('#layoutSidenav');
