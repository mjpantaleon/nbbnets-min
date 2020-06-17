<template>

        <b-modal id="verifier-login" centered class="modal">
            <template v-slot:modal-header>
                <h5>Enter Verifier Credentials</h5>
            </template>

            <template v-slot:default>

                <b-alert show variant="danger" v-if="errCredentials">{{ errCredentials }}</b-alert>

                <b-form-group
                    id="fieldset-1"
                    label="Verifier Username"
                    label-for="username">
                        <b-form-input id="username" type="text" v-model="verifierUname" :state="checkUN" trim></b-form-input>
                </b-form-group>

                <b-form-group
                    id="fieldset-1"
                    label="Verifier Password"
                    label-for="password">
                        <b-form-input id="password" type="password" v-model="verifierPass" :state="checkPW" trim></b-form-input>
                </b-form-group>


            </template>

            <template v-slot:modal-footer>
            <!-- Emulate built in modal footer ok and cancel button actions -->
                <b-button 
                    size="sm" 
                    variant="success" 
                    @click="verifierLogin()">
                        Proceed
                </b-button>
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
        name: 'Modal',
        props: [
            'item'
        ],

        data(){
            return{
                verifierUname: '',
                verifierPass: '',
                errCredentials: '',
            }
        },

        computed:{
            checkUN(){
                return this.verifierUname.length >= 5 ? true : false
            },
            checkPW(){
                return this.verifierPass.length > 3 ? true : false
            }
        }, /* computed */

        mounted(){
            // console.log(this.value)
        }, /* mounted */

        methods: {

            verifierLogin(){

                this.errCredentials = ''

                if(this.verifierUname && this.verifierPass){

                    axios
                        .post('verifier-credentials', {
                            username: this.verifierUname,
                            password: this.verifierPass,
                        })
                        .then(response => {

                            if(response.data.status == true){
                                this.$emit('setUname', response.data.data)
                                this.$bvModal.hide('verifier-login')
                            } else{
                                this.errCredentials = response.data.message
                            }
                            
                        })

                } else{
                    this.errCredentials = 'Please fill up all fields'
                }
            },

            cancelModal(){
                this.$bvModal.hide('verifier-login')
            },

        }

    };

</script>