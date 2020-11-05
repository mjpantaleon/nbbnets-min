<template>
  <div class="main-div">
      <b-row id="bb-crumb-sticky">
          <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item active>
                        <b-icon icon="droplet-half" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Blood Unit
                    </b-breadcrumb-item>
                    <b-breadcrumb-item active>
                        <b-icon icon="droplet-half" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Blood Testing List
                    </b-breadcrumb-item>
                </b-breadcrumb>
          </b-col>
        </b-row>

        <h4><b-icon icon="droplet-half"></b-icon> Blood Testing List</h4>
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
                        {{ data.item.first_name }} {{ data.item.middle_name }}, {{ data.item.last_name }}
                    </template>

                    <template v-slot:cell(gender)="data">
                        <span v-if="data.item.gender == 'M'">Male</span>
                        <span v-else>Female</span>
                    </template>

                    <!-- <template v-slot:cell(bdate)="data">
                        {{ data.item.bdate | moment("MMMM DD, YYYY") }}
                    </template> -->

                    <template v-slot:cell(created_dt)="data">
                        <!-- {{ data.item.created_dt | moment("dddd, MMMM Do YYYY, h:mm:ss a") }} -->
                        {{ data.item.created_dt | moment("MMMM DD, YYYY - h:mm:ss a") }}
                    </template>

                    <template v-slot:cell(status)="data">
                        <b-badge variant="danger" v-if="data.item.status == '1'">FOR TESTING</b-badge>
                        <b-badge variant="success" v-else>DONE</b-badge>
                    </template>

                    <template v-slot:cell(action)="data">
                        <b-link v-if="data.item.status == '1'" class="btn btn-danger btn-sm" :to="{ path: '/blood-testing/' + data.item.donor_sn }"
                            v-b-tooltip.hover title="Proceed to Testing">
                            <b-icon icon="droplet-half"></b-icon>
                        </b-link>

                        <span v-else class="text-center"> - </span>
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
                // { key: 'bdate', label: 'Birthday' },
                { key: 'address', label: 'Address' },
                { key: 'created_dt', label: 'Date Screened' },
                { key: 'status', label: 'Status' },
                { key: 'action', label: 'Action' },
            ],
            data: '',
            isLoading: false,

            first_name: '',
            middle_name: '',
            last_name: '',
            name_suffix: '',
            gender: '',
            // bdate: '',
            address: '',
            created_dt: '',
            status: '',

            // pagination
            perPage: 10,
            currentPage: 1,
        }
    }, /* data */

    mounted(){
        this.getCandidates();
    }, /* mounted */

    methods: {
        getCandidates(){
            this.isLoading = true

            axios
            .get('/for-testing-list')
            .then(response => (
                this.data = response.data,
                this.isLoading = false
            ))
            .catch(error => console.log(error))
        },

    }, /* methods */

    computed: {
        // pagination
        rows() {
            return this.data.length
        },
    }, /* computed */
}
</script>

<style>

</style>