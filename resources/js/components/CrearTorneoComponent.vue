  <template>
  <v-stepper
    v-model="e6"
    vertical
  >
    <v-stepper-step
      :complete="e6 > 1"
      editable
      step="1"
    >
      Nombre y categorias (aqui tambien van los puntos para pertenecer a cada categoria y si la fase de grupos es eliminatoria o no)
    </v-stepper-step>

    <v-stepper-content step="1">
      <v-card
        color=#E0F7FA
        class="mb-12"
        height="300px"
      >
      
      <v-row>
        <v-col
        cols="12"
        md="5">
          <v-form :v-model="valid" lazy-validation >
                <v-container>
                    <v-text-field
                        v-model="nombreTorneo"
                        class="subheading font-weight-bold"
                        label="Nombre del Torneo"
                        required
                    ></v-text-field>
                </v-container>
          </v-form>

          <v-spacer></v-spacer>
          <v-btn
          v-if="!nuevaCategoria"
          dark
          color=#009688
          height="25%"
          width="70%"
          v-model="nuevaCategoria"
          @click="[(nuevaCategoria=!nuevaCategoria)]"
          >
          <v-icon>mdi-plus</v-icon>
          Nueva categoria
          </v-btn>

          <div v-if="nuevaCategoria">
            <v-form :v-model="valid" lazy-validation>
                <v-container>
                    <v-text-field
                        v-model="nombreNuevaCategoria"
                        class="subheading font-weight-bold"
                        label="Nombre de la Categoria"
                        required
                    ></v-text-field>
                    <v-text-field
                        v-model="puntosMinimos"
                        class="subheading font-weight-bold"
                        label="Puntos minimos de la Categoria"
                        required
                    ></v-text-field>

                </v-container>
                <v-btn
                dark
                color=#009688
                height="25%"
                width="40%"
                v-model="nuevaCategoria"
                @click="[(nuevaCategoria=!nuevaCategoria,agregarCategoria())]"
                >Agregar</v-btn>

                <v-btn
                  dark
                color=#009688
                height="25%"
                width="40%"
                v-model="nuevaCategoria"
                @click="[(nuevaCategoria=!nuevaCategoria,puntosMinimos=null,nombreNuevaCategoria='')]"
                >Cancelar</v-btn>
          </v-form>
          </div>
          

        </v-col>
      
        <v-col>

          <v-simple-table dark>
            
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-center">
                    Categoria
                  </th>
                  <th class="text-center">
                    Rango de puntos
                  </th>
                  <th class="text-center">
                    Acciones
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(categoria,indice) in arrayCategorias"
                  :key="categoria.nombre"
                >
                

                  <td><center>{{ categoria.nombre }}</center></td>
                  
                  <td v-if="arrayCategorias[indice+1]!=null"><center>{{categoria.puntosMinimo}} - {{ arrayCategorias[indice+1].puntosMinimo-1 }}</center></td>
                  <td v-if="arrayCategorias[indice+1]==null"><center>{{categoria.puntosMinimo}} - ∞ </center></td>
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                      
                      <center><v-icon
                        v-bind="attrs"
                        v-on="on"
                        @click="[eliminarCategoria(indice)]"
                        color="error"
                      >mdi-delete</v-icon></center>
                    </template>
                    <span>Eliminar</span>
                  </v-tooltip>
                  
                </tr>
              </tbody>
            </template>
          </v-simple-table>
        
          
          

          
          


        </v-col>
      </v-row>
      
      
      
      </v-card>
      <v-btn
        color="primary"
        @click="e6 = 2"
      >
        Continue
      </v-btn>
    </v-stepper-content>

    <v-stepper-step
      :complete="e6 > 2"
      editable
      step="2"
    >
      Importar lista de jugadores (se debera poder exportar los jugadores y sus puntos al finalizarlo)
    </v-stepper-step>

    <v-stepper-content step="2">
      <v-card
        color="grey lighten-1"
        class="mb-12"
        height="200px"
      ></v-card>
      <v-btn
        color="primary"
        @click="e6 = 3"
      >
        Continue
      </v-btn>
    </v-stepper-content>

    <v-stepper-step
      :complete="e6 > 3"
      editable
      step="3"
    >
      Configuracion de categorias (agregar jugadores a cada categoria con sus puntos)
    </v-stepper-step>

    <v-stepper-content step="3">
      <v-card
        color="grey lighten-1"
        class="mb-12"
        height="200px"
      ></v-card>
      <v-btn
        color="primary"
        @click="e6 = 4"
      >
        Continue
      </v-btn>
    </v-stepper-content>

    <v-stepper-step step="4"
    editable
    >
      Configuracion de puntos (puntos que se ganan segun el rank de los jugadores)
    </v-stepper-step>
    <v-stepper-content step="4">
      <v-card
        color="grey lighten-1"
        class="mb-12"
        height="200px"
      ></v-card>
      <v-btn
        color="primary"
        @click="e6 = e6"
      >
        Guardar
      </v-btn>
    </v-stepper-content>
  </v-stepper>
</template>

<!--como wea hacer para ver el progreso de un socio en los torneos si son independientes (relacionamos los terneos?)
podria ser sino una nueva fecha de un torneo anterior entonces aqui aplicamos una relacion-->



<script>
export default {

data: () => ({
    e6: 1,
    valid: false,
    nombreTorneo:"",
    nuevaCategoria: false,
    arrayCategorias:[],
    nombreNuevaCategoria:"",
    puntosMinimos:null,
      
    }),

    methods: {
      agregarCategoria(){
        var categoria = {nombre:this.nombreNuevaCategoria,puntosMinimo:this.puntosMinimos}
        this.arrayCategorias.push(categoria);
        this.nombreNuevaCategoria = "";
        this.puntosMinimos = "";

      },

      eliminarCategoria(indice){
        this.arrayCategorias.splice(indice,1);
      }
      
    },
}
</script>

<style>

</style>