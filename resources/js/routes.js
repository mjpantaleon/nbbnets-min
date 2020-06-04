import Login from './components/MainApp/Login.vue';

import Donation from './components/Donation.vue';
import NewWalkin from './components/Donation/NewWalkin.vue';

import CandidateDonor from './components/Donor/CandidateDonor.vue';
import SearchDonor from './components/Donor/SearchDonor.vue';
import DonorDetails from './components/Donor/DonorDetails.vue';
import RegisterNewDonor from './components/Donor/RegisterNewDonor.vue';

import RegisterUnit from './components/RegisterBloodUnit.vue';


export const routes = [
    { name: 'login', path: '/', component: Login },

    { name: 'candidate-donor', path: '/candidate-donors', component: CandidateDonor },
    { name: 'search-donor', path: '/search-donor', component: SearchDonor },
    { name: 'donor-details', path: '/donor-details/:id', component: DonorDetails },
    { name: 'register-new-donor', path: '/register-new-donor', component: RegisterNewDonor },
    
    { name: 'donation', path: '/donation', component: Donation },
    { name: 'new-walk-in', path: '/new-walk-in/:id', component: NewWalkin },

    // { name: 'register-blood', path: '/register-blood', component: RegisterUnit },


];