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
                        Blood Request Details
                    </b-breadcrumb-item>
                </b-breadcrumb>
          </b-col>
        </b-row>

        <h4><b-icon icon="file-text" variant="primary"></b-icon> Blood Request Details</h4>
        <hr>
        
        <b-row>
            <b-col cols="4">
                <!-- <h4 class="text-secondary"><b-icon icon="clipboard"></b-icon> Blood Request Details</h4> -->
                
                <b-table-simple stacked>
                    <b-tbody>
                        <b-tr>
                            <b-td stacked-heading="Request ID: ">
                                {{ request_id }}
                            </b-td>
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
                                <h1 class="text-success">{{ blood_type }}</h1>
                            </b-td>
                        </b-tr>
                    </b-tbody>
                </b-table-simple>
            </b-col>
            <!-- {{selected_blood_type}} -->
            <!-- AVAILABLE BLOOD UNITS BASED ON -->
            <b-col cols="8">
                <!-- BLOOD TYPE SELECTION -->
                <b-row>
                    <b-col cols="6">
                        <b-form-group
                            id="fieldset-horizontal"
                            label-cols-sm="4"
                            label-cols-lg="4"
                            description="Select civil status"
                            label="Select Blood Type"
                            label-for="blood_types">
                            <b-form-select v-model="selected_blood_type" 
                                :options="blood_types" id="blood_types"></b-form-select>
                        </b-form-group>
                    </b-col>

                    <b-col>
                        <b-button block
                            variant="warning"
                            @click.prevent="getAvailableCpUnits()">
                            <b-icon icon="check-circle"></b-icon>&nbsp;SEARCH AVAILABLE UNITS
                        </b-button>
                    </b-col>
                </b-row>

                <b-row>
                    
                    <b-table striped bordered
                        :items="components"
                        :fields="table_fields"
                        :per-page="perPage">
                            <template v-slot:cell(selection)="data">
                                <b-form-checkbox
                                id="checkbox"
                                v-model="data.item.selected_item"
                                name="checkbox"></b-form-checkbox>
                            </template>

                            <template v-slot:cell(donation_id)="data">
                                {{ data.item.donation_id }}
                            </template>

                            <template v-slot:cell(blood_type)="data">
                                {{ data.item.blood_type }}
                            </template>

                            <template v-slot:cell(status)="data">
                                <span class="text-success" v-if="data.item.comp_stat == 'AVA'">Available</span>
                            </template>
                    </b-table>
                </b-row>

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

            selected_blood_type: '',
            blood_types: [
                { value: 'A Pos', text: 'A Pos' },
                { value: 'A Neg', text: 'A Neg' },
                { value: 'B Pos', text: 'B Pos' },
                { value: 'B Neg', text: 'B Neg' },
                { value: 'AB Pos', text: 'AB Pos' },
                { value: 'AB Neg', text: 'AB Neg' },
                { value: 'O Pos', text: 'O Pos' },
                { value: 'O Neg', text: 'O Neg' },
            ],

            selected: '',

            components: [],

            table_fields: [
                { key: 'selection', label: 'Selection' },
                { key: 'donation_id', label: 'Donation ID' },
                { key: 'blood_type', label: 'Blood Type' },
                { key: 'status', label: 'Status' },
            ],

            // pagination
            perPage: 10,
            currentPage: 1,
        }
    },/* data */

    mounted(){
        this.getForLookUp()
        // this.getAvailableCpUnits()

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

                this.firstname = response.data.patient_min.firstname,
                this.middlename = response.data.patient_min.middlename,
                this.lastname = response.data.patient_min.lastname,
                this.name_suffix = response.data.patient_min.name_suffix,

                this.blood_type = response.data.patient_min.blood_type
            ))
        },

        async getAvailableCpUnits(){
            await axios
            .post('/available-cp-components', {
                selected_blood_type: this.selected_blood_type
            })
            .then(response => (
                this.components = response.data
            ))
        }
    }, /* methods */

    computed: {
        // pagination
        rows() {
            return this.components.length
        },
    }

    
}
</script>

<style>

</style>