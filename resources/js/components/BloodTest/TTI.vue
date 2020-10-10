<template>
  <div class="main-div">
      <b-row id="bb-crumb-sticky">
          <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item active>
                        <b-icon icon="droplet-half" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Blood Testing
                    </b-breadcrumb-item>
                    <b-breadcrumb-item active>
                        <b-icon icon="droplet-half" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        TTI Blood Test
                    </b-breadcrumb-item>
                </b-breadcrumb>

          </b-col>
        </b-row>

        <h4><b-icon icon="droplet-half"></b-icon> TTI Blood Test</h4>
        <hr>

        <b-row>
            <b-col cols="5">
                <b-form-group
                    id="donation-id"           
                    label-cols-sm="2"
                    label-cols-lg="2"
                    description="Date From"
                    label="Date From"
                    label-for="donation-id">
                        <b-form-datepicker 
                            v-model="date_from"
                            class="mb-2">
                        </b-form-datepicker>
                </b-form-group>
            </b-col>
            <b-col cols="5">
                <b-form-group
                    id="donation-id"           
                    label-cols-sm="1"
                    label-cols-lg="1"
                    description="Search Date To"
                    label="To"
                    label-for="donation-id">
                        <b-form-datepicker
                            v-model="date_to"
                            class="mb-2">
                        </b-form-datepicker>
                </b-form-group>
            </b-col>
            <b-col cols="2" class="ml-auto">
                <b-button type="submit"
                    variant="warning"
                    @click.prevent="getApprovedDonorList()">
                    <b-icon icon="search"></b-icon>&nbsp;SEARCH
                </b-button>
            </b-col>
        </b-row>
        
        <!-- DONT FORGET TO PLACE LOOP HERE -->

        <template v-if="data.length != 0">
        <b-row>
            <b-col>
                <b-table striped hover
                    responsive="sm"
                    :fields="tti_fields"
                    :items="data">

                    <template v-slot:cell(donor)="data">
                        {{ data.item.first_name }} {{ data.item.middle_name ? data.item.middle_name : null }} {{ data.item.last_name }} {{ data.item.name_suffix ? data.item.name_suffix : null }}    
                    </template>

                    <template v-slot:cell(donation_id)="data">
                        <b-form-input placeholder="Scan Donation ID" v-model="data.item.donation_id"></b-form-input>
                        
                    </template>

                    <template v-slot:cell(HBSAG)="data">
                         <b-form-select v-model="data.item.HBSAG" :options="hbsag_option"></b-form-select>
                    </template>

                    <template v-slot:cell(HCV)="data">
                        <b-form-select v-model="data.item.HCV" :options="hcv_option"></b-form-select>
                    </template>

                    <template v-slot:cell(HIV)="data">
                        <b-form-select v-model="data.item.HIV" :options="hiv_option"></b-form-select>
                    </template>

                    <template v-slot:cell(MALA)="data">
                        <b-form-select v-model="data.item.MALA" :options="mala_option"></b-form-select>
                    </template>

                    <template v-slot:cell(RPR)="data">
                        <b-form-select v-model="data.item.RPR" :options="rpr_option"></b-form-select>
                    </template>

                </b-table>
            </b-col>
        </b-row> 

        <b-row>
            <b-col md="4">
                <b-button block
                    variant="success"
                    @click.prevent="showModal">
                    <b-icon icon="check-circle"></b-icon>&nbsp;SUBMIT TEST RESULTS
                </b-button>
            </b-col>
        </b-row>
        </template>

        <template v-else>
            <b-row>
                <b-col class="text-center">
                    No record/s to display
                </b-col>    
            </b-row>   
        </template>

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
                <b-link class="btn btn-info"
                    size="sm" variant="info" @click="ok()">
                    OK
                </b-link>
            </template>
        </b-modal>
        <!-- =============== MODALS ================ -->

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
            showSuccessMsg: false,
            date_from: '',
            date_to: '',

            data: [],

            message: '',
            
            // donation_id: '',
            // HBSAG: '',
            // HCV: '',
            // HIV: '',
            // MALA: '',
            // RPR: '',
            
            hbsag_option: [
                { text: 'N', value: 'n' },
                { text: 'R', value: 'r' }
            ],
            hcv_option: [
                { text: 'N', value: 'n' },
                { text: 'R', value: 'r' }
            ],
            hiv_option: [
                { text: 'N', value: 'n' },
                { text: 'R', value: 'r' }
            ],
            mala_option: [
                { text: 'Negative', value: 'n' },
                { text: 'Positive', value: 'r' }
            ],
            rpr_option: [
                { text: 'N', value: 'n' },
                { text: 'R', value: 'r' }
            ],

            tti_fields: [
                { key: 'donor', label: 'Donor' },
                { key: 'donation_id', label: 'Donation ID' },
                { key: 'HBSAG', label: 'Hbsag' },
                { key: 'HCV', label: 'Hcv' },
                { key: 'HIV', label: 'Hiv' },
                { key: 'MALA', label: 'Malaria' },
                { key: 'RPR', label: 'Syphilis' }
            ]
        }
    }, /* data */

    methods: {
        async getApprovedDonorList(){
            await axios
            .post('/get-approved-donor-list',{
                date_from: this.date_from,
                date_to: this.date_to,
            })
            .then(response => {
                if(response.data){
                    this.data = response.data
                } else {
                    this.data = []
                }
            })
        },
        showModal(){
            this.$bvModal.show('verifier-login')
            // this.modalOpen = !this.modalOpen;
        },

        openModal() {
            this.modalOpen = !this.modalOpen;
        },

        setUname(e){

            axios
                .post('/save-tti-blood-test', {
                    blood_testing: this.data,
                    verifier: e,
                })
                .then(response => {

                    if(response.data){
                        this.message = response.data.message
                        this.showSuccessMsg = true
                        this.getApprovedDonorList()
                    }
                    
                })

            // this.getDonationId()

        }

    }, /* methods */

    
}
</script>

<style>

</style>