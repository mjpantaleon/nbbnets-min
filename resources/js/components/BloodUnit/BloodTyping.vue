<template>
    <div class="main-div">
        <b-row id="bb-crumb-sticky">
          <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item active>
                        <b-icon icon="file-text" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Blood Unit
                    </b-breadcrumb-item>
                    <b-breadcrumb-item active>
                        <!-- <b-icon icon="person-plus"></b-icon>  -->
                        Blood Typing
                    </b-breadcrumb-item>
                </b-breadcrumb>
          </b-col>
        </b-row>

        <h4><b-icon icon="file-text"></b-icon> Blood Typing</h4>
        <hr>

        <b-row>
            <b-col cols="5">
                <b-form-group
                    id="donation-id"           
                    label-cols-sm="2"
                    label-cols-lg="2"
                    description="Date From"
                    label="Date From"
                    label-for="donation-id">
                        <b-form-datepicker 
                            v-model="date_from"
                            class="mb-2">
                        </b-form-datepicker>
                </b-form-group>
            </b-col>
            <b-col cols="5">
                <b-form-group
                    id="donation-id"           
                    label-cols-sm="1"
                    label-cols-lg="1"
                    description="Search Date To"
                    label="To"
                    label-for="donation-id">
                        <b-form-datepicker
                            v-model="date_to"
                            class="mb-2">
                        </b-form-datepicker>
                </b-form-group>
            </b-col>
            <b-col cols="2" class="ml-auto">
                <b-button type="submit"
                    variant="warning"
                    @click.prevent="getDonationId()">
                    <b-icon icon="search"></b-icon>&nbsp;SEARCH
                </b-button>
            </b-col>
        </b-row>

        <b-row>
            <b-col cols="4">
                <b-card-group deck v-if="donation_ids">
                    <b-card header="CLICK TO SELECT ID">
                        <b-list-group>
                            <b-list-group-item  
                                v-for="(value, key) in donation_ids" 
                                v-bind:key="key">

                                <b-form-checkbox
                                    v-model="checked"
                                    :value="key"
                                    unchecked-value="0"
                                    >
                                    {{ key }}
                                </b-form-checkbox>
                            </b-list-group-item>
                        </b-list-group>
                    </b-card>
                </b-card-group>
                 <b-card-group deck v-else>
                    <b-card header="CLICK TO SELECT ID">
                        <b-list-group>
                            {{ select_id_notice }}
                        </b-list-group>
                    </b-card>
                </b-card-group>               
            </b-col>
            
            <b-col cols="8">
                <b-row>
                    <b-col>
                        <template v-if="isLoading">
                            <div class="d-flex justify-content-center mb-3">
                                <b-spinner variant="danger" label="Please wait..."></b-spinner>
                            </div>
                        </template>
                        
                        <template v-if="data.length != 0">
                            <b-table class="mt-3" 
                                id="main-table"
                                responsive="sm"
                                striped hover
                                head-variant="light"
                                table-variant="light"
                                :fields="fields"
                                :items="data"
                                :per-page="perPage"
                                :current-page="currentPage">

                                <template v-slot:cell(donationId)="data">
                                    {{ data.item.donation_id }}
                                    <!-- {{ data.item }} -->
                                </template>

                                <template v-slot:cell(abo)="data">
                                    <b-form-select :name="'abo-' + data.item.donation_id" v-model="final_data[data.index]['abo']" :options="abo" size="sm"></b-form-select>
                                </template>

                                <template v-slot:cell(rhType)="data">
                                    <b-form-select :name="'rh-' + data.item.donation_id" v-model="final_data[data.index]['rh']" :options="rhType" size="sm"></b-form-select>
                                </template>
 
                            </b-table>

                            <b-pagination
                                v-model="currentPage"
                                :total-rows="rows"
                                :per-page="perPage"
                                aria-controls="main-table">
                            </b-pagination>

                            <b-row>
                                <b-col class="text-right">
                                    <b-button variant="success" @click="showModal">Submit</b-button>
                                    <b-button variant="danger">Cancel</b-button>
                                </b-col>
                            </b-row>

                        </template>

                        <template v-else>
                            <div class="alert alert-info mt-3">
                                <span class="text-center text-danger">
                                    <h5><b-icon icon="info-square"></b-icon>&nbsp;&nbsp;No selected donation ID</h5>
                                </span>
                            </div>
                        </template>

                    </b-col>
                </b-row>
            </b-col>

        </b-row>


    </div>
</template>

<script>
export default {
    data(){
        return{

            fields: [
                { key: 'donationId', label: 'Donation ID' },
                { key: 'abo', label: 'ABO' },
                { key: 'rhType', label: 'RH Type' },
            ],

            abo: [
                { text: 'A', value: 'A' },
                { text: 'B', value: 'B' },
                { text: 'AB', value: 'AB' },
                { text: 'O', value: 'O' },
            ],
            rhType: [
                { text: 'Positive', value: 'Pos' },
                { text: 'Negative', value: 'Neg' },
            ],
            data: [],
            isLoading: false,

            final_data:[],

            date_from: '',
            date_to: '',

            donation_ids: [],
            select_id_notice: "No Items to display",
            displayStatus: 0,

            checked: [],

            perPage: 10,
            currentPage: 1,

        }
    }, /* data */

    mounted(){

    }, /* mounted */

    methods: {
        getDonationId (){

            axios
                .post('/get-donation-id', {
                    date_from: this.date_from,
                    date_to: this.date_to,
                })
                .then(response => {

                    if(response.data){
                        this.donation_ids = response.data
                        console.log(this.donation_ids)
                    } else{
                        this.donation_ids = []
                        this.select_id_notice = "No Data Found"
                    }
                    
                })

        },

        showModal(){
            
            var err = false;

            

        },

    }, /* methods */

    computed: {
        // pagination
        rows() {

        },
    }, /* computed */

    watch:{
        checked: function(val){
            this.data = []
            this.final_data = []

            val.forEach((v) => {
                this.data.splice(v,0,this.donation_ids[v])
                this.final_data.splice(v,0,this.donation_ids[v])
            })
        },

        data: function(val){
            console.log(val)   
        },
        
    }
}
</script>

<style>

</style>