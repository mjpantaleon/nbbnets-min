<template>
  <div class="main-div">
      <b-row id="bb-crumb-sticky">
          <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item active>
                        <b-icon icon="file-text" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Blood Request & Issuance
                    </b-breadcrumb-item>
                    <b-breadcrumb-item active>
                        Blood Request List
                    </b-breadcrumb-item>
                </b-breadcrumb>
          </b-col>
        </b-row>

        <h4><b-icon icon="file-text"></b-icon> Blood Request List</h4>
        <hr>

        <b-row>
            <b-col md="6">
                <b-link class="btn btn-success" :to="{ path: '/blood-request/create' }">
                    <b-icon icon="clipboard"></b-icon>&nbsp;CREATE NEW BLOOD REQUEST
                </b-link>
            </b-col>

            <b-col md="auto" class="ml-auto">
                <!-- <label for="datepicker" class="mr-sm-2">Donation date</label> -->
                <b-input-group class="mb-3">
                    <b-form-datepicker
                        :state="checkDate"
                        v-model="selected_dt">
                    </b-form-datepicker>
                    
                </b-input-group>
            </b-col>
        </b-row>

        <template v-if="data.length != 0">
        <b-row>
            <b-col>
                <b-table striped bordered
                    responsive="sm"
                    :fields="request_fields"
                    :items="data">

                    <template v-slot:cell(reference_num)="data">
                        {{ data.item.reference }}
                    </template>

                    <template v-slot:cell(patient_name)="data">
                        {{ data.item.lastname }}
                    </template>

                    <template v-slot:cell(blood_type)="data">
                        <span class="text-danger"><b>{{data.item.blood_type}}</b></span>
                    </template>

                    <template v-slot:cell(request_type)="data">
                        {{ data.item.request_type }}
                    </template>

                    <template v-slot:cell(status)="data">
                        <span class="text-danger" v-if="data.item.status == 'FLU'"><b>FOR LOOK UP</b></span>
                        <span class="text-success" v-if="data.item.status == 'RES'"><b>RESERVED</b></span>
                        <span class="text-primary" v-if="data.item.status == 'Released'"><b>ISSUED</b></span>
                    </template>

                    <template v-slot:cell(action)="data">
                        <router-link :to="{ path: '/blood-request/view/' + data.item.request_id }" title="View Blood Request Details">
                            <b-icon icon="search" class="border border-primary p-1" variant="primary" font-scale="2.1"></b-icon>
                        </router-link>
                        
                        <template v-if="data.item.status == 'RES' && data.item.status != 'Released'">
                        <router-link :to="{ path: '/blood-request/issue/' + data.item.request_id }" title="Issue Blood request">
                            <b-icon icon="check-circle" class="border border-success p-1" variant="success" font-scale="2.1"></b-icon>
                        </router-link>
                        </template>
                    </template>
                </b-table>
            </b-col>
        </b-row>
        </template>

        <template v-else>
        <b-row>
            <b-col class="text-center">No record/s to this display</b-col>
        </b-row>
        </template>


  </div>
</template>

<script>
export default {
    data(){
        return{
            showSuccessMsg: false,

            date_from: '',
            date_to: '',

            selected_dt: '',

            data: [],

            message: '',

            request_fields: [
                { key: 'reference_num', label: 'Reference #' },
                { key: 'patient_name', label: 'Patient Last Name' },
                { key: 'blood_type', label: 'Blood Type' },
                { key: 'request_type', label: 'Request Type' },
                { key: 'status', label: 'Status' },
                { key: 'action', label: 'Action' },
            ],

        }
    }, /* data() */

    mounted() {
        this.getBloodRequests()
    }, /* mounted */


    methods: {
        async getBloodRequests(){

            if(this.selected_dt == ''){
                const now = new Date()
                const today = now.getFullYear() + '-' + ('0' + (now.getMonth()+1)).slice(-2)+ '-'+ ('0' + now.getDate()).slice(-2);
                this.selected_dt = today
            }


            await axios
            .post('/blood-request-list', {
                // date_from: this.date_from,
                // date_to: this.date_to

                selected_dt: this.selected_dt
            })
            .then(response => {
                if(response.data){
                    this.data = response.data
                } else {
                    this.data = []
                }
            })
        },
    }, /* methods */

    watch: {
        // watch the value of donation date as it change
        selected_dt: function (val){
            this.getBloodRequests()
        }
    }, /* watch */

    computed: {
        // pagination
        rows() {
            return this.data.length
        },
        checkDate(){
            return this.selected_dt.length > 5 ? true : false;
        }
    }
}
</script>

<style>

</style>