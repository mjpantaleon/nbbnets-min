<template>
  <div class="main-div">
      <b-row id="bb-crumb-sticky">
          <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item active>
                        <b-icon icon="droplet-half" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Blood Testing
                    </b-breadcrumb-item>
                    <!-- <b-breadcrumb-item href="#home" active>
                        <b-icon icon="person-plus" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Register New Donor
                    </b-breadcrumb-item> -->
                </b-breadcrumb>
          </b-col>
        </b-row>

        <h4><b-icon icon="droplet-half"></b-icon> Blood Testing</h4>
        <hr>

        <b-alert show variant="danger" v-if="errMessage"><h3>{{ errMessage }}</h3></b-alert>
        <!-- {{HBSAG}} -->
        <b-row>
            <b-col>
                <table class="table table-bordered table-light">
                    <thead>
                        <th>Donation ID</th>
                        <th>HBsag</th>
                        <th>HCV</th>
                        <th>HIV</th>
                        <th>Malaria</th>
                        <th>Syhpilis</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <b-form-input name="donation_id"
                                    :state="checkID"
                                    placeholder="Scan Donation ID" v-model="donation_id" required></b-form-input>
                            </td>
                            <td>
                                <b-form-select name="HBSAG" 
                                    :options="HBSAG_OPT" v-model="HBSAG" size="sm"></b-form-select>
                            </td>
                            <td>
                                <b-form-select name="HCV"
                                    :options="HCV_OPT" v-model="HCV" size="sm"></b-form-select>
                            </td>
                            <td>
                                <b-form-select name="HIV" 
                                    :options="HIV_OPT" v-model="HIV" size="sm"></b-form-select>
                            </td>
                            <td>
                                <b-form-select name="MALA" 
                                    :options="MALA_OPT" v-model="MALA" size="sm"></b-form-select>
                            </td>
                            <td>
                                <b-form-select name="RPR" 
                                    :options="RPR_OPT" v-model="RPR" size="sm"></b-form-select>
                            </td>
                        </tr>

                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </b-col>
        </b-row>

        <b-row>
            <b-col md="4" class="text-left">
                <b-button variant="success"
                    block 
                    :disabled="disableBtn"
                    @click.prevent="showModal">
                    <b-icon icon="check-circle"></b-icon>&nbsp;Submit</b-button>
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
                <b-link class="btn btn-success" :to="{ path: '/pre-screened-list' }"
                    size="sm" variant="success" @click="ok()">
                    OK
                </b-link>
            </template>
        </b-modal>


        <b-modal v-model="showErrorMsg" centered
            title="STOP"
            header-bg-variant="danger"
            body-bg-variant="light" 
            footer-bg-variant="danger"
            header-text-variant="light"
            hide-header-close>
            
            <h5 class="alert-heading text-center">
                <b-icon variant="danger" icon="exclamation-octagon-fill"></b-icon>&nbsp;This Donation ID already exist! You cannot proceed the Blood Testing.
            </h5>
            
            <template v-slot:modal-footer="{ ok }">
                <b-link class="btn btn-danger"
                    size="sm" variant="danger" @click="ok()">
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
            showErrorMsg: false,
            disableBtn: false,

            donation_id: '',

            HBSAG: '',
            HCV: '',
            HIV: '',
            MALA: '',
            RPR: '',

            
            HBSAG_OPT: [
                { text: 'N', value: 'n' },
                { text: 'R', value: 'r' }
            ],
            HCV_OPT: [
                { text: 'N', value: 'n' },
                { text: 'R', value: 'r' }
            ],
            HIV_OPT: [
                { text: 'N', value: 'n' },
                { text: 'R', value: 'r' }
            ],
            MALA_OPT: [
                { text: 'Negative', value: 'n' },
                { text: 'Positive', value: 'r' }
            ],
            RPR_OPT: [
                { text: 'N', value: 'n' },
                { text: 'R', value: 'r' }
            ],
            displayBtn: false,
            donationExist: false,
            errMessage: '',
            
        }
    }, /* data */

    computed: {
        checkID(){
            return this.donation_id.length > 15 ? true : false
        },
    }, /* computed */

    methods: {
        
        // MODAL
        showModal(){
            // alert('button has been clicked');
            var err
            this.errMessage = ''

            // check first for errors
            err = this.checkError()

            // if there were errors found
            if(err){
                // return this error message
                this.errMessage = 'Please do not leave any blank inputs...';
            } else{
                // show verifier form
                this.$bvModal.show('verifier-login')
                // this.modalOpen = !this.modalOpen;
            }

        },

        checkError(){

            var err = false;
            
            if(this.donation_id == "" || this.HBSAG == "" || this.HCV == "" || this.HIV == "" || this.MALA == "" || this.RPR == ""){
                return err = true
            }
            
            return err

        },


        // LAST METHOD
        setUname(e){

            // console.log(e)
            axios
                .post('/save-blood-test-result/' + this.$route.params.id, {
                    donor_sn :  this.$route.params.id,
                    donation_id : this.donation_id,
                    HBSAG : this.HBSAG,
                    HCV : this.HCV,
                    HIV : this.HIV,
                    MALA : this.MALA,
                    RPR : this.RPR,
                    verifier: e,
                })
                .then(response => {

                    if(response.data.status){
                        
                        // this.donation_ids = response.data
                        this.showSuccessMsg = true
                        this.disableBtn = true
                        
                    } else{
                        this.showErrorMsg = true
                    }
                    
                })
        }
    }
}
</script>

<style>

</style>