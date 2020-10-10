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
                        NAT
                    </b-breadcrumb-item>
                </b-breadcrumb>

          </b-col>
        </b-row>

        <h4><b-icon icon="droplet-half"></b-icon> NAT</h4>
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
                    @click.prevent="getComponentsForTest()">
                    <b-icon icon="search"></b-icon>&nbsp;SEARCH
                </b-button>
            </b-col>
        </b-row>

        {{data}}
        
  </div>
</template>

<script>
export default {
    data(){
        return{
            date_from: '',
            date_to: '',

            data: [],
        }
    }, /* data */

    methods: {
        async getComponentsForTest(){
            await axios
            .post('/components-for-nat-test', {
                date_from: this.date_from,
                date_to: this.date_to
            })
            .then(response =>{
                if(response.data){
                    this.data = response.data
                } else{
                    this.data = []
                }
            })
        }

    }, /* methods */
}
</script>

<style>

</style>