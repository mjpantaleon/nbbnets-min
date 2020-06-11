<template>
    <div class="main-div">
        <div class="row justify-content-center mb-5">

            <div v-if="displayBlank">

            </div>

            <b-card v-else bg-variant="dark" text-variant="white" no-body class="overflow-hidden">
                <b-row no-gutters>
                <b-col md="6">
                    <b-card-img src="../img/bg-login.png" alt="Image" class="rounded-0"></b-card-img>
                </b-col>
                <b-col md="6">
                    <b-card-body title="User Login">
                    <b-alert :show="showError" variant="danger" v-if="errorMessage">{{errorMessage}}</b-alert>
                    <hr>
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
                    </b-card-body>
                </b-col>
                </b-row>
            </b-card>
        </div>
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
        getUser(){
            axios
                .get('/get-user')
                .then(response => {

                    if(response.data.status){
                        this.$router.push('pre-screened-list')
                    } else{
                        this.displayBlank = false
                    }
                })
        },
        
        login(){

            this.showError = false

            axios
                .post('/login-attempt', {
                    userId: this.username,
                    password: this.password
                })
                .then(response => {

                    if(response.data.status){
                        this.$store.state.isLogged = true
                        // this.$store.state.userInfo = response.data.user
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