<template>
  <div>
    <v-container :fluid="true">
      <v-card>
        <v-simple-table>
          <template v-slot:default>
            <thead>
              <tr>
                <th class="text-center">Categoria</th>
                <th class="text-center">Rango de puntos</th>
                <th class="text-center">Eliminar</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(categoria, indice) in arrayCategorias"
                :key="categoria.nombre"
              >
                <td>
                  <center>
                    {{ categoria.nombre }}
                  </center>
                </td>

                <td v-if="arrayCategorias[indice + 1] != null">
                  <center>
                    {{ categoria.puntosMinimo }}
                    -
                    {{ arrayCategorias[indice + 1].puntosMinimo - 1 }}
                  </center>
                </td>
                <td v-if="arrayCategorias[indice + 1] == null">
                  <center>{{ categoria.puntosMinimo }} - ∞</center>
                </td>
                <v-tooltip bottom>
                  <template v-slot:activator="{ on, attrs }">
                    <center>
                      <v-icon
                        v-bind="attrs"
                        v-on="on"
                        @click="[eliminarCategoria(indice)]"
                        color="error"
                        >mdi-delete</v-icon
                      >
                    </center>
                  </template>
                  <span>Eliminar</span>
                </v-tooltip>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
      </v-card>
    </v-container>
  </div>
</template>

<script>
import { mapState } from "vuex";
export default {
  data() {
    return {};
  },
  methods: {
    eliminarCategoria(indice) {
      this.arrayCategorias.splice(indice, 1);
    },
  },
  computed: {
    ...mapState("CrearTorneo", ["arrayCategorias"]),
  },
};
</script>
