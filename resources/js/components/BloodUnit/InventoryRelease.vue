<template>
    <div class="main-div">
        <b-row id="bb-crumb-sticky">
          <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item active>
                        <b-icon icon="file-text" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Blood Stocks
                    </b-breadcrumb-item>
                    <b-breadcrumb-item active>
                        Release to Inventory
                    </b-breadcrumb-item>
                </b-breadcrumb>
          </b-col>
        </b-row>

        <h4><b-icon icon="file-text"></b-icon> Release to Inventory</h4>
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
                                    <span v-if="data.item.test && hasAdditionalTest(data.item) == false">
                                        <span class="text-success" v-if="data.item.test.result == 'N'">NR</span>
                                        <span class="text-danger" v-if="data.item.test.result == 'R'">R</span>
                                    </span>
                                    <span v-else class="text-danger">PENDING</span>
                                </template>

                                <template v-slot:cell(plasma)="data">
                                    <div v-if="data.item.units.plasma"> 
                                        <!-- <div v-if="data.item.units.has82">
                                            <b-icon icon="check" variant="success" class="h5 border border-info rounded" style=""></b-icon>
                                        </div> -->
                                        <b-form-checkbox
                                            v-if="data.item.units.plasma"
                                            v-model="checked"
                                            :value="'80-' + data.item.donation_id"
                                            unchecked-value="0"
                                            :disabled="(!data.item.type || !data.item.test || !data.item.donor_min || hasAdditionalTest(data.item))"
                                            class="text-center"
                                            >
                                        </b-form-checkbox>
                                        <div v-else>
                                            <b-icon :icon="bvicon" class="h5 rounded"></b-icon>
                                        </div>
                                    </div>

                                </template>

                                <template v-slot:cell(platelets)="data">
                                    <div v-if="data.item.units.platelets"> 
                                        <b-form-checkbox
                                            v-if="data.item.units.has81"
                                            v-model="checked"
                                            :value="'81-' + data.item.donation_id"
                                            unchecked-value="0"
                                            :disabled="(!data.item.type || !data.item.test || !data.item.donor_min || hasAdditionalTest(data.item))"
                                            >
                                        </b-form-checkbox>
                                        <div v-else>
                                            <b-icon :icon="bvicon" class="h5 rounded"></b-icon>
                                        </div>
                                    </div>                                   
                                </template>

                                <template v-slot:cell(redcell)="data">
                                    <div v-if="data.item.units.red_cell"> 
                                        <b-form-checkbox
                                            v-if="data.item.units.has82"
                                            v-model="checked"
                                            :value="'82-' + data.item.donation_id"
                                            unchecked-value="0"
                                            :disabled="(!data.item.type || !data.item.test || !data.item.donor_min || hasAdditionalTest(data.item))"
                                            >
                                        </b-form-checkbox>
                                        <div v-else>
                                            <b-icon :icon="bvicon" class="h5 rounded"></b-icon>
                                        </div>
                                    </div>                                  
                                </template>

                                <template v-slot:cell(whiteblood)="data">
                                    <div v-if="data.item.units.white_blood_cell">
                                        <b-form-checkbox
                                            v-if="data.item.units.has83"
                                            v-model="checked"
                                            :value="'83-' + data.item.donation_id"
                                            unchecked-value="0"
                                            :disabled="(!data.item.type || !data.item.test || !data.item.donor_min || hasAdditionalTest(data.item))"
                                            >
                                        </b-form-checkbox>
                                        <div v-else>
                                            <b-icon :icon="bvicon" class="h5 rounded"></b-icon>
                                        </div>
                                    </div>                                
                                </template>

                                <template v-slot:cell(stemcell)="data">
                                    <div v-if="data.item.units.stem_cell">
                                        <b-form-checkbox
                                            v-if="data.item.units.has84"
                                            v-model="checked"
                                            :value="'84-' + data.item.donation_id"
                                            unchecked-value="0"
                                            :disabled="(!data.item.type || !data.item.test || !data.item.donor_min || hasAdditionalTest(data.item))"
                                            >
                                        </b-form-checkbox>
                                        <div v-else>
                                            <b-icon :icon="bvicon" class="h5 rounded"></b-icon>
                                        </div>
                                    </div>

                                </template>
 
                            </b-table>

                            <b-row>
                                <b-col class="text-right">
                                    <b-button variant="success" @click="proceed()" :disabled="proceed_disabled">Proceed</b-button>
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
                <b-icon icon="person-check"></b-icon>&nbsp;Blood Label entry has been successfully added!
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
            proceed_disabled: true,

            bvicon: 'upc-scan',
            

        }
    }, /* data */

    mounted(){

    }, /* mounted */

    methods: {
        getDonationId (){

            axios
                .post('/get-donation-id-release', {
                    date_from: this.date_from,
                    date_to: this.date_to,
                })
                .then(response => {

                    if(response.data){
                        this.data = response.data.data
                    } else{
                        this.data = null
                    }
                    
                })

        },

        hasAdditionalTest(d){

            if(!d.additionaltest){
                return false
            }else{
            //   if(d.additionaltest.nat != null && d.additionaltest.antibody != null){
                if(d.additionaltest.nat && d.additionaltest.antibody){
                    return true
                }else{
                    return false
                }
            }
        },

        proceed(){
            this.$bvModal.show('verifier-login')
        },

        fromModalId(data){

            if(data[1]){
                // display preview
                this.printBloodBagLabel(data[0]);

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

        setUname(e){

            axios
                .post('/save-blood-labelling', {
                    label_data: this.checked,
                    verifier: e,
                })
                .then(response => {

                    if(response.data){
                        this.showSuccessMsg = true
                        this.checked = []
                    }
                    
                })

            this.getDonationId()

        },

        hasNotSaved(cid, data){

            var ret = true

            if(data){
                for (let i = 0; i < data.length; i++) {
                    if(data[i].component_cd == cid){
                        ret = false
                        break;   
                    }
                }
                
                return ret

            } else{
                return ret
            }

            console.log(cid + ' ' + ret)

        }

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

            if(val.length){
                this.proceed_disabled = false
            } else{
                this.proceed_disabled = true
            }

        },
        
    }
}
</script>

<style>

</style>