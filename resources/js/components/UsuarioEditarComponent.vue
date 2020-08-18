<template>
<div>
  <v-card> 
    <h1> <center>Modificar usuario</center></h1>

  <v-form v-model="valid" ref="form" lazy-validation>
    <v-container>
      <v-text-field v-model="usuario.nombre" :rules="nombreRules" :counter="20" label="Nombre" required></v-text-field>

      <v-text-field v-model="usuario.apellido" :rules="apellidoRules" :counter="20" label="Apellido" required></v-text-field>

      <v-text-field v-model="usuario.mail" :rules="emailRules" label="E-mail" required></v-text-field>

      <v-text-field v-model="usuario.telefono" label="telefono" :rules="telefonoRules" required></v-text-field>

      
      <v-btn depressed color="primary" :disabled="!valid" @click.prevent="updateUsuario">Guardar cambios</v-btn>
    </v-container>
  </v-form>

  </v-card>



     <v-snackbar
      v-model="snackbar"
      timeout="3000"
    >
      Usuario modificado corectamente

      <template v-slot:action="{ attrs }">
        <v-btn
          color="blue"
          text
          v-bind="attrs"
          @click="snackbar = false"
        >
          Cerrar
        </v-btn>
      </template>
    </v-snackbar>


</div>
</template>











<script>
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
    
    emailRules: [
      v => !!v || "E-mail requerido",
      v => /.+@.+/.test(v) || "E-mail no valido"
    ],
    snackbar : false
    }
  },

  methods: {
    
 
    updateUsuario(){
      if (this.valid) {
        axios.put('/usuario',{
          'id': this.usuario.id,
          'nombre':this.usuario.nombre,
          'apellido':this.usuario.apellido,
          'mail':this.usuario.mail,
          'telefono':this.usuario.telefono,
          'socio':this.usuario.socio,
        })
        .then(response => {
           this.snackbar = true
          })
        .catch(function (error) {
                    console.log(error);
                });
      }
    },

  }

}

</script>