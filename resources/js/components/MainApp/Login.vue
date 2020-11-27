<template>
    <div class="main-div">
        <div class="row justify-content-center mb-5">
            <b-row>
                <div v-if="displayBlank"></div>

                <b-col v-else md="6" offset="3">
                    <b-card-img src="../img/cp-icon.png" alt="Image" class="rounded-0"></b-card-img>
                    
                    <b-form-group
                            id="fieldset-1"
                            description="type-in your username."
                            label="Username"
                            label-for="username">
                        <b-form-input id="username" type="text" v-model="username" :state="checkUN" trim></b-form-input>
                    </b-form-group>

                    <b-form-group
                            id="fieldset-1"
                            description="type-in your password."
                            label="Password"
                            label-for="password">
                        <b-form-input id="password" type="password" v-model="password" :state="checkPW" trim></b-form-input>
                    </b-form-group>

                    <b-button block
                            b-icon="person"
                            variant="success"
                            size="small"
                            @click.prevent="login()">
                    <b-icon icon="person-bounding-box"></b-icon> LOGIN</b-button>
                </b-col>

                <b-col md="6">
                </b-col>
            </b-row>
        </div>


        <b-modal v-model="showError" centered
            title="Error"
            header-bg-variant="danger"
            body-bg-variant="light" 
            footer-bg-variant="danger"
            header-text-variant="light"
            hide-header-close>
            
            <h5 class="alert-heading text-center">
                <b-icon icon="person-bounding-box"></b-icon>&nbsp;{{ errorMessage }}
            </h5>
            
            <template v-slot:modal-footer="{ ok }">
                <b-link class="btn btn-danger"
                    size="sm" variant="danger" @click="ok()">
                    OK
                </b-link>
            </template>
        </b-modal>

    </div>
</template>

<script>
export default {
    data(){
        return{
            cardTitle: 'Blood Donation',
            username: '',
            password: '',
            showError: false,
            errorMessage: '',
            displayBlank: true,
        }
    }, /* data */

    computed:{
        checkUN(){
            return this.username.length >= 5 ? true : false
        },
        checkPW(){
            return this.password.length > 3 ? true : false
        }
    }, /* computed */

    mounted(){
        this.getUser()
    }, /* mounted */

    methods: {
        async getUser(){
            await axios
                .get('/get-user')
                .then(response => {

                    if(response.data.status){
                        this.$router.push('pre-screened-list')
                    } else{
                        this.displayBlank = false
                    }
                })
        },
        
        async login(){
            await axios
                .post('/login-attempt', {
                    userId: this.username,
                    password: this.password
                })
                .then(response => {

                    if(response.data.status){
                        this.$store.state.isLogged = true
                        this.$router.push('pre-screened-list')
                    } else{
                        this.showError = true
                        this.errorMessage = response.data.error
                    }
                })
        }
    }
}
</script>

<style scoped>

</style>