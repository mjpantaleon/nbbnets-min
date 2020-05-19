<template>
  <div class="main-div">
        <b-row>
            <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item :to="{ path: 'donation' }">
                        <b-icon icon="card-checklist" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Donation
                    </b-breadcrumb-item>
                    <b-breadcrumb-item :to="{ path: 'search-donor' }">
                        <b-icon icon="search" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Search Donor</b-breadcrumb-item>
                    <b-breadcrumb-item active>
                        <b-icon icon="person-lines-fill" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Donor Details</b-breadcrumb-item>
                    <!-- <b-breadcrumb-item href="#bar">Donor Details</b-breadcrumb-item> -->
                    <!-- <b-breadcrumb-item>Baz</b-breadcrumb-item> -->
                </b-breadcrumb>
            </b-col>
        </b-row>

        <h4><b-icon icon="person-lines-fill"></b-icon>&nbsp;Donor Details</h4>
        <hr>

        <b-row>
            <b-col md="6">
                <b-button-group>
                    <b-link class="btn btn-primary" :to="{ path: 'edit-donor-details' }">
                        <b-icon icon="pencil"></b-icon> UPDATE INFO</b-link>
                    <b-link class="btn btn-success" :to="{ path: 'new-walk-in' }">
                        <b-icon icon="person-plus"></b-icon> NEW WALK-IN</b-link>
                </b-button-group>
            </b-col>
        </b-row>

        <b-row>
            <b-col md="6" class="mt-3">
                <b-table striped
                    stacked
                    :fields="fields"
                    :items="items"
                    table-variant="light">

                    <template v-slot:cell(donationStatus)="data">
                        <b v-if="data.value == 'MAY DONATE'" class="text-success">{{ data.value }}</b>
                        <b v-else class="text-danger">{{ data.value }}</b>
                    </template>
                </b-table>
            </b-col>

            <b-col md="6" class="mt-3">
                <b-card no-body bg-variant="success" text-variant="white" header="DONATION HISTORY">
                    <b-table
                    :items="history"
                    striped
                    head-variant="info"
                    table-variant="light"></b-table>
                </b-card>
            </b-col>
        </b-row>
  </div>
</template>

<script>
export default {
    data() {
      return {
        fields: [
            'donorID',
            'donationStatus',
            'donorName',
            'donorSuffix',
            'birthDate',
            'civilStatu',
            'occupation',
            'nationality',
            'telephoneNo',
            'mobileNo',
            'email',
        ],
        items: [
          { donorID: '13006202000001', 
            donationStatus: 'MAY DONATE', 
            donorName: 'Juan dela Cruz' ,
            nameSuffix: '' ,
            gender: 'Male' ,
            birthDate: '1965-08-12' ,
            civilStatus: 'Married' ,
            occupation: '' ,
            nationality: 'Filipino' ,
            telephoneNo: '' ,
            mobileNo: '' ,
            email: 'juan.dela.cruz@yahoo.com' },
        ],

        history: [
            { Facility: 'Davao Blood Center', Date: '2020-01-07', Remarks: '' },
            { Facility: 'Philippine Blood Center', Date: '2019-08-11', Remarks: '' },
        ],
      }
    }
}
</script>

<style>

</style>