<template>
    <div class="main-div">
        <b-row id="bb-crumb-sticky">
          <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item active>
                        <b-icon icon="file-text" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Blood Unit
                    </b-breadcrumb-item>
                    <b-breadcrumb-item active>
                        <!-- <b-icon icon="person-plus"></b-icon>  -->
                        Blood Processing
                    </b-breadcrumb-item>
                </b-breadcrumb>
          </b-col>
        </b-row>

        <h4><b-icon icon="file-text"></b-icon> Blood Processing</h4>
        <hr>

        <b-row>
            <b-col cols="4">
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
            <b-col cols="4">
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
            <b-col cols="2">
                <!-- <b-form-group
                    id="fieldset-horizontal"
                    label-cols-sm="4"
                    label-cols-lg="3"> -->
                <b-form-select v-model="col_method" :options="col_method_list" id="col_method"></b-form-select>
                <!-- </b-form-group> -->
            </b-col>
            <b-col cols="2" class="ml-auto">
                <b-button type="submit"
                    variant="warning"
                    @click.prevent="getDonationId()">
                    <b-icon icon="search"></b-icon>&nbsp;SEARCH
                </b-button>
            </b-col>
        </b-row>

        <b-alert show variant="danger" v-if="errMessage">{{ errMessage }}</b-alert>

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
                        
                        <template v-if="data.length != 0">

                            <b-table
                                v-if="col_method == 'P'"
                                id="main-table"
                                responsive="sm"
                                striped hover
                                head-variant="light"
                                table-variant="light"
                                :fields="pheresis_fields"
                                :items="data"
                                :per-page="perPage"
                                :current-page="currentPage">

                                <template v-slot:cell(donationId)="data">
                                    {{ data.item.donation_id }}
                                </template>

                                <template v-slot:cell(p-01)="data">
                                    <b-form-input v-model="final_data[data.index]['P01']" :name="data.item.donation_id + '-01'" size="sm"></b-form-input>
                                </template>

                                <template v-slot:cell(p-02)="data">
                                    <b-form-input v-model="final_data[data.index]['P02']" :name="data.item.donation_id + '-02'" size="sm"></b-form-input>
                                </template>
 
                            </b-table>

                            <b-table
                                v-else
                                id="main-table"
                                responsive="sm"
                                striped hover
                                head-variant="light"
                                table-variant="light"
                                :fields="wb_fields"
                                :items="data"
                                :per-page="perPage"
                                :current-page="currentPage">

                                <template v-slot:cell(donationId)="data">
                                    {{ data.item.donation_id }}
                                </template>

                                <template v-slot:cell(bag)="data">
                                    {{ data.item.blood_bag }}
                                </template>

                                <template v-slot:cell(plasma)="data">
                                    <b-form-input v-model="final_data[data.index]['plasma']" :name="'platelets-' + data.item.donation_id" size="sm"></b-form-input>
                                </template>

                                <template v-slot:cell(redbloodcell)="data">
                                    <b-form-input v-model="final_data[data.index]['redbloodcell']" :name="'redbloodcell-' + data.item.donation_id" size="sm"></b-form-input>
                                </template>

                                <template v-slot:cell(platelets)="data">
                                    <b-form-input v-model="final_data[data.index]['platelets']" :name="'platelets-' + data.item.donation_id" size="sm"></b-form-input>
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
                <b-icon icon="person-check"></b-icon>&nbsp;Blood Typing entry has been successfully added!
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

            pheresis_fields: [
                { key: 'donationId', label: 'Donation ID'},
                { key: 'p-01', label: 'Aliquote -01', thClass: 'text-center aliquote-column-width', tdClass: 'text-center' },
                { key: 'p-02', label: 'Aliquote -02', thClass: 'text-center aliquote-column-width', tdClass: 'text-center' },
            ],

            wb_fields: [
                { key: 'donationId', label: 'Donation ID'},
                { key: 'bag', label: 'Blood Bag', thClass: 'text-center', tdClass: 'text-center' },
                { key: 'plasma', label: 'Plasma', thClass: 'text-center wb-column-width', tdClass: 'text-center' },
                { key: 'redbloodcell', label: 'Packed Red Blood Cell', thClass: 'text-center wb-column-width', tdClass: 'text-center' },
                { key: 'platelets', label: 'Platelet Concentrate', thClass: 'text-center wb-column-width', tdClass: 'text-center' },
            ],

            // fields: [
            //     { key: 'donationId', label: 'Donation ID'},
            //     { key: 'plasma', label: 'Plasma', thClass: 'text-center', tdClass: 'text-center' },
            //     { key: 'platelets', label: 'Platelets', thClass: 'text-center', tdClass: 'text-center' },
            //     { key: 'redcell', label: 'Red Cell', thClass: 'text-center', tdClass: 'text-center' },
            //     { key: 'whiteblood', label: 'White Blood Cell', thClass: 'text-center', tdClass: 'text-center' },
            //     { key: 'stemcell', label: 'Stem Cell', thClass: 'text-center', tdClass: 'text-center' },
            // ],

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
            col_method:'WB',

            col_method_list: [
                { value: 'WB', text: 'Whole Blood' },
                { value: 'P', text: 'Pheresis' },
                { value: 'WBP', text: 'Whole Blood Pheresis' },
            ],
            

        }
    }, /* data */

    mounted(){

    }, /* mounted */

    methods: {
        getDonationId (){

            axios
                .post('/get-donation-id-processing', {
                    date_from: this.date_from,
                    date_to: this.date_to,
                    col_method: this.col_method,
                })
                .then(response => {

                    if(response.data){
                        console.log(response.data)
                        this.donation_ids = response.data
                    } else{
                        this.donation_ids = null
                        this.select_id_notice = "No Data Found"
                    }
                    
                })

        },

        showModal(){

            // var err
            // this.errMessage = ''

            // err = this.checkError()

            // console.log(this.final_data)

            // if(err){
            //     this.errMessage = 'Please fill up all fields'
            // } else{
                this.$bvModal.show('verifier-login')
                // this.modalOpen = !this.modalOpen;
            // }

        },

        // checkError(){

        //     var err = false;

        //     this.final_data.forEach((v) => {
        //         if(v.plasma == "" || v.platelets == "" || v.redcell == "" || v.whiteblood == "" || v.stemcell == ""){
        //             return err = true
        //         }
        //     })

        //     return err

        // },

        openModal() {
            this.modalOpen = !this.modalOpen;
        },

        setUname(e){

            axios
                .post('/save-blood-processing', {
                    blood_processing: this.final_data,
                    col_method: this.col_method,
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

    computed:{

    }, /* computed */

    watch:{
        checked: function(val){

            this.data = []
            this.final_data = []

            val.forEach((v) => {
                this.data.splice(v,0,this.donation_ids[v])
                this.final_data.splice(v,0,this.donation_ids[v])
            })

        },

        final_data: function(val){
            
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
        
    }
}
</script>

<style>

</style>