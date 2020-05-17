<template>
    <div class="main-div">
        <div class="row justify-content-center mb-5">

            <b-card bg-variant="dark" text-variant="white" no-body class="overflow-hidden">
                <b-row no-gutters>
                <b-col md="6">
                    <b-card-img src="../img/blood-donor.jpg" alt="Image" class="rounded-0"></b-card-img>
                    <!-- <b-card-body :title="cardTitle">
                        <b-card-text>
                            This is a wider card with supporting text as a natural lead-in to additional content.
                            This content is a little bit longer.
                        </b-card-text>
                    </b-card-body> -->
                </b-col>
                <b-col md="6">
                    <b-card-body title="User Login">
                    <hr>
                        <b-form-group
                            id="fieldset-1"
                            description="type-in your username."
                            label="Username"
                            label-for="username"
                            :invalid-feedback="invalidFeedback"
                            :valid-feedback="validFeedback"
                            :state="state">
                        <b-form-input id="username" type="text" v-model="username" :state="state" trim></b-form-input>
                        </b-form-group>

                        <b-form-group
                            id="fieldset-1"
                            description="type-in your password."
                            label="Password"
                            label-for="password"
                            :invalid-feedback="invalidFeedback"
                            :valid-feedback="validFeedback"
                            :state="state">
                        <b-form-input id="password" type="password" v-model="password" :state="state" trim></b-form-input>
                        </b-form-group>

                        <b-button block
                            b-icon="person"
                            variant="success"
                            size="small">
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
            cardTitle: 'Blood Donation'
        }
    }
}
</script>

<style scoped>

</style>