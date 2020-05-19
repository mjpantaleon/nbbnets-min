import Login from './components/MainApp/Login.vue';

import Donation from './components/Donation.vue';
import SearchDonor from './components/Donation/SearchDonor.vue';
import DonorDetails from './components/Donation/DonorDetails.vue';
import NewWalkin from './components/Donation/NewWalkin.vue';

import RegisterUnit from './components/RegisterBloodUnit.vue';


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
    },
    { name: 'search-donor', path: '/search-donor', component: SearchDonor },
    { name: 'donor-details', path: '/donor-details', component: DonorDetails },
    { name: 'new-walk-in', path: '/new-walk-in', component: NewWalkin },

    { name: 'register-blood', path: '/register-blood', component: RegisterUnit },


];