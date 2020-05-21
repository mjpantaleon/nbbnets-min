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
                                    description="Collection date"
                                    label="Date Collected"
                                    label-for="collection_date">

                                <b-form-datepicker v-model="created_dt" id="collection_date"></b-form-datepicker>
                                </b-form-group>  
                                {{ created_dt }}
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
                                <b-form-input v-model="fname" id="fname"></b-form-input>
                                </b-form-group>

                                <b-form-group
                                    id="fieldset-horizontal"
                                    label-cols-sm="4"
                                    label-cols-lg="3"
                                    description="Middle"
                                    label-for="mname">
                                <b-form-input v-model="mname" id="mname"></b-form-input>
                                </b-form-group>

                                <b-form-group
                                    id="fieldset-horizontal"
                                    label-cols-sm="4"
                                    label-cols-lg="3"
                                    description="Last Name"
                                    label-for="lname">
                                <b-form-input v-model="lname" id="lname"></b-form-input>
                                </b-form-group>

                                <b-form-group
                                    id="fieldset-horizontal"
                                    label-cols-sm="4"
                                    label-cols-lg="3"
                                    description="Suffix"
                                    label-for="suffix">
                                <b-form-input v-model="name_suffix" id="suffix"></b-form-input>
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
                                    label-for="type_of_donor">
                                    <b-form-select v-model="selected_donor_type" :options="type_of_donor" id="type_of_donor"></b-form-select>
                                </b-form-group>
                                {{ selected_donor_type }}
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
                                    label-for="mh_pe_result">
                                <b-form-select v-model="selected_mh_pe" :options="mh_pe_result" id="mh_pe_result"></b-form-select>
                                </b-form-group>
                                {{ selected_mh_pe }}
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
                                    label-for="collection_method">
                                <b-form-select v-model="selected_method" :options="collection_method" id="collection_method"></b-form-select>
                                </b-form-group>
                                {{ selected_method }}
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
                                    label-for="collection_status">
                                <b-form-select v-model="selected_status" :options="collection_status" id="collection_method"></b-form-select>
                                </b-form-group>
                                {{ selected_status }}
                            </td>
                        </tr>

                        <template v-if="selected_mh_pe == 'A'">
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
                                <b-button block variant="success">
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
        fname: '',
        mname: '',
        lname: '',
        name_suffix: '',

        created_dt: new Date(),
        donation_id: '',
        updated_by: '',
        password: '',

        selected_donor_type: 'V',
        selected_mh_pe: 'A',
        selected_method: 'WB',
        selected_status: 'COL',

        type_of_donor: [
          { value: 'AU', text: 'Autologous' },
          { value: 'V', text: 'Voluntary' },
          { value: 'REP', text: 'Family/ Replacement' },
          { value: 'PAID', text: 'Paid' },
        ],

        mh_pe_result: [
            { value: 'A', text: 'ACCEPTED' },
            { value: 'TD', text: 'TEMPORARY DEFERRED' },
            { value: 'PD', text: 'PERMANENTLY DEFERRED' },
            { value: 'ID', text: 'INDEFINITELY DEFFERED' },
        ],

        collection_method: [
          { value: 'WB', text: 'Whole Blood' },
          { value: 'AP', text: 'Apheresis' },
        ],

        collection_status: [
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
        }
    }
}
</script>

<style>

</style>