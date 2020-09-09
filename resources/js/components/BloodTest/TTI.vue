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
                        TTI
                    </b-breadcrumb-item>
                </b-breadcrumb>

                <h4><b-icon icon="droplet-half"></b-icon> TTI</h4>
                <hr>
          </b-col>
        </b-row>

        <b-alert show variant="danger" v-if="errMessage"><h3>{{ errMessage }}</h3></b-alert>

        <b-row>
            <b-col>
                <table class="table table-striped table-bordered">
                    <thead>
                        <th>Donation ID</th>
                        <th>HBSAG</th>
                        <th>HCV</th>
                        <th>HIV</th>
                        <th>Malaria</th>
                        <th>Syphilis</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <b-form-input v-model="donationID" placeholder="Scan Donation ID"></b-form-input>
                            </td>

                            <td>
                                <b-form-select v-model="hbsag" :options="HBSAG"></b-form-select>
                            </td>

                            <td>
                                <b-form-select v-model="hcv" :options="HCV"></b-form-select>
                            </td>  

                            <td>
                                <b-form-select v-model="hiv" :options="HIV"></b-form-select>
                            </td> 

                            <td>
                                <b-form-select v-model="malaria" :options="MALA"></b-form-select>
                            </td>  

                            <td>
                                <b-form-select v-model="syphilis" :options="RPR"></b-form-select>
                            </td> 
                        </tr>

                        
                    </tbody>
                </table>
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

            donationID: null,
            hbsag: null,
            hcv: null,
            hiv: null,
            malaria: null,
            syphilis: null,

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