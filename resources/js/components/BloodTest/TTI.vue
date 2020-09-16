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

          </b-col>
        </b-row>

        <h4><b-icon icon="droplet-half"></b-icon> TTI</h4>
        <hr>

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
                        <th>&nbsp;</th>
                    </thead>
                    <tbody>
                        <tr v-for="(input,i) in inputs" :key="i">
                            <td>
                                <b-form-input v-model="input.donation_id" placeholder="Scan Donation ID"></b-form-input>
                            </td>

                            <td>
                                <b-form-select v-model="input.HBSAG" :options="hbsag_option"></b-form-select>
                            </td>

                            <td>
                                <b-form-select v-model="input.HCV" :options="hcv_option"></b-form-select>
                            </td>  

                            <td>
                                <b-form-select v-model="input.HIV" :options="hiv_option"></b-form-select>
                            </td> 

                            <td>
                                <b-form-select v-model="input.MALA" :options="mala_option"></b-form-select>
                            </td>  

                            <td>
                                <b-form-select v-model="input.RPR" :options="rpr_option"></b-form-select>
                            </td> 

                            <td>
                                <b-avatar variant="danger"
                                    icon="trash-fill"
                                    button
                                    @click="remove(i)" v-show="i || ( !i && inputs.length > 1)">
                                    </b-avatar>

                                <template v-if="i || ( !i && inputs.length > 1)">&nbsp;|&nbsp;</template>

                                <b-avatar variant="primary"
                                    button
                                    icon="plus-circle-fill"
                                    @click="add(i)" v-show="i == inputs.length-1">
                                    </b-avatar>
                            </td>
                        </tr>                       
                    </tbody>
                </table>
            </b-col>
        </b-row>

        <b-row>
            <b-col md="3">
                <b-button variant="success"
                    block
                    :disabled="disableBtn"
                    @click.prevent="showModal">
                    <b-icon icon="check-circle-fill"></b-icon>&nbsp;SUBMIT TEST RESULT
                </b-button>
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
            showErrorMsg: false,
            disableBtn: false,

            inputs: [
                { 
                    donation_id: '',
                    HBSAG: '',
                    HCV: '',
                    HIV: '',
                    MALA: '',
                    RPR: '',
                }
            ],

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

            isLoading: false,
            errMessage: '',
        }
    }, /* data */

    mounted(){

    }, /* mounted */

    methods: {
        add () {
        this.inputs.push({
            donation_id: '',
            HBSAG: '',
            HCV: '',
            HIV: '',
            MALA: '',
            RPR: '',
        })
        console.log(this.inputs)
        },

        remove (index) {
        this.inputs.splice(index, 1)
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

            this.inputs.forEach((v) => {
                if(v.donation_id == "" || v.HBSAG == "" || v.HCV == "" || v.HIV == "" || v.MALA == "" || v.RPR == ""){
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
                    blood_testing: this.inputs,
                    verifier: e,
                })
                .then(response => {

                    if(response.data){
                        // this.showSuccessMsg = true
                        // this.disableBtn = true
                    } else{
                        this.showErrorMsg = true
                    }
                })

            // this.getDonationId()
        }

        
    }, /* methods */

    computed: {
        
    }, /* computed */

    watch: {
        // checked: function(val){
        //     // this.isLoading = true;
        //     // console.log(this.isLoading)

        //     this.data = []
        //     this.final_data = []

        //     val.forEach((v) => {
        //         this.data.splice(v,0,this.donation_ids[v])
        //         this.final_data.splice(v,0,this.donation_ids[v])
        //     })

        //     // this.isLoading = false

        // },

        // checkAll: function(val){

        //     if(val){
        //         this.data = []
        //         this.final_data = []

        //         var checked_list = []

        //         Object.keys(this.donation_ids).forEach(function (key){
        //             checked_list.splice(key,0,key)
        //         })

        //         this.checked = checked_list

        //     } else{

        //         this.checked = []

        //     }
        // },
    }, /* watch */
}
</script>

<style>

</style>