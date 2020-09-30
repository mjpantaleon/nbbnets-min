<template>
  <div class="main-div">
      <b-row id="bb-crumb-sticky">
          <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item active>
                        <b-icon icon="droplet-half" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Blood Testing
                    </b-breadcrumb-item>
                    <b-breadcrumb-item active>
                        <b-icon icon="droplet-half" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        List for Testing
                    </b-breadcrumb-item>
                </b-breadcrumb>

          </b-col>
        </b-row>

        <h4><b-icon icon="droplet-half"></b-icon> List for Testing</h4>
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
                    @click.prevent="getApprovedDonorList()">
                    <b-icon icon="search"></b-icon>&nbsp;SEARCH
                </b-button>
            </b-col>
        </b-row>
        
        <!-- DONT FORGET TO PLACE LOOP HERE -->
        <!-- <b-row>
            <b-col>

                <b-list-group>
                    <b-list-group-item  
                        v-for="(value, key) in data" 
                        v-bind:key="key">

                        <b-form-checkbox
                            :value="key"
                            unchecked-value="0"
                            >
                            {{ key }}
                        </b-form-checkbox>
                        
                    </b-list-group-item>
                </b-list-group>
            </b-col>
        </b-row> -->

        <template v-if="data">    
        <b-row>
            <b-col v-for="(value, key) in data" v-bind:key="key">
                <b-table striped hover
                    responsive="sm"
                    :fields="tti_fields"
                    :items="value">

                    <template v-slot:cell(donor)="value">
                        <!-- {{ value.item.first_name }}, {{ value.item.middle_name ? value.item.middle_name : null }}, {{ value.item.last_name }}, {{ value.item.name_suffix ? value.item.name_suffix : null }} -->
                        {{ value.item.first_name }}
                    </template>

                    <!-- <template v-slot:cell(donation_id)>
                        <b-form-input placeholder="Scan Donation ID" v-model="donation_id"></b-form-input>
                    </template>

                    <template v-slot:cell(HBSAG)>
                         <b-form-select v-model="HBSAG" :options="hbsag_option"></b-form-select>
                    </template>

                    <template v-slot:cell(HCV)>
                        <b-form-select v-model="HCV" :options="hcv_option"></b-form-select>
                    </template>

                    <template v-slot:cell(HIV)>
                        <b-form-select v-model="HIV" :options="hiv_option"></b-form-select>
                    </template>

                    <template v-slot:cell(MALA)>
                        <b-form-select v-model="MALA" :options="mala_option"></b-form-select>
                    </template>

                    <template v-slot:cell(RPR)>
                        <b-form-select v-model="RPR" :options="rpr_option"></b-form-select>
                    </template> -->

                </b-table>
            </b-col>
        </b-row> 
        </template>

        <template v-else>
            No records found
        </template>

  </div>
</template>

<script>
export default {
    data(){
        return{
            date_from: '',
            date_to: '',

            data: null,
            final_data: [],
            records: null,
            
            inputs: [
                {
                    donation_id: '',
                    HBSAG: '',
                    HCV: '',
                    HIV: '',
                    MALA: '',
                    RPR: '',
                }
            ],

            hbsag_option: [
                { text: 'N', value: 'n' },
                { text: 'R', value: 'r' }
            ],
            hcv_option: [
                { text: 'N', value: 'n' },
                { text: 'R', value: 'r' }
            ],
            hiv_option: [
                { text: 'N', value: 'n' },
                { text: 'R', value: 'r' }
            ],
            mala_option: [
                { text: 'Negative', value: 'n' },
                { text: 'Positive', value: 'r' }
            ],
            rpr_option: [
                { text: 'N', value: 'n' },
                { text: 'R', value: 'r' }
            ],

            tti_fields: [
                { key: 'donor', label: 'Donor' },
                { key: 'donation_id', label: 'Donation ID' },
                { key: 'HBSAG', label: 'Hbsag' },
                { key: 'HCV', label: 'Hcv' },
                { key: 'HIV', label: 'Hiv' },
                { key: 'MALA', label: 'Malaria' },
                { key: 'RPR', label: 'Syphilis' }
            ]
        }
    }, /* data */

    methods: {
        getApprovedDonorList(){
            axios
            .post('/get-approved-donor-list',{
                date_from: this.date_from,
                date_to: this.date_to,
            })
            .then(response => {
                if(response.data){
                    this.data = response.data
                } else {
                    this.data = null
                }
            })
        }
    },

    
}
</script>

<style>

</style>