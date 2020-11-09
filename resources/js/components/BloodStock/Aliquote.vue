<template>
  <div class="main-div">
        <b-row id="bb-crumb-sticky">
          <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item active>
                        <b-icon icon="file-text" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Aliquote
                    </b-breadcrumb-item>
                    <!-- <b-breadcrumb-item href="#home" active>
                        <b-icon icon="person-plus" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Register New Donor
                    </b-breadcrumb-item> -->
                </b-breadcrumb>
          </b-col>
        </b-row>

        <h4><b-icon icon="file-text"></b-icon> Aliquote</h4>
        <hr>

        <b-row>
            <b-col col xl="6" lg="6" md="12" sm="12" xs="12">

                <b-card 
                    body-class="sticker-preview"
                    header-bg-variant="danger"
                    header-text-variant="white">

                    <template v-slot:header>

                        <b-container>
                            <b-row>
                                <b-col cols="3">
                                    <h6>Blood Unit</h6>
                                </b-col>
                                <b-col cols="5">
                                </b-col>
                                <b-col cols="4" class="text-right">
                                    <router-link to="/available-stocks" class="btn btn-secondary btn-sm">
                                        <b-icon icon="arrow-left"></b-icon> Back to List
                                    </router-link>
                                </b-col>
                            </b-row>
                        </b-container>

                    </template>

                    <b-card-body class="text-center" style="padding: 0;">
                        <iframe id="preview" :src="uri" width="400" height="270" frameborder="0"></iframe>
                    </b-card-body>

                </b-card>

            </b-col>

            <b-col col xl="6" lg="6" md="12" sm="12" xs="12">

                <b-card 
                    v-if="show_aliquote == true"
                    body-class="sticker-preview"
                    header-bg-variant="success"
                    header-text-variant="white">

                    <template v-slot:header>
                        <h6>ALIQOUTED UNITS</h6>
                    </template>

                    <b-card-body class="text-center" style="padding: 0;">
                            <b-table
                                id="main-table"
                                responsive="sm"
                                striped hover
                                head-variant="light"
                                table-variant="light"
                                :fields="fields"
                                :items="data.data"
                                :per-page="perPage"
                                :current-page="currentPage">

                                <template v-slot:cell(volume)="data">
                                    {{ data.item.component_vol }}
                                </template>

                                <template v-slot:cell(aliquotedBy)="data">
                                    {{ data.item.created_by }}
                                </template>

                                <template v-slot:cell(dateCreated)="data">
                                    {{ data.item.created_dt }}
                                </template>

                                <template v-slot:cell(status)="data">
                                    {{ data.item.comp_stat }}
                                </template>

                                <!-- <template v-slot:cell(volume)="data">
                                    {{ data.item.blood_type }}
                                </template> -->

                                 <!-- <template v-slot:cell(action)="data"> -->
                                    <!-- {{ data.item.expiration_dt }} -->
                                    <!-- <b-button variant="success" @click="proceed()" size="sm">
                                        <b-icon icon="search"></b-icon>
                                    </b-button> -->
                                    <!-- <router-link :to="'/unit/'+data.item.donation_id+'/'+data.item.component_cd" title="View Blood Unit Details">
                                        <b-icon icon="search" class="border border-success p-1" variant="success" font-scale="2.1"></b-icon>
                                    </router-link>
                                </template> -->
                            </b-table>

                            <b-input-group
                                class="mb-3">
                                <b-form-input type="number" v-model="aliquote_val"></b-form-input>
                                <b-input-group-append>
                                    <b-button @click="showModal()">NEW ALIQUOTE</b-button>
                                </b-input-group-append>
                            </b-input-group>


                    </b-card-body>

                </b-card>

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
                <b-icon icon="person-check"></b-icon>&nbsp;Aliquote Successfull!
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
            fields: [
                { key: 'volume', label: 'VOLUME' },
                { key: 'aliquotedBy', label: 'ALIQOUTED BY' },
                { key: 'dateCreated', label: '	DATE CREATED' },
                { key: 'status', label: 'STATUS' },
                // { key: 'action', label: '' },
            ],
            data: [],
            isLoading: false,

            uri: '',

            // pagination
            perPage: 10,
            currentPage: 1,

            showSuccessMsg: false,
            
            show_aliquote: false,
            aliquote_val: 0
        }
    }, /* data */

    mounted(){

        if(this.$route.params.mtd == 'P'){
            this.uri = '/preview?data='+this.$route.params.id+","+this.$route.params.mtd
        } else{
            this.uri = '/preview?data='+this.$route.params.comp+"-"+this.$route.params.id+","+this.$route.params.mtd
            this.getAliquotes(this.$route.params.id, this.$route.params.comp)
        }

    }, /* mounted */

    methods: {
        async getAliquotes(id, comp){
            this.isLoading = true

            await axios
                .post('/get-aliqoute-wb',{
                    donation_id: id,
                    comp: comp
                })
                .then(response => (
                    this.data = response.data,
                    this.show_aliquote = response.data.display_aliquote
                ))
                .catch(error => console.log(error))
        },
        showModal(){
            if(this.aliquote_val){
                this.$bvModal.show('verifier-login')
            }
        },

        async setUname(e){

            await axios
                .post('/save-aliquote-wb', {
                    aliquote_val: this.aliquote_val,
                    data: this.data,
                    donation_id: this.$route.params.id,
                    comp: this.$route.params.comp,
                    verifier: e,
                })
                .then(response => {
                    if(response.data){
                        // this.donation_ids = response.data
                        this.showSuccessMsg = true
                    }
                })

            this.getAliquotes(this.$route.params.id, this.$route.params.comp)

        }

    }, /* methods */

    computed: {
        // pagination
        rows() {

        },
    }, /* computed */
}
</script>

<style>

</style>