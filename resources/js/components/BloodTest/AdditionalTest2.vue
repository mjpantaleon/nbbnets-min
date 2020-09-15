<template>
  <div class="main-div">
        <b-row id="bb-crumb-sticky">
          <b-col>
                <b-breadcrumb>
                    <b-breadcrumb-item active>
                        <b-icon icon="droplet-half" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Blood Testing
                    </b-breadcrumb-item>
                    <b-breadcrumb-item active>
                        <b-icon icon="droplet-half" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
                        Additional Test 2
                    </b-breadcrumb-item>
                </b-breadcrumb>
          </b-col>
        </b-row>

        <h4><b-icon icon="droplet-half"></b-icon> Nat/ Zika</h4>
        <hr>

         <b-row>
          <b-col>
            <table class="table table-striped table-bordered">
              <thead>
                <th>Donation ID</th>
                <th>NAT</th>
                <th>ZIKA</th>
                <th>&nbsp;</th>
              </thead>
              <tbody>
                <tr v-for="(input, i) in inputs" :key="i">
                  <td>
                    <b-form-input v-model="input.donation_id" placeholder="Scan Donation ID"></b-form-input>
                  </td>

                  <td>
                    <b-form-select v-model="input.NAT" :options="nat_options"></b-form-select>
                  </td>

                  <td>
                    <b-form-select v-model="input.ZIKA" :options="zika_options"></b-form-select>
                  </td>

                  <td>
                    <b-avatar variant="danger"
                        icon="trash-fill"
                        button
                        @click="remove(i)" v-show="i || ( !i && inputs.length > 1)">
                        </b-avatar>

                    <template v-if="i || ( !i && inputs.length > 1)">&nbsp;|&nbsp;</template>

                    <b-avatar variant="primary"
                        button
                        icon="plus-circle-fill"
                        @click="add(i)" v-show="i == inputs.length-1">
                        </b-avatar>
                  </td>
                </tr>
              </tbody>
            </table>
          </b-col>
        </b-row>

        <b-row>
          <b-col md="3">
              <b-button variant="success"
                  block
                  :disabled="disableBtn"
                  @click.prevent="showModal">
                  <b-icon icon="check-circle-fill"></b-icon>&nbsp;SUBMIT TEST RESULT
              </b-button>
          </b-col>
        </b-row>
  </div>
</template>

<script>
export default {
  data(){
    return{
      inputs: [
        {
          donation_id: '',
          NAT: '',
          ZIKA: '',
        }
      ],

      nat_options: [
        { text: 'N', value: 'n' },
        { text: 'R', value: 'r' }
      ],

      zika_options: [
        { text: 'N', value: 'n' },
        { text: 'R', value: 'r' }
      ]
    }
  }, /* data() */

   methods: {
    add () {
      this.inputs.push({
          donation_id: '',
          NAT: '',
          ZIKA: '',
      })
      console.log(this.inputs)
    },

    remove (index) {
      this.inputs.splice(index, 1)
    },
   }
}
</script>

<style>

</style>