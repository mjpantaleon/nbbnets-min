<template>
  <div class="main-div">

      <b-row id="bb-crumb-sticky">
          <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item href="#home" active>
                        <b-icon icon="card-checklist" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Donation
                    </b-breadcrumb-item>
                </b-breadcrumb>
          </b-col>
      </b-row>

      <h4><b-icon icon="card-checklist"></b-icon> Donation</h4>
      <hr>

      <b-row>
            <b-col md="6">
                <b-link class="btn btn-success" :to="{ path: 'search-donor' }">
                    <b-icon icon="person-plus"></b-icon>&nbsp;NEW WALK-IN
                </b-link>
            </b-col>

            <b-col md="auto" class="ml-auto">
                <!-- <label for="datepicker">Donation date</label> -->
                <b-input-group class="mb-3">
                    <b-form-datepicker
                        :state="checkDate"
                        v-model="donation_dt">
                    </b-form-datepicker>
                    
                </b-input-group>
            </b-col>
      </b-row>

      <b-row>
          <b-col>
            <template v-if="isLoading">
                <div class="d-flex justify-content-center mb-3">
                    <b-spinner variant="danger" small label="Please wait..."></b-spinner>
                </div>
            </template>

            <template v-if="data.length != 0">
                <b-table id="main-table" class="mt-5"
                    sticky-header
                    :items="data"
                    :fields="fields"
                    :per-page="perPage"
                    :current-page="currentPage"
                    striped
                    head-variant="light"
                    table-variant="light">

                    <template v-slot:cell(fullname)="data">
                        {{ data.item.fname }} {{ data.item.mname }}, {{ data.item.lname }}
                    </template>

                    <template v-slot:cell(donationType)="data">
                        <span v-if="data.item.donation_type == 'V'">Voluntary</span>
                        <span v-if="data.item.donation_type == 'A'">Autologous</span>
                        <span v-if="data.item.donation_type == 'R'">Family/ Replacement</span>
                        <span v-if="data.item.donation_type == 'P'">Paid</span>
                    </template>

                    <template v-slot:cell(mhpe)="data">
                        <b class="text-success" v-if="data.item.mh_pe_stat == 'A'">ACCEPTED</b>
                        <b class="text-danger" v-if="data.item.mh_pe_stat == 'TD'">TEMPORARY DEFERRED</b>
                        <b class="text-danger" v-if="data.item.mh_pe_stat == 'PD'">PERMANENTLY DEFERRED</b>
                        <b class="text-danger" v-if="data.item.mh_pe_stat == 'ID'">INDEFINITELY DEFERRED</b>
                    </template>

                    <template v-slot:cell(collectionMethod)="data">
                        <span v-if="data.item.collection_method == 'WB'">WHOLE BLOOD</span>
                        <span v-if="data.item.collection_method == 'CP' || data.item.collection_method == 'P'">PHERESIS</span>
                    </template>

                    <template v-slot:cell(collectionStat)="data">
                        <b class="text-success" v-if="data.item.collection_stat == 'COL'">Collected</b>
                        <b class="text-danger" v-if="data.item.collection_stat == 'UNS'">Un-successful</b>
                    </template>
                    
                    <template v-slot:cell()="data">
                        {{ data.item.donation_id }}
                    </template>
                    
                </b-table>

                <b-pagination
                    v-model="currentPage"
                    :total-rows="rows"
                    :per-page="perPage"
                    aria-controls="main-table">
                </b-pagination>
            </template>

            <template v-else>
                <div class="alert alert-info">
                    <span class="text-center text-danger">
                        <h6><b-icon icon="info-square"></b-icon> No records to display</h6>
                    </span>
                </div>
            </template>
          </b-col>
      </b-row>

  </div>
</template>

<script>
export default {
    data(){
        return{
            data: '',
            message: '',
            donation_dt: '',
            selected: '',

            isLoading: false,
            fields: [
                { key: 'fullname', label: 'Donor'},
                { key: 'donationType', label: 'Type of Donor'},
                { key: 'mhpe', label: 'MH/ PE'},
                { key: 'collectionMethod', label: 'Collection Method'},
                { key: 'collectionStat', label: 'Collection Status'},
                { key: 'donationID', label: 'Donation ID'},
            ],
            perPage: 10,
            currentPage: 1,
        }
    }, /* data */

    mounted() {
        this.getDonations()
    }, /* mounted */

    methods: {
        async getDonations(){
            this.isLoading = true

            if(this.donation_dt == ''){
                const now = new Date()
                const today = now.getFullYear() + '-' + ('0' + (now.getMonth()+1)).slice(-2)+ '-'+ ('0' + now.getDate()).slice(-2);
                this.donation_dt = today
            }


            await axios
            .post('/donation-list-data',{
                donation_dt: this.donation_dt
            })
            .then(response => {
                if(response.data){
                    this.data = response.data
                    this.isLoading = false
                }
            })
        }
    }, /* methods */

    watch: {
        // watch the value of donation date as it change
        donation_dt: function (val){
            this.getDonations()
        }
    }, /* watch */

    computed: {
        // pagination
        rows() {
            return this.data.length
        },
        checkDate(){
            return this.donation_dt.length > 5 ? true : false;
        }
    }
}
</script>

<style>

</style>