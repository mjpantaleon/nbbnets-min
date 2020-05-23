<template>
  <div class="main-div">
        <b-row>
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
                    <b-link class="btn btn-primary" :to="{ path: '/edit-donor-details/' + this.$route.params.id }">
                        <b-icon icon="pencil"></b-icon> UPDATE INFO</b-link>

                    <b-link v-if="donor_stat == 'A'" class="btn btn-success" 
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
                        <tr>
                            <th>Donor ID</th>
                            <td>{{ donor_id ? donor_id : 'NULL' }}</td>
                        </tr>
                        <tr>
                            <th>Donor Status</th>
                            <td>
                                <b v-if="donor_stat == 'A'" class="text-success">MAY DONATE</b>
                                <b v-else class="text-danger">CANNOT DONATE</b>
                            </td>
                        </tr>
                        <tr>
                            <th>Donor Name</th>
                            <td>{{ fname }} {{ mname ? mname : '' }} {{ lname }} {{ name_suffix ? name_suffix : '' }} </td>
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
                            <td>{{ civil_stat }}</td>
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
                        
                    </table>
                    
            </b-col>

            <!-- <b-col md="6" class="mt-3">
                <b-card no-body bg-variant="success" text-variant="white" header="DONATION HISTORY">
                    <b-table
                    :items="history"
                    striped
                    head-variant="info"
                    table-variant="light"></b-table>
                </b-card>
            </b-col> -->
        </b-row>
  </div>
</template>

<script>
export default {
    data() {
      return {
          donor_id: '',
          donor_stat: '',
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
      }
    }, /*data */

    mounted(){
        this.getDonorDetails();
    }, /* mounted */

    methods:{
        getDonorDetails(){
            axios
            .get('/donor-profile/' + this.$route.params.id)
            .then(response => (
                this.donor_id = response.data.donor_id,
                this.donor_stat = response.data.donor_stat,
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
                this.email = response.data.email7
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