<template>
    <div class="main-div">
        <div class="row justify-content-center mb-5">

            <b-card bg-variant="dark" text-variant="white" no-body class="overflow-hidden">
                <b-row no-gutters>
                <b-col md="6">
                    <b-card-img src="../img/bg-login.png" alt="Image" class="rounded-0"></b-card-img>
                </b-col>
                <b-col md="6">
                    <b-card-body title="User Login">
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
                            size="small">
                            <b-icon @click.prevent="login()" icon="person-bounding-box"></b-icon> LOGIN</b-button>
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

    methods: {
        login(){
            // code here
        }
    }
}
</script>

<style scoped>

</style>