import Login from './components/MainApp/Login.vue';

import Donation from './components/Donation.vue';
import NewWalkin from './components/Donation/NewWalkin.vue';

import SearchDonor from './components/Donor/SearchDonor.vue';
import DonorDetails from './components/Donor/DonorDetails.vue';
import RegisterNewDonor from './components/Donor/RegisterNewDonor.vue';
import UpdateDonorInfo from './components/Donor/UpdateDetails.vue';

import PreScreenedList from './components/PreScreenedDonor/List.vue';
import PreScreenedInfo from './components/PreScreenedDonor/Info.vue';
import PreScreening from './components/PreScreenedDonor/Create.vue';
// import RegisterUnit from './components/RegisterBloodUnit.vue';

//BLOOD UNIT
import RegisterBloodUnit from './components/BloodUnit/RegisterBloodUnit.vue';
import BloodTyping from './components/BloodUnit/BloodTyping.vue';
import BloodProcessing from './components/BloodUnit/BloodProcessing.vue';
// import Aliquote from './components/BloodUnit/Aliquote.vue';
// import BloodTesting from './components/BloodUnit/BloodTesting.vue';
import BloodTestMin from './components/BloodUnit/BloodTestingMin.vue';
import Labelling from './components/BloodUnit/Labelling.vue';

import InventoryRelease from './components/BloodStock/InventoryRelease.vue';
import AvailableBloodStocks from './components/BloodStock/AvailableBloodStocks.vue';
import Units from './components/BloodStock/Aliquote.vue';

export const routes = [
    { name: 'login', path: '/', component: Login },

    { name: 'search-donor', path: '/search-donor', component: SearchDonor },
    { name: 'donor-details', path: '/donor-details/:id', component: DonorDetails },
    { name: 'register-new-donor', path: '/register-new-donor', component: RegisterNewDonor },
    { name: 'update-donor-details', path: '/update-donor-details/:id', component: UpdateDonorInfo },
    
    { name: 'pre-screened-list', path: '/pre-screened-list', component: PreScreenedList },
    { name: 'pre-screened-info', path: '/pre-screened-info/:id', component: PreScreenedInfo },
    { name: 'pre-screening', path: '/pre-screening', component: PreScreening },

    { name: 'donation', path: '/donation', component: Donation },
    { name: 'new-walk-in', path: '/new-walk-in/:id', component: NewWalkin },


    // BLOOD UNIT
    // { name: 'register-blood', path: '/register-blood', component: RegisterBloodUnit },
    { name: 'blood-typing', path: '/blood-typing', component: BloodTyping },
    { name: 'blood-processing', path: '/blood-processing', component: BloodProcessing },
    // { name: 'aliquote', path: '/aliquote', component: Aliquote },
    // { name: 'blood-testing', path: '/blood-testing', component: BloodTesting },
    { name: 'blood-testing', path: '/blood-testing/:id', component: BloodTestMin },
    { name: 'labelling', path: '/labelling', component: Labelling },

    { name: 'inventory-release', path: '/release-to-inventory', component: InventoryRelease },
    { name: 'available-stocks', path: '/available-stocks', component: AvailableBloodStocks },

    { name: 'units', path: '/unit/:id/:comp', component: Units },

    // { name: 'register-blood', path: '/register-blood', component: RegisterUnit },


];