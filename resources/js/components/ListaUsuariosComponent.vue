<template>
  <menu>
    <li
    v-for="usuario in usuarios" :key="usuario.mail"
    >

    </li>
  </menu>
</template>


<script>
  export default {
    created(){
      axios.get('/usuario/mostrar').then
      (response => this.usuarios = response.data);
    },
    data() {
      return {
      usuarios:[],
      }
    },
    methods:{
      
    }
  }
</script>