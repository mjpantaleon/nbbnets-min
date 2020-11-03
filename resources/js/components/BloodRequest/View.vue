<template>
  <div class="main-div">
       <b-row id="bb-crumb-sticky">
          <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item active>
                        <b-icon icon="file-text" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Blood Stocks
                    </b-breadcrumb-item>
                    <b-breadcrumb-item :to="{ path: '/blood-request/list' }">
                        <b-icon icon="file-text" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Blood Request
                    </b-breadcrumb-item>
                    <b-breadcrumb-item active>
                        Blood Request Details
                    </b-breadcrumb-item>
                </b-breadcrumb>
          </b-col>
        </b-row>

        <h4><b-icon icon="file-text" variant="primary"></b-icon> Blood Request Details</h4>
        <hr>
        
        <b-row>
            <b-col cols="4">
                <!-- <h4 class="text-secondary"><b-icon icon="clipboard"></b-icon> Blood Request Details</h4> -->
                
                <b-table-simple stacked>
                    <b-tbody>
                        <b-tr>
                            <b-td stacked-heading="Blood Type: ">
                                <h1 class="text-success">{{ blood_type }}</h1>
                            </b-td>
                            <b-td stacked-heading="Request ID: ">
                                {{ request_id }}
                            </b-td>
                            <b-td stacked-heading="Reference #: ">
                                {{ reference }}
                            </b-td>
                            <b-th stacked-heading="Status: ">
                                <span class="text-danger" v-if="status == 'FLU'">FOR LOOK UP</span>
                                <span class="text-success" v-if="status == 'RES'">RESERVED</span>
                            </b-th>
                            <b-td stacked-heading="Patient ID: ">
                                {{ patient_id }}
                            </b-td>
                            <b-td stacked-heading="Patient Name: ">
                                {{ firstname }} {{ middlename ? middlename : '' }} {{ lastname }} {{ name_suffix ? name_suffix : '' }}
                            </b-td>
                        </b-tr>

                        <b-tr v-for="(detail, j) in details" :key="j">
                            <template v-if="detail.donation_id == null">
                            <b-td  stacked-heading="For look Up - " variant="danger">
                                {{ detail.component_name.comp_name }}
                            </b-td>
                            </template>

                            <template v-if="detail.donation_id != null">
                            <b-td  stacked-heading="Reserved - " variant="success">
                                {{ detail.component_name.comp_name }}
                            </b-td>
                            </template>
                        </b-tr>
                    </b-tbody>
                </b-table-simple>

                
            </b-col>
            <!-- {{selected_blood_type}} -->
            <!-- AVAILABLE BLOOD UNITS BASED ON -->
            <b-col cols="8">
                <!-- BLOOD TYPE SELECTION -->
                <b-row>
                    <b-col cols="6">
                        <b-form-group
                            id="fieldset-horizontal"
                            label-cols-sm="4"
                            label-cols-lg="4"
                            description="Select civil status"
                            label="Select Blood Type"
                            label-for="blood_types">
                            <b-form-select v-model="selected_blood_type" 
                                :options="blood_types" id="blood_types"></b-form-select>
                        </b-form-group>
                    </b-col>

                    <b-col>
                        <b-button block
                            variant="warning"
                            @click.prevent="getAvailableCpUnits()">
                            <b-icon icon="check-circle"></b-icon>&nbsp;SEARCH AVAILABLE UNITS
                        </b-button>
                    </b-col>
                </b-row>

               
                <b-row>      
                    <b-table striped bordered
                        :fields="fields"
                        :items="data"
                        :per-page="perPage"
                        :current-page="currentPage"
                        id="main-table">
                            <template v-slot:cell(selection)="data">
                                <b-form-checkbox
                                v-model="data.item.selected_item"
                                value=true
                                unchecked-value=false></b-form-checkbox>
                            </template>

                            <template v-slot:cell(donation_id)="data">
                                {{ data.item.donation_id }}
                            </template>

                            <template v-slot:cell(blood_type)="data">
                                {{ data.item.blood_type }}
                            </template>

                            <template v-slot:cell(cp_component)="data">
                                {{ data.item.comp_name }}
                            </template>

                            <template v-slot:cell(status)="data">
                                <span class="text-success" v-if="data.item.comp_stat == 'AVA'">Available</span>
                            </template>
                    </b-table>

                    <!-- pagination -->
                    <template v-if="data.length != 0">
                    <b-pagination
                        v-model="currentPage"
                        :total-rows="rows"
                        :per-page="perPage"
                        aria-controls="main-table">
                    </b-pagination>
                    </template>
                </b-row>

                <b-row class="mt-3" v-if="data.length != 0">
                    <b-col md="4">
                        <template>
                        <b-button block type="submit" variant="success"
                            @click.prevent="showModal()">
                            <b-icon icon="check-circle"></b-icon>&nbsp;RESERVE BLOOD UNITS 
                        </b-button>
                        </template>
                    </b-col>
                </b-row>

                <template v-if="isLoading">
                <b-row>
                    <b-col class="text-center">
                        
                        <b-spinner variant="danger" label="Please wait..."></b-spinner>
                    </b-col>
                </b-row>
                </template>

                <template v-if="data.length == 0">
                <b-row>
                    <b-col>
                        <div class="alert alert-info mt-3">
                            <span class="text-center text-danger">
                                <h5><b-icon icon="info-square"></b-icon> No record/s to display</h5>
                            </span>
                        </div>
                    </b-col>
                </b-row>
                </template>
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
            isLoading: false,
            message: '',

            data: [],

            request_id: '',
            reference: '',
            status: '',

            patient_id: '',

            firstname: '',
            middlename: '',
            lastname: '',
            name_suffix: '',

            blood_type: '',

            // details
            details: '',

            selected_blood_type: '',
            blood_types: [
                { value: 'A Pos', text: 'A Pos' },
                { value: 'A Neg', text: 'A Neg' },
                { value: 'B Pos', text: 'B Pos' },
                { value: 'B Neg', text: 'B Neg' },
                { value: 'AB Pos', text: 'AB Pos' },
                { value: 'AB Neg', text: 'AB Neg' },
                { value: 'O Pos', text: 'O Pos' },
                { value: 'O Neg', text: 'O Neg' },
            ],

            selected: '',

            fields: [
                { key: 'selection', label: 'Selection' },
                { key: 'donation_id', label: 'Donation ID' },
                { key: 'blood_type', label: 'Blood Type' },
                { key: 'cp_component', label: 'Component' },
                { key: 'status', label: 'Status' },
            ],

            // pagination
            perPage: 10,
            currentPage: 1,
        }
    },/* data */

    mounted(){
        this.getForLookUp()
        // this.getAvailableCpUnits()

    }, /* mounted */

    methods: {
        async getForLookUp(){
            await axios
            .get('/for-look-up/' + this.$route.params.id)
            .then(response => (
                // this.data = response.data

                this.request_id = response.data.request_id,
                this.reference = response.data.reference,
                this.status = response.data.status,

                this.patient_id = response.data.patient_id,

                this.firstname = response.data.patient_min.firstname,
                this.middlename = response.data.patient_min.middlename,
                this.lastname = response.data.patient_min.lastname,
                this.name_suffix = response.data.patient_min.name_suffix,

                this.blood_type = response.data.patient_min.blood_type,

                this.details = response.data.details
            ))
        },

        async getAvailableCpUnits(){
            this.isLoading = true

            await axios
            .post('/available-cp-components', {
                selected_blood_type: this.selected_blood_type
            })
            .then(response => {
                
                if(response.data){
                    this.data = response.data
                    this.isLoading = false
                } else{
                    this.data = []
                    this.isLoading = false
                }
            })
        },

        showModal(){   
            this.$bvModal.show('verifier-login');
            // this.modalOpen = !this.modalOpen;
        },


        openModal() {
            this.modalOpen = !this.modalOpen;
        },

        async setUname(e){

            var post_data = this.getChecked()
            // this.details

            await axios
            .post('/reserve-blood-units',  {
                post_data,
                component_details : this.details,

                // request_id : this.details[0].request_id,
                // request_component_id : this.details[0].request_component_id,
            })
            .then(response => {

                // this.data = response.data
                if(response.data){
                    this.message = response.data.message
                    this.showSuccessMsg = true
                    this.getForLookUp()
                    this.getAvailableCpUnits()
                    
                    console.log(response.data)
                }
            })

        },

        // async reserveBloodUnits(){

        //     var post_data = this.getChecked()
        //     // this.details

        //     await axios
        //     .post('/reserve-blood-units',  {
        //         post_data,
        //         component_details : this.details,

        //         // request_id : this.details[0].request_id,
        //         // request_component_id : this.details[0].request_component_id,
        //     })
        //     .then(response => {

        //         // this.data = response.data
        //         if(response.data){
                    
        //             console.log(response.data)
        //         }
        //     })
        // },

        getChecked(){

            var ret = []

            this.data.forEach((v) => {
                if(v.selected_item == "true"){
                    ret.push(v)
                }
            })

            return ret

        }
    }, /* methods */

    computed: {
        // pagination
        rows() {
            return this.data.length
        },
    },

    watch:{
        data: function(val){
            console.log(val)
        }
    }
    
}
</script>

<style>

</style>