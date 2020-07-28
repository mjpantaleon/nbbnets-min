<template>
  <div class="main-div">
        <b-row id="bb-crumb-sticky">
          <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item active>
                        <b-icon icon="file-text" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Aliquote
                    </b-breadcrumb-item>
                    <!-- <b-breadcrumb-item href="#home" active>
                        <b-icon icon="person-plus" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Register New Donor
                    </b-breadcrumb-item> -->
                </b-breadcrumb>
          </b-col>
        </b-row>

        <h4><b-icon icon="file-text"></b-icon> Aliquote</h4>
        <hr>

        <b-row>
            <b-col col xl="6" lg="6" md="12" sm="12" xs="12">

                <b-card 
                    body-class="sticker-preview"
                    header-bg-variant="danger"
                    header-text-variant="white">

                    <template v-slot:header>

                        <b-container>
                            <b-row>
                                <b-col cols="3">
                                    <h6>Blood Unit</h6>
                                </b-col>
                                <b-col cols="5">
                                </b-col>
                                <b-col cols="4" class="text-right">
                                    <router-link to="/available-stocks" class="btn btn-secondary btn-sm">
                                        <b-icon icon="arrow-left"></b-icon> Back to List
                                    </router-link>
                                </b-col>
                            </b-row>
                        </b-container>

                    </template>

                    <b-card-body class="text-center" style="padding: 0;">
                        <iframe id="preview" :src="uri" width="400" height="270" frameborder="0"></iframe>
                    </b-card-body>

                </b-card>

            </b-col>

            <b-col col xl="6" lg="6" md="12" sm="12" xs="12">

                <b-card 
                    body-class="sticker-preview"
                    header-bg-variant="success"
                    header-text-variant="white">

                    <template v-slot:header>
                        <h6>ALIQOUTED UNITS</h6>
                    </template>

                    <b-card-body class="text-center" style="padding: 0;">
                            <b-table
                                id="main-table"
                                responsive="sm"
                                striped hover
                                head-variant="light"
                                table-variant="light"
                                :fields="fields"
                                :items="data"
                                :per-page="perPage"
                                :current-page="currentPage">

                                <template v-slot:cell(bloodtype)="data">
                                    {{ data.item.blood_type }}
                                </template>

                                 <template v-slot:cell(action)="data">
                                    <!-- {{ data.item.expiration_dt }} -->
                                    <!-- <b-button variant="success" @click="proceed()" size="sm">
                                        <b-icon icon="search"></b-icon>
                                    </b-button> -->
                                    <router-link :to="'/unit/'+data.item.donation_id+'/'+data.item.component_cd" title="View Blood Unit Details">
                                        <b-icon icon="search" class="border border-success p-1" variant="success" font-scale="2.1"></b-icon>
                                    </router-link>
                                </template>
                            </b-table>

                            <b-input-group
                                class="mb-3">
                                <b-form-input type="number"></b-form-input>
                                <b-input-group-append>
                                    <b-button>NEW ALIQUOTE</b-button>
                                </b-input-group-append>
                            </b-input-group>


                    </b-card-body>

                </b-card>

            </b-col>
        </b-row>
  </div>
</template>

<script>
export default {
    data(){
        return{
            fields: [
                { key: 'volume', label: 'VOLUME' },
                { key: 'aliquotedBy', label: 'ALIQOUTED BY' },
                { key: 'dateCreated', label: '	DATE CREATED' },
                { key: 'status', label: 'STATUS' },
                { key: 'action', label: '' },
            ],
            data: [],
            isLoading: false,

            uri: '',

            // pagination
            perPage: 10,
            currentPage: 1,
        }
    }, /* data */

    mounted(){

        if(this.$route.params.mtd == 'P'){
            this.uri = '/preview?data='+this.$route.params.id+","+this.$route.params.mtd
        } else{
            this.uri = '/preview?data='+this.$route.params.comp+"-"+this.$route.params.id+","+this.$route.params.mtd
        }

        
    }, /* mounted */

    methods: {
        // getCandidates(){
        //     this.isLoading = true

        //     axios
        //     .get('/')
        //     .then(response => (

        //     ))
        //     .catch(error => console.log(error))
        // },

    }, /* methods */

    computed: {
        // pagination
        rows() {

        },
    }, /* computed */
}
</script>

<style>

</style>