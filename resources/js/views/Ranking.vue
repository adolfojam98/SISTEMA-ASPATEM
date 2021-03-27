<template>
  <div>
<h1>
  {{count}}
  
  </h1>

 <v-btn @click="setCount">+</v-btn> 
  </div>
</template>


<script>
import { mapMutations, mapState } from 'vuex'
export default {
  computed: {
    ...mapState(['count'])
  
  },
  methods: {
    ...mapMutations(['setCount'])
  },
}

</script>