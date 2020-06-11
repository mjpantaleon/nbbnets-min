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
        {{test_results}} {{symptoms}}
        <b-row>
            <b-col md="6">
                <b-jumbotron bg-variant="light"> 
                    <template v-slot:lead>
                        <b><b-icon icon="info-square-fill" class="text-primary"></b-icon>&nbsp;Have you been diagnosed to have COVID-19?</b>
                        <hr>
                        <b-form-group>
                            <b-form-radio v-model="selected" name="first_answer" value="0">Yes</b-form-radio>
                            <b-form-radio v-model="selected" name="first_answer" value="1">Not sure</b-form-radio>
                        </b-form-group>
                    </template>
                </b-jumbotron>
            </b-col>
            <b-col md="6" v-if="selected">

                <b-jumbotron v-if="selected == '0'" bg-variant="light"> 
                    <template v-slot:lead>
                        <b><b-icon icon="info-square-fill" class="text-warning"></b-icon>&nbsp;Do you have the following tests results? </b>
                        <hr>
                        <b-form-group>
                            <b-form-checkbox-group
                                id="checkbox-group-1"
                                v-model="test_results"
                                :options="test_result_list"
                                name="test_results">
                                </b-form-checkbox-group>
                        </b-form-group>
                    </template>
                </b-jumbotron>

                <b-jumbotron v-if="selected == '1'" bg-variant="light"> 
                    <template v-slot:lead>
                        <b><b-icon icon="info-square-fill" class="text-warning"></b-icon>&nbsp;For the past 28 weeks have ever had the following? </b>
                        <hr>
                        <b-form-group label="please select atleast 5 from the items below">
                            <b-form-checkbox-group stacked
                                id="checkbox-group-1"
                                v-model="symptoms"
                                :options="symptoms_list"
                                name="symptoms">
                                </b-form-checkbox-group>
                        </b-form-group>
                    </template>
                </b-jumbotron>
            </b-col>
        </b-row>
        

        <!-- first name, middle, last name and suffix -->
        <!-- <b-row>
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
        </b-row> -->

        <!-- gender -->
        <!-- <b-row>
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
        </b-row> -->

        <!-- bdate -->
        <!-- <b-row>
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
                </b-form-group>
            </b-col>
        </b-row> -->
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

            // first_answer
            selected: '',

            gerder_list: [
                { value: 'M', text: 'Male' },
                { value: 'F', text: 'Female' },
            ],

            nationality_list: [
                { value: '137', text: 'Filipino' },
            ],

            test_results: [],
            test_result_list: [
                { text: 'A. Initial posiive RT - PCR result', value: 'a' },
                { text: 'B. Repeat RT - PCR Negative result', value: 'b' },
                { text: 'C. No test results', value: 'c' }
            ],

            symptoms: [],
            symptoms_list:[
                { text: 'a. Cough', value: 'a' },
                { text: 'b. Fever (C 38.0)', value: 'b' },
                { text: 'c. Difficulty breathing', value: 'c' },
                { text: 'd. Diarrhea', value: 'd' },
                { text: 'e. Fatigue', value: 'e' },
                { text: 'f. Body aches and pain', value: 'f' },
            ]

        }
    }
}
</script>

<style>

</style>