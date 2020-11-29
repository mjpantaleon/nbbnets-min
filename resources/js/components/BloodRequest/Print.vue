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
                        <b-icon icon="file-post" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Print Blood Issuance Form
                    </b-breadcrumb-item>
                </b-breadcrumb>
          </b-col>
        </b-row>

        <h4><b-icon icon="file-post"></b-icon> Print Blood Issuance Form</h4>
        <hr>
        <!-- {{units}} -->
        <b-row>
            <b-col>
                <div class="issuance-form" id="printableArea">
                    <table width="800" height="100" align="center">
                        <tr>
                            <td width="25%" align="center"><img src="/images/doh.png" width="90"></td>
                            <td align="center" width="50%"><br>
                            Republic of the Philippines
                            <br>Department of Health
                            <h3>PHILIPPINE BLOOD CENTER</h3>
                            <h4>BLOOD ISSUANCE FORM</h4>
                            </td>
                            <td width="25%"></td>
                        </tr>
                    </table>

                    <br><br>
                    <table width="800" align="center" border="0" cellpadding="2" cellspacing="0" style="text-align: bottom;">
                        <tr>
                            <td width="15%">Issued to: </td>
                            <td class="fill"> {{ hospital }}</td>
                            <td width="18%" class="subs">Date/Time Issued: </td>
                            <td class="fill">{{ created_dt }}</td>
                        </tr>
                        <tr>
                            <td>Type of Request:</td>
                            <td class="fill" colspan="3">CONVALESCENT PLASMA</td>
                        </tr>
                        <tr>
                            <td>Name of Patient:</td>
                            <td class="fill"> {{ firstname }} {{ middlename }} {{ lastname }} {{ name_suffix }}</td>
                            <td class="subs">Age/Gender </td>
                            <td class="fill"> {{ age }}/{{ gender }}</td>
                        </tr>

                        <tr>
                            <td>Blood Type</td>
                            <td class="fill">{{ blood_type }}</td>
                        </tr>
                    </table>

                    <table width="600" align="center" cellspacing="5" cellpadding="2" style="size: 11px !important">
                        <tr align="center">
                            <th>Serial Number</th>
                            <th>Date of Collection</th>
                            <th>Expiration Date</th>
                            <th>Blood Product</th>
                        </tr>

                        <tr align="center" v-for="(issued_unit, i) in issued_units" :key="i">
                            <template v-if="issued_unit.comp_stat == 'REL'">
                                <td>{{ issued_unit.donation_id }}</td>
                                <td>{{ issued_unit.collection_dt }}</td>
                                <td>{{ issued_unit.expiration_dt }}</td>
                                <td>{{ issued_unit.component_abbr }}</td>
                            </template>
                        </tr>
                    </table>
                    <br><br>

                    <table width="800" align="center" border="0">
                        <tr>
                            <td colspan="4">RESULT OF TEST DONE: 	<b>NON-REACTIVE to HBsAg, Syphilis, HIV, HCV, Malaria</b>
                            </td>	
                        </tr>
                        <tr>
                            <td class="fill">&nbsp;<br><br></td>
                            <td width="10%"></td>
                            <td width="15%" valign="bottom">Received by:</td>
                            <td class="fill" width="30%"></td>

                        </tr>		
                        <tr>
                            <td align="center" valign="top">Medical Technologist</td>
                            <td></td>
                            <td><br>Date & Time: </td>
                            <td class="fill"></td>
                        </tr>
                        <tr>
                            <td class="fill" align="center"><br>Pedrito Y. Tagayuna, FPSP</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>		
                        <tr>
                            <td align="center">Officer In Charge</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>

                    <table width="800" align="center" border="0">
                                <tr>
                            <td width="15%" style="border-top: 1px solid #000;"></td>
                            <td style="border-top: 1px solid #000; font-size:8pt;" colspan="2" align="center" valign="top">
                            <br>Lung Center Compound, Quezon Avenue, Quezon City 1104<br>
                            Direct Line: (02) 709-3703 | Fax: (02) 709-3792</td>
                            <td width="15%" align="right" style="border-top: 1px solid #000;"><img src="/images/pbc.jpg" width="80"></td>
                        </tr>
                    </table>

                </div>
            </b-col>
        </b-row>
       

        <b-row>
            <b-col>
                <b-button @click.prevent="printDiv()"
                    variant="primary">
                    <b-icon icon="file-post"></b-icon> PRINT ISSUANCE FORM
                </b-button>
            </b-col>
        </b-row>
    </div>
</template>

<script>
export default {
    name: 'issuance-form',

    data(){ 
        return{
            units: [],
            issued_units: '',

            hospital: '',
            created_dt: '',
            
            firstname: '',
            middlename: '',
            lastname: '',
            name_suffix: '',

            age: '',
            gender: '',
            blood_type: '',

            count: '',
            comp_name: '',

            donation_id: '',
            collection_dt: '',
            expiration_dt: '',
            details: '',
            
        }
    }, /* data */

    mounted() {
       this.dataForIssuance()
       this.issuedBloodUnits()
       this.getNow()
    }, /* mounted */

    methods: {
        // printDiv(){
        //     window.print()
        // },

        async dataForIssuance(){

            await axios
            .get('/data-for-issuance/' + this.$route.params.id)
            .then(response => (

                // this.units = response.data,

                this.hospital = response.data.physician_details.hospital,

                this.firstname = response.data.patient_details.firstname,
                this.middlename = response.data.patient_details.middlename,
                this.lastname = response.data.patient_details.lastname,
                this.name_suffix = response.data.patient_details.name_suffix,

                this.gender = response.data.patient_details.gender,
                this.age = response.data.patient_details.age,
                this.blood_type = response.data.patient_details.blood_type,

                this.comp_name = response.data.details.comp_name
            ))
        },

        async issuedBloodUnits(){
            await axios
            .get('/issued-blood-units/' + this.$route.params.id)
            .then(response => (
                this.issued_units = response.data
            ))
        },


        getNow: function() {
            const today = new Date();
            const date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
            const time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
            const dateTime = date +' '+ time;
            this.created_dt = dateTime;
        },

        printDiv() { 
            let contents = document.getElementById("printableArea").innerHTML;
            let frame1 = document.createElement('iframe');
            frame1.name = "frame1";
            frame1.style.position = "absolute";
            frame1.style.top = "-1000000px";
            document.body.appendChild(frame1);
            let frameDoc = frame1.contentWindow ? frame1.contentWindow : frame1.contentDocument.document ? frame1.contentDocument.document : frame1.contentDocument;
            frameDoc.document.open();
            frameDoc.document.write('<html lang="en"><head><title>Blood Issuance Form</title>');
            frameDoc.document.write('<link rel="stylesheet" type="text/css" href="/css/custom.css"/>');
            frameDoc.document.write('</head><body>');
            frameDoc.document.write(contents);
            frameDoc.document.write('</body></html>');
            frameDoc.document.close();
            setTimeout(function () {
                window.frames["frame1"].focus();
                window.frames["frame1"].print();
                document.body.removeChild(frame1);
            }, 500);
            return false;
        }
    }, /* methods */

    
}
</script>

<style scoped>
/* .issuance-form{
    padding: 48px;
    width: 595;
    height: 842;
    font-family: 'Times New Roman', Times, serif;
    size: 11px;
} */
/* body {
		font-size: 11pt;
}
.fill {
    font-weight: bold;
    text-transform: uppercase;
    border-bottom: 1px solid #000;
}
.subs {
    text-indent: 5px;
}
.text-centered {
    text-align: center;
}
h3 {
    margin-top: 5px;
    line-height: 1;
}
h4 {
    line-height: 1;
    margin-top: -15px;
} */
</style>