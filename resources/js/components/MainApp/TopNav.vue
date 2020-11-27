<template>
    <b-navbar toggleable="lg" sticky style="background-color: #ff9000; color: #eafffe; !important ">
        <b-navbar-brand class="lead text-light">
            <img src="img/favicon.ico" alt="">
            <b>NBBNetS - Convalescent Plasma</b>
        </b-navbar-brand>

        <b-navbar-toggle target="nav-collapse" v-if="showMenu"></b-navbar-toggle>

        <b-collapse id="nav-collapse" is-nav v-if="showMenu">
        <b-navbar-nav v-if="showMenu">
            <b-nav-item-dropdown right v-if="uLevel === -1 || uLevel === 1 || uLevel === 3">
                <template v-slot:button-content>
                    PRE-SCREENING
                </template>
                <b-dropdown-item :to="{ path: '/pre-screened-list' }">Pre-Screened List</b-dropdown-item>
                <b-dropdown-item :to="{ path: '/pre-screening' }">Pre-Screening</b-dropdown-item>
                <b-dropdown-divider></b-dropdown-divider>

                <b-dropdown-item :to="{ path: '/pre-screening-igg' }">IGG</b-dropdown-item>
                <b-dropdown-item :to="{ path: '/hla-hna-test' }">HLA & HNA</b-dropdown-item>
            </b-nav-item-dropdown>

            <b-nav-item-dropdown right v-if="uLevel === -1 || uLevel === 1 || uLevel === 7">
                <template v-slot:button-content>
                    BLOOD TESTING
                </template>

                <b-dropdown-item :to="{ path: '/blood-test-tti' }">TTI</b-dropdown-item>
                <b-dropdown-item :to="{ path: '/blood-typing' }">Blood Typing</b-dropdown-item>
                <b-dropdown-divider></b-dropdown-divider>

                <b-dropdown-item :to="{ path: '/blood-test-nat' }">NAT</b-dropdown-item>
                <b-dropdown-item :to="{ path: '/blood-test-zika' }">ZIKA</b-dropdown-item>
            </b-nav-item-dropdown>

            <b-nav-item-dropdown right v-if="uLevel === -1 || uLevel === 1 || uLevel === 3">
                <template v-slot:button-content>
                    DONOR & DONATION
                </template>
                <b-dropdown-item :to="{ path: '/donation' }">New Walk-in</b-dropdown-item>
            </b-nav-item-dropdown>


            <b-nav-item-dropdown right v-if="uLevel === -1 || uLevel === 1 || uLevel === 4">
                <template v-slot:button-content>
                    BLOOD UNIT
                </template>
                
                <b-dropdown-item :to="{ path: '/blood-processing' }">Blood Processing</b-dropdown-item>
                <b-dropdown-item :to="{ path: '/labelling' }">Blood Label</b-dropdown-item>
                <!-- <b-dropdown-item :to="{ path: '/aliquote' }">Aliquote</b-dropdown-item> -->
                
            </b-nav-item-dropdown>

            <b-nav-item-dropdown right v-if="uLevel === -1 || uLevel === 1 || uLevel === 4">
                <template v-slot:button-content>
                    BLOOD STOCKS
                </template>
                <b-dropdown-item :to="{ path: '/release-to-inventory' }">Release to Inventory</b-dropdown-item>
                <b-dropdown-item :to="{ path: '/available-stocks' }">Available Blood Stocks</b-dropdown-item>
                <!-- <b-dropdown-item :to="{ path: '/aliquote' }">Aliquote</b-dropdown-item> -->
                
            </b-nav-item-dropdown>

            <b-nav-item-dropdown right v-if="uLevel === -1 || uLevel === 1 || uLevel === 4">
                <template v-slot:button-content>
                    BLOOD REQUEST & ISSUANCE
                </template>
                <b-dropdown-item :to="{ path: '/blood-request/list' }">Blood Request List</b-dropdown-item>
                <!-- <b-dropdown-item :to="{ path: '/aliquote' }">Aliquote</b-dropdown-item> -->
                
            </b-nav-item-dropdown>
            
            <!-- 
            <b-nav-item-dropdown right>
                <template v-slot:button-content>
                    BLOOD STOCKS
                </template>
                <b-dropdown-item :to="{ path: '/release-to-inventory' }">Release to Inventory</b-dropdown-item>
                <b-dropdown-item :to="{ path: '/available-stocks' }">Available Blood Stocks</b-dropdown-item>
                <b-dropdown-item :to="{ path: '' }"></b-dropdown-item>
            </b-nav-item-dropdown>

            <b-nav-item-dropdown right>
                <template v-slot:button-content>
                    REPORTS
                </template>
                <b-dropdown-item :to="{ path: '/agency-report' }">Agency Report</b-dropdown-item>
                <b-dropdown-item :to="{ path: '/printable-forms' }">Printable Forms</b-dropdown-item>
                <b-dropdown-item :to="{ path: '' }"></b-dropdown-item>
            </b-nav-item-dropdown>

            <b-nav-item-dropdown right>
                <template v-slot:button-content>
                    ADMINISTRATION
                </template>
                <b-dropdown-item :to="{ path: '/manage-user' }">Manage Users</b-dropdown-item>
                <b-dropdown-item :to="{ path: '/blood-bag-template' }">Blood Bag Template</b-dropdown-item>
                <b-dropdown-item :to="{ path: '' }"></b-dropdown-item>
            </b-nav-item-dropdown> -->
        </b-navbar-nav>

        <!-- Right aligned nav items -->
        <b-navbar-nav class="ml-auto" v-if="showMenu">
            <!-- <b-nav-item :to="{ path:'about' }">About</b-nav-item> -->

            <b-nav-item-dropdown right>
            <!-- Using 'button-content' slot -->
            <template v-slot:button-content>
                <b-icon icon="person-bounding-box"></b-icon>&nbsp;&nbsp;<em>{{ fullname }}</em>
            </template>
            <!-- <b-dropdown-item :to="{ path: '' }">Change Password</b-dropdown-itemsssss> -->
            <b-dropdown-item @click.prevent="logout()">Logout</b-dropdown-item>
            </b-nav-item-dropdown>
        </b-navbar-nav>
        </b-collapse>
    </b-navbar>
</template>

<script>
export default {
    data(){
        return{
            // user: '',
            showMenu: false,
            fullname: '',
            uLevel: ''
        }
    }, /* data */

    computed: {
        isUserLogged: {
            get(){
                return this.$store.state.isLogged
            },
            set(value){
                this.value = this.$store.state.isLogged
            }
        }
    }, /* computed */

    mounted(){
        this.getUser()
    },

    methods: {

        async getUser(){

             await axios
                .get('/get-user')
                .then(response => {

                    if(response.data.status){
                        this.fullname = response.data.name,
                        this.uLevel = response.data.ulevel,
                        this.showMenu = true
                    } else{
                        console.log(response.data.error)
                    }

                })

        },

        logout(){

            axios
                .get('/logout')
                .then(response => {
                    if(response.data.status){
                        this.$store.state.isLogged = false
                        this.showMenu = false
                        this.$router.push('/')
                    } else{
                        this.showError = true
                        this.errorMessage = response.data.error
                    }
                })
        }
    },

    watch:{
        isUserLogged: function(val){
            this.getUser()
            // this.$forceUpdate()
        }
    }

}
</script>