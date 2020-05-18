<template>
  <div class="main-div">
        
        <b-row>
            <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item :to="{ path: 'donation' }">
                        <b-icon icon="card-checklist" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Donation
                    </b-breadcrumb-item>
                    <b-breadcrumb-item href="#foo" active>
                        <b-icon icon="search" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Search Donor</b-breadcrumb-item>
                    <!-- <b-breadcrumb-item :to="{ path: 'donor-details' }">
                        <b-icon icon="person-lines-fill" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Donor Details</b-breadcrumb-item> -->
                    <!-- <b-breadcrumb-item href="#bar">Donor Details</b-breadcrumb-item> -->
                    <!-- <b-breadcrumb-item>Baz</b-breadcrumb-item> -->
                </b-breadcrumb>
            </b-col>
        </b-row>

        <h4><b-icon icon="search"></b-icon> Search Donor</h4>
        <hr>
        
        <b-row>
            <b-col cols="4">
                <b-form-group
                    id="fieldset-horizontal"
                    label-cols-sm="4"
                    label-cols-lg="4"
                    description="Last name"
                    label="Donor name"
                    label-for="input-horizontal">
                <b-form-input id="input-horizontal"></b-form-input>
                </b-form-group>
            </b-col>

            <b-col cols="3">
                <b-form-group
                    id="fieldset-horizontal"
                    description="First name"
                    label-for="input-horizontal">
                <b-form-input id="input-horizontal"></b-form-input>
                </b-form-group>
            </b-col>

            <b-col cols="3">
                <b-form-group
                    id="fieldset-horizontal"
                    description="Middle name"
                    label-for="input-horizontal">
                <b-form-input id="input-horizontal"></b-form-input>
                </b-form-group>
            </b-col>

            <b-col cols="2" class="ml-auto">
                <b-button
                    variant="warning">
                    <b-icon icon="search"></b-icon>&nbsp;SEARCH
                </b-button>
            </b-col>
        </b-row>

        <b-row>
          <b-col>
            <!-- <b-table class="mt-5"
                :fields="fields"
                :items="items"
                responsive="sm"
                striped
                head-variant="dark"
                table-variant="light">

                <template v-slot:cell(index)="data">
                    {{ data.index + 1 }}
                </template>

                <template v-slot:cell(Name)="data">
                    <b class="text-info">{{ data.value.last.toUpperCase() }}</b>
                </template>

                <template v-slot:cell(nameage)="data">
                    {{ data.item.name.first }} is {{ data.item.age }} years old
                </template>  
            
            </b-table> -->

            <b-table class="mt-3"
                :fields="fields"
                :items="items"
                responsive="sm"
                striped
                head-variant="dark"
                table-variant="light">

                <template v-slot:cell(status)="data">
                    <b v-if="data.value == 'MAY DONATE'" class="text-success">{{ data.value }}</b>
                    <b v-else class="text-danger">{{ data.value }}</b>
                </template>

                <template v-slot:cell(action)>
                    <b-link class="btn btn-info btn-sm" :to="{ path: 'donor-details' }">
                        <b-icon icon="search"></b-icon>
                    </b-link>

                    <b-link class="btn btn-success btn-sm" :to="{ path: 'edit-donor-details' }">
                        <b-icon icon="pencil"></b-icon>
                    </b-link>
                </template>     

            </b-table>
          </b-col>
      </b-row>
  </div>
</template>

<script>
export default {
    data(){
        return{
            fields: [
                'donorID',
                'status',
                'name',
                'gender',
                'birthDate',
                'region',
                'province',
                'city',
                'brgy',
                'address',
                'action',
            ],
            items: [
                {   donorID: '13006202000001',
                    status: 'MAY DONATE',
                    name: 'Cruz, Juan dela',
                    gender: 'Male',
                    birthDate: '1965-08-12',
                    region: ' 03',
                    province: 'Bulacan',
                    city: 'San Jose del Monte',
                    brgy: 'Poblacion 1',
                    address: 'Pleasant Ville',
                },
                {   donorID: '13006202000003',
                    status: 'CANNOT DONATE',
                    name: 'Enrile, Once Ponye',
                    gender: 'Male',
                    birthDate: '1949-02-28',
                    region: 'NCR',
                    province: 'Metro Manila',
                    city: 'Manila',
                    brgy: 'Rizal Ave.',
                    address: 'Enrile Residence',
                },
            ]   
        }
    }
}
</script>

<style>

</style>