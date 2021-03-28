<template>
  <div>
<h1>
  {{count}}
  
  </h1>

 <v-btn @click="sumar">+</v-btn> 
 <v-btn @click="restar">-</v-btn> 
  </div>
</template>


<script>
import { mapMutations, mapState } from 'vuex'
export default {
  computed: {
    ...mapState(['count'])
  
  },
  methods: {
    ...mapMutations(['sumar','restar'])
  },
}

</script>