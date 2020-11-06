<template>
  <div class="main-div">
        <b-row id="bb-crumb-sticky">
          <b-col>
                <b-breadcrumb>
                   <b-breadcrumb-item active>
                        <b-icon icon="droplet-half" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Pre-screening
                    </b-breadcrumb-item>
                    <b-breadcrumb-item active>
                        <b-icon icon="file-earmark-ruled" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Pre-Screening
                    </b-breadcrumb-item>
                </b-breadcrumb>
          </b-col>
        </b-row>

        <h4><b-icon icon="file-earmark-ruled"></b-icon> Pre-Screening</h4>
        <hr>
        
        <b-row>
            <b-col md="6">
                <b class="lead">
                    <b-icon icon="person-bounding-box"></b-icon>&nbsp;Personnal Information</b>

                <!-- first name, middle, last name and suffix -->
                <b-row class="mt-3">
                    <b-col>
                        <b-form-group
                            id="fieldset-horizontal"           
                            label-cols-sm="3"
                            label-cols-lg="3"
                            description="First name"
                            label="First name"
                            label-for="first-name">
                            <b-form-input :state="checkFirstName" v-model="fname" id="first-name"></b-form-input>
                        </b-form-group>
                    </b-col>
                </b-row>

                <b-row>
                    <b-col>
                        <b-form-group
                            id="fieldset-horizontal"
                             label-cols-sm="3"
                            label-cols-lg="3"
                            description="Middle name/ Initial"
                            label="Middle name"
                            label-for="middle-name">
                        <b-form-input :state="checkMiddleName" v-model="mname" id="middle-name"></b-form-input>
                        </b-form-group>
                    </b-col>
                </b-row>

                <b-row>
                    <b-col>
                        <b-form-group
                            id="fieldset-horizontal"
                             label-cols-sm="3"
                            label-cols-lg="3"
                            description="Last name"
                            label="Last name"
                            label-for="last-name">
                        <b-form-input :state="checkLastName" v-model="lname" id="last-name"></b-form-input>
                        </b-form-group>
                    </b-col>
                </b-row>

                <b-row>
                    <b-col>
                        <b-form-group
                            id="fieldset-horizontal"
                            label-cols-sm="3"
                            label-cols-lg="3"
                            description="Suffix"
                            label-for="name-suffix">
                        <b-form-input v-model="name_suffix" id="name-suffix"></b-form-input>
                        </b-form-group>
                    </b-col>
                </b-row>

                <!-- nationality -->
                <b-row>
                    <b-col>
                            <b-form-group
                                id="fieldset-horizontal"
                                label-cols-sm="3"
                                label-cols-lg="3"
                                description="Select Nationality"
                                label="Nationality"
                                label-for="nationality">
                                <b-form-select v-model="nationality" 
                                    :options="nationality_list" id="nationality"></b-form-select>
                            </b-form-group>
                    </b-col>
                </b-row>

                <!-- gender -->
                <b-row>
                    <b-col>
                            <b-form-group
                                id="fieldset-horizontal"
                                label-cols-sm="3"
                                label-cols-lg="3"
                                description="select gender"
                                label="Gender"
                                label-for="gerder_list">
                                <b-form-select v-model="gender" 
                                    :options="gerder_list" id="gerder_list"></b-form-select>
                            </b-form-group>
                    </b-col>
                </b-row>

                <template v-if="gender == 'F'">
                <b-row>
                    <b-col>
                        <b-form-group
                            id="fieldset-horizontal"
                            label-cols-sm="3"
                            label-cols-lg="3"
                            description="select answer"
                            label="Been Pregnant?"
                            label-for="been_pregnant_option">
                            <b-form-select v-model="been_pregnant" 
                                    :options="yes_no_option" id="been_pregnant_option"></b-form-select>
                        </b-form-group>
                    </b-col>
                </b-row>

                <b-row>
                    <b-col>
                        <b-form-group
                            id="fieldset-horizontal"
                            label-cols-sm="3"
                            label-cols-lg="3"
                            description="select answer"
                            label="Had Blood Transfusion?"
                            label-for="had_transfusion_option">
                            <b-form-select v-model="had_blood_transfusion" 
                                    :options="yes_no_option" id="had_transfusion_option"></b-form-select>
                        </b-form-group>
                    </b-col>
                </b-row>
                </template>

                <!-- bdate -->
                <b-row>
                    <b-col>
                        <b-form-group
                            id="fieldset-horizontal"
                            label-cols-sm="3"
                            label-cols-lg="3"
                            description="Select Birth day from Calendar"
                            label="Birth Day"
                            label-for="bdate">
                            <b-form-input type="date" :state="checkBday" v-model="bdate" id="bdate"></b-form-input>
                        </b-form-group>
                    </b-col>
                </b-row>

                <!-- age -->
                <b-row>
                    <b-col>
                        <b-form-group
                            id="fieldset-horizontal"
                            label-cols-sm="3"
                            label-cols-lg="3"
                            description="Computed age"
                            label-for="age">
                            <b class="text-danger">{{ calculateAge }} y/o</b>
                        </b-form-group>
                    </b-col>
                </b-row>

                <!-- weight -->
                <b-row>
                    <b-col>
                        <b-form-group
                            id="fieldset-horizontal"           
                            label-cols-sm="3"
                            label-cols-lg="3"
                            description="Weight in Kilo grams"
                            label="Weight"
                            label-for="weight">
                            <b-form-input type="number" :state="checkWeight" v-model="weight" id="weight"></b-form-input>
                        </b-form-group>
                    </b-col>
                </b-row>

                <!-- address -->
                <b-row>
                    <b-col>
                        <b-form-group
                            id="fieldset-horizontal"           
                            label-cols-sm="3"
                            label-cols-lg="3"
                            description="Complete address"
                            label="Address"
                            label-for="address">
                            <b-form-textarea :state="checkAddess" v-model="address" id="address"></b-form-textarea>
                        </b-form-group>
                    </b-col>
                </b-row>

                <!-- email -->
                <b-row>
                    <b-col>
                            <b-form-group
                                id="fieldset-horizontal"
                                label-cols-sm="3"
                                label-cols-lg="3"
                                description="Type-in Email"
                                label="Email"
                                label-for="email">
                                <b-form-input :state="checkEmail" type="email" v-model="email" id="email"></b-form-input>
                            </b-form-group>
                    </b-col>
                </b-row>

                <b class="lead">
                    <b-icon icon="chat"></b-icon>&nbsp;How would you like us to reach you?</b>
                <hr>


                <!-- fb -->
                <b-row>
                    <b-col>
                        <b-form-group
                            id="fieldset-horizontal"
                            label-cols-sm="3"
                            label-cols-lg="3"
                            description="Type-in facebook link"
                            label="Facebook"
                            label-for="fb">
                            <b-form-input v-model="fb" id="fb"></b-form-input>
                        </b-form-group>
                    </b-col>
                </b-row>

                <!-- mobile no -->
                <b-row>
                    <b-col>
                            <b-form-group
                                id="fieldset-horizontal"
                                label-cols-sm="3"
                                label-cols-lg="3"
                                description="Type-in Mobile No."
                                label="Mobile No."
                                label-for="mobile_no">
                                <b-form-input v-model="mobile_no" id="mobile_no"></b-form-input>
                            </b-form-group>
                    </b-col>
                </b-row>

                <b class="lead"><b-icon icon="phone"></b-icon>&nbsp;What is the best time to call you?</b>
                <hr>  

                <!-- time availability -->
                <b-row>
                    <b-col>
                            <b-form-group
                                id="fieldset-horizontal"
                                label-cols-sm="3"
                                label-cols-lg="3"
                                description="Type-in Time of availability"
                                label="Time of Availability"
                                label-for="time_availability">
                                <!-- <b-form-input v-model="time_availability" id="time_availability"></b-form-input> -->
                                <b-time v-model="time_availability" show-seconds locale="en"></b-time>
                            </b-form-group>
                    </b-col>
                </b-row>

            </b-col>  
            
            <!-- PRE - SCREENING QUESTIONAIRE -->
            <b-col md="6">
                <b class="lead"><b-icon icon="file-earmark-ruled"></b-icon>&nbsp;Pre - Screening Questionaire</b>
                
                <!--  FIRST_ANSWER -->
                <b-jumbotron class="mt-3" bg-variant="success" text-variant="light"> 
                    <template v-slot:lead>
                        <b><b-icon icon="info-square-fill"></b-icon>&nbsp;Have you been diagnosed to have COVID-19?</b>
                        <hr>
                        <b-form-group>
                            <b-form-radio v-model="first_answer" name="first_answer" value="0">Yes</b-form-radio>
                            <b-form-radio v-model="first_answer" name="first_answer" value="1">Not sure</b-form-radio>
                        </b-form-group>
                    </template>
                </b-jumbotron>

                <!--  TEST RESULTS -->
                <b-row>
                    <b-col v-if="first_answer">
                        <b-jumbotron v-if="first_answer == '0'" bg-variant="secondary" text-variant="light"> 
                            <template v-slot:lead>
                                <b><b-icon icon="info-square-fill" class="text-warning"></b-icon>&nbsp;Do you have the following tests results? </b>
                                <hr>
                                <b-form-group>
                                    <b-form-checkbox-group stacked
                                        v-model="test_results"
                                        :options="test_result_list"
                                        name="test_results">
                                        </b-form-checkbox-group>
                                </b-form-group>
                            </template>
                        </b-jumbotron>
                    </b-col>
                </b-row>

                <!-- SYMPTOMS-->
                <b-row>
                    <b-col>
                    <!-- <b-col md="8" v-if="selected || test_results && symptoms"> -->
                        <b-jumbotron v-if="first_answer == '1' || test_results == 'c' && symptoms" 
                            bg-variant="danger" text-variant="light"> 
                            <template v-slot:lead>
                                <b><b-icon icon="info-square-fill" class="text-warning"></b-icon>&nbsp;For the past 28 weeks have you ever had the following? </b>
                                <hr>
                                <b-form-group>
                                    <template slot:label>
                                        <p><i class="text-sm"> please select atleast five (5) from the list below</i></p>
                                    </template>
                                    <b-form-checkbox-group stacked
                                        v-model="symptoms"
                                        :options="symptoms_list"
                                        name="symptoms">
                                        </b-form-checkbox-group>
                                </b-form-group>
                            </template>
                        </b-jumbotron>
                    </b-col>
                </b-row>
            </b-col>
        </b-row>

        <b-row>
            <b-col md="4">
                <b-button variant="success"
                    block
                    type="submit"
                    :disabled="disableBtn"
                    @click.prevent="submitEntry()">
                    <b-icon icon="check-circle"></b-icon>&nbsp;SUBMIT ENTRY</b-button>
            </b-col>
        </b-row>

        <!-- =============== MODALS ================ -->

        <!-- SHOW THIS MODAL AFTER SUCCESSFUL ACTION -->
        <b-modal v-model="showSuccessMsg" centered
            title="SUCCESS"
            header-bg-variant="success"
            body-bg-variant="light" 
            footer-bg-variant="success"
            header-text-variant="light"
            hide-header-close>
            
            <h5 class="alert-heading text-center">
                <b-icon icon="person-check"></b-icon>&nbsp;{{message}}
            </h5>
            
            <template v-slot:modal-footer="{ ok }">
                <b-link class="btn btn-success" :to="{ path: '/pre-screened-list' }"
                    size="sm" variant="success" @click="ok()">
                    OK
                </b-link>
            </template>
        </b-modal>

        <!-- IF SOME FIELDS ARE MISSING -->
        <b-modal v-model="showNoAnswer" centered
            title="STOP"
            header-bg-variant="danger"
            body-bg-variant="light" 
            footer-bg-variant="danger"
            header-text-variant="light"
            hide-header-close>
            
            <h5 class="alert-heading text-center">
                <b-icon icon="info-square" class="text-warning"></b-icon>&nbsp;
                Please dont forget to answer the Personnal Information/ Pre-screening Questionaire!
            </h5>
            
            <template v-slot:modal-footer="{ ok }">
                <b-link class="btn btn-danger"
                    size="sm" variant="danger" @click="ok()">
                    OK
                </b-link>
            </template>
        </b-modal>

  </div>
</template>

<script>
export default {
    data(){
        return{
            message: '',
            // confirmation
            showSuccessMsg: false,
            disableBtn: false,
            showNoAnswer: false,

            //  Donor Details
            fname: '',
            mname: '',
            lname: '',
            name_suffix: '',

            gender: 'M',
            // if Female
            been_pregnant: 'N',
            had_blood_transfusion: 'N',

            bdate: '',
            age: '',

            weight: '',

            nationality: 'Filipino',
            address: '',
            
            // How would you like us to reach you
            email: '',
            fb: '',  
            mobile_no: '',

            time_availability: '',              // When is the best time to call you?

            // first_answer
            first_answer: '',

            yes_no_option: [
                { value: 'Y', text: 'Yes' },
                { value: 'N', text: 'No' },
            ],

            gerder_list: [
                { value: 'M', text: 'Male' },
                { value: 'F', text: 'Female' },
            ],

            nationality_list: [
                { value: 'Filipino', text: 'Filipino' },
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
        checkWeight(){
            return this.weight.length > 1 ? true : false
        },
        checkAddess(){
            return this.address.length > 8 ? true : false
        },
        checkEmail(){
            return this.email.length > 8 ? true : false
        },
        
        calculateAge: function() {
            var now = new Date();
            let birthDate = new Date(this.bdate);

            function isLeap(year) {
                return year % 4 == 0 && (year % 100 != 0 || year % 400 == 0);
            }
            
            // days since the birthdate    
            var days = Math.floor((now.getTime() - birthDate.getTime())/1000/60/60/24);
            var age = 0;
            // iterate the years
            for (var y = birthDate.getFullYear(); y <= now.getFullYear(); y++){
                var daysInYear = isLeap(y) ? 366 : 365;
                if (days >= daysInYear){
                days -= daysInYear;
                age++;
                // increment the age only if there are available enough days for the year.
                }
            }
            return this.age = age;

        },
    }, /* computed */

    methods: {
        submitEntry(){
            // check if has answered the personnal info/questionaire
            if(this.first_answer == '' || this.fname == '' || this.mname == '' || this.lname == '' || this.bdate == '' || this.email == ''){
                this.showNoAnswer = true
            } else{
                axios
                .post('/submit-entry', {
                    fname : this.fname,
                    mname : this.mname,
                    lname : this.lname,
                    name_suffix : this.name_suffix,
    
                    nationality : this.nationality,
                    gender : this.gender,
    
                    bdate : this.bdate,
                    age : this.age,
    
                    weight : this.weight,
                    address : this.address,
    
                    email : this.email,
                    fb : this.fb,
                    mobile_no : this.mobile_no,
    
                    time_availability : this.time_availability,
    
                    first_answer : this.first_answer,
                    test_results : this.test_results,
                    symptoms : this.symptoms,

                    been_pregnant: this.been_pregnant,
                    had_blood_transfusion: this.had_blood_transfusion
                })
                .then(response =>{
                    if(response.data){
                         this.message = response.data.message
                        this.showSuccessMsg = true,
                        this.disableBtn = true
                    }
                })
                .catch(error => console.log(error))
            }

        }
    }
}
</script>

<style>

</style>