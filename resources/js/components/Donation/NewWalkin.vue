<template>
  <div class="main-div">
        <!-- BREAD-CRUMBS -->
        <b-row id="bb-crumb-sticky">
            <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item :to="{ path: '/donation' }">
                        <b-icon icon="card-checklist" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Donation
                    </b-breadcrumb-item>
                    <b-breadcrumb-item :to="{ path: '/search-donor' }">
                        <b-icon icon="search" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Search Donor</b-breadcrumb-item>
                    <b-breadcrumb-item :to="{ path: '/donor-details/' + this.$route.params.id }">
                        <b-icon icon="person-lines-fill" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Donor Details</b-breadcrumb-item>
                    <b-breadcrumb-item active>
                        <b-icon icon="person-plus"></b-icon> New Walk-in Donation</b-breadcrumb-item>
                </b-breadcrumb>
            </b-col>
        </b-row>

        <h4><b-icon icon="person-plus"></b-icon>&nbsp;New Walkin Donation</h4>
        <hr>

        <b-alert show variant="danger" v-if="errMessage"><h3>{{ errMessage }}</h3></b-alert>
        
        <!-- {{questions}} {{ hemoglobin }} {{body_weight}} {{blood_pressure}} {{pulse_rate}} {{temperature}} {{other_reason}} -->
        <b-row>
            <b-col md="6">
                <!-- <b-card no-body bg-variant="dark" text-variant="light" header="Donor Details"></b-card> -->
                    <table class="table table-bordered table-sm bg-light">
                        <tr>
                            <td>
                                <b-form-group
                                    id="fieldset-horizontal"
                                    label-cols-sm="4"
                                    label-cols-lg="3"
                                    description="Please select from the calendar"
                                    label="Date Collected"
                                    label-for="collection_date">

                                <b-form-datepicker v-model="created_dt"
                                    :state="checkDP" id="collection_date"></b-form-datepicker>
                                </b-form-group>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <b-form-group
                                    id="fieldset-horizontal"
                                    label-cols-sm="4"
                                    label-cols-lg="3"
                                    description="Full name"
                                    label="Donor"
                                    label-for="fname"
                                    aria-readonly="true">
                                    <h5>{{ fname }} {{ mname }} {{ lname }} {{ name_suffix }}</h5>
                                </b-form-group>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <b-form-group
                                    id="fieldset-horizontal"
                                    label-cols-sm="4"
                                    label-cols-lg="3"
                                    description="type of donor"
                                    label="Type of Donor"
                                    label-for="donation_type_list">
                                    <b-form-select v-model="donation_type" :options="donation_type_list" id="donation_type_list"></b-form-select>
                                </b-form-group>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <b-form-group
                                    id="fieldset-horizontal"
                                    label-cols-sm="4"
                                    label-cols-lg="3"
                                    description="mh/ pe result"
                                    label="MH/ PE REsult"
                                    label-for="mh_pe_stat_list">
                                <b-form-select v-model="mh_pe_stat" :options="mh_pe_stat_list" id="mh_pe_stat_list"></b-form-select>
                                </b-form-group>
                            </td>
                        </tr>


                        <tr>
                            <td>
                                <b-form-group
                                    id="fieldset-horizontal"
                                    label-cols-sm="4"
                                    label-cols-lg="3"
                                    description="collection method"
                                    label="Method of Blood Collection"
                                    label-for="collection_method_list">
                                <b-form-select v-model="collection_method" :options="collection_method_list" id="collection_method_list"></b-form-select>
                                </b-form-group>
                            </td>
                        </tr>

                        <tr v-if="collection_method == 'WB'">
                            <td>
                                <b-form-group
                                    id="fieldset-horizontal"
                                    label-cols-sm="4"
                                    label-cols-lg="3"
                                    description="blood bag"
                                    label="Blood Bag"
                                    label-for="blood_bag">
                                <b-form-select v-model="blood_bag" :options="blood_bag_list" id="blood_bag"></b-form-select>
                                </b-form-group>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <b-form-group
                                    id="fieldset-horizontal"
                                    label-cols-sm="4"
                                    label-cols-lg="3"
                                    description="collection status"
                                    label="Status of Collection"
                                    label-for="collection_stat_list">
                                <b-form-select v-model="collection_stat" :options="collection_stat_list" id="collection_stat_list"></b-form-select>
                                </b-form-group>
                            </td>
                        </tr>

                        <tr v-if="collection_stat != 'COL'">
                            <td>
                                <b-form-group
                                    id="fieldset-horizontal"
                                    label-cols-sm="4"
                                    label-cols-lg="3"
                                    description="reason for unsuccessful collection"
                                    label="Reason for Unsuccessful Collection"
                                    label-for="coluns_res_list">
                                    <b-form-select v-model="coluns_res"
                                        :state="checkReasonUnsBULGE" 
                                        :options="coluns_res_list" id="coluns_res_list"></b-form-select>
                                </b-form-group>
                            </td>
                        </tr>

                        <template v-if="mh_pe_stat == 'A'">
                        <!-- DONATION ID -->
                        <tr>
                            <td>
                                <b-form-group
                                    label-cols-sm="4"
                                    label-cols-lg="3"
                                    description="Scan Donation ID"
                                    label="Donation ID"
                                    label-for="donation_id">
                                    
                                    <b-form-input v-model="donation_id" 
                                    :state="checkDonationID" id="donation_id"></b-form-input>
                                </b-form-group>
                            </td>
                        </tr>
                        </template>

                        <tr>
                            <td>
                                <b-button block variant="success"
                                    :disabled="enableBtn"
                                    @click.prevent="showModal(mh_pe_stat)">
                                    <b-icon icon="check-circle"></b-icon> VERIFY AND PROCEED</b-button>
                            </td>
                        </tr>
                    </table>
                
            </b-col> <!-- b-col md="6" left side -->


            <!-- !ONLY SHOW THIS PART IF MH_PE_STAT IS NOT EQUAL TO A -->
            <!-- <b-col md="6"> -->
            <b-col>
                <!-- imported component -->
                <mhpe-question v-if="mh_pe_stat != 'A'" md="6"
                    @questionSelected="questionSelected" 
                    @hemoglobinSelected="hemoglobinSelected"
                    @bodyWeightSelected="bodyWeightSelected"
                    @pulseRateSelected="pulseRateSelected"
                    @bloodPressureSelected="bloodPressureSelected"
                    @temparatureSelected="temparatureSelected"
                    @otherReason="otherReason"></mhpe-question>
            </b-col>
        </b-row>



        <!-- ================== MODALS =================== -->
        <!-- VERFIER MODAL POP-UP -->
        <verifier-modal @setUname="setUname"></verifier-modal>

        <!-- SHOW THIS MODAL AFTER SUCCESSFUL ACTION -->
        <b-modal v-model="showSuccessMsg" centered
            title="Success!"
            header-bg-variant="success"
            body-bg-variant="light" 
            footer-bg-variant="success"
            header-text-variant="light"
            hide-header-close>
            
            <h4 class="alert-heading text-center">
                <b-icon icon="droplet-half"></b-icon>&nbsp;{{message}}
            </h4>
            
            <template v-slot:modal-footer="{ ok }">
                <b-link class="btn btn-success" :to="{ path: '/donation' }"
                    size="sm" variant="success" @click="ok()">
                    OK
                </b-link>
            </template>
        </b-modal>

        <!-- SHOW IF NO CREATED DATE SELECTED -->
        <b-modal v-model="showErrorMsg" centered
            title="STOP"
            header-bg-variant="danger"
            body-bg-variant="light" 
            footer-bg-variant="danger"
            header-text-variant="light"
            hide-header-close>
            
            <h4 class="alert-heading text-center">
                <b-icon variant="danger" icon="exclamation-octagon-fill"></b-icon>&nbsp;
                <!-- This Donation ID already exist! You cannot proceed the New Walk-in Donation registry. -->
                {{ mismatch_msg }}
            </h4>
            
            <template v-slot:modal-footer="{ ok }">
                <b-link class="btn btn-danger"
                    size="sm" variant="danger" @click="ok()">
                    OK
                </b-link>
            </template>
        </b-modal>
        <!-- ================== MODALS =================== -->
        </div>
</template>

<script>
import MhpeQuestion from './Questions.vue';
import VerifierModal from "../Tools/VerifierModal.vue";

export default {
    components: {MhpeQuestion, VerifierModal},
    data() {
      return {
        enableBtn: false,
        showSuccessMsg: false,
        showErrorMsg: false,
        message: '',
        errMessage: '',
        mismatch_msg: '',

        fname: '',
        mname: '',
        lname: '',
        name_suffix: '',
        

        // FIELD VALUES TO SEND
        created_dt: '',
        donor_sn: this.$route.params.id,
        donation_type: 'V',

        mh_pe_deferral: '',
        // mh_pe_question: '',
        // mh_pe_remark: '',
        mh_pe_stat: 'A',

        // MH
        questions: [],

        // PE
        hemoglobin: '',
        body_weight: '',
        blood_pressure: '',
        pulse_rate: '',
        temperature: '',

        // other reason
        other_reason: '',

        collection_method: 'WB',
        collection_stat: 'COL',
        coluns_res: '',

        donation_id: '',
        approved_by: '',
        password: '',

        blood_bag:'Q',

        // SELECTION LISTS
        donation_type_list: [
          { value: 'AU', text: 'Autologous' },
          { value: 'V', text: 'Voluntary' },
          { value: 'REP', text: 'Family/ Replacement' },
          { value: 'PA', text: 'Paid' },
        ],

        mh_pe_stat_list: [
            { value: 'A', text: 'ACCEPTED' },
            { value: 'TD', text: 'TEMPORARY DEFERRED' },
            { value: 'PD', text: 'PERMANENTLY DEFERRED' },
            { value: 'ID', text: 'INDEFINITELY DEFFERED' },
        ],

        collection_method_list: [
          { value: 'WB', text: 'Whole Blood' },
          { value: 'CP', text: 'Pheresis' },
        //   { value: 'WBP', text: 'Whole Blood Pheresis' },
        ],

        collection_stat_list: [
          { value: 'COL', text: 'Successful' },
          { value: 'UNS', text: 'Unsuccessful' },
        ],

        coluns_res_list: [
            { value: 'BULGE', text: 'Bulge' },
            { value: 'FAINT', text: 'Faint' },
            { value: 'CLOT', text: 'Clot' }
        ],

        blood_bag_list: [
            { value: 'Q', text: 'Quadruple' },
            { value: 'T', text: 'Triple' },
            { value: 'D', text: 'Double' },
            { value: 'S', text: 'Single' }
        ]
        
      }
    }, /* data */

    computed: {
        checkDonationID(){
            return this.donation_id.length > 15 ? true : false
        },
        checkVerifier(){
            return this.approved_by.length > 6 ? true : false
        },
        checkPassword(){
            return this.password.length > 3 ? true : false
        },
        checkDP(){
            return this.created_dt.length > 5 ? true : false
        },
        checkReasonUns(){
            return this.coluns_res.length > 3 ? true : false
        },
        checkReasonUnsBULGE(){
            return this.coluns_res_list > 3 ? true : false
        }
    },

    mounted() {
        this.getDonorDetails();
    }, /* mounted */

    methods: {
        async getDonorDetails(){
            await axios
            .get('/donor-profile/' + this.$route.params.id)
            .then(response => (
                this.fname = response.data.fname,
                this.mname = response.data.mname,
                this.lname = response.data.lname,
                this.name_suffix = response.data.name_suffix
            ))
            .catch(error => console.log(error))
        },

        // MODAL
        showModal(status){
            // alert('button has been clicked');
            var err
            this.errMessage = ''

            // check first for errors
            err = this.checkError(status)

            // if there were errors found
            if(err){
                // return this error message
                this.errMessage = 'Please do not leave any blank inputs...';
            } else{
                // show verifier form
                this.$bvModal.show('verifier-login');
                // this.modalOpen = !this.modalOpen;
            }

        },

        checkError(status){
            // alert(status);
            var err = false;
            
            if(status == 'A'){

                if(this.donation_id == "" || this.created_dt == ""){
                    return err = true
                }

                return err

            } else{
                if(this.created_dt == ''){
                    return err = true
                }

                return err
            }
            

        },

        // LAST METHOD
        async setUname(e){
            await axios
                .post('/create-new-walkin', {
                    created_dt: this.created_dt,
                    donor_sn: this.donor_sn,
                    donation_type: this.donation_type,

                    // questions: this.questions,
                    hemoglobin: this.hemoglobin,
                    body_weight: this.body_weight,
                    blood_pressure: this.blood_pressure,
                    pulse_rate: this.pulse_rate,
                    temperature: this.temperature,

                    mh_pe_deferral: this.mh_pe_deferral,
                    mh_pe_question: this.questions,
                    mh_pe_remark: this.other_reason,    /* change to from mh_pe_remarks to other_reason */
                    mh_pe_stat: this.mh_pe_stat,


                    collection_method: this.collection_method,
                    collection_stat: this.collection_stat,
                    blood_bag: this.blood_bag,
                    coluns_res: this.coluns_res,
                    donation_id: this.donation_id,
                    approved_by: this.approved_by,
                    verifier: e,
                })
                .then(response => {
                    
                    if(response.data.status){
                        this.enableBtn = true,
                        this.message = response.data.message
                        this.showSuccessMsg = true
                    } else {
                        this.showErrorMsg = true
                        this.mismatch_msg = response.data.message
                    }
                })
                .catch(error => console.log(error))
        },

        // MH QUESTION SELECTED
        questionSelected(a){
            this.questions = a
        },

        hemoglobinSelected(e){
            this.hemoglobin = e
            // alert(e)
        },

        bodyWeightSelected(e){
            this.body_weight = e 
        },

        bloodPressureSelected(e){
            this.blood_pressure = e
        },

        pulseRateSelected(e){
            this.pulse_rate = e
        },

        temparatureSelected(e){
            this.temperature = e
        },

        otherReason(e){
            this.other_reason = e
        }

        
    }
}
</script>

<style>

</style>