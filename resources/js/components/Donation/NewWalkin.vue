<template>
  <div class="main-div">
        <!-- BREAD-CRUMBS -->
        <b-row>
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

        <!-- SHOW THIS MODAL AFTER SUCCESSFUL ACTION -->
        <b-modal v-model="showSuccessMsg" centered
            title="Success!"
            header-bg-variant="success"
            body-bg-variant="light" 
            footer-bg-variant="success"
            header-text-variant="light"
            hide-header-close>
            
            <h4 class="alert-heading text-center">
                <b-icon icon="droplet-half"></b-icon>&nbsp;New Walk-in donation has been added!
            </h4>
            
            <template v-slot:modal-footer="{ ok }">
                <b-link class="btn btn-success" :to="{ path: '/donation' }"
                    size="sm" variant="success" @click="ok()">
                    OK
                </b-link>
            </template>
        </b-modal>

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
                                    description="First Name"
                                    label="Donor"
                                    label-for="fname"
                                    aria-readonly="true">
                                <b-form-input v-model="fname" id="fname" readonly></b-form-input>
                                </b-form-group>

                                <b-form-group
                                    id="fieldset-horizontal"
                                    label-cols-sm="4"
                                    label-cols-lg="3"
                                    description="Middle"
                                    label-for="mname">
                                <b-form-input v-model="mname" id="mname" readonly></b-form-input>
                                </b-form-group>

                                <b-form-group
                                    id="fieldset-horizontal"
                                    label-cols-sm="4"
                                    label-cols-lg="3"
                                    description="Last Name"
                                    label-for="lname">
                                <b-form-input v-model="lname" id="lname" readonly></b-form-input>
                                </b-form-group>

                                <b-form-group
                                    id="fieldset-horizontal"
                                    label-cols-sm="4"
                                    label-cols-lg="3"
                                    description="Suffix"
                                    label-for="suffix">
                                <b-form-input v-model="name_suffix" id="suffix" readonly></b-form-input>
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

                        <!-- VERFIER USER ID -->
                        <tr>
                            <td>
                                <b-form-group
                                    id="fieldset-horizontal"
                                    label-cols-sm="4"
                                    label-cols-lg="3"
                                    description="type-in verifier User ID"
                                    label="Verifier User ID"
                                    label-for="updated_by">

                                    <b-form-input v-model="updated_by"
                                        :state="checkVerifier" id="updated_by"></b-form-input>
                                </b-form-group>
                            </td>
                        </tr>

                        <!-- PASSWORD -->
                        <tr>
                            <td>
                                <b-form-group
                                    id="fieldset-horizontal"
                                    label-cols-sm="4"
                                    label-cols-lg="3"
                                    description="type-in verifier Password"
                                    label="Password"
                                    label-for="password">

                                    <b-form-input type="password" v-model="password"
                                        :state="checkPassword" id="password"></b-form-input>
                                </b-form-group>
                            </td>
                        </tr>
                        </template>

                        <tr>
                            <td>
                                <b-button block variant="success"
                                    :disabled="enableBtn"
                                    @click.prevent="addNewWalkin()">
                                    <b-icon icon="check-circle"></b-icon> VERIFY AND PROCEED</b-button>
                            </td>
                        </tr>
                    </table>
                
            </b-col>
        </b-row>

        </div>
</template>

<script>
export default {
    data() {
      return {
        enableBtn: false,
        showSuccessMsg: false,

        fname: '',
        mname: '',
        lname: '',
        name_suffix: '',

        // FIELD VALUES TO SEND
        created_dt: '',
        donor_sn: this.$route.params.id,
        donation_type: 'V',
        mh_pe_stat: 'A',
        collection_method: 'WB',
        collection_stat: 'COL',

        donation_id: '',
        updated_by: '',
        password: '',

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
          { value: 'AP', text: 'Apheresis' },
        ],

        collection_stat_list: [
          { value: 'COL', text: 'Successful' },
          { value: 'UNS', text: 'Unsuccessful' },
        ],
        
      }
    }, /* data */

    computed: {
        checkDonationID(){
            return this.donation_id.length > 15 ? true : false
        },
        checkVerifier(){
            return this.updated_by.length > 6 ? true : false
        },
        checkPassword(){
            return this.password.length > 3 ? true : false
        },
        checkDP(){
            return this.created_dt.length > 5 ? true : false
        }
    },

    mounted() {
        this.getDonorDetails();
    }, /* mounted */

    methods: {
        getDonorDetails(){
            axios
            .get('/donor-profile/' + this.$route.params.id)
            .then(response => (
                this.fname = response.data.fname,
                this.mname = response.data.mname,
                this.lname = response.data.lname,
                this.name_suffix = response.data.name_suffix
            ))
            .catch(error => console.log(error))
        },

        addNewWalkin(){
            axios
            .post('/create-new-walkin', {
                created_dt: this.created_dt,
                donor_sn: this.donor_sn,
                donation_type: this.donation_type,
                mh_pe_stat: this.mh_pe_stat,
                collection_method: this.collection_method,
                collection_stat: this.collection_stat,
                donation_id: this.donation_id,
                updated_by: this.updated_by
            })
            .then(response => (
                this.enableBtn = true,
                this.showSuccessMsg = true
            ))
            .catch(error => console.log(error))
        }
    }
}
</script>

<style>

</style>