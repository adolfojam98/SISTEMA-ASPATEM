<template>
  <v-form v-model="valid">
    <v-container>
      
        
          <v-text-field
            v-model="nombre"
            :rules="nameRules"
            :counter="20"
            label="Nombre"
            required
          ></v-text-field>
        

       
          <v-text-field
            v-model="apellido"
            :rules="nameRules"
            :counter="20"
            label="Apellido"
            required
          ></v-text-field>
        

        
          <v-text-field
            v-model="email"
            :rules="emailRules"
            label="E-mail"
            required
          ></v-text-field> 

        
        <v-text-field
            v-model="telefono"
            label="telefono"
            required
        ></v-text-field> 


         <v-text-field
            v-model="puntos"
            label="puntos"
            required
          ></v-text-field> 

<v-btn depressed color="primary" @click.prevent="cargarUsuario">Enviar</v-btn>

    </v-container>
  </v-form>
</template>

<script>
  export default {
    data: () => ({
      valid: false,
      nombre: '',
      apellido: '',
      puntos:0,
      telefono:'',
      nameRules: [
        v => !!v || 'Nombre requerido',
        v => v.length <= 10 || 'El nombre debe ser menos a 20 caracteres',
      ],
      email: '',
      emailRules: [
        v => !!v || 'E-mail requerido',
        v => /.+@.+/.test(v) || 'E-mail no valido',
      ],
    }),
    methods:{
        cargarUsuario(){
          
          console.log(mensaje)
            axios.post('/usuario/guardar',{
           'nombre': this.nombre,
            'apellido': this.apellido,
            'mail': this.email,
            'puntos': this.puntos,
            'telefono': this.telefono,
            'socio': true 
          })
            .then(response => { 
	          console.log(response)
            })
            .catch(error => {
            console.log(error.response)
            });
        }
    }
  }
</script>

