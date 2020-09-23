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
                    @click.prevent="getDonationId()">
                    <b-icon icon="search"></b-icon>&nbsp;SEARCH
                </b-button>
            </b-col>
        </b-row>

        <!-- DONT FORGET TO PLACE LOOP HERE -->
        <b-row>
            <b-col cols="2">
                Juan Manual Marquez
            </b-col>

            <b-col cols="4">
                <b-form-group
                    id="donation-id"
                    label-for="donation-id">
                        <b-form-input placeholder="Scan Donation ID" v-model="input.donation_id"></b-form-input>
                </b-form-group>
            </b-col>

            <b-col>
                <b-form-select v-model="input.HBSAG" :options="hbsag_option"></b-form-select>
            </b-col>

             <b-col>
                <b-form-select v-model="input.HCV" :options="hcv_option"></b-form-select>
            </b-col>

             <b-col>
                <b-form-select v-model="input.HIV" :options="hiv_option"></b-form-select>
            </b-col>

             <b-col>
                <b-form-select v-model="input.MALA" :options="mala_option"></b-form-select>
            </b-col>

             <b-col>
                <b-form-select v-model="input.RPR" :options="rpr_option"></b-form-select>
            </b-col>
        </b-row>

        
  </div>
</template>

<script>
export default {
    data(){
        return{
            date_from: '',
            date_to: '',

            input: [
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
        }
    }
}
</script>

<style>

</style>