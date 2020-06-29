<template>

        <b-modal id="verifier-id" centered class="modal" no-close-on-backdrop>
            <template v-slot:modal-header>
                <h5>Re-scan Donation ID</h5>
            </template>

            <template v-slot:default>

                <b-alert show variant="danger" v-if="errCredentials">{{ errCredentials }}</b-alert>

                <b-form-group>
                    <b-form-input type="text" v-model="donationId" trim></b-form-input>
                </b-form-group>

            </template>

            <template v-slot:modal-footer>
            <!-- Emulate built in modal footer ok and cancel button actions -->
                <b-button 
                    size="sm" 
                    variant="danger" 
                    @click="cancelModal()">
                        Cancel
                </b-button>
            </template>
        </b-modal>

</template>

<script>

    export default {
        name: 'donation-id-modal',

        props: {
            item: String,
        },

        data(){
            return{
                donationId: '',
                errCredentials: '',
                modalHide: true,
            }
        },

        computed:{
            
        }, /* computed */

        mounted(){
            this.$root.$on('bv::modal::show', (bvEvent, modalId) => {
                if(modalId == 'verifier-id'){
                    this.donationId = ''
                    this.errCredentials = ''
                }
            })
            this.$root.$on('bv::modal::shown', (bvEvent, modalId) => {
                if(modalId == 'verifier-id'){
                    this.modalHide = false
                }
            })

        }, /* mounted */

        methods: {

            cancelModal(){
                this.$bvModal.hide('verifier-id')
                this.$emit('fromModalId', [this.item, false])
            },

        },

        watch: {

            donationId: function(val){

                this.errCredentials = ''

                var id = this.item.split("-")

                if(this.donationId && this.modalHide == false){
                    if(this.donationId == id[1]){
                        this.$emit('fromModalId', [this.item, true])
                        this.modalHide = true
                        this.$bvModal.hide('verifier-id')
                    } else{
                        this.errCredentials = 'Donation ID did not match'
                    }
                } else{
                    this.donationId = ''
                    this.errCredentials = ''
                }

            }
        }


    };

</script>