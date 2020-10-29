<template>
  <div class="main-div">
      <b-row id="bb-crumb-sticky">
          <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item active>
                        <b-icon icon="file-text" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Blood Stocks
                    </b-breadcrumb-item>
                    <b-breadcrumb-item active>
                        Blood Request
                    </b-breadcrumb-item>
                </b-breadcrumb>
          </b-col>
        </b-row>

        <h4><b-icon icon="file-text"></b-icon> Blood Request</h4>
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

        <!-- <b-row>
            <b-col cols="5">
                <b-form-group
                    id="donation-id"           
                    label-cols-sm="2"
                    label-cols-lg="2"
                    description="Date From"
                    label="Date From"
                    label-for="donation-id">
                        <b-form-datepicker 
                            v-model="date_from"
                            class="mb-2">
                        </b-form-datepicker>
                </b-form-group>
            </b-col>
            <b-col cols="5">
                <b-form-group
                    id="donation-id"           
                    label-cols-sm="1"
                    label-cols-lg="1"
                    description="Search Date To"
                    label="To"
                    label-for="donation-id">
                        <b-form-datepicker
                            v-model="date_to"
                            class="mb-2">
                        </b-form-datepicker>
                </b-form-group>
            </b-col>
            <b-col cols="2" class="ml-auto">
                <b-button type="submit"
                    variant="warning"
                    @click.prevent="getBloodRequests()">
                    <b-icon icon="search"></b-icon>&nbsp;SEARCH
                </b-button>
            </b-col>
        </b-row> -->

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

                    <template v-slot:cell(request_type)="data">
                        {{ data.item.request_type }}
                    </template>

                    <template v-slot:cell(status)="data">
                        {{ data.item.status }}
                    </template>

                    <template v-slot:cell(action)="data">
                        <router-link :to="'/blood-request/view/'+data.item.request_id" title="View Blood Request Details">
                            <b-icon icon="reply-fill" class="border border-danger p-1" variant="danger" font-scale="2.1"></b-icon>
                        </router-link>
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