<template>
  <div class="main-div">
        <b-row id="bb-crumb-sticky">
          <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item active>
                        <b-icon icon="file-text" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Blood Testing
                    </b-breadcrumb-item>
                    <!-- <b-breadcrumb-item href="#home" active>
                        <b-icon icon="person-plus" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Register New Donor
                    </b-breadcrumb-item> -->
                </b-breadcrumb>
          </b-col>
        </b-row>

        <h4><b-icon icon="file-text"></b-icon> Blood Testing</h4>
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
                    @click.prevent="getDonationId()">
                    <b-icon icon="search"></b-icon>&nbsp;SEARCH
                </b-button>
            </b-col>
        </b-row>

        <b-alert show variant="danger" v-if="errMessage"><h3>{{ errMessage }}</h3></b-alert>

        <b-row>
            <b-col cols="4">
                <b-card-group deck v-if="donation_ids">
                    <b-card header="CLICK TO SELECT ID">

                        <b-form-checkbox
                            v-model="checkAll"
                            v-if="donation_ids"
                            style="margin-bottom: 10px;">
                            Check All
                        </b-form-checkbox>

                        <b-list-group>
                            <b-list-group-item  
                                v-for="(value, key) in donation_ids" 
                                v-bind:key="key">

                                <b-form-checkbox
                                    v-model="checked"
                                    :value="key"
                                    unchecked-value="0"
                                    >
                                    {{ key }}
                                </b-form-checkbox>
                                
                            </b-list-group-item>
                        </b-list-group>
                    </b-card>
                </b-card-group>
                 <b-card-group deck v-else>
                    <b-card header="CLICK TO SELECT ID">
                        <b-list-group>
                            {{ select_id_notice }}
                        </b-list-group>
                    </b-card>
                </b-card-group>               
            </b-col>
            
           <b-col cols="8">
                <b-row>
                    <b-col>
                        <template v-if="isLoading">
                            <div class="d-flex justify-content-center mb-3">
                                <b-spinner variant="danger" label="Please wait..."></b-spinner>
                            </div>
                        </template>
                        
                        <!-- <template> -->
                        <template v-if="data.length != 0">
                            <b-table
                                id="main-table"
                                responsive="sm"
                                striped hover
                                head-variant="light"
                                table-variant="light"
                                :fields="fields"
                                :items="data"
                                :per-page="perPage"
                                :current-page="currentPage">

                                <template v-slot:cell(donationId)="data">
                                    {{ data.item.donation_id }}
                                </template>

                                <template v-slot:cell(HBSAG)="data">
                                    <b-form-select :name="'HBSAG-' + data.item.donation_id" v-model="final_data[data.index]['HBSAG']" 
                                        :options="HBSAG" size="sm"></b-form-select>
                                </template>

                                <template v-slot:cell(HCV)="data">
                                    <b-form-select :name="'HCV-' + data.item.donation_id" v-model="final_data[data.index]['HCV']" 
                                    :options="HCV" size="sm"></b-form-select>
                                </template>

                                <template v-slot:cell(HIV)="data">
                                    <b-form-select :name="'HIV-' + data.item.donation_id" v-model="final_data[data.index]['HIV']" 
                                    :options="HIV" size="sm"></b-form-select>
                                </template>

                                 <template v-slot:cell(MALA)="data">
                                    <b-form-select :name="'MALA-' + data.item.donation_id" v-model="final_data[data.index]['MALA']" 
                                    :options="MALA" size="sm"></b-form-select>
                                </template>

                                <template v-slot:cell(RPR)="data">
                                    <b-form-select :name="'RPR-' + data.item.donation_id" v-model="final_data[data.index]['RPR']" 
                                    :options="RPR" size="sm"></b-form-select>
                                </template>


 
                            </b-table>

                            <!-- <b-pagination
                                v-model="currentPage"
                                :total-rows="rows"
                                :per-page="perPage"
                                aria-controls="main-table">
                            </b-pagination> -->

                            <b-row>
                                <b-col class="text-right">
                                    <b-button variant="success" @click="showModal">Submit</b-button>
                                    <b-button variant="danger">Cancel</b-button>
                                </b-col>
                            </b-row>

                        </template>

                        <template v-else>
                            <div class="alert alert-info">
                                <span class="text-center text-danger">
                                    <h5><b-icon icon="info-square"></b-icon>&nbsp;&nbsp;No selected donation ID</h5>
                                </span>
                            </div>
                        </template>

                    </b-col>
                </b-row>
            </b-col>

        </b-row>

        <!-- VERFIER MODAL POP-UP -->
        <verifier-modal @setUname="setUname"></verifier-modal>

        <!-- =============== MODALS ================ -->
        <!-- SHOW THIS MODAL AFTER SUCCESSFUL ACTION -->
        <b-modal v-model="showSuccessMsg" centered
            title="SUCCESS"
            header-bg-variant="success"
            body-bg-variant="light" 
            footer-bg-variant="success"
            header-text-variant="light"
            hide-header-close>
            
            <h5 class="alert-heading text-center">
                <b-icon icon="person-check"></b-icon>&nbsp;Blood Testing result/s has been successfully saved!
            </h5>
            
            <template v-slot:modal-footer="{ ok }">
                <b-link class="btn btn-success"
                    size="sm" variant="success" @click="ok()">
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

            fields: [
                { key: 'donationId', label: 'Donation ID' },
                { key: 'HBSAG', label: 'HepB' },
                { key: 'HCV', label: 'HepC' },
                { key: 'HIV', label: 'HIV' },
                { key: 'MALA', label: 'Malaria' },
                { key: 'RPR', label: 'Syphilis' },
            ],

            HBSAG: [
                { text: 'N', value: 'n' },
                { text: 'R', value: 'r' }
            ],
            HCV: [
                { text: 'N', value: 'n' },
                { text: 'R', value: 'r' }
            ],
            HIV: [
                { text: 'N', value: 'n' },
                { text: 'R', value: 'r' }
            ],
            MALA: [
                { text: 'Negative', value: 'n' },
                { text: 'Positive', value: 'r' }
            ],
            RPR: [
                { text: 'N', value: 'n' },
                { text: 'R', value: 'r' }
            ],

            data: [],
            isLoading: false,

            final_data:[],

            date_from: '',
            date_to: '',

            donation_ids: null,
            select_id_notice: "No Items to display",
            displayStatus: 0,

            checked: [],
            checkAll: 1,

            perPage: 10,
            currentPage: 1,

            errMessage: '',
        }
    }, /* data */

    mounted(){

    }, /* mounted */

    methods: {
        getDonationId (){
                       
            axios
                .post('/get-donation-id-testing-details', {
                    date_from: this.date_from,
                    date_to: this.date_to,
                })
                .then(response => {

                    if(response.data){
                        this.donation_ids = response.data
                        this.isLoading = false
                    } else{
                        this.donation_ids = null
                        this.select_id_notice = "No Data Found"
                    }
                    
                })

        },


        // MODAL
        showModal(){

            var err
            this.errMessage = ''

            err = this.checkError()

            if(err){
                this.errMessage = 'Please do not leave any blank inputs...'
            } else{
                this.$bvModal.show('verifier-login')
                // this.modalOpen = !this.modalOpen;
            }

        },

        checkError(){

            var err = false;

            this.final_data.forEach((v) => {
                if(v.HBSAG == "" || v.HCV == "" || v.HIV == "" || v.MALA == "" || v.RPR == ""){
                    return err = true
                }
            })

            return err

        },

        openModal() {
            this.modalOpen = !this.modalOpen;
        },

        // LAST METHOD
        setUname(e){

            axios
                .post('/save-blood-testing', {
                    blood_testing: this.final_data,
                    verifier: e,
                })
                .then(response => {

                    if(response.data){
                        // this.donation_ids = response.data
                        this.showSuccessMsg = true
                        this.checked = []
                        
                    }
                    
                })

            this.getDonationId()

        }

        
    }, /* methods */

    computed: {
        // pagination
        rows() {

        },
    }, /* computed */

    watch: {
        checked: function(val){
            // this.isLoading = true;
            // console.log(this.isLoading)

            this.data = []
            this.final_data = []

            val.forEach((v) => {
                this.data.splice(v,0,this.donation_ids[v])
                this.final_data.splice(v,0,this.donation_ids[v])
            })

            // this.isLoading = false

        },

        checkAll: function(val){

            if(val){
                this.data = []
                this.final_data = []

                var checked_list = []

                Object.keys(this.donation_ids).forEach(function (key){
                    checked_list.splice(key,0,key)
                })

                this.checked = checked_list

            } else{

                this.checked = []

            }
        },
    }, /* watch */
}
</script>

<style>

</style>