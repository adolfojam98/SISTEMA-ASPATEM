<template>
<div>
  <v-card> 
    <h1> <center>Modificar usuario</center></h1>

  <v-form v-model="valid" ref="form" lazy-validation>
    <v-container>
      <v-text-field v-model="usuario.nombre" :rules="nombreRules" :counter="20" label="Nombre" required></v-text-field>

      <v-text-field v-model="usuario.apellido" :rules="apellidoRules" :counter="20" label="Apellido" required></v-text-field>

      <v-text-field v-model="usuario.dni" :rules="dniRules" label="DNI" required></v-text-field>

      <v-text-field v-model="usuario.mail" :rules="emailRules" label="E-mail" required></v-text-field>

      <v-text-field v-model="usuario.telefono" label="Telefono" :rules="telefonoRules" required></v-text-field>

      <v-layout justify-center align-center>
      <v-switch center="true" v-model="usuario.socio" label="Socio" class="mx-4"></v-switch>
      </v-layout>
      
      <v-btn block large depressed color="primary" :disabled="!valid" @click.prevent="updateUsuario">Guardar cambios</v-btn>
    </v-container>
  </v-form>

  </v-card>





</div>
</template>











<script>
import { mapActions } from 'vuex';
export default {

  props : ["usuario"],

  data() { 
    return {
        
        valid: false,

    nombreRules: [
      v => !!v || "Nombre requerido",
      v => (v && v.length <= 20)  || "El nombre debe ser menor a 20 caracteres"
    ],

    apellidoRules :[
      v => !!v || "Apellido requerido",
      v => (v && v.length <= 20)  || "El apellido debe ser menor a 20 caracteres"
    ],

    telefonoRules : [
      v => !!v || "Telefono requerido",
    ],
    
    dni: "",
        dniRules:[
            v => !!v || "Importe requerido",
            v => v >= 10000000 || "El DNI debe tener 8 caracteres",
            v => v < 100000000 || "El DNI debe tener 8 caracteres"
        ],

        email: "",
        emailRules: [
            v => !!v || "E-mail requerido",
            v => /.+@.+[.].+/.test(v) || "E-mail no valido"
        ],
   
    }
  },

  methods: {
    ...mapActions(['callSnackbar']),
    
 
    updateUsuario(){
      if (this.valid) {
        axios.put('/usuario',{
          'id': this.usuario.id,
          'nombre':this.usuario.nombre,
          'apellido':this.usuario.apellido,
          'mail':this.usuario.mail,
          'telefono':this.usuario.telefono,
          'socio':this.usuario.socio,
          'dni':this.usuario.dni,
        })
        .then(response => {
           this.callSnackbar(['Usuario modificado corectamente'])
           this.$emit("reFiltrar");
          })
        .catch(function (error) {
                    console.log(error);
                });
      }
    },

  }

}

</script>