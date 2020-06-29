<template>

        <b-modal id="preview-label" class="modal">

            <template v-slot:default>

                <loadingInline label="Pleae wait.." v-if="loading"></loadingInline>
                <div v-if="!loading" >
                    <div v-html="html" style="width:375px; height:250px; margin-left:auto; margin-right:auto;">
                    </div>
                </div>

            </template>

        </b-modal>

</template>

<script>

export default {
  props : ['data'],
  data(){
      return {
          loading : false, 
          html : '<p>Hello</p>',
      }
  },
  mounted(){

    this.$root.$on('bv::modal::show', (bvEvent, modalId) => {
        if(modalId == 'preview-label'){
            let w = window.open('https://www.google.com/','winname','directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=375,height=270');
            w.onload = () => {
                console.log(w)
                    // w.print();
                w.close();
            };
        }
    })


    // console.log(this.data)

    // $("#preview-label").modal("show");
    // let {facility_cd,preview : {donation_id,component_cd}} = this;
    // let url =  'http://'+window.location.host+window.location.pathname+'label?facility_cd='+facility_cd+'&donation_id='+donation_id+'&component_cd='+component_cd;
    // let that = this;
    // axios.get(url)
    // .then(({data}) => {
    //     that.html = data;
    //     that.loading = false;
    // })
    // let w = window.open('https://www.google.com/','winname','directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=375,height=270');
    // w.onload = () => {
    //         w.print();
    //     // w.close();
    // };
  }
}
</script>