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
                        HLA & HNA
                    </b-breadcrumb-item>
                </b-breadcrumb>

          </b-col>
        </b-row>

        <h4><b-icon icon="droplet-half" variant="warning"></b-icon> HLA & HNA</h4>
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
                    @click.prevent="getComponentsForHnaTest()">
                    <b-icon icon="search"></b-icon>&nbsp;SEARCH
                </b-button>
            </b-col>
        </b-row>

        <template v-if="isLoading">
        <b-row>
            <b-col class="text-center">
                <b-spinner variant="danger" label="Please wait..."></b-spinner>
            </b-col>
        </b-row>
        </template>

        <template v-if="data.length != 0">
            <b-row>
                <b-col>
                    <b-table striped bordered
                        responsive="sm"
                        :fields="hna_fields"
                        :items="data">

                        <template v-slot:cell(donor)="data">
                            {{ data.item.first_name }} {{ data.item.middle_name ? data.item.middle_name : null }} {{ data.item.last_name }} {{ data.item.name_suffix ? data.item.name_suffix : null }}
                        </template>

                        <template v-slot:cell(gender)="data">
                            <span class="text-primary" v-if="data.item.gender == 'F'">Female</span>
                            <span class="text-danger" v-else>Male</span>
                        </template>

                        <template v-slot:cell(donation_id)="data">
                            <b-form-input placeholder="Scan Donation ID" v-model="data.item.donation_id"></b-form-input>
                        </template>


                        <template v-slot:cell(test_1)="data">
                            <b-form-select v-model="data.item.test_1" :options="hna_option"></b-form-select>
                        </template>
                        
                        <template v-slot:cell(test_2)="data">
                            <b-form-select v-model="data.item.test_2" :options="hna_option"></b-form-select>
                        </template>

                        <template v-slot:cell(test_3)="data">
                            <b-form-select v-model="data.item.test_3" :options="hna_option"></b-form-select>
                        </template>
                    </b-table>
                </b-col>
            </b-row>

            <b-row>
                <b-col md="4">
                    <b-button block
                        variant="success"
                        @click.prevent="showModal">
                        <b-icon icon="check-circle"></b-icon>&nbsp;SUBMIT HNA & HNL RESULTS
                    </b-button>
                </b-col>
            </b-row>
        </template>

        <template v-else>
            <b-row>
                <b-col class="text-center">No record/s to display</b-col>
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
            isLoading: false,
            showSuccessMsg: false,

            date_from: '',
            date_to: '',

            data: [],

            message: '',

            hna_option: [
                { text: 'Positive', value: 'P' },
                { text: 'Negative', value: 'N' }
            ],

            hna_fields: [
                { key: 'donor', label: 'Donor Name' },
                { key: 'gender', label: 'Gender' },
                { key: 'donation_id', label: 'Donation ID' },
                { key: 'test_1', label: 'Test 1' },
                { key: 'test_2', label: 'Test 2' },
                { key: 'test_3', label: 'Test 3' },
            ]

        }
    }, /* data() */


    methods: {
        async getComponentsForHnaTest(){
            this.isLoading = true

            await axios
            .post('/donor-list-for-hla-hna', {
                date_from: this.date_from,
                date_to: this.date_to
            })
            .then(response => {
                if(response.data){
                    this.isLoading = false
                    this.data = response.data
                } else {
                    this.isLoading = false
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

        async setUname(e){
            this.isLoading = true

            await axios
                .post('/save-hla-hna-result', {
                    hla_hna_results: this.data,
                    verifier: e,
                })
                .then(response => {

                    if(response.data){
                        this.isLoading = false
                        this.message = response.data.message
                        this.showSuccessMsg = true
                        this.getComponentsForHnaTest()
                    }
                    
                })

        }
    }
}
</script>

<style>

</style>