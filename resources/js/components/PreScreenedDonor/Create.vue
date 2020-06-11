<template>
  <div class="main-div">
        <b-row id="bb-crumb-sticky">
          <b-col>
                <b-breadcrumb>
                    <!-- <b-breadcrumb-item :to="{ path: '/pre-screened-list' }">
                        <b-icon icon="person-lines-fill" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Pre-Screened Donors
                    </b-breadcrumb-item> -->
                    <b-breadcrumb-item active>
                        <b-icon icon="file-earmark-ruled" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Pre-Screening
                    </b-breadcrumb-item>
                    <!-- <b-breadcrumb-item href="#home" active>
                        <b-icon icon="person-plus" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Register New Donor
                    </b-breadcrumb-item> -->
                </b-breadcrumb>
          </b-col>
        </b-row>

        <h4><b-icon icon="file-earmark-ruled"></b-icon> Pre-Screening</h4>
        <hr>

         <!-- first name, middle, last name and suffix -->
        <b-row>
                <b-col cols="4">
                    <b-form-group
                        id="fieldset-horizontal"           
                        label-cols-sm="4"
                        label-cols-lg="4"
                        description="First name"
                        label="Full name"
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

        <!-- gender -->
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

        <!-- bdate -->
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

            <b-col cols="3">
                <b-form-group
                    id="fieldset-horizontal"
                    description="Computed age"
                    label-for="age">
                    <b class="text-danger">{{ calculateAge }} y/o</b>
                    <!-- <b class="text-danger">{{ age }} y/o</b> -->
                </b-form-group>
            </b-col>
        </b-row>
  </div>
</template>

<script>
export default {
    data(){
        return{
            //  Donor Details
            fname: '',
            mname: '',
            lname: '',
            name_suffix: '',

            gender: 'M',
            bdate: '',
            age: '',

            weight: '',

            nationality: '137',
            address: '',
            
            // How would you like us to reach you
            email: '',
            fb: '',  
            tel_no: '',

            contact_me_at: '',              // When is the best time to call you?

            // QUESTIONAIRE
            $symptoms: '',                  // Has the following
            $diagnosed_with_covid: '',      // QUESTION #1
            $test_results: '',              // QUESTION #2

            gerder_list: [
                { value: 'M', text: 'Male' },
                { value: 'F', text: 'Female' },
            ],

            nationality_list: [
                { value: '137', text: 'Filipino' },
            ],

        }
    }
}
</script>

<style>

</style>