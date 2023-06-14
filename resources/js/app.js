
import './bootstrap';
import { createApp } from 'vue';

const app = createApp({});





import Dashboard from './components/Dashboard.vue';
import Category from './components/category/Category.vue';
app.component('dashboard', Dashboard);
app.component('category', Category);


app.mount('#layoutSidenav');
