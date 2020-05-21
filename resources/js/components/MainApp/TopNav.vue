<template>
    <b-navbar toggleable="lg" type="dark" variant="dark" sticky>
        <b-navbar-brand :to="{ path: '/' }" class="lead">
            <img src="img/favicon.ico" alt="">
            NBBNetS - Convalescent Plasma
        </b-navbar-brand>

        <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

        <b-collapse id="nav-collapse" is-nav>
        <b-navbar-nav>
            <b-nav-item-dropdown right>
                <template v-slot:button-content>
                    DONATION
                </template>
                <b-dropdown-item :to="{ path: 'donation' }">New Walk-in</b-dropdown-item>
                <!-- <b-dropdown-item :to="{ path: 'register-new-donor' }">Register New Donor</b-dropdown-item> -->
            </b-nav-item-dropdown>

            <b-nav-item-dropdown right>
                <!-- Using 'button-content' slot -->
                <template v-slot:button-content>
                    BLOOD UNIT
                </template>
                <b-dropdown-item :to="{ path: 'register-blood' }">Register Blood Unit</b-dropdown-item>
                <b-dropdown-item :to="{ path: 'blood-typing' }">Blood Typing</b-dropdown-item>
                <b-dropdown-item :to="{ path: 'blood-processing' }">Blood Processing</b-dropdown-item>
                <b-dropdown-item :to="{ path: 'blood-testing' }">Blood Testing</b-dropdown-item>
                <b-dropdown-item :to="{ path: 'labeling' }">Labeling</b-dropdown-item>
            </b-nav-item-dropdown>

            <b-nav-item-dropdown right>
                <!-- Using 'button-content' slot -->
                <template v-slot:button-content>
                    BLOOD STOCKS
                </template>
                <b-dropdown-item :to="{ path: 'release-to-inventory' }">Release to Inventory</b-dropdown-item>
                <b-dropdown-item :to="{ path: 'available-stocks' }">Available Blood Stocks</b-dropdown-item>
                <b-dropdown-item :to="{ path: '' }"></b-dropdown-item>
            </b-nav-item-dropdown>

            <b-nav-item-dropdown right>
                <!-- Using 'button-content' slot -->
                <template v-slot:button-content>
                    REPORTS
                </template>
                <b-dropdown-item :to="{ path: 'agency-report' }">Agency Report</b-dropdown-item>
                <b-dropdown-item :to="{ path: 'printable-forms' }">Printable Forms</b-dropdown-item>
                <b-dropdown-item :to="{ path: '' }"></b-dropdown-item>
            </b-nav-item-dropdown>

            <b-nav-item-dropdown right>
                <!-- Using 'button-content' slot -->
                <template v-slot:button-content>
                    ADMINISTRATION
                </template>
                <b-dropdown-item :to="{ path: 'manage-user' }">Manage Users</b-dropdown-item>
                <b-dropdown-item :to="{ path: 'blood-bag-template' }">Blood Bag Template</b-dropdown-item>
                <b-dropdown-item :to="{ path: '' }"></b-dropdown-item>
            </b-nav-item-dropdown>
        </b-navbar-nav>

        <!-- Right aligned nav items -->
        <b-navbar-nav class="ml-auto">
            <!-- <b-nav-item :to="{ path:'about' }">About</b-nav-item> -->

            <b-nav-item-dropdown right>
            <!-- Using 'button-content' slot -->
            <template v-slot:button-content>
                <b-icon icon="person-bounding-box"></b-icon>&nbsp;&nbsp;<em>User</em>
            </template>
            <b-dropdown-item :to="{ path: '' }">Change Password</b-dropdown-item>
            <b-dropdown-item :to="{ path: '' }">Logout</b-dropdown-item>
            </b-nav-item-dropdown>
        </b-navbar-nav>
        </b-collapse>
    </b-navbar>
</template>