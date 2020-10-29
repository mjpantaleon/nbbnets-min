<template>
  <div class="main-div">
      <b-row id="bb-crumb-sticky">
          <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item active>
                        <b-icon icon="file-text" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Blood Stocks
                    </b-breadcrumb-item>
                    <b-breadcrumb-item :to="{ path: '/blood-request/list' }">
                        <b-icon icon="file-text" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Blood Request
                    </b-breadcrumb-item>
                    <b-breadcrumb-item active>
                        Create Blood Request
                    </b-breadcrumb-item>
                </b-breadcrumb>
          </b-col>
        </b-row>

        <h4><b-icon icon="file-text"></b-icon> Create Blood Request</h4>
        <hr>

        <h4 class="text-secondary mt-3"> <b-icon icon="person-bounding-box"></b-icon> Patient Details</h4>
        <!-- Patient Full Name -->
        <b-row>
            <b-col cols="4">
                <b-form-group
                    id="fieldset-horizontal"           
                    label-cols-sm="4"
                    label-cols-lg="4"
                    description="First name"
                    label="Patient name"
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

            <b-col cols="2">
                <b-form-group
                    id="fieldset-horizontal"
                    description="Suffix"
                    label-for="name-suffix">
                <b-form-input v-model="name_suffix" id="name-suffix"></b-form-input>
                </b-form-group>
            </b-col>
        </b-row>

        <!-- blood type -->
        <b-row>
            <b-col cols="4">
                <b-form-group
                    id="fieldset-horizontal"
                    label-cols-sm="4"
                    label-cols-lg="4"
                    description="Select Blood Type"
                    label="Blood Type"
                    label-for="blood_type">
                    <b-form-select v-model="blood_type" 
                        :options="blood_type_list" id="blood_type"></b-form-select>
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

         <!-- civil_stat -->
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
                    <b-form-input type="date" v-model="bdate" id="bdate"></b-form-input>
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

        <!-- address -->
        <b-row>
            <b-col md="8">
                    <b-form-group
                        id="fieldset-horizontal"
                        label-cols-sm="2"
                        label-cols-lg="2"
                        description="Type-in address"
                        label="Address"
                        label-for="address">
                        <b-form-input v-model="address" id="address"></b-form-input>
                    </b-form-group>
            </b-col>
        </b-row>

        <!-- diagnosis -->
        <b-row>
            <b-col md="8">
                    <b-form-group
                        id="fieldset-horizontal"
                        label-cols-sm="2"
                        label-cols-lg="2"
                        description="Type-in diagnosis"
                        label="Diagnosis"
                        label-for="diagnosis">
                        <b-form-input v-model="diagnosis" id="diagnosis"></b-form-input>
                    </b-form-group>
            </b-col>
        </b-row>

        <h4 class="text-secondary mt-3"> <b-icon icon="person-square"></b-icon> Physician Details</h4>
        <!-- Patient Full Name -->
        <b-row>
            <b-col cols="4">
                <b-form-group
                    id="fieldset-horizontal"           
                    label-cols-sm="4"
                    label-cols-lg="4"
                    description="First name"
                    label="Physician name"
                    label-for="first-name">
                    <b-form-input v-model="dr_fname" id="first-name"></b-form-input>
                </b-form-group>
            </b-col>

            <b-col cols="3">
                <b-form-group
                    id="fieldset-horizontal"
                    description="Middle name"
                    label-for="middle-name">
                <b-form-input v-model="dr_mname" id="middle-name"></b-form-input>
                </b-form-group>
            </b-col>

            <b-col cols="3">
                <b-form-group
                    id="fieldset-horizontal"
                    description="Last name"
                    label-for="last-name">
                <b-form-input v-model="dr_lname" id="last-name"></b-form-input>
                </b-form-group>
            </b-col>

            <b-col cols="2">
                <b-form-group
                    id="fieldset-horizontal"
                    description="Suffix"
                    label-for="name-suffix">
                <b-form-input v-model="dr_name_suffix" id="name-suffix"></b-form-input>
                </b-form-group>
            </b-col>
        </b-row>

        <!-- Mobile # -->
        <b-row>
            <b-col md="8">
                    <b-form-group
                        id="fieldset-horizontal"
                        label-cols-sm="2"
                        label-cols-lg="2"
                        description="Type-in Mobile #"
                        label="Mobile #"
                        label-for="mobile_no">
                        <b-form-input v-model="mobile_no" id="mobile_no"></b-form-input>
                    </b-form-group>
            </b-col>
        </b-row>

        <!-- Email Address -->
        <b-row>
            <b-col md="8">
                    <b-form-group
                        id="fieldset-horizontal"
                        label-cols-sm="2"
                        label-cols-lg="2"
                        description="Type-in Email address"
                        label="Email Address"
                        label-for="email">
                        <b-form-input type="email" v-model="email" id="email"></b-form-input>
                    </b-form-group>
            </b-col>
        </b-row>


        <h4 class="text-secondary mt-3"> <b-icon icon="droplet-half"></b-icon> Blood Unit</h4>
        <!-- Blood Unit details -->
        <b-row>
            <b-col cols="4">
                {{cp_components}}
            </b-col>
        </b-row>
  </div>
</template>

<script>
export default {
     data(){
        return{
            enableBtn: false,
            showSuccessMsg: false,

            cp_components: '',

            // patient details
            fname: '',
            mname: '',
            lname: '',
            name_suffix: '',

            blood_type: '',
            gender: 'M',
            civil_stat: 'S',
            bdate: '',
            age: '',

            address: '',
            diagnosis: '',

            // physician
            dr_fname: '',
            dr_mname: '',
            dr_lname: '',
            dr_name_suffix: '',

            mobile_no: '',
            email: '',

            gerder_list: [
                { value: 'M', text: 'Male' },
                { value: 'F', text: 'Female' },
            ],

            blood_type_list: [
                { value: 'A Pos', text: 'A Pos' },
                { value: 'A Neg', text: 'A Neg' },
                { value: 'B Pos', text: 'B Pos' },
                { value: 'B Neg', text: 'B Neg' },
                { value: 'AB Pos', text: 'AB Pos' },
                { value: 'AB Neg', text: 'AB Neg' },
                { value: 'O Pos', text: 'O Pos' },
                { value: 'O Neg', text: 'O Neg' },
            ],

            civil_status_list: [
                { value: 'S', text: 'Single' },
                { value: 'M', text: 'Married' },
                { value: 'W', text: 'Widowed' },
                { value: 'X', text: 'Separated' },
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
        async getCpComponents(){

           await axios
           .get('/cp-components')
            .then(response => (
                this.cp_components = response.data
            ))
            .catch(error => console.log(error))
        }
    },

    mounted(){
        this.getCpComponents()
    },
}
</script>

<style>

</style>