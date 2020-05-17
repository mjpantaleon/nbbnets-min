import Login from './components/MainApp/Login.vue';
import Donation from './components/Donation.vue';
import SelectDonor from './components/Donation/SelectDonor.vue';
import DonorDetails from './components/Donation/DonorDetails.vue';


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
    { name: 'search-donor', path: '/search-donor', component: SelectDonor },
    { name: 'donor-details', path: '/donor-details', component: DonorDetails },


];