<template>
    <v-card> 
        <h1> <center>Gestionar relaciones del usuario: {{usuario.nombre}} {{usuario.apellido}}</center></h1>


        
            
        

            <div max-width="125px">
                <v-autocomplete 
                v-model="relacionadoCon"
                :items="usuarios"
                :item-text= "nombreCompleto"
                item-value="id"
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
            
            <v-btn depressed color="primary" @click.prevent="agregarRelacion()">Agregar relacion</v-btn>

            <v-snackbar
            v-model="snackbarAgregadocorrectamente"
            timeout="3000"
            >
            Relacion agregada corectamente

            <template v-slot:action="{ attrs }">
                <v-btn
                color="blue"
                text
                v-bind="attrs"
                @click="snackbarAgregadocorrectamente = false"
                >
                Cerrar
                </v-btn>
            </template>
            </v-snackbar>

            <v-snackbar
            v-model="snackbarRelacionExistente"
            color = "error"
            timeout="3000"
            top
            >
            Esa relacion ya existe

            <template v-slot:action="{ attrs }">
                <v-btn
                
                text
                v-bind="attrs"
                @click="snackbarRelacionExistente = false"
                >
                Cerrar
                </v-btn>
            </template>
            </v-snackbar>
        
    <relaciones-usuario-lista :usuario = "usuario" ></relaciones-usuario-lista>
    </v-card>
</template>








<script>
export default {

  props : ["usuario"],

  data() { 
    return {
      relacion: null,
      relacionadoCon: null,
      usuarios : [],
      tipos: ['Amistad', 'Familiar', 'Referido'],
      snackbarAgregadocorrectamente : false,
      snackbarRelacionExistente : false,
      exist : Boolean,
        }
    },


    methods:{
       nombreCompleto: item => item.apellido + ' ' + item.nombre,


            posibles(){
            axios.get(`/usuario/${this.usuario.id}/relacionables`).then(res => {
            this.usuarios = res.data;
            })
        },



           async agregarRelacion(){
                
                const existeRelacion = await axios.get('/usuario/relacion/existe',{
                    params : {
                            id_socio_A: this.usuario.id,
                            id_socio_B: this.relacionadoCon,
                        }
                })
                
    
                    console.log(existeRelacion);
    
                    if(!existeRelacion.data){
                        const guardar = await axios.post('/usuario/relacion',{
                            "id_socio_A" :this.usuario.id,
                            "id_socio_B" :this.relacionadoCon,
                            "relacion" :this.relacion,
                        })
                        this.snackbarAgregadocorrectamente = true;
    
    }                   
                    
                        else {
                            this.snackbarRelacionExistente = true
                        }
                }
                  
            },
            watch: {
                usuario : function(){
                    this.posibles();
                }
            },

            


   mounted(){
       
      this.posibles();
   },


       
}


</script>