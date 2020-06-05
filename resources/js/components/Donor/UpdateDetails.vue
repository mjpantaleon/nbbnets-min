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
                    <!-- <b-breadcrumb-item :to="{ path: '/donor-details/' + this.$route.params.id }">
                        <b-icon icon="person-lines-fill" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Donor Details</b-breadcrumb-item> -->
                    <b-breadcrumb-item active>
                        <b-icon icon="pencil" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Update Donor Information</b-breadcrumb-item>
                    <!-- <b-breadcrumb-item href="#bar">Donor Details</b-breadcrumb-item> -->
                    <!-- <b-breadcrumb-item>Baz</b-breadcrumb-item> -->
                </b-breadcrumb>
            </b-col>
        </b-row>

        <h4><b-icon icon="pencil"></b-icon> Update Donor Information</h4>
        <hr>

        <b-row>
          <b-col md="8">
                <b-form-group
                    id="fieldset-horizontal"
                    label-cols-sm="2"
                    label-cols-lg="2"
                    description="Donor ID"
                    label="Donor ID"
                    label-for="donor_id">

                    <span v-if="donor_id != ''">{{ donor_id }}</span>
                    <span>NULL</span>
                </b-form-group>
          </b-col>
      </b-row>

      <b-row>
          <b-col md="8">
                <b-form-group
                    id="fieldset-horizontal"
                    label-cols-sm="2"
                    label-cols-lg="2"
                    description="Donation status"
                    label="Donation Status"
                    label-for="donation_stat">

                    <h5 v-if="donation_stat != 'REA'" class="text-success">MAY DONATE</h5>
                    <h5 v-else class="text-danger">CANNOT DONATE</h5>
                </b-form-group>
          </b-col>
      </b-row>

      <b-row>
          <b-col cols="4">
                <b-form-group
                    id="fieldset-horizontal"           
                    label-cols-sm="4"
                    label-cols-lg="4"
                    description="First name"
                    label="Donor name"
                    label-for="first-name">
                    <h5 class="lead">{{ fname }}</h5>
                </b-form-group>
            </b-col>

            <b-col cols="3">
                <b-form-group
                    id="fieldset-horizontal"
                    description="Middle name"
                    label-for="middle-name">
                    <h5 class="lead">{{ mname ? mname : '' }}</h5>
                </b-form-group>
            </b-col>

            <b-col cols="3">
                <b-form-group
                    id="fieldset-horizontal"
                    description="Last name"
                    label-for="last-name">
                    <h5 class="lead">{{ lname }}</h5>
                </b-form-group>
            </b-col>

            <b-col cols="2">
                <b-form-group
                    id="fieldset-horizontal"
                    description="Suffix"
                    label-for="name-suffix">
                    <h5 class="lead">{{ name_suffix ? name_suffix : '' }}</h5>
                </b-form-group>
            </b-col>
      </b-row>

      <b-row>
          <b-col cols="4">
                <b-form-group
                    id="fieldset-horizontal"
                    label-cols-sm="4"
                    label-cols-lg="4"
                    description="select gender"
                    label="Gender"
                    label-for="gerder_list">
                    <b-form-select v-model="gender" 
                        :options="gerder_list" id="gerder_list"></b-form-select>
                </b-form-group>
          </b-col>
      </b-row>

      <b-row>
            <b-col cols="8">
                <b-form-group
                    id="fieldset-horizontal"
                    label-cols-sm="2"
                    label-cols-lg="2"
                    description="Select Birth day from Calendar"
                    label="Birth Day"
                    label-for="bdate">
                    <b-form-input type="date" v-model="bdate" id="bdate"></b-form-input>
                </b-form-group>
            </b-col>
      </b-row>

      <b-row>
          <b-col cols="8">
                <b-form-group
                    id="fieldset-horizontal"
                    label-cols-sm="2"
                    label-cols-lg="2"
                    description="Select civil status"
                    label="Civil Status"
                    label-for="civil_status_list">
                    <b-form-select v-model="civil_stat" 
                        :options="civil_status_list" id="civil_status_list"></b-form-select>
                </b-form-group>
          </b-col>
      </b-row>

      <b-row>
          <b-col md="8">
                <b-form-group
                    id="fieldset-horizontal"
                    label-cols-sm="2"
                    label-cols-lg="2"
                    description="Type-in occupation"
                    label="Occupation"
                    label-for="occupation">
                    <b-form-input v-model="occupation" id="occupation"></b-form-input>
                </b-form-group>
          </b-col>
      </b-row>

        <!-- nationality -->
      <b-row>
          <b-col cols="4">
                <b-form-group
                    id="fieldset-horizontal"
                    label-cols-sm="4"
                    label-cols-lg="4"
                    description="Select Nationality"
                    label="Nationality"
                    label-for="nationality">
                    <b-form-select v-model="nationality" 
                        :options="nationality_list" id="nationality"></b-form-select>
                </b-form-group>
          </b-col>
      </b-row>

        <!-- tel no -->
      <b-row>
          <b-col md="8">
                <b-form-group
                    id="fieldset-horizontal"
                    label-cols-sm="2"
                    label-cols-lg="2"
                    description="Type-in Telephone No."
                    label="Telephone No."
                    label-for="tel_no">
                    <b-form-input v-model="tel_no" id="tel_no"></b-form-input>
                </b-form-group>
          </b-col>
      </b-row>

        <!-- mobile no -->
      <b-row>
          <b-col md="8">
                <b-form-group
                    id="fieldset-horizontal"
                    label-cols-sm="2"
                    label-cols-lg="2"
                    description="Type-in Mobile No."
                    label="Mobile No."
                    label-for="mobile_no">
                    <b-form-input v-model="mobile_no" id="mobile_no"></b-form-input>
                </b-form-group>
          </b-col>
      </b-row>

        <!-- email -->
      <b-row>
          <b-col md="8">
                <b-form-group
                    id="fieldset-horizontal"
                    label-cols-sm="2"
                    label-cols-lg="2"
                    description="Type-in Email"
                    label="Email"
                    label-for="email">
                    <b-form-input  type="email" v-model="email" id="email"></b-form-input>
                </b-form-group>
          </b-col>
      </b-row>

      <b-row>
          <b-col md="4">
              <b-button variant="info" 
                    block
                    type="submit"
                    :disabled="enableBtn"
                    @click.prevent="UpdateDonorDetails()">
                    <b-icon icon="pencil"></b-icon>&nbsp;UPDATE DONOR INFO
              </b-button>
          </b-col>
      </b-row>



  </div>
</template>

<script>
export default {
    data(){
        return{
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

            gerder_list: [
                { value: 'M', text: 'Male' },
                { value: 'F', text: 'Female' },
            ],

            civil_status_list: [
                { value: 'S', text: 'Single' },
                { value: 'M', text: 'Married' },
                { value: 'W', text: 'Widowed' },
                { value: 'X', text: 'Separated' },
            ],

            nationality_list: [
                { value: '137', text: 'Filipino' },
            ],
        }
    }, /* data */

    mounted(){
        this.getDonorDetails();
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

        UpdateDonorDetails(){

        }
    }, /* methods */
}
</script>

<style>

</style>