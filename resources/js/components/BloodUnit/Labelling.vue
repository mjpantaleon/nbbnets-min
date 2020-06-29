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
                        Blood Label
                    </b-breadcrumb-item>
                </b-breadcrumb>
          </b-col>
        </b-row>

        <h4><b-icon icon="file-text"></b-icon> Blood Label</h4>
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

        <b-alert show variant="danger" v-if="errMessage">{{ errMessage }}</b-alert>

        <b-row>
            <b-col >
                <b-row>
                    <b-col>
                        <template v-if="isLoading">
                            <div class="d-flex justify-content-center mb-3">
                                <b-spinner variant="danger" label="Please wait..."></b-spinner>
                            </div>
                        </template>
                        
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
                                <template v-slot:cell(donor)="data">
                                    <span v-if="data.item.donor_min">Done</span>
                                    <span v-else class="text-danger">PENDING</span>
                                </template>

                                <template v-slot:cell(bloodtype)="data">
                                    <span v-if="data.item.type">{{ data.item.type.blood_type }}</span> 
                                    <span v-else class="text-danger">PENDING</span>
                                </template>

                                <template v-slot:cell(bloodtest)="data">
                                    <!-- {{ hasAdditionalTest(data.item) }} -->
                                    <!-- <span v-if="data.item.test && hasAdditionalTest(data.item)">
                                        <span class="text-success" v-if="data.item.test.result == 'N'">NR</span>
                                        <span class="text-danger" v-if="data.item.test.result == 'R'">R</span>
                                    </span>
                                    <span v-if="!data.item.test || !hasAdditionalTest(data.item)" class="text-danger">PENDING</span> -->
                                    <span v-if="data.item.test && hasAdditionalTest(data.item) == false">
                                        <span class="text-success" v-if="data.item.test.result == 'N'">NR</span>
                                        <span class="text-danger" v-if="data.item.test.result == 'R'">R</span>
                                    </span>
                                    <span v-else class="text-danger">PENDING</span>
                                </template>

                                <template v-slot:cell(plasma)="data">
                                    <!-- {{ data.item.units }} -->
                                    <b-form-checkbox
                                        v-if="data.item.units.plasma"
                                        v-model="checked"
                                        :value="'80-' + data.item.donation_id"
                                        unchecked-value="0"
                                        :disabled="(!data.item.type || !data.item.test || !data.item.donor_min || hasAdditionalTest(data.item))"
                                        class="text-center"
                                        >
                                    </b-form-checkbox>
                                </template>

                                <template v-slot:cell(platelets)="data">
                                    <b-form-checkbox
                                        v-if="data.item.units.platelets"
                                        v-model="checked"
                                        :value="'81-' + data.item.donation_id"
                                        unchecked-value="0"
                                        :disabled="(!data.item.type || !data.item.test || !data.item.donor_min || hasAdditionalTest(data.item))"
                                        >
                                    </b-form-checkbox>                                    
                                </template>

                                <template v-slot:cell(redcell)="data">
                                    <b-form-checkbox
                                        v-if="data.item.units.red_cell"
                                        v-model="checked"
                                        :value="'82-' + data.item.donation_id"
                                        unchecked-value="0"
                                        :disabled="(!data.item.type || !data.item.test || !data.item.donor_min || hasAdditionalTest(data.item))"
                                        >
                                    </b-form-checkbox>                                    
                                </template>

                                <template v-slot:cell(whiteblood)="data">
                                    <b-form-checkbox
                                        v-if="data.item.units.white_blood_cell"
                                        v-model="checked"
                                        :value="'83-' + data.item.donation_id"
                                        unchecked-value="0"
                                        :disabled="(!data.item.type || !data.item.test || !data.item.donor_min || hasAdditionalTest(data.item))"
                                        >
                                    </b-form-checkbox>                                    
                                </template>

                                <template v-slot:cell(stemcell)="data">
                                    <b-form-checkbox
                                        v-if="data.item.units.stem_cell"
                                        v-model="checked"
                                        :value="'84-' + data.item.donation_id"
                                        unchecked-value="0"
                                        :disabled="(!data.item.type || !data.item.test || !data.item.donor_min || hasAdditionalTest(data.item))"
                                        >
                                    </b-form-checkbox>                                    
                                </template>
 
                            </b-table>

                            <b-row>
                                <b-col class="text-right">
                                    <b-button variant="success" @click="proceed()">Submit</b-button>
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

        <donation-id-modal @fromModalId="fromModalId" :item="lastChecked"></donation-id-modal>
        <preview-modal :data="previewData"></preview-modal>

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

import DonationIdModal from "../Tools/DonationIdModal.vue";
import PreviewModal from "../Tools/PreviewModal.vue";

export default {
    components: {
        DonationIdModal,
        PreviewModal
    },
    data(){
        return{
            showSuccessMsg: false,

            fields: [
                { key: 'donationId', label: 'Donation ID', thClass: 'text-center', tdClass: 'text-center'},
                { key: 'donor', label: 'DONOR', thClass: 'text-center', tdClass: 'text-center'},
                { key: 'bloodtype', label: 'BLOOD TYPE', thClass: 'text-center', tdClass: 'text-center'},
                { key: 'bloodtest', label: 'BLOOD TEST', thClass: 'text-center', tdClass: 'text-center'},
                { key: 'plasma', label: 'Plasma', thClass: 'text-center', tdClass: 'text-center' },
                { key: 'platelets', label: 'Platelets', thClass: 'text-center', tdClass: 'text-center' },
                { key: 'redcell', label: 'Red Cell', thClass: 'text-center', tdClass: 'text-center' },
                { key: 'whiteblood', label: 'White Blood Cell', thClass: 'text-center', tdClass: 'text-center' },
                { key: 'stemcell', label: 'Stem Cell', thClass: 'text-center', tdClass: 'text-center' },
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
            key: '',
            lastChecked: '',
            previewData: '',
            prev_checked: [],
            

        }
    }, /* data */

    mounted(){

    }, /* mounted */

    methods: {
        getDonationId (){

            axios
                .post('/get-donation-id-labelling', {
                    date_from: this.date_from,
                    date_to: this.date_to,
                })
                .then(response => {

                    if(response.data){
                        this.data = response.data.data
                        // this.checked = response.data.checked
                        // console.log()


                    } else{
                        this.data = null
                        // this.select_id_notice = "No Data Found"
                    }
                    
                })

        },

        hasAdditionalTest(d){

            if(!d.additionaltest){
                return false
            }else{
            //   if(d.additionaltest.nat != null && d.additionaltest.antibody != null){
                if(d.additionaltest.nat  && d.additionaltest.antibody){
                    return true
                }else{
                    return false
                }
            }
        },

        proceed(){

        },

        fromModalId(data){

            if(data[1]){

                this.printBloodBagLabel(data[0]);

                // display preview

                // this.previewData = data[0]

                // let url =  'http://'+window.location.host+window.location.pathname+'label?facility_cd='+facility_cd+'&donation_id='+donation_id+'&component_cd='+component_cd;
                // let url =  'http://'+window.location.host+'/preview?data='+data[0];

                // let winname = window.open(url,'winname','directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=375,height=270');
                // winname.onload = () => {
                //     // winname.print();
                //     winname.close();
                // };

            } else{
                this.deleteChecked(data[0])
            }
            
        },

        deleteChecked(data){

            var index = this.prev_checked.indexOf(data);

            if (index >= 0) {
                this.prev_checked.splice( index, 1 );
            }

            var index = this.checked.indexOf(data);

            if (index >= 0) {
                this.checked.splice( index, 1 );
            }

        },

    }, /* methods */

    computed:{

    }, /* computed */

    watch:{

        checked: function(val){

            if(val.length > this.prev_checked.length){  // Added check

                for (let i = 0; i < val.length; i++) {
                    if(this.prev_checked.includes(val[i])){
                        continue;
                    } else{
                        this.lastChecked = val[i]
                        this.prev_checked = val
                        // this.last
                        break;
                    }
                }

                this.$bvModal.show('verifier-id')


            } else{ // Removed check

                 for (let i = 0; i < this.prev_checked.length; i++) {
                    if(val.includes(this.prev_checked[i])){
                        continue;
                    } else{
                        this.deleteChecked(this.prev_checked[i])
                        break;
                    }
                }               


            }

        },
        
    }
}
</script>

<style>

</style>