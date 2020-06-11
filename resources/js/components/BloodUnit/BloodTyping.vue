<template>
  <div class="main-div">
        <b-row id="bb-crumb-sticky">
          <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item active>
                        <b-icon icon="file-text" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Blood Typing
                    </b-breadcrumb-item>
                    <!-- <b-breadcrumb-item href="#home" active>
                        <b-icon icon="person-plus" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Register New Donor
                    </b-breadcrumb-item> -->
                </b-breadcrumb>
          </b-col>
        </b-row>

        <h4><b-icon icon="file-text"></b-icon> Blood Typing</h4>
        <hr>

        <b-row>
            <b-col>
                <template v-if="isLoading">
                    <div class="d-flex justify-content-center mb-3">
                        <b-spinner variant="danger" label="Please wait..."></b-spinner>
                    </div>
                </template>
            
                <template v-if="data.length != 0">
                <b-table id="main-table" class="mt-5" head-variant="light" table-variant="light"
                    :fields="fields"
                    :items="data"
                    :per-page="perPage"
                    :current-page="currentPage"
                    striped>

                    <template v-slot:cell(fullname)="data">
                        {{ data.item.fname }} {{ data.item.mname }}, {{ data.item.lname }}
                    </template>

                    <template v-slot:cell(gender)="data">
                        <span v-if="data.item.gender == 'M'">Male</span>
                        <span v-else>Female</span>
                    </template>

                    <template v-slot:cell(approval_status)="data">
                        <b-badge variant="danger" v-if="data.item.approval_status == '0'">FOR REVIEW</b-badge>
                        <b-badge variant="success" v-else>APPROVED</b-badge>
                    </template>

                    <template v-slot:cell(action)="data">
                        <b-link class="btn btn-info btn-sm" :to="{ path: '/pre-screened-info/' + data.item.id }"
                            v-b-tooltip.hover title="View details">
                            <b-icon icon="search"></b-icon>
                        </b-link>
                    </template>  

                </b-table>
                
                <!-- pagination -->
                <b-pagination
                    v-model="currentPage"
                    :total-rows="rows"
                    :per-page="perPage"
                    aria-controls="main-table">
                </b-pagination>
                </template>

                <template v-else>
                    <div class="alert alert-info mt-3">
                        <span class="text-center text-danger">
                            <h5><b-icon icon="info-square"></b-icon> No records found</h5>
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
            fields: [
                { key: 'fullname', label: 'Fullname' },
                { key: 'gender', label: 'Gender' },
                { key: 'bdate', label: 'Birthday' },
                { key: 'address', label: 'Address' },
                { key: 'created_dt', label: 'Date Screened' },
                { key: 'approval_status', label: 'Status' },
                { key: 'action', label: 'Action' },
            ],
            data: '',
            isLoading: false,

            fname: '',
            mname: '',
            lname: '',
            name_suffix: '',
            gender: '',
            bdate: '',
            address: '',
            created_dt: '',
            approval_status: '',

            // pagination
            perPage: 10,
            currentPage: 1,
        }
    }, /* data */

    mounted(){

    }, /* mounted */

    methods: {
        // getCandidates(){
        //     this.isLoading = true

        //     axios
        //     .get('/')
        //     .then(response => (

        //     ))
        //     .catch(error => console.log(error))
        // },

    }, /* methods */

    computed: {
        // pagination
        rows() {

        },
    }, /* computed */
}
</script>

<style>

</style>