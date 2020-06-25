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
            }
        },

        computed:{
            
        }, /* computed */

        mounted(){

        }, /* mounted */

        methods: {

            cancelModal(){
                this.$emit('fromModalId', [this.item, false])
                this.$bvModal.hide('verifier-id')
            },

        },

        watch: {

            donationId: function(val){

                this.errCredentials = ''

                var id = this.item.split("-")

                console.log(id[1])
                console.log(this.donationId)
                console.log(this.errCredentials)

                if(this.donationId == id[1]){
                    this.$emit('fromModalId', [this.item, true])
                    this.$forceUpdate()
                    this.$bvModal.hide('verifier-id')
                } else{
                    this.errCredentials = 'Donation ID did not match'
                }

            }
        }


    };

</script>