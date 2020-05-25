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
        <!-- SHOW THIS MODAL AFTER SUCCESSFUL ACTION -->
        <b-modal v-model="showSuccessMsg" centered
            title="Success!"
            header-bg-variant="success"
            body-bg-variant="light" 
            footer-bg-variant="success"
            header-text-variant="light"
            hide-header-close>
            
            <h4 class="alert-heading text-center">
                <b-icon icon="person-check"></b-icon>&nbsp;New Donor has been added!
            </h4>
            
            <template v-slot:modal-footer="{ ok }">
                <b-link class="btn btn-success" :to="{ path: '/search-donor' }"
                    size="sm" variant="success" @click="ok()">
                    OK
                </b-link>
            </template>
        </b-modal>

        <!-- <b-form-input type="text" name="txtBdate" v-model="bdate"></b-form-input> -->
        {{ ngayon }}

      <!-- <b-table table-variant="light" bordered></b-table> -->
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
                    <b-form-select v-model="gender" 
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
                    :disabled="enableBtn"
                    @click.prevent="addNewDonor()">
                    <b-icon icon="person-plus"></b-icon>&nbsp;REGISTER NEW DONOR
              </b-button>
          </b-col>
      </b-row>
      
  </div>
</template>

<script>
export default {
    data(){
        // how to pass value of bday (date-picker) here???
        const now = new Date(this.txtBdate)
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate())

        return{
            ngayon: now,
            enableBtn: false,
            showSuccessMsg: false,

            fname: '',
            mname: '',
            lname: '',
            name_suffix: '',
            gender: 'M',
            bdate: '',
            civil_stat: 'S',
            occupation: '',
            nationality: '137',
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

            // age: today.getFullYear() - '2020-06-25'
            
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
        },
    }, /* computed */

    watch:{
        
    },

    methods: {
        addNewDonor(){
            axios
            .post('/create-new-donor', {
                fname : this.fname,
                mname : this.mname,
                lname : this.lname,
                name_suffix : this.name_suffix,
                gender : this.gender,
                bdate : this.bdate,
                civil_stat : this.civil_stat,
                occupation : this.occupation,
                nationality : this.nationality,
                tel_no : this.tel_no,
                mobile_no : this.mobile_no,
                email : this.email,
            })
            .then(response => (
                this.showSuccessMsg = true,
                this.enableBtn = true
            ))
            .catch(error => console.log(error))
        }
    }
}
</script>

<style>

</style>