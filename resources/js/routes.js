import Login from './components/MainApp/Login.vue';
import Donation from './components/Donation.vue';


export const routes = [
    {
        name: 'login',
        path: '/',
        component: Login
    },
    {
        name: 'donation',
        path: '/donation',
        component: Donation
    }

];