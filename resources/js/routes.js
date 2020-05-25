import Login from './components/MainApp/Login.vue';

import Donation from './components/Donation.vue';
import NewWalkin from './components/Donation/NewWalkin.vue';

import SearchDonor from './components/Donor/SearchDonor.vue';
import DonorDetails from './components/Donor/DonorDetails.vue';
import RegisterNewDonor from './components/Donor/RegisterNewDonor.vue';

import RegisterUnit from './components/RegisterBloodUnit.vue';


export const routes = [
    { name: 'login', path: '/', component: Login },
    
    { name: 'donation', path: '/donation', component: Donation },
    { name: 'search-donor', path: '/search-donor', component: SearchDonor },
    { name: 'donor-details', path: '/donor-details/:id', component: DonorDetails },
    { name: 'new-walk-in', path: '/new-walk-in/:id', component: NewWalkin },
    { name: 'register-new-donor', path: '/register-new-donor', component: RegisterNewDonor },

    { name: 'register-blood', path: '/register-blood', component: RegisterUnit },


];