<template>
  <div class="main-div">
      <h4><b-icon icon="card-checklist"></b-icon> Donation</h4>
      <hr>

      <b-row>
            <b-col md="6">
                <b-button
                    variant="success">
                    <b-icon icon="person-plus"></b-icon>&nbsp;NEW WALK-IN
                </b-button>
            </b-col>

            <b-col md="auto">
                <b-calendar v-model="value" @context="onContext" locale="en-US"></b-calendar>
            </b-col>
            <!-- <b-col>
                <p>Value: <b>'{{ value }}'</b></p>
                <p class="mb-0">Context:</p>
                <pre class="small">{{ context }}</pre>
            </b-col> -->
      </b-row>

  </div>
</template>

<script>
export default {
    data() {
      return {
        value: '',
        context: null
      }
    },
    methods: {
      onContext(ctx) {
        this.context = ctx
      }
    }
}
</script>

<style>

</style>