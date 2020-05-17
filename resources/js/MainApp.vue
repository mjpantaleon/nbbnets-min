<template>
    <div>
        <TopNav />

        <div class="container mt-5">
            <!-- DISPLAY CONTENT HERE -->
            <router-view></router-view>
        </div>

        <Footer />        
    </div>
</template>

<script>
import TopNav from './components/MainApp/TopNav';
import Footer from './components/MainApp/Footer';

    export default {
        components: {TopNav, Footer},
        mounted(){
            console.log('Main App mounted');
        }
    }
</script>

<style scoped>

</style>