<template>
    <div>
        <v-card>
            <v-form v-model="valid" ref="form" lazy-validation>
                <v-col>
                    <v-select
                    v-model="mes"
                    :items="meses"
                    filled
                    label="Mes"
                    ></v-select>

                    <v-select
                    v-model="anio"
                    :items="anios"
                    filled
                    label="Año"
                    ></v-select>

                    <v-btn
                        depressed
                        color="primary"
                        :disabled="!valid"
                        @click.prevent="generarCuota"
                        >Generer cuota
                    </v-btn>
                </v-col>
            </v-form>
        </v-card>
    </div>
</template>


<script>
  export default {

      props : ["usuarioID"],

    data() { 
    return {
        valid: false,
        mes:null,
        anio:new Date().getFullYear(),
        meses:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
        anios: [new Date().getFullYear(),new Date().getFullYear()-1],
        }
    },

    methods:{

       generarCuota(){
           axios.post('/generarCuota',{
               mes : this.mes,
               anio : this.anio,
               usuario_id : this.usuarioID,
           })
           .then((res)=>{
               console.log(res.data.message);
           })

           
       }
       
       
    },






  }
</script>