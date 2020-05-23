<template>
  <div class="main-div">
        
        <b-row>
            <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item :to="{ path: '/donation' }">
                        <b-icon icon="card-checklist" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Donation
                    </b-breadcrumb-item>
                    <b-breadcrumb-item active>
                        <b-icon icon="search" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Search Donor</b-breadcrumb-item>
                </b-breadcrumb>
            </b-col>
        </b-row>

        <h4><b-icon icon="search"></b-icon> Search Donor</h4>
        <hr>
        
        <!-- SEARCH MODULE -->
        <b-row>
            <b-col cols="4">
                <b-form-group
                    id="fieldset-horizontal"           
                    label-cols-sm="4"
                    label-cols-lg="4"
                    description="First name"
                    label="Donor name"
                    label-for="first-name">
                    <b-form-input v-model="fname" id="first-name"></b-form-input>
                </b-form-group>
            </b-col>

            <b-col cols="3">
                <b-form-group
                    id="fieldset-horizontal"
                    description="Middle name"
                    label-for="middle-name">
                <b-form-input v-model="mname" id="middle-name"></b-form-input>
                </b-form-group>
            </b-col>

            <b-col cols="3">
                <b-form-group
                    id="fieldset-horizontal"
                    description="Last name"
                    label-for="last-name">
                <b-form-input v-model="lname" id="last-name"></b-form-input>
                </b-form-group>
            </b-col>

            <b-col cols="2" class="ml-auto">
                <b-button type="submit"
                    variant="warning"
                    @click.prevent="searchDonor()">
                    <b-icon icon="search"></b-icon>&nbsp;SEARCH
                </b-button>
            </b-col>
        </b-row>

        <!-- {{ data }} -->
        <b-link class="btn btn-success" :to="{ path: 'register-new-donor' }">
            <b-icon icon="person-plus"></b-icon> Register New Donor
        </b-link>

        <!-- TABLE -->
        <b-row>
          <b-col>
            <template v-if="isLoading">
                <div class="d-flex justify-content-center mb-3">
                    <b-spinner variant="danger" label="Please wait..."></b-spinner>
                </div>
            </template>
            
            <template>
                <b-table class="mt-3" id="bulletin-table"
                    responsive="sm"
                    striped hover
                    head-variant="light"
                    table-variant="light"
                    :fields="fields"
                    :items="data"
                    :per-page="perPage"
                    :current-page="currentPage">

                    <template v-slot:cell(donorStatus)="data">
                        <b v-if="data.item.donor_stat == 'A'" class="text-success">MAY DONATE</b>
                        <b v-else class="text-danger">CANNOT DONATE</b>
                    </template>

                    <template v-slot:cell(name)="data">
                        {{ data.item.fname }} {{ data.item.mname }}, {{ data.item.lname }}
                    </template>

                    <template v-slot:cell(gender)="data">
                        <span v-if="data.item.gender == 'M'">Male</span>
                        <span v-else>Female</span>
                    </template>

                    <template v-slot:cell(action)="data">
                        <b-link class="btn btn-info btn-sm" :to="{ path: '/donor-details/' + data.item.seqno }"
                            v-b-tooltip.hover title="View donor details">
                            <b-icon icon="search"></b-icon>
                        </b-link>

                        <b-link class="btn btn-success btn-sm" :to="{ path: '/edit-donor-details/' + data.item.seqno  }"
                            v-b-tooltip.hover title="Update donor details">
                            <b-icon icon="pencil"></b-icon>
                        </b-link>
                    </template>   

                    <!--  SHOULD SHOW NO RECORDS FOUND IF
                    <template v-else>
                        <b-tr>
                            <b-th colspan="11">No records found.</b-th>
                        </b-tr>
                    </template> -->

                </b-table>
            </template>

            <b-pagination
                v-model="currentPage"
                :total-rows="rows"
                :per-page="perPage"
                aria-controls="bulletin-table">
                </b-pagination>
            </b-col>
        </b-row>


  </div>
</template>

<script>
export default {
    data(){
        return{
            data: '',
            isLoading: false,

            fname: '',
            mname: '',
            lname:  '',

            fields: [
                { key: 'seqno', label: 'Seqno' },
                { key: 'donorStatus', label: 'Donor Status' },
                { key: 'name', label: 'Fullname' },
                { key: 'gender', label: 'Gender' },
                { key: 'bdate', label: 'Birthday' },
                { key: 'region', label: 'Region' },
                { key: 'province', label: 'Province' },
                { key: 'city', label: 'City' },
                { key: 'brgy', label: 'Brgy' },
                { key: 'address', label: 'Address' },
                { key: 'action', label: '' }
            ],
            perPage: 5,
            currentPage: 1,
        }
    }, /* data */

    computed: {
        rows() {
            return this.data.length
        }

    }, /* computed */

    mounted(){
        // this.getDonors()
        // if(this.data.length == 0){
        //     this.showAddBtn = true
        // }
    }, /* mounted */

    methods: {
        searchDonor(){
            this.isLoading = true

            axios
            .post('/get-searched-donor',{
                fname: this.fname,
                mname: this.mname,
                lname: this.lname,
            })
            .then(response => {
                this.data = response.data;
                this.isLoading = false
                this.showAddBtn = false
            })
        }

        // getDonors(){
        //     axios
        //     .get('/donor-list-data')
        //     .then(response => {
        //         this.data = response.data
        //         this.hasResult = true
        //     })
        // },
    }, /* methods */
}
</script>

<style>

</style>    