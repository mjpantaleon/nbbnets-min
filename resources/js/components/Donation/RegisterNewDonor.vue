<template>
  <div class="main-div">
      <b-row>
          <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item :to="{ path: '/donation' }">
                        <b-icon icon="card-checklist" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Donation
                    </b-breadcrumb-item>
                    <b-breadcrumb-item href="#home" active>
                        <b-icon icon="person-plus" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Register New Donor
                    </b-breadcrumb-item>
                    <!-- <b-breadcrumb-item href="#foo">Select Donor</b-breadcrumb-item>
                    <b-breadcrumb-item href="#bar">Donor Details</b-breadcrumb-item>
                    <b-breadcrumb-item>Baz</b-breadcrumb-item> -->
                </b-breadcrumb>
          </b-col>
      </b-row>

      <h4><b-icon icon="person-plus"></b-icon> Register New Donor</h4>
      <hr>

      <b-table table-variant="light" bordered></b-table>
      <b-row>
            <b-col cols="4">
                <b-form-group
                    id="fieldset-horizontal"           
                    label-cols-sm="4"
                    label-cols-lg="4"
                    description="First name"
                    label="Donor name"
                    label-for="first-name">
                    <b-form-input :state="checkFirstName" v-model="fname" id="first-name"></b-form-input>
                </b-form-group>
            </b-col>

            <b-col cols="3">
                <b-form-group
                    id="fieldset-horizontal"
                    description="Middle name"
                    label-for="middle-name">
                <b-form-input :state="checkMiddleName" v-model="mname" id="middle-name"></b-form-input>
                </b-form-group>
            </b-col>

            <b-col cols="3">
                <b-form-group
                    id="fieldset-horizontal"
                    description="Last name"
                    label-for="last-name">
                <b-form-input :state="checkLastName" v-model="lname" id="last-name"></b-form-input>
                </b-form-group>
            </b-col>

            <b-col cols="2">
                <b-form-group
                    id="fieldset-horizontal"
                    description="Suffix"
                    label-for="name-suffix">
                <b-form-input v-model="name_suffix" id="name-suffix"></b-form-input>
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
                    <b-form-select v-model="selected_gender" 
                        :options="gerder_list" id="gerder_list"></b-form-select>
                </b-form-group>
          </b-col>
      </b-row>

      <b-row>
            <b-col cols="4">
                <b-form-group
                    id="fieldset-horizontal"
                    label-cols-sm="4"
                    label-cols-lg="4"
                    description="Select Birth day from Calendar"
                    label="Birth Day"
                    label-for="bdate">
                    <b-form-input type="date" :state="checkBday" v-model="bdate" id="bdate"></b-form-input>
                </b-form-group>
            </b-col>
            <!-- <b-col cols="3">
                <b-form-group
                    id="fieldset-horizontal"
                    description="Birth day Calendar"
                    label-for="bdate">
                    <b-form-datepicker v-model="bdate" id="bdate"></b-form-datepicker>
                </b-form-group>
            </b-col> -->
      </b-row>

      <b-row>
          <b-col cols="4">
                <b-form-group
                    id="fieldset-horizontal"
                    label-cols-sm="4"
                    label-cols-lg="4"
                    description="Select civil status"
                    label="Civil Status"
                    label-for="civil_status_list">
                    <b-form-select v-model="selected_civil_stat" 
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

      <b-row>
          <b-col cols="4">
                <b-form-group
                    id="fieldset-horizontal"
                    label-cols-sm="4"
                    label-cols-lg="4"
                    description="Select Nationality"
                    label="Nationality"
                    label-for="nationality">
                    <b-form-select v-model="selected_nationality" 
                        :options="nationality" id="nationality"></b-form-select>
                </b-form-group>
          </b-col>
      </b-row>

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
              <b-button variant="success" 
                    block
                    type="submit"
                    @click="submit()">
                    <b-icon icon="person-plus"></b-icon>&nbsp;REGISTER NEW DONOR
              </b-button>
          </b-col>
      </b-row>
      
  </div>
</template>

<script>
export default {
    data(){
        return{
            fname: '',
            mname: '',
            lname: '',
            name_suffix: '',
            gender: 'M',
            bdate: '',
            tel_no: '',
            mobile_no: '',
            email: '',

            selected_gender: 'M',
            selected_civil_stat: 'S',
            selected_nationality: '137',

            occupation: '',

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

            nationality: [
                { value: '137', text: 'Filipino' },
            ],
        }
    }, /* data */

    computed: {
        checkFirstName(){
             return this.fname.length > 2 ? true : false
        },
        checkMiddleName(){
            return this.mname.length > 1 ? true : false
        },
        checkLastName(){
             return this.lname.length > 2 ? true : false
        },
        checkBday(){
            return this.bdate.length > 4 ? true : false
        }
    }
}
</script>

<style>

</style>