<template>
  <div class="main-d">
      <b-card no-body>
        <b-tabs card active-nav-item-class="font-weight-bold text-danger">
            <!-- =============== MEDICAL HISTORY TAB ================= -->
            <b-tab title="Medical History" active>
            {{ questions }}
                <b-card-text>
                  <!-- STATIC -->
                  <table class="table table-bordered table-striped">
                   <th class="bg-secondary text-light">Are you</th>
                   <tbody>
                    <tr>
                      <td>Feeling healthy today?
                        <b-form-checkbox class="float-right" v-model="questions" value="1"></b-form-checkbox></td>
                    </tr>
                    <tr>
                      <td>Currently taking medication? Have you taken any medication from the deferral list?
                        <b-form-checkbox class="float-right" v-model="questions" value="2"></b-form-checkbox></td>
                    </tr>
                    <tr>
                      <td>Have you received any vaccination?
                        <b-form-checkbox class="float-right" v-model="questions" value="3"></b-form-checkbox></td>
                    </tr>
                   </tbody>
                 </table>

                 <table class="table table-bordered table-striped">
                   <th class="bg-secondary text-light">In the past three days</th>
                   <tbody>
                    <tr>
                      <td>Have you taken aspirin or anything that has aspirin in it?
                        <b-form-checkbox class="float-right" v-model="questions" value="4"></b-form-checkbox></td>
                    </tr>
                   </tbody>
                 </table>

                 <table class="table table-bordered table-striped">
                   <th class="bg-secondary text-light">QUESTION No. 5 FOR FEMALE DONORS: In the past 1 and 1/2 months (6 weeks)</th>
                   <tbody>
                   <tr>
                     <td>Have you been pregnant or are you pregnant now? Last Menstrual Period:
                       <b-form-checkbox class="float-right" v-model="questions" value="5"></b-form-checkbox></td>
                   </tr>
                   </tbody>
                 </table>

                 <table class="table table-bordered table-striped">
                    <th class="bg-secondary text-light">
                      In the past 12 weeks have you</th>
                    <tbody>
                      <tr>
                        <td>Donated blood,platelet or plasma?
                          <b-form-checkbox class="float-right" v-model="questions" value="6"></b-form-checkbox></td>
                      </tr>
                   </tbody>
                 </table>

                 <table class="table table-bordered table-striped">
                   <th class="bg-secondary text-light">
                     In the past 12 months have you</th>
                    <tbody>
                      <tr>
                        <td>Had a blood transfusion?
                          <b-form-checkbox class="float-right" v-model="questions" value="7"></b-form-checkbox></td>
                      </tr>

                      <tr>
                        <td>Had surgical operation, dental extraction?
                          <b-form-checkbox class="float-right" v-model="questions" value="8"></b-form-checkbox></td>
                      </tr>

                      <tr>
                        <td>Had tattoo, ear or body piercing, accidental contact with blood, needle-stick, and Acupuncture?
                          <b-form-checkbox class="float-right" v-model="questions" value="9"></b-form-checkbox></td>
                      </tr>

                      <tr>
                        <td>Had sexual contact with high risk individuals?
                          <b-form-checkbox class="float-right" v-model="questions" value="10"></b-form-checkbox></td>
                      </tr>

                      <tr>
                        <td>Had sexual contact either anyone in exchange for material or monetary gain?
                          <b-form-checkbox class="float-right" v-model="questions" value="11"></b-form-checkbox></td>
                      </tr>

                      <tr>
                        <td>Had sexual contact with a person who has worked abroad?
                          <b-form-checkbox class="float-right" v-model="questions" value="12"></b-form-checkbox></td>
                      </tr>
                      
                      <tr>
                        <td>Engaged in casual sex?
                          <b-form-checkbox class="float-right" v-model="questions" value="13"></b-form-checkbox></td>
                      </tr>

                      <tr>
                        <td>Lived with a person who has hepatitis?
                          <b-form-checkbox class="float-right" v-model="questions" value="14"></b-form-checkbox></td>
                      </tr>

                      <tr>
                        <td>Have you been imprisoned?
                          <b-form-checkbox class="float-right" v-model="questions" value="15"></b-form-checkbox></td>
                      </tr>

                      <tr>
                        <td>Have any of your relatives had Creutzfeldt-Jakob (Mad Cow)disease?
                          <b-form-checkbox class="float-right" v-model="questions" value="16"></b-form-checkbox></td>
                      </tr>
                    </tbody>
                 </table>

                 <table class="table table-bordered table-striped">
                   <th class="bg-secondary text-light">
                      Have you ever</th>
                    <tbody>
                      <tr>
                        <td>Lived outside your place of residence?
                          <b-form-checkbox class="float-right" v-model="questions" value="17"></b-form-checkbox></td>
                      </tr>
                      <tr>
                        <td>Lived outside the Philippines?
                          <b-form-checkbox class="float-right" v-model="questions" value="18"></b-form-checkbox></td>
                      </tr>
                      <tr>
                        <td>Used needles to take drugs,steriods,or anything not prescribed by your doctor?
                          <b-form-checkbox class="float-right" v-model="questions" value="19"></b-form-checkbox></td>
                      </tr>
                      <tr>
                        <td>Used clotting factor concentrates?
                          <b-form-checkbox class="float-right" v-model="questions" value="20"></b-form-checkbox></td>
                      </tr>
                      <tr>
                        <td>Had a positve test for the HIV/AIDS virus,Hepatitis virus,Syphilis or Malaria?
                          <b-form-checkbox class="float-right" v-model="questions" value="21"></b-form-checkbox></td>
                      </tr>
                      <tr>
                        <td>Had Hepatitis?
                          <b-form-checkbox class="float-right" v-model="questions" value="22"></b-form-checkbox></td>
                      </tr>
                      <tr>
                        <td>Had Malaria?
                          <b-form-checkbox class="float-right" v-model="questions" value="23"></b-form-checkbox></td>
                      </tr>
                      <tr>
                        <td>Been told to have or treated for genital wart,syphilis,gonorrhea or other Sexually Transmissible Infections?
                          <b-form-checkbox class="float-right" v-model="questions" value="24"></b-form-checkbox></td>
                      </tr>
                      <tr>
                        <td>Had any type of cancer, for example Leukemia?
                          <b-form-checkbox class="float-right" v-model="questions" value="25"></b-form-checkbox></td>
                      </tr>
                      <tr>
                        <td>Had any problems with your heart and lungs?
                          <b-form-checkbox class="float-right" v-model="questions" value="26"></b-form-checkbox></td>
                      </tr>
                      <tr>
                        <td>Had a bleeding condition or blood disease?
                          <b-form-checkbox class="float-right" v-model="questions" value="27"></b-form-checkbox></td>
                      </tr>
                      <tr>
                        <td>Are you giving blood because you wanted to be tested HIV or Hepatitis virus?
                          <b-form-checkbox class="float-right" v-model="questions" value="28"></b-form-checkbox></td>
                      </tr>
                      <tr>
                        <td>Are you aware that if you have AIDS/Hepatitis virus, you can give it to someone else though you may feel and have a negative HIV/Hepatitis test?
                          <b-form-checkbox class="float-right" v-model="questions" value="29"></b-form-checkbox></td>
                      </tr>
                    </tbody>
                 </table>

                </b-card-text>
            </b-tab>
            <!-- =============== MEDICAL HISTORY TAB ================= -->



            <!-- =============== PYSICAL EXAM TAB ================= -->
            <b-tab title="Physical Exam">
                <b-card-text>
                  {{pe_selected}}
                  <!-- DYNAMIC -->
                 <!-- <table class="table table-bordered" v-for="(pe_question, i) in pe_questions" :key="i">
                    <th class="bg-info" colspan="2">{{ pe_question.header_text }}</th>
                     <tr>
                       <td>
                         {{ pe_question.q_text }}
                         <b-form-checkbox class="float-right" 
                            v-model="mh_selected" :value="pe_question.q_text"></b-form-checkbox>
                       </td>
                     </tr>
                 </table> -->
                 <!-- <table class="table table-bordered" v-for="(mh_question, i) in mh_questions" :key="i">
                    <th class="bg-info" colspan="2">{{ mh_question.header_text }}</th>
                     <tr>
                       <td>
                         {{ mh_question.q_text }}
                         <b-form-checkbox class="float-right" 
                            v-model="mh_selected" :value="mh_question.q_no"></b-form-checkbox>
                       </td>
                     </tr>
                 </table> -->

                 <table class="table table-bordered table-striped">
                    <th class="bg-secondary text-light">
                      Hemoglobin</th>
                    <tbody>
                      <tr>
                        <td>Low Hemoglobin
                          <b-form-radio class="float-right" v-model="pe_selected.Hemoglobin" value="Low Hemoglobin"></b-form-radio></td>
                      </tr>
                      <tr>
                        <td>High Hemoglobin
                          <b-form-radio class="float-right" v-model="pe_selected.Hemoglobin" value="High Hemoglobin"></b-form-radio></td>
                      </tr>
                   </tbody>
                 </table>

                 <table class="table table-bordered table-striped">
                    <th class="bg-secondary text-light">
                      Body Weight</th>
                    <tbody>
                      <tr>
                        <td>Low Body Weight
                          <b-form-radio class="float-right" v-model="pe_selected.body_weight"  value="Low Hemoglobin"></b-form-radio></td>
                      </tr>
                      <tr>
                        <td>High Body Weight
                          <b-form-radio class="float-right" v-model="pe_selected.body_weight" value="High Hemoglobin"></b-form-radio></td>
                      </tr>
                   </tbody>
                 </table>

                </b-card-text>
            </b-tab>
            <!-- =============== PYSICAL EXAM TAB ================= -->
        </b-tabs>
    </b-card>
  </div>
</template>

<script>
export default {
  data(){
    return{
      // static fields
      questions: [],
      
      // PE
      hemoglobin: '',
      body_weight: '',

      // dynamic fields
      mh_questions: [],
      mh_selected: [],

      pe_questions: [],
      pe_selected: [
        { hemoglobin: '' },
        { body_weight: '' },
      ],
    }
  }, /* data */

  mounted() {
    this.getMHQuestions();
    this.getPEQuestions();
  }, /* mounted */

  methods:{
    getMHQuestions(){
      axios
      .get('/mh-question-list')
      .then(response => (
        this.mh_questions = response.data
      ))
      .catch(error => console.log(error))
    },

    getPEQuestions(){
      axios
      .get('/pe-question-list')
      .then(response => (
        this.pe_questions = response.data
      ))
      .catch(error => console.log(error))
    }
    
  }, /* methods */
}
</script>

<style>

</style>