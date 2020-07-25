<template>
    <v-card> 
        <h1> <center>Gestionar relaciones del usuario: {{usuario.nombre}} {{usuario.apellido}}</center></h1>


        
            
        

            <div max-width="125px">

                <v-autocomplete 
                v-model="relacionadoCon"
                :items="usuarios"
                item-text="nombre"
                dense
                filled
                label="Relacionado con"
                ></v-autocomplete>
                
            </div>

    
      
            <div max-width="150px">
                <v-autocomplete
                v-model="relacion"
                :items="tipos"
                label="Tipo de relacion"
                dense
                filled
                ></v-autocomplete>
            </div>
            
            <v-btn depressed color="primary" @click.prevent="falta">Agregar relacion</v-btn>
        

    </v-card>
</template>








<script>
export default {

  props : ["usuario"],

  data() { 
    return {
      relacion: null,
      relacionadoCon: [],
      usuarios : [],
      tipos: ['Amistad', 'Familiar', 'Referido'],
        }
    },


    methods:{
            posibles(){
            axios.get(`/usuario/${this.usuario.id}/relaciones`).then(res => {
            this.usuarios = res.data;
            })
        }
    },


   mounted(){
       
      this.posibles();
   },

   
   updated(){

      this.posibles();
   }

       
}


</script>