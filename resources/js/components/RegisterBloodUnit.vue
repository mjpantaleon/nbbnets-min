<template>
  <div class="main-div">
       <!-- BREAD-CRUMBS -->
        <b-row>
            <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item active="">
                        <b-icon icon="droplet-half" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Register Blood Unit
                    </b-breadcrumb-item>
                    <!-- <b-breadcrumb-item :to="{ path: 'search-donor' }">
                        <b-icon icon="search" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Search Donor</b-breadcrumb-item>
                    <b-breadcrumb-item :to="{ path: 'donor-details' }">
                        <b-icon icon="person-lines-fill" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Donor Details</b-breadcrumb-item>
                    <b-breadcrumb-item active>
                        <b-icon icon="person-plus"></b-icon> New Walk-in Donation</b-breadcrumb-item> -->
                    <!-- <b-breadcrumb-item>Baz</b-breadcrumb-item> -->
                </b-breadcrumb>
            </b-col>
        </b-row>

        <h4><b-icon icon="droplet-half"></b-icon>&nbsp;Register Blood Unit</h4>
        <hr>

        <b-row>
            <b-col md="6">
                <table class="table table-striped bg-light">
                    <tr>
                        <td>
                            <b-form-group
                                id="fieldset-horizontal"
                                label-cols-sm="4"
                                label-cols-lg="3"
                                description="Scan Donation ID"
                                label="Donation ID"
                                label-for="donation_id">
                            <b-form-input v-model="donation_id" id="donation_id"></b-form-input>
                            </b-form-group>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b-form-group
                                id="fieldset-horizontal"
                                label-cols-sm="4"
                                label-cols-lg="3"
                                description="Select blood bag"
                                label="Blood Bag"
                                label-for="blood_bag">
                                <b-form-radio-group
                                    v-model="selected"
                                    :options="options"
                                    name="blood_bag">
                                </b-form-radio-group>
                            </b-form-group>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b-button block variant="success">REGISTER UNIT</b-button>
                        </td>
                    </tr>
                </table>
                
            </b-col>
        </b-row>
  </div>
</template>

<script>
export default {
    data() {
      return {
        selected: 'S',
        options: [
          { value: 'S', text: 'Single' },
          { value: 'D', text: 'Double' },
          { value: 'T', text: 'Triple' },
          { value: 'Q', text: 'Quadruple' },
        ],

        fields: [
            'index',
            'donationID',
            'bloodBag',
            'action'
        ],
      }
    }
}
</script>

<style>

</style>