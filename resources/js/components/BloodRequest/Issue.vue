<template>
  <div class="main-div">
      <b-row id="bb-crumb-sticky">
          <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item active>
                        <b-icon icon="file-text" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Blood Request & Issuance
                    </b-breadcrumb-item>
                    <b-breadcrumb-item :to="{ path: '/blood-request/list' }">
                        <b-icon icon="file-text" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Blood Request List
                    </b-breadcrumb-item>
                    <b-breadcrumb-item active>
                        Issue Blood Request
                    </b-breadcrumb-item>
                </b-breadcrumb>
          </b-col>
        </b-row>

        <h4><b-icon icon="file-text"></b-icon> Issue Blood Request</h4>
        <hr>

        <b-row>
            <b-col class="lead">
                <b>Request Status : 
                    <span v-if="status == 'FLU'" class="text-danger">FOR LOOK UP</span>
                    <span v-if="status == 'RES'" class="text-success">RESERVED</span>
                    <span v-if="status == 'Released'" class="text-primary">ISSUED</span>
                </b>
            </b-col>
            <b-col class="lead">
                <b>Reference ID: {{reference}}</b>
            </b-col>
        </b-row>

        <h4 class="text-secondary mt-3"> <b-icon icon="person-bounding-box"></b-icon> Patient Details</h4>
        <b-row>
            <b-col>
                <table class="table">
                    <tbody>
                        <tr>
                            <th class="lead">Patient ID : </th>
                            <td class="lead">{{patient_id}}</td>
                        </tr>
                        <tr>
                            <th class="lead">Patient Name : </th>
                            <td class="lead">{{firstname}} {{middlename}} {{lastname}} {{name_suffix}}</td>
                        </tr>
                        <tr>
                            <th class="lead">Blood Type : </th>
                            <th class="lead">{{blood_type}}</th>
                        </tr>
                        <tr>
                            <th class="lead">Gender : </th>
                            <th class="lead" v-if="gender == 'F'">Female</th>
                            <th class="lead" v-if="gender == 'M'">Male</th>
                        </tr>
                        <tr>
                            <th class="lead">Civil Status : </th>
                            <th class="lead" v-if="civil_status== 'S'">Single</th>
                            <th class="lead" v-if="civil_status== 'M'">Married</th>
                            <th class="lead" v-if="civil_status== 'W'">Widowed</th>
                        </tr>
                        <tr>
                            <th class="lead">Birthday : </th>
                            <th class="lead">{{bdate | moment("MMMM DD, YYYY")}}</th>
                        </tr>
                        <tr>
                            <th class="lead">Age : </th>
                            <th class="lead">{{age}}</th>
                        </tr>
                    </tbody>
                </table>
            </b-col>
        </b-row>

        <h4 class="text-secondary mt-3"> <b-icon icon="person-square"></b-icon> Physician Details</h4>
        <b-row>
           <b-col>
               <table class="table">
                   <tbody>
                       <tr>
                            <td class="lead">Physician Name : </td>
                            <td class="lead">{{dr_fname}} {{dr_mname}} {{dr_lname}} {{dr_name_suffix}}</td>
                        </tr>
                        <tr>
                            <td class="lead">Mobile # : </td>
                            <td class="lead">{{mobile_no}}</td>
                        </tr>
                        <tr>
                            <td class="lead">Email : </td>
                            <td class="lead">{{email}}</td>
                        </tr>
                   </tbody>
               </table>
           </b-col>
        </b-row>

        <h4 class="text-secondary mt-3"> <b-icon icon="droplet-half"></b-icon> Blood Unit</h4>
        <b-row>
            <b-col>
                <table class="table">
                    <tbody v-for="(detail, i) in details" :key="i">
                        <tr v-if="detail.donation_id != null">
                            <td class="lead">{{detail.donation_id}}</td>
                            <td class="lead">{{detail.component_name.comp_name}}</td>
                        </tr>
                    </tbody>
                </table>
            </b-col>
            <!-- <b-col v-if="details.donation == null" class="lead text-center text-danger"><b>No reserved blood units yet</b></b-col> -->
        </b-row>
        
        <b-row>
            <b-col md="4" v-if="status != 'FLU' || status != 'Released' || details.donation_id != ''">
                <b-button block
                    variant="success"
                    @click.prevent="showModal">
                    <b-icon icon="check-circle"></b-icon>&nbsp;ISSUE BLOOD REQUEST
                </b-button>
            </b-col>
            <!-- <b-col v-if="status != 'FLU' && status != 'Released'" class="lead text-center text-danger">
                <b>No reserved blood units yet</b>
            </b-col> -->
        </b-row>

        <verifier-modal @setUname="setUname"></verifier-modal>

        <!-- =============== MODALS ================ -->
        <!-- SHOW THIS MODAL AFTER SUCCESSFUL ACTION -->
        <b-modal v-model="showSuccessMsg" centered
            title="INFO"
            header-bg-variant="info"
            body-bg-variant="light" 
            footer-bg-variant="info"
            header-text-variant="light"
            hide-header-close>
            
            <h5 class="alert-heading text-center">
                <b-icon variant="danger" icon="droplet-half"></b-icon>&nbsp;{{message}}
            </h5>
            
            <template v-slot:modal-footer="{ ok }">
            <!-- <template v-slot:modal-footer="{ ok }"> -->
                <b-link class="btn btn-info" :to="{ path: '/blood-request/list' }"
                    size="sm" variant="info" @click="ok()">
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
            showSuccessMsg: false,

            message: '',

            // patient details
            patient_id: '',

            firstname: '',
            middlename: '',
            lastname: '',
            name_suffix: '',

            blood_type: '',

            gender: '',
            civil_status: '',
            bdate: '',
            age: '',

            diagnosis: '',

            // physician
            dr_fname: '',
            dr_mname: '',
            dr_lname: '',
            dr_name_suffix: '',

            mobile_no: '',
            email: '',

            // blood unit
            details: '',
            
            status: '',
            reference: '',
        }
    }, /* data */

    mounted(){
        this.getBloodRequestDetails()
    }, /* mounted */


    methods: {
        async getBloodRequestDetails(){
            
            await axios
            .get('/blood-request-details/' + this.$route.params.id)
            .then(response => (
                // patient details
                this.patient_id = response.data.patient_details.patient_id,

                this.firstname = response.data.patient_details.firstname,
                this.middlename = response.data.patient_details.middlename,
                this.lastname = response.data.patient_details.lastname,
                this.name_suffix = response.data.patient_details.name_suffix,

                this.blood_type = response.data.patient_details.blood_type,

                this.gender = response.data.patient_details.gender,
                this.civil_status = response.data.patient_details.civil_status,

                this.bdate = response.data.patient_details.birthday,
                this.age = response.data.patient_details.age,

                // physician details
                this.dr_fname = response.data.physician_details.fname,
                this.dr_mname = response.data.physician_details.mname,
                this.dr_lname = response.data.physician_details.lname,
                this.dr_name_suffix = response.data.physician_details.name_suffix,

                this.mobile_no = response.data.physician_details.mobile_num,
                this.email = response.data.physician_details.email,

                this.details = response.data.details,

                this.status = response.data.status,
                this.reference = response.data.reference
                // this.data = response.data
                // console.log(response.data)
            ));
        },


        showModal(){   
            this.$bvModal.show('verifier-login');
            // this.modalOpen = !this.modalOpen;
        },


        openModal() {
            this.modalOpen = !this.modalOpen;
        },

        async setUname(e){

            await axios
            .post('/issue-blood-request/' + this.$route.params.id)
            .then(response => {

                // this.data = response.data
                if(response.data){
                    this.message = response.data.message
                    this.showSuccessMsg = true
                    
                    console.log(response.data)
                }
            })

        },


    }, /* methods */
}
</script>

<style>

</style>