<template>
  <div>
<h1>
  {{count}}
  
  </h1>

 <v-btn @click="sumar">+</v-btn> 
 <v-btn @click="restar">-</v-btn> 
 <v-btn @click="callSnackBar(['Se llama','blue'])">-</v-btn> 
  </div>
</template>


<script>
import { mapActions, mapMutations, mapState } from 'vuex'
export default {
  computed: {
    ...mapState(['count'])
  
  },
  methods: {
    ...mapMutations(['sumar','restar']),
    ...mapActions(['callSnackBar']),
    
  },
}

</script>