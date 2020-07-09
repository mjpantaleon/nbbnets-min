<template>
  <div class="main-div">
        <b-row id="bb-crumb-sticky">
            <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item :to="{ path: '/donation' }">
                        <b-icon icon="card-checklist" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Donation
                    </b-breadcrumb-item>
                    <b-breadcrumb-item :to="{ path: '/search-donor' }">
                        <b-icon icon="search" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Search Donor</b-breadcrumb-item>
                    <b-breadcrumb-item active>
                        <b-icon icon="person-lines-fill" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Donor Details</b-breadcrumb-item>
                    <!-- <b-breadcrumb-item href="#bar">Donor Details</b-breadcrumb-item> -->
                    <!-- <b-breadcrumb-item>Baz</b-breadcrumb-item> -->
                </b-breadcrumb>
            </b-col>
        </b-row>

        <h4><b-icon icon="person-lines-fill"></b-icon>&nbsp;Donor Details</h4>
        <hr>

        <b-row>
            <b-col md="6">
                <b-button-group>
                    <b-link class="btn btn-primary" :to="{ path: '/update-donor-details/' + this.$route.params.id }">
                        <b-icon icon="pencil"></b-icon> UPDATE INFO</b-link>

                    <b-link v-if="donation_stat != '' && donation_stat != 'N'" class="btn btn-success" 
                        :to="{ path: '/new-walk-in/' + this.$route.params.id }">
                        <b-icon icon="person-plus"></b-icon> NEW WALK-IN</b-link>

                    <b-link v-else  class="btn btn-secondary" disabled>
                        <b-icon icon="person-plus"></b-icon> NEW WALK-IN</b-link>
                </b-button-group>
            </b-col>
        </b-row>

        <b-row>
            <b-col md="6" class="mt-3">
                <!-- <b-card header-bg-variant="dark" text-variant="light" header="Donor Details"
                    no-body></b-card> -->
                    <table class="table table-striped bg-light">
                        <tbody>
                        <tr>
                            <th>Donor ID</th>
                            <td>{{ donor_id ? donor_id : 'NULL' }}</td>
                        </tr>
                        <tr>
                            <th>Donation Status</th>
                            <td>
                                <h5 v-if="donation_stat != '' && donation_stat != 'N'" class="text-success">MAY DONATE</h5>
                                <h5 v-else class="text-danger">CANNOT DONATE</h5>
                            </td>
                        </tr>
                        <tr>
                            <th>Donor Name</th>
                            <td>
                                <h5 class="lead">{{ fname }} {{ mname ? mname : '' }} {{ lname }} {{ name_suffix ? name_suffix : '' }}</h5></td>
                        </tr>
                        <tr>
                            <th>Gender</th>
                            <td>
                                <span v-if="gender == 'M'">Male</span>
                                <span v-else>Female</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Birth Date</th>
                            <td>{{ bdate }}</td>
                        </tr>
                        <tr>
                            <th>Civil Status</th>
                            <td v-if="civil_stat == 'S'"> SINGLE </td>
                            <td v-if="civil_stat == 'M'"> MARRIED </td>
                            <td v-if="civil_stat == 'W'"> WIDOWED </td>
                            <td v-if="civil_stat == 'X'"> SEPARATED </td>
                        </tr>
                        <tr>
                            <th>Occupation</th>
                            <td>{{ occupation }}</td>
                        </tr>
                        <tr>
                            <th>Nationality</th>
                            <td>{{ nationality ? nationality = 'Filipino' : '' }}</td>
                        </tr>
                        <tr>
                            <th>Telephone No.</th>
                            <td>{{ tel_no ? tel_no : '' }}</td>
                        </tr>
                        <tr>
                            <th>Mobile No.</th>
                            <td>{{ mobile_no ? mobile_no : '' }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ email ? email : '' }}</td>
                        </tr>
                        </tbody>
                    </table>
                    
            </b-col>

            <b-col md="6" class="mt-3">

                <template v-if="data.length == 0">
                    <table class="table table-bordered table-striped">
                        <th class="text-center">
                            <h6 class="text-danger">No Donation History to display</h6>
                        </th>
                    </table>
                </template>

                <b-card v-else no-body bg-variant="success" text-variant="white" header="DONATION HISTORY">
                    <b-table
                    :fields="fields"
                    :items="data"
                    striped
                    head-variant="info"
                    table-variant="light">
                        <template v-slot:cell(facility_name)="data">
                            {{ data.item.facility_name }}
                        </template>

                        <template v-slot:cell(created_dt)="data">
                            <!-- {{ data.item.created_dt | moment("dddd, MMMM Do YYYY, h:mm:ss a") }} -->
                            {{ data.item.created_dt | moment("MMMM DD, YYYY - h:mm:ss a") }}
                        </template>
                    </b-table>
                </b-card>

                
            </b-col>
        </b-row>
  </div>
</template>

<script>
export default {
    data() {
      return {
        donor_id: '',
        donation_stat: '',
        fname: '',
        mname: '',
        lname: '',
        name_suffix: '',
        gender: '',
        bdate: '',
        civil_stat: '',
        occupation: '',
        nationality: '',
        tel_no: '',
        mobile_no: '',
        email: '',

        //   donor history
        data: '',
        fields: [
            { key: 'facility_name', label: 'Facility' },
            { key: 'created_dt', label: 'Donation Date' }
        ]
      }
    }, /*data */

    mounted(){
        this.getDonorDetails();
        this.getDonorHistory();
    }, /* mounted */

    methods:{
        getDonorDetails(){
            axios
            .get('/donor-profile/' + this.$route.params.id)
            .then(response => (
                this.donor_id = response.data.donor_id,
                this.donation_stat = response.data.donation_stat,
                this.fname = response.data.fname,
                this.mname = response.data.mname,
                this.lname = response.data.lname,
                this.name_suffix = response.data.name_suffix,
                this.gender = response.data.gender,
                this.bdate = response.data.bdate,
                this.civil_stat = response.data.civil_stat,
                this.occupation = response.data.occupation,
                this.nationality = response.data.nationality,
                this.tel_no = response.data.tel_no,
                this.mobile_no = response.data.mobile_no,
                this.email = response.data.email
            ))
            .catch(error => console.log(error))
        },

        getDonorHistory(){
            axios
            .get('/donor-history/' + this.$route.params.id)
            .then(response => (
                this.data = response.data
            ))
            .catch(error => console.log(error))
        }
    }, /* methods */

    computed:{
        
    }
}
</script>

<style>

</style>