<template>
  <div class="main-div">
        <b-row id="bb-crumb-sticky">
          <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item :to="{ path: '/pre-screened-list' }">
                        <b-icon icon="file-text" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Pre-Screened List
                    </b-breadcrumb-item>
                    <b-breadcrumb-item active>
                        <b-icon icon="file-richtext" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Pre-Screened Information
                    </b-breadcrumb-item>
                    <!-- <b-breadcrumb-item href="#home" active>
                        <b-icon icon="person-plus" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Register New Donor
                    </b-breadcrumb-item> -->
                </b-breadcrumb>
          </b-col>
        </b-row>

        <h4><b-icon icon="file-richtext"></b-icon> Pre-Screened Information</h4>
        <hr>
        
        <!-- DONOR DETAILS -->
        <b-row>
            <b-col md="8">
                <b-jumbotron bg-variant="light">
                    <template v-slot:header>
                        <b-icon icon="person-bounding-box"></b-icon>&nbsp;
                        {{first_name}} {{middle_name ? middle_name : ''}} {{last_name}} {{name_suffix ? name_suffix : ''}}
                    </template>

                    <template v-slot:lead>
                        <b-row>
                            <b-col><b><b-icon icon="info-square"></b-icon>&nbsp;Status:</b></b-col>
                            <b-col>
                                <b-badge class="float-right" v-if="status == 0" variant="danger">FOR REVIEW</b-badge>
                                <b-badge class="float-right" v-else variant="success">APPROVED</b-badge>
                            </b-col>
                        </b-row>

                        <b-row>
                            <b-col>
                                <b><b-icon icon="calendar"></b-icon>&nbsp;Screening date:</b> 
                            </b-col>
                            <b-col>
                               <span class="float-right">
                                   <b class="text-primary">{{ created_dt | moment("MMMM DD, YYYY - h:mm:ss a") }}</b></span>
                            </b-col>
                        </b-row>

                        <b-row>
                            <b-col class="lead"><b><b-icon icon="chat"></b-icon>&nbsp;Contact preferences:</b></b-col>
                            <b-col class="lead">
                                <ul>
                                    <li v-if="email != ''" class="text-primary  float-right"><b>{{email}}</b></li>
                                    <li v-if="fb != ''" class="text-primary  float-right"><b>{{fb}}</b></li>
                                    <li v-if="mobile_no != ''" class="text-primary  float-right"><b>{{mobile_no}}</b></li>
                                </ul>
                            </b-col>
                        </b-row>
                        <b-row>
                        </b-row>

                        <b-row>
                            <b-col class="lead">
                                <b><b-icon icon="phone"></b-icon>&nbsp;Time of Availability:&nbsp;
                                    <span class="text-primary float-right">{{ time_availability }}</span></b>
                                </b-col>
                        </b-row>

                    </template>
                    <hr>

                    <!-- <b-row>
                        <b-col class="lead">
                            <span><b>Birthday:</b> {{ bdate | moment("MMMM DD, YYYY") }}</span>
                        </b-col>
                    </b-row> -->

                    <b-row>
                        <b-col class="lead">
                            <span><b>Age:</b> {{ age }} y/o</span>
                        </b-col>
                    </b-row>    

                    <b-row>
                        <b-col class="lead">
                            <span><b>Weight:</b> {{ weight }} kg</span>
                        </b-col>
                    </b-row>

                    <b-row>
                        <b-col class="lead">
                            <span v-if="gender == 'M'"><b>Gender:</b> Male</span>
                            <span v-else><b>Gender:</b> Female</span>
                        </b-col>
                    </b-row>
                    
                    <b-row>
                        <b-col class="lead">
                            <span><b>Nationality:</b> {{ nationality ? nationality = 'Filipino' : ''}}</span>
                        </b-col>
                    </b-row>

                    <b-row>
                        <b-col class="lead">
                            <span><b>Address:</b> {{address ? address : '' }}</span>
                        </b-col>
                    </b-row>
                    
                </b-jumbotron>
            </b-col>
        </b-row>

        <!-- PRE - SCREENING DETAILS -->
        <b-row>
            <b-col md="8">
                <b-card no-body>
                    <b-tabs card active-nav-item-class="font-weight-bold text-primary">
                        <b-tab title="Pre - Screening Details" active>
                            <b-row>
                                <b-col class="lead">1. Have been diagnosed to have COVID-19?</b-col>
                            </b-row>
                            <b-row>
                                <b-col class="lead">
                                    <ul>
                                        <li v-if="first_answer == 0" class="text-success"><b><b-icon icon="check"></b-icon>&nbsp;YES</b></li>
                                        <li v-else-if="first_answer == 2" class="text-success"><b><b-icon icon="check"></b-icon>&nbsp;NO</b></li>
                                        <li v-else class="text-success"><b><b-icon icon="check"></b-icon>&nbsp;NOT SURE</b></li>
                                    </ul>
                                </b-col>
                            </b-row>
                            <hr>


                            <template v-if="second_answers">
                            <b-row>
                                <b-col class="lead">2. Have the following result/s:</b-col>
                            </b-row>
                            <b-row>
                                <b-col class="lead">
                                    <ul v-for="(second_answer, i) in second_answers" :key="i">
                                        <li class="text-success" v-if="second_answer == 'a'"><b><b-icon icon="check"></b-icon>&nbsp;RT-PCR POSITIVE&nbsp;</b></li> 
                                        <li class="text-success" v-if="second_answer == 'b'"><b><b-icon icon="check"></b-icon>&nbsp;RT-PCR NEGATIVE&nbsp;</b></li> 
                                        <li class="text-success" v-if="second_answer == 'c'"><b><b-icon icon="check"></b-icon>&nbsp;NO TEST RESULTS</b></li> 
                                    </ul>
                                </b-col>
                            </b-row>
                            <hr>
                            </template>

                            <!-- CONDITIONAL ROW IF FIRST_ANSWER IS = 1 OR  SECOND_ANSWER = C THEN SHOW THIS ROW-->
                            <template  v-if="first_answer == 1 || second_answers == 'c' || not_sure_answers">
                                <b-row>
                                    <b-col class="lead">Symptoms felt for twenty eight (28) days:</b-col>
                                </b-row>
                                <b-row>
                                    <b-col class="lead">
                                            <ul v-for="(not_sure_answer, i) in not_sure_answers" :key="i">
                                                <li class="text-success" v-if="not_sure_answer == 'a'"><b><b-icon icon="check"></b-icon>&nbsp;Cough&nbsp;</b></li> 
                                                <li class="text-success" v-if="not_sure_answer == 'b'"><b><b-icon icon="check"></b-icon>&nbsp;Fever (C 38.0)&nbsp;</b></li> 
                                                <li class="text-success" v-if="not_sure_answer == 'c'"><b><b-icon icon="check"></b-icon>&nbsp;Difficulty Breathing</b></li> 
                                                <li class="text-success" v-if="not_sure_answer == 'd'"><b><b-icon icon="check"></b-icon>&nbsp;Diarrhea</b></li> 
                                                <li class="text-success" v-if="not_sure_answer == 'e'"><b><b-icon icon="check"></b-icon>&nbsp;Fatigue</b></li> 
                                                <li class="text-success" v-if="not_sure_answer == 'f'"><b><b-icon icon="check"></b-icon>&nbsp;Body aches and pain</b></li> 
                                            </ul>
                                        
                                    </b-col>
                                </b-row>
                            </template>

                        </b-tab>
                    </b-tabs>
                </b-card>
            </b-col>
        </b-row>

        <b-row class="mt-3">
            <b-col md="4">
                <template v-if="status == '0'">
                <b-button block type="submit" variant="success"
                    :disabled="enableBtn"
                    @click.prevent="submitVerdict()">
                    <b-icon icon="check-circle"></b-icon>&nbsp;APPROVE 
                </b-button>
                </template>
            </b-col>
            <b-col md="4">
                <b-link class="btn btn-warning float-right" :to="{ path: '/pre-screened-list' }">
                    <b-icon icon="box-arrow-left"></b-icon>&nbsp;BACK TO LIST 
                </b-link>
            </b-col>
        </b-row>

        <!-- =============== MODALS ================ -->

        <!-- SHOW THIS MODAL AFTER SUCCESSFUL ACTION -->
        <b-modal v-model="showSuccessMsg" centered
            title="SUCCESS"
            header-bg-variant="success"
            body-bg-variant="light" 
            footer-bg-variant="success"
            header-text-variant="light"
            hide-header-close
            no-close-on-backdrop>
            
            <h5 class="alert-heading text-center">
                <b-icon icon="person-check"></b-icon>&nbsp;This person has been approved as Donor
            </h5>
            
            <template v-slot:modal-footer="{ ok }">
                <b-link class="btn btn-success" :to="{ path: '/pre-screened-list' }"
                    size="sm" variant="success" @click="ok()">
                    OK
                </b-link>
            </template>
        </b-modal>

        <!-- =============== MODALS ================ -->



  </div>
</template>

<script>
export default {
    data(){
        return {
            showSuccessMsg: false,
            enableBtn: false,

            first_name: '',
            middle_name: '',
            last_name: '',
            name_suffix: '',

            weight: '',
            age: '',

            gender: '',
            bdate: '',

            email: '',
            fb: '',
            mobile_no: '',

            nationality: '',
            address: '',

            time_availability: '',

            created_dt: '',
            status: '',

            first_answer: '',
            second_answers: '',
            not_sure_answers: '',
            availability: ''
        }
    }, /* data */

    mounted(){
        this.getPreScreenedDonor();
    }, /* mounted */

    methods: {
        getPreScreenedDonor(){
            axios
            .get('/pre-screened-donor/' + this.$route.params.id)
            .then(response => (
                this.first_name = response.data.first_name,
                this.middle_name = response.data.middle_name,
                this.last_name = response.data.last_name,
                this.name_suffix = response.data.name_suffix,

                this.weight = response.data.weight,
                this.age = response.data.age,

                this.gender = response.data.gender,
                this.bdate = response.data.bdate,

                this.email = response.data.email,
                this.fb = response.data.fb,
                this.mobile_no = response.data.mobile_no,

                this.nationality = response.data.nationality,
                this.address = response.data.address,

                this.created_dt = response.data.created_dt,
                this.status = response.data.status,

                this.first_answer = response.data.first_answer,
                this.second_answers = response.data.second_answer,
                this.not_sure_answers = response.data.not_sure_answer,
                this.time_availability = response.data.time_availability

            ))
        },

        submitVerdict(){
            axios
            .post('/pre-screened-update/' + this.$route.params.id, {
                first_name : this.first_name,
                middle_name : this.middle_name,
                last_name : this.last_name,
                name_suffix : this.name_suffix,

                gender : this.gender,
                bdate : this.bdate,
                // civil_stat : this.civil_stat,
                // occupation : this.occupation,
                email : this.email,
                nationality : this.nationality,
                // tel_no : this.tel_no,
                mobile_no : this.mobile_no,
                // facility_cd : this.$store.state.userInfo.facility_cd,
                // facility_user : this.$store.state.userInfo.user_id
            })
            .then(response => {
                this.showSuccessMsg = true,
                this.enableBtn = true
            })
            .catch(error => console.log(error))
        }

        
    }
}
</script>

<style scoped>
/* removes the dot in li */
li { list-style: none; }

</style>