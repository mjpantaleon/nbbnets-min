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
                        HNA & HNL
                    </b-breadcrumb-item>
                </b-breadcrumb>

          </b-col>
        </b-row>

        <h4><b-icon icon="droplet-half" variant="warning"></b-icon> HNA & HNL</h4>
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
                    @click.prevent="getComponentsForHnaTest()">
                    <b-icon icon="search"></b-icon>&nbsp;SEARCH
                </b-button>
            </b-col>
        </b-row>


  </div>
</template>

<script>
export default {
    data(){
        return{
            showSuccessMsg: false,

            date_from: '',
            date_to: '',

            data: [],

            message: '',

            hna_option: [
                { text: 'Positive', value: 'P' },
                { text: 'Negative', value: 'N' }
            ],

            hna_fields: [
                { key: 'donation_id', label: 'Donation ID' },
                { key: 'component_abbr', label: 'Component' },
                { key: 'hna_result', label: 'HNA & HNL Result' },
            ],

        }
    }
}
</script>

<style>

</style>