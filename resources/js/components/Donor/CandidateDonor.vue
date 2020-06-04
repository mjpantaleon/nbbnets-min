<template>
  <div class="main-div">
        <b-row id="bb-crumb-sticky">
          <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item active>
                        <b-icon icon="person-lines-fill" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Candidate Donors
                    </b-breadcrumb-item>
                    <!-- <b-breadcrumb-item href="#home" active>
                        <b-icon icon="person-plus" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Register New Donor
                    </b-breadcrumb-item> -->
                </b-breadcrumb>
          </b-col>
        </b-row>

        <h4><b-icon icon="person-lines-fill"></b-icon> Candidate Donors</h4>
        <hr>

        <b-row>
            <b-col>
                <b-table id="main-table" class="mt-5" head-variant="light" table-variant="light"
                    :fields="fields"
                    :per-page="perPage"
                    :current-page="currentPage"
                    striped>

                </b-table>

                <!-- pagination -->
                <b-pagination
                    v-model="currentPage"
                    :total-rows="rows"
                    :per-page="perPage"
                    aria-controls="main-table">
                </b-pagination>
            </b-col>
        </b-row>
  </div>
</template>

<script>
export default {
    data(){
        return{
            fields: [
                { key: 'fullname', label: 'Fullname' },
                { key: 'gender', label: 'Gender' },
                { key: 'bdate', label: 'Birthday' },
                { key: 'dateScreened', label: 'Date Screened' },
                { key: 'address', label: 'Address' },
                { key: 'status', label: 'Status' },
                { key: 'action', label: 'Action' },
            ],
            // pagination
            perPage: 10,
            currentPage: 1,
        }
    }, /* data */

    computed: {
        // pagination
        rows() {
            // return this.data.length
        },
    }, /* computed */
}
</script>

<style>

</style>