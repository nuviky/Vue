import { createApp } from 'vue';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap/dist/js/bootstrap.js';
import App from './App.vue';
import Home from '@/views/Home';
import Add from '@/views/Add';
import Edit from '@/views/Edit';
import { createRouter, createWebHashHistory } from 'vue-router';

const routes = [
    {path: '/', component: Home},
    {path: '/new', component: Add},
    {path: '/edit/:id', component: Edit}
]

const router = createRouter({
    history: createWebHashHistory(),
    routes,
})

createApp(App).use(router).mount('#app')
