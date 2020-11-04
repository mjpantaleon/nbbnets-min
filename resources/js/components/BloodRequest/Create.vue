<template>
  <div class="main-div">
      <b-row id="bb-crumb-sticky">
          <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item active>
                        <b-icon icon="file-text" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Blood Request & Issuance
                    </b-breadcrumb-item>
                    <b-breadcrumb-item :to="{ path: '/blood-request/list' }">
                        <b-icon icon="file-text" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Blood Request List
                    </b-breadcrumb-item>
                    <b-breadcrumb-item active>
                        Create Blood Request
                    </b-breadcrumb-item>
                </b-breadcrumb>
          </b-col>
        </b-row>

        <h4><b-icon icon="file-text"></b-icon> Create Blood Request</h4>
        <hr>

        <h4 class="text-secondary mt-3"> <b-icon icon="person-bounding-box"></b-icon> Patient Details</h4>
        <!-- Patient Full Name -->
        <b-row>
            <b-col cols="4">
                <b-form-group
                    id="fieldset-horizontal"           
                    label-cols-sm="4"
                    label-cols-lg="4"
                    description="First name"
                    label="Patient name"
                    label-for="first-name">
                    <b-form-input v-model="fname" id="first-name"></b-form-input>
                </b-form-group>
            </b-col>

            <b-col cols="3">
                <b-form-group
                    id="fieldset-horizontal"
                    description="Middle name"
                    label-for="middle-name">
                <b-form-input v-model="mname" id="middle-name"></b-form-input>
                </b-form-group>
            </b-col>

            <b-col cols="3">
                <b-form-group
                    id="fieldset-horizontal"
                    description="Last name"
                    label-for="last-name">
                <b-form-input v-model="lname" id="last-name"></b-form-input>
                </b-form-group>
            </b-col>

            <b-col cols="2">
                <b-form-group
                    id="fieldset-horizontal"
                    description="Suffix"
                    label-for="name-suffix">
                <b-form-input v-model="name_suffix" id="name-suffix"></b-form-input>
                </b-form-group>
            </b-col>
        </b-row>

        <!-- blood type -->
        <b-row>
            <b-col cols="4">
                <b-form-group
                    id="fieldset-horizontal"
                    label-cols-sm="4"
                    label-cols-lg="4"
                    description="Select Blood Type"
                    label="Blood Type"
                    label-for="blood_type">
                    <b-form-select v-model="blood_type" 
                        :options="blood_type_list" id="blood_type"></b-form-select>
                </b-form-group>
            </b-col>
        </b-row>

        <!-- gender -->
        <b-row>
            <b-col cols="4">
                <b-form-group
                    id="fieldset-horizontal"
                    label-cols-sm="4"
                    label-cols-lg="4"
                    description="select gender"
                    label="Gender"
                    label-for="gerder_list">
                    <b-form-select v-model="gender" 
                        :options="gerder_list" id="gerder_list"></b-form-select>
                </b-form-group>
            </b-col>
        </b-row>

         <!-- civil_stat -->
        <b-row>
            <b-col cols="4">
                <b-form-group
                    id="fieldset-horizontal"
                    label-cols-sm="4"
                    label-cols-lg="4"
                    description="Select civil status"
                    label="Civil Status"
                    label-for="civil_status_list">
                    <b-form-select v-model="civil_stat" 
                        :options="civil_status_list" id="civil_status_list"></b-form-select>
                </b-form-group>
            </b-col>
        </b-row>

        <!-- bdate -->
        <b-row>
            <b-col cols="4">
                <b-form-group
                    id="fieldset-horizontal"
                    label-cols-sm="4"
                    label-cols-lg="4"
                    description="Select Birth day from Calendar"
                    label="Birth Day"
                    label-for="bdate">
                    <b-form-input type="date" v-model="bdate" id="bdate"></b-form-input>
                </b-form-group>
            </b-col>

            <b-col cols="3">
                <b-form-group
                    id="fieldset-horizontal"
                    description="Computed age"
                    label-for="age">
                    <b class="text-danger">{{ calculateAge }} y/o</b>
                    <!-- <b class="text-danger">{{ age }} y/o</b> -->
                </b-form-group>
            </b-col>
        </b-row>

        <!-- address -->
        <b-row>
            <b-col md="8">
                    <b-form-group
                        id="fieldset-horizontal"
                        label-cols-sm="2"
                        label-cols-lg="2"
                        description="Type-in address"
                        label="Address"
                        label-for="address">
                        <b-form-input v-model="address" id="address" placeholder="Blk/ Lot/ St/ Subd/ Brgy/ City/Mun/ Prov"></b-form-input>
                    </b-form-group>
            </b-col>
        </b-row>

        <!-- diagnosis -->
        <b-row>
            <b-col md="8">
                    <b-form-group
                        id="fieldset-horizontal"
                        label-cols-sm="2"
                        label-cols-lg="2"
                        description="Type-in diagnosis"
                        label="Diagnosis"
                        label-for="diagnosis">
                        <b-form-input v-model="diagnosis" id="diagnosis"></b-form-input>
                    </b-form-group>
            </b-col>
        </b-row>

        <h4 class="text-secondary mt-3"> <b-icon icon="person-square"></b-icon> Physician Details</h4>
        <!-- Patient Full Name -->
        <b-row>
            <b-col cols="4">
                <b-form-group
                    id="fieldset-horizontal"           
                    label-cols-sm="4"
                    label-cols-lg="4"
                    description="First name"
                    label="Physician name"
                    label-for="first-name">
                    <b-form-input v-model="dr_fname" id="first-name"></b-form-input>
                </b-form-group>
            </b-col>

            <b-col cols="3">
                <b-form-group
                    id="fieldset-horizontal"
                    description="Middle name"
                    label-for="middle-name">
                <b-form-input v-model="dr_mname" id="middle-name"></b-form-input>
                </b-form-group>
            </b-col>

            <b-col cols="3">
                <b-form-group
                    id="fieldset-horizontal"
                    description="Last name"
                    label-for="last-name">
                <b-form-input v-model="dr_lname" id="last-name"></b-form-input>
                </b-form-group>
            </b-col>

            <b-col cols="2">
                <b-form-group
                    id="fieldset-horizontal"
                    description="Suffix"
                    label-for="name-suffix">
                <b-form-input v-model="dr_name_suffix" id="name-suffix"></b-form-input>
                </b-form-group>
            </b-col>
        </b-row>

        <!-- Mobile # -->
        <b-row>
            <b-col md="8">
                    <b-form-group
                        id="fieldset-horizontal"
                        label-cols-sm="2"
                        label-cols-lg="2"
                        description="Type-in Mobile #"
                        label="Mobile #"
                        label-for="mobile_no">
                        <b-form-input v-model="mobile_no" id="mobile_no" placeholder="09XX - XXX - XXXX"></b-form-input>
                    </b-form-group>
            </b-col>
        </b-row>

        <!-- Email Address -->
        <b-row>
            <b-col md="8">
                    <b-form-group
                        id="fieldset-horizontal"
                        label-cols-sm="2"
                        label-cols-lg="2"
                        description="Type-in Email address"
                        label="Email Address"
                        label-for="email">
                        <b-form-input type="email" v-model="email" id="email" placeholder="sample_email@yahoo.com"></b-form-input>
                    </b-form-group>
            </b-col>
        </b-row>

        <!-- <h4 class="text-secondary mt-3"> <b-icon icon="exclamation"></b-icon> Additional Information</h4> -->
        <!-- Additional Information -->
        <!-- <b-row>
            <b-col cols="4">
                <b-form-group
                    id="fieldset-horizontal"
                    label-cols-sm="4"
                    label-cols-lg="4"
                    description="select request type"
                    label="Request type"
                    label-for="request_type">
                    <b-form-select v-model="request_type" 
                        :options="request_type_options" id="request_type"></b-form-select>
                </b-form-group>
            </b-col>
        </b-row> -->

        <!-- get agencies from db -->
        <!-- <b-row>
            <b-col cols="4">
                <b-form-group
                    id="fieldset-horizontal"
                    label-cols-sm="4"
                    label-cols-lg="4"
                    description="select agency"
                    label="Agency"
                    label-for="agency">
                    <b-form-select v-for="(agency, j) in agencies" :key="j" :options="agency.agency_name" id="agency">
                        </b-form-select>
                </b-form-group>
            </b-col>
        </b-row> -->


        <h4 class="text-secondary mt-3"> <b-icon icon="droplet-half"></b-icon> Blood Unit</h4>
        <!-- Blood Unit details -->
        <b-row v-for="(cp_component, i) in cp_components" :key="i">
            <b-col md="8">
                <b-form-group
                        id="fieldset-horizontal"
                        label-cols-sm="6"
                        label-cols-lg="6"
                        description="# of units"
                        :label="cp_component.comp_name"
                        label-for="requested_units">
                        <b-form-input type="number" v-model="cp_component.requested_units" :name="cp_component.comp_name" id="requested_units"></b-form-input>
                    </b-form-group>
            </b-col>
        </b-row>

        <b-row>
            <b-col md="4">
                <b-button block
                    variant="success"
                    @click.prevent="showModal">
                    <b-icon icon="check-circle"></b-icon>&nbsp;CREATE BLOOD REQUEST
                </b-button>
            </b-col>
        </b-row>

        <verifier-modal @setUname="setUname"></verifier-modal>

        <!-- =============== MODALS ================ -->
        <!-- SHOW THIS MODAL AFTER SUCCESSFUL ACTION -->
        <b-modal v-model="showSuccessMsg" centered
            title="INFO"
            header-bg-variant="info"
            body-bg-variant="light" 
            footer-bg-variant="info"
            header-text-variant="light"
            hide-header-close>
            
            <h5 class="alert-heading text-center">
                <b-icon variant="danger" icon="droplet-half"></b-icon>&nbsp;{{message}}
            </h5>
            
            <template v-slot:modal-footer="{ ok }">
            <!-- <template v-slot:modal-footer="{ ok }"> -->
                <b-link class="btn btn-info" :to="{ path: '/blood-request/list' }"
                    size="sm" variant="info" @click="ok()">
                    OK
                </b-link>
            </template>
        </b-modal>
        <!-- =============== MODALS ================ -->


        <!-- SHOW THIS MODAL FOR EMPTY FIELDS -->
        <b-modal v-model="showErrorMsg" centered
            title="WARNING"
            header-bg-variant="danger"
            body-bg-variant="light" 
            footer-bg-variant="danger"
            header-text-variant="light"
            hide-header-close>
            
            <h4 class="alert-heading text-center">
                <b-icon variant="danger" icon="exclamation-octagon-fill"></b-icon>&nbsp;
                {{ error_msg }}
            </h4>
            
            <template v-slot:modal-footer="{ ok }">
                <b-link class="btn btn-danger"
                    size="sm" variant="danger" @click="ok()">
                    OK
                </b-link>
            </template>
        </b-modal>
  </div>
</template>

<script>
import VerifierModal from "../Tools/VerifierModal.vue";
export default {
    components: {
        VerifierModal
    },
     data(){
        return{
            enableBtn: false,
            showSuccessMsg: false,

            showErrorMsg: false,
            error_msg: 'Please do not leave any blank inputs!',

            cp_components: '',
            final_data: [],
            message: '',

            // patient details
            fname: '',
            mname: '',
            lname: '',
            name_suffix: '',

            blood_type: '',
            gender: 'M',
            civil_stat: 'S',
            bdate: '',
            age: '',

            address: '',
            diagnosis: '',

            // additional info
            // request_type: '',
            // agencies: '',

            request_type_options: [
                { value: 'Indigent', text: 'Indigent' },
                { value: 'Kabalikat', text: 'Kabalikat' },
                { value: 'MOA-Sharing', text: 'MOA-Sharing' },
                { value: 'MOA-To Pay', text: 'MOA-To Pay' },
                { value: 'Processing Fee', text: 'Processing Fee' },
                { value: 'Sharing', text: 'Sharing' }
            ],

            // physician
            dr_fname: '',
            dr_mname: '',
            dr_lname: '',
            dr_name_suffix: '',

            mobile_no: '',
            email: '',

            gerder_list: [
                { value: 'M', text: 'Male' },
                { value: 'F', text: 'Female' },
            ],

            blood_type_list: [
                { value: 'A Pos', text: 'A Pos' },
                { value: 'A Neg', text: 'A Neg' },
                { value: 'B Pos', text: 'B Pos' },
                { value: 'B Neg', text: 'B Neg' },
                { value: 'AB Pos', text: 'AB Pos' },
                { value: 'AB Neg', text: 'AB Neg' },
                { value: 'O Pos', text: 'O Pos' },
                { value: 'O Neg', text: 'O Neg' },
            ],

            civil_status_list: [
                { value: 'S', text: 'Single' },
                { value: 'M', text: 'Married' },
                { value: 'W', text: 'Widowed' },
                { value: 'X', text: 'Separated' },
            ],


            // age: today.getFullYear() - '2020-06-25'
            
        }
    }, /* data */

    computed: {
        checkBday(){
            return this.bdate.length > 4 ? true : false
        },

        calculateAge: function() {
            var now = new Date();
            let birthDate = new Date(this.bdate);

            function isLeap(year) {
                return year % 4 == 0 && (year % 100 != 0 || year % 400 == 0);
            }
            
            // days since the birthdate    
            var days = Math.floor((now.getTime() - birthDate.getTime())/1000/60/60/24);
            var age = 0;
            // iterate the years
            for (var y = birthDate.getFullYear(); y <= now.getFullYear(); y++){
                var daysInYear = isLeap(y) ? 366 : 365;
                if (days >= daysInYear){
                days -= daysInYear;
                age++;
                // increment the age only if there are available enough days for the year.
                }
            }
            return this.age = age;

        },
    }, /* computed */

    methods: {

        async getCpComponents(){

            await axios
            .get('/cp-components')
            .then(response => (
                this.cp_components = response.data
            ))
            .catch(error => console.log(error))
        },

        showModal(){
            // alert('button has been clicked');
            var err
            this.errMessage = ''

            // check first for errors
            err = this.checkError()

            // if there were errors found
            if(err){
                // return this error message
                this.showErrorMsg = true
            } else{
                // show verifier form
                this.$bvModal.show('verifier-login');
                this.modalOpen = !this.modalOpen;
            }

        },

        checkError(){

            var err = false;

            // how to add number of blood units request? to avoid sending of empty request
            
            if(this.fname == "" || this.lname == "" || this.blood_type == "" || this.bdate == '' || this.diagnosis == ''
                || this.dr_fname == '' || this.dr_lname == '' || this.mobile_no == '' || this.email == ''){
                return err = true
            }
            
            return err

        },

        openModal() {
            this.modalOpen = !this.modalOpen;
        },

        async setUname(e){

            console.log(this.cp_components)

            await axios
            .post('/blood-request', {
                // patient details
                fname: this.fname,
                mname: this.mname,
                lname: this.lname,
                name_suffix: this.name_suffix,

                blood_type: this.blood_type,
                gender: this.gender,
                civil_stat: this.civil_stat,
                bdate: this.bdate,
                age: this.age,

                address: this.address,
                diagnosis: this.diagnosis,

                // physician
                dr_fname: this.dr_fname,
                dr_mname: this.dr_mname,
                dr_lname: this.dr_lname,
                dr_name_suffix: this.dr_name_suffix,

                mobile_no: this.mobile_no,
                email: this.email,
                requested_units: this.cp_components,

                verifier: e,
            })
            .then(response => {

                if(response.data){
                    this.message = response.data.message
                    this.showSuccessMsg = true
                    this.getCpComponents()
                }
                
            })

        }

    }, /* methods */

    mounted(){
        this.getCpComponents()
        // this.getAgencyList()
    },
}
</script>

<style>

</style>