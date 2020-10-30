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
                        For Look Up
                    </b-breadcrumb-item>
                </b-breadcrumb>
          </b-col>
        </b-row>

        <h4><b-icon icon="search" variant="primary"></b-icon> For Look Up</h4>
        <hr>
        
        <b-row>
            <b-col cols="4">
                <h4 class="text-secondary"><b-icon icon="clipboard"></b-icon> Blood Request Details</h4>
                
                <b-table-simple stacked>
                    <b-tbody>
                        <b-tr>
                            <b-th stacked-heading="Request ID: ">
                                {{ request_id }}
                            </b-th>
                            <b-td stacked-heading="Reference #: ">
                                {{ reference }}
                            </b-td>
                            <b-th stacked-heading="Status: ">
                                <span class="text-danger" v-if="status == 'FLU'">FOR LOOK UP</span>
                            </b-th>
                            <b-td stacked-heading="Patient ID: ">
                                {{ patient_id }}
                            </b-td>
                            <b-td stacked-heading="Patient Name: ">
                                {{ firstname }} {{ middlename ? middlename : '' }} {{ lastname }} {{ name_suffix ? name_suffix : '' }}
                            </b-td>
                            <b-td stacked-heading="Blood Type: ">
                                {{ blood_type }}
                            </b-td>
                        </b-tr>
                    </b-tbody>
                </b-table-simple>
            </b-col>
            <b-col cols="8">
                
            </b-col>
        </b-row>
  </div>
</template>

<script>
export default {
    data(){
        return{
            data: '',

            request_id: '',
            reference: '',
            status: '',

            patient_id: '',

            firstname: '',
            middlename: '',
            lastname: '',
            name_suffix: '',

            blood_type: '',
        }
    },/* data */

    mounted(){
        this.getForLookUp()

    }, /* mounted */

    methods: {
        async getForLookUp(){
            await axios
            .get('/for-look-up/' + this.$route.params.id)
            .then(response => (
                // this.data = response.data

                this.request_id = response.data.request_id,
                this.reference = response.data.reference,
                this.status = response.data.status,

                this.patient_id = response.data.patient_id,

                this.firstname = response.data.firstname,
                this.middlename = response.data.middlename,
                this.lastname = response.data.lastname,
                this.name_suffix = response.data.name_suffix,

                this.blood_type = response.data.blood_type
            ))
        }
    }, /* methods */

    
}
</script>

<style>

</style>