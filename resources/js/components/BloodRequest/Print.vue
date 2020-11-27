<template>
    <div class="main-div">
        <!-- <b-row id="bb-crumb-sticky">
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
        </b-row> -->
      
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
            </table>

            <table width="700" align="center" cellspacing="20" cellpadding="2">
                <tr>
                    <td width="15%" align="right">Blood Type</td>
                    <td class="fill text-centered">{{ blood_type }}</td>
                    <td width="20%" align="right">Blood Product</td>
                    <td class="fill">count - comp_name</td>
                </tr>
            </table>

            <table width="600" align="center" cellspacing="5" cellpadding="2">
                <tr>
                    <th>Serial Number</th>
                    <th>Date of Collection</th>
                    <th>Expiration Date</th>
                </tr>

                <tr v-for="(detail, i) in details" :key="i">
                    <td>{{ detail.donation_id }}</td>
                    <td>{{ detail.collection_dt }}</td>
                    <td>{{ detail.expiration_dt  }} {{ detail.comp_stat  }}</td>
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

    </div>
</template>

<script>
export default {
    data(){
        return{
            units: [],

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

                // this.units = response.data
                this.hospital = response.data.physician_details.hospital,

                this.firstname = response.data.patient_details.firstname,
                this.middlename = response.data.patient_details.middlename,
                this.lastname = response.data.patient_details.lastname,
                this.name_suffix = response.data.patient_details.name_suffix,

                this.gender = response.data.patient_details.gender,
                this.age = response.data.patient_details.age,
                this.blood_type = response.data.patient_details.blood_type,

                this.details = response.data.details.blood_unit_dtls

                // this.firstname = response.data.firstname,
                // this.middlename = response.data.middlename,
                // this.lastname = response.data.lastname,
                // this.name_suffix = response.data.name_suffix,
                // this.age = response.data.age,
                // this.gender = response.data.gender,
                // this.blood_type = response.data.blood_type
                // console.log(response.data)
            ))
        },


        getNow: function() {
            const today = new Date();
            const date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
            const time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
            const dateTime = date +' '+ time;
            this.created_dt = dateTime;
        }
    }, /* methods */

    
}
</script>

<style scoped>
.issuance-form{
    padding: 48px;
    width: 595;
    height: 842;
    /* border: 1px solid #333; */
    font-family: 'Times New Roman', Times, serif;
    size: 14px;
}
body {
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
}
</style>