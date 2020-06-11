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
                        {{first_name}} {{middle_name ? middle_name : ''}} {{last_name}} {{name_suffix ? name_suffix : ''}}
                    </template>

                    <template v-slot:lead>
                        <b>Screening date:</b> {{ screen_dt }}
                        
                        <b-badge class="float-right" v-if="approval_status == 0" variant="danger">FOR REVIEW</b-badge>
                        <b-badge class="float-right" v-else variant="success">APPROVED</b-badge>
                    </template>
                    <hr>

                    <b-row>
                        <b-col>
                            <span v-if="gender == 'M'"><b>Gender:</b> Male</span>
                            <span v-else><b>Gender:</b> Female</span>
                        </b-col>
                    </b-row>
                    
                    <b-row>
                        <b-col>
                            <span><b>Birthday:</b> {{ bdate }}</span>
                        </b-col>
                    </b-row>
                    
                    <b-row>
                        <b-col>
                            <span v-if="civil_stat == 'S'"><b>Civil Status:</b> Single</span>
                            <span v-if="civil_stat == 'M'"><b>Civil Status:</b> Married</span>
                            <span v-if="civil_stat == 'W'"><b>Civil Status:</b> Widowed</span>
                            <span v-if="civil_stat == 'X'"><b>Civil Status:</b> Separated</span>
                        </b-col>
                    </b-row>

                    <b-row>
                        <b-col>
                            <span><b>Occupation:</b> {{ occupation ? occupation : '' }}</span>
                        </b-col>
                    </b-row>

                    <b-row>
                        <b-col>
                            <span><b>Nationality:</b> {{ nationality ? nationality = 'Filipino' : ''}}</span>
                        </b-col>
                    </b-row>

                    <b-row>
                        <b-col>
                            <span><b>Telephone #:</b> {{ tel_no ? tel_no : '' }}</span>
                        </b-col>
                    </b-row>

                    <b-row>
                        <b-col>
                            <span><b>Mobile #:</b> {{ mobile_no ? mobile_no : '' }}</span>
                        </b-col>
                    </b-row>

                    <b-row>
                        <b-col>
                            <span><b>Email:</b> {{ email ? email : '' }}</span>
                        </b-col>
                    </b-row>

                    <b-row>
                        <b-col>
                            <span><b>Region:</b> {{ home_region ? home_region = 'NCR' : '' }}</span>
                        </b-col>
                    </b-row>

                    <b-row>
                        <b-col>
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
                                <b-col>1. Do you weigh 50kg or more?</b-col>
                                <b-col>
                                    <span v-if="q_1 == 1" class="text-success float-right"><b>YES</b></span>
                                    <span v-else class="text-danger float-right"><b>NO</b></span>
                                </b-col>
                            </b-row>
                            <hr>

                        </b-tab>
                    </b-tabs>
                </b-card>
            </b-col>
        </b-row>

        <template v-if="approval_status == '0'">
        <b-row class="mt-3">
            <b-col md="4">
                <b-button block type="submit" variant="success"
                    :disabled="enableBtn"
                    @click.prevent="submitVerdict()">
                        <b-icon icon="check-circle"></b-icon>&nbsp;APPROVE 
                    </b-button>
            </b-col>
        </b-row>
        </template>

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

            facility_cd: '',
            facility_user: '',

            first_name: '',
            middle_name: '',
            last_name: '',
            name_suffix: '',

            gender: '',
            bdate: '',
            civil_stat: '',

            tel_no: '',
            mobile_no: '',

            email: '',
            nationality: '',
            occupation: '',
            home_region: '',
            address: '',

            positive_with_itpcr: '',
            igm_level: '',
            igg_level: '',

            screen_dt: '',
            approval_status: '',



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

                this.gender = response.data.gender,
                this.bdate = response.data.bdate,

                this.civil_stat = response.data.civil_stat,
                this.tel_no = response.data.tel_no,
                this.mobile_no = response.data.mobile_no,

                this.email = response.data.email,
                this.nationality = response.data.nationality,
                this.occupation = response.data.occupation,
                this.home_region = response.data.home_region,
                this.address = response.data.address,

                this.q_1 = response.data.q_1,
                this.q_2 = response.data.q_2,
                this.q_3 = response.data.q_3,
                this.q_4 = response.data.q_4,
                this.q_5 = response.data.q_5,
                this.q_6 = response.data.q_6,
                this.q_7 = response.data.q_7,
                this.q_8 = response.data.q_8,
                this.q_9 = response.data.q_9,
                this.q_10 = response.data.q_10,

                this.positive_with_itpcr = response.data.positive_with_itpcr,
                this.igm_level = response.data.igm_level,
                this.igg_level = response.data.igg_level,

                this.screen_dt = response.data.screen_dt,
                this.approval_status = response.data.approval_status

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
                civil_stat : this.civil_stat,
                occupation : this.occupation,
                nationality : this.nationality,
                tel_no : this.tel_no,
                mobile_no : this.mobile_no,
                email : this.email,
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

<style>

</style>