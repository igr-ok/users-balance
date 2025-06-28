import '../css/app.css';
import './bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';

import { createApp } from 'vue';
import DashboardPage from './Pages/DashboardPage.vue';
import OperationPage from './Pages/OperationPage.vue';
import LoginPage from './Pages/LoginPage.vue';

const app = createApp({});
app.component('dashboard-page', DashboardPage);
app.component('operation-page', OperationPage);
app.component('login-page', LoginPage);
app.mount('#app');


