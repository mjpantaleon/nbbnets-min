<template>
    <div class="main-div">
        <b-row id="bb-crumb-sticky">
          <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item active>
                        <b-icon icon="file-text" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Blood Request & Issuance
                    </b-breadcrumb-item>

                    <b-breadcrumb-item :to="{ path: '/blood-request/list' }">
                        <b-icon icon="file-text" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Blood Request List
                    </b-breadcrumb-item>

                    <b-breadcrumb-item active>
                        Print Blood Issuance Form
                    </b-breadcrumb-item>
                </b-breadcrumb>
          </b-col>
        </b-row>
      
        <div class="issuance-form" id="printableArea">
            <center>    
            <div class="row">
                <div class="col"><img src="\images\doh.png" alt=""></div>
                <div class="col">
                    <div style="size: 10px">Republic of the Philippines</div>
                    <div style="size: 10px">Department of Health</div>
                    <div style="size: 14px"><b>PHILIPPINE BLOOD CENTER</b></div>
                    <div style="size: 12px"><b>BLOOD ISSUANCE FORM</b></div>
                </div>
                <div class="col">
                    &nbsp;
                </div>
            </div>
            </center>

            <div class="mt-5">
                <div class="row">
                    <div class="col-lg">Issued to: <u><b>ST LUKES MEDICAN CENTER - GLOBAL CITY</b></u></div>
                    <div class="col-sm">Date/Time Issued: <u><b>11-26-2020 02:50 PM</b></u></div>
                </div>
                <div class="row">
                    <div class="col">Type of Request: <u><b>CONVALESCENT PLASMA</b></u></div>
                    <div class="col">&nbsp;</div>
                </div>
                <div class="row">
                    <div class="col">Name of Patient: <u><b>PORIFICACION BENEDICTO COMBANTRINO IV</b></u></div>
                    <div class="col">Age/Gender: <u>0/M</u></div>
                </div>
            </div>
        </div>


        <b-row class="mt-5">
            <b-col md="4">
                <b-button
                    variant="secondary"
                    @click="printDiv()">
                    <b-icon icon="file-post p1"></b-icon>&nbsp;PRINT
                </b-button>
            </b-col>
        </b-row>

    </div>
</template>

<script>
export default {
    methods: {
        printDiv(){
            window.print()
        }
    }
}
</script>

<style scoped>
.issuance-form{
    padding: 48px;
    width: 595;
    height: 842;
    border: 1px solid #333;
    font-family: 'Times New Roman', Times, serif;
    size: 14px;
}
</style>