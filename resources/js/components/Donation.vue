<template>
  <div class="main-div">

      <b-row>
          <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item href="#home" active>
                        <b-icon icon="card-checklist" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Donation
                    </b-breadcrumb-item>
                    <!-- <b-breadcrumb-item href="#foo">Select Donor</b-breadcrumb-item>
                    <b-breadcrumb-item href="#bar">Donor Details</b-breadcrumb-item>
                    <b-breadcrumb-item>Baz</b-breadcrumb-item> -->
                </b-breadcrumb>
          </b-col>
      </b-row>

      <h4><b-icon icon="card-checklist"></b-icon> Donation</h4>
      <hr>

      <b-row>
            <b-col md="6">
                <b-link class="btn btn-success" :to="{ path: 'search-donor' }">
                    <b-icon icon="person-plus"></b-icon>&nbsp;NEW WALK-IN
                </b-link>
            </b-col>

            <b-col md="auto" class="ml-auto">
                <b-form-datepicker v-model="donation_dt"></b-form-datepicker>
            </b-col>
      </b-row>

      <b-row>
          <b-col>
            <b-table class="mt-5"
                sticky-header
                :items="items"
                striped
                head-variant="light"
                table-variant="light"></b-table>
          </b-col>
      </b-row>

  </div>
</template>

<script>
export default {
    data(){
        return{
            donation_dt: '',
            items: [
                {   Donor: 'Cruz, Juan dela', 
                    TypeOfDonor: 'Voluntary', 
                    MHPE: 'Accepted', 
                    Method: 'Apheresis', 
                    Collection: 'Successful', 
                    DonationID: 'NVBSP20209000001' },
                {   Donor: 'Enrille, Juan Ponce', 
                    TypeOfDonor: 'Voluntary', 
                    MHPE: 'Accepted', 
                    Method: 'Apheresis', 
                    Collection: 'Successful', 
                    DonationID: 'NVBSP20209000002' },
                {   Donor: 'Saturnino, Jon Jon S.', 
                    TypeOfDonor: 'Voluntary', 
                    MHPE: 'Accepted', 
                    Method: 'Apheresis', 
                    Collection: 'Successful', 
                    DonationID: 'NVBSP20209000005' },
            ]
        }
    }
}
</script>

<style>

</style>