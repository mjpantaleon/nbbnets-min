<template>
  <div>
    <footer class="footer">

        <div class="container">
            <div class="row">
                <div class="col-md-4 mt-3">
                    <h6 class="lead">
                        <b-icon icon="info-square"></b-icon>&nbsp;About</h6>
                    <p>
                        In the spirit of "Bayanihan Act to Heal as One" the initiative of NVBSP together 
                        with Philippine Blood Center to cater Covid-19 survivor's yet another act of Heroism
                        by donating their Convalescent Plasma to help Covid-19 patients fight and 
                        conquer the disease.
                    </p>
                </div>

                <div class="col-md-2 mt-3">
                    &nbsp;
                </div>

                <div class="col-md-3 mt-3">
                    <h6 class="lead">
                        <b-icon icon="chat-quote"></b-icon>&nbsp;Contact Us</h6>
                    <p>
                        If you want to be part of "Bayanihan Act to Heal as One" do not hesitate to contact 
                        us any time. You can email or call us at:
                    <ul>
                        <li>support@nbbnets.net</li>
                        <li>(02) 8995 - 3846 loc. 214</li>
                    </ul>
                    </p>
                </div>
                <div class="col-md-3 mt-3">
                    <h6 class="lead">
                        <b-icon icon="link"></b-icon>&nbsp;Helpful Links</h6>
                    <p>
                        Other websites maintained by NVBSP-IMU
                        <ul>
                            <li><a id="links" href="#">NVBSP</a></li>
                            <li><a id="links" href="#">NBBNETS</a></li>
                            <li><a id="links" href="#">BSI</a></li>
                        </ul>
                    </p>
                </div>
            </div>
            
            <hr class="line">
        </div>

        <!-- LOGOS -->
        <b-row class="row justify-content-center align-items-center">
            <b-row>
                <b-col class="mt-3">
                    <img src="img/doh-logo.png" width="50px" alt="" srcset="">
                    <img src="img/nvbsp-logo.png" width="50px" alt="" srcset="">
                    <img src="img/imu-logo.png" width="60px" alt="" srcset="">
                </b-col>
            </b-row> 
        </b-row>

        <b-row>
            <b-col>
                <p class="text-center mt-3 mb-3">
                    NBBNetS - Convalescent Plasma, developed by NVBSP - Information Management Unit<br>
                    Copyright &copy 2020 - All Rights Reserved 
                </p>
            </b-col>
        </b-row>
    </footer>
  </div>
</template>

<script>
export default {

}
</script>

<style scoped>
.footer {
    background: #231816;
    color: #fffddb;
}
.line {
    border: 1px solid #fffddd;
}
#links {
    text-decoration: none;
    color: #fffddb;
}
</style>
