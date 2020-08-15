<template>
<div>

    <v-simple-table>
        <template v-slot:default>
            <thead>
                <tr v-if='existenRelaciones'>
                    <th class="text-left">Usuario</th>
                    <th class="text-left">Tipo de Relacion</th>
                    <th class="text-left">Eliminar</th>
                </tr>
            </thead>
            <tbody>
            
            
                <tr v-for="relacion in relaciones" :key="relacion.id">
                    <td>
                        {{ relacion.usuario.nombre }} , {{ relacion.usuario.apellido }}
                    </td>
                    <td>{{ relacion.relacion }}</td>
                    <td>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon
                                    v-bind="attrs"
                                    v-on="on"
                                    right
                                    @click="[confirmarEliminarModal = true, relacionAEliminar = relacion]"
                                    color="error">
                                    mdi-delete
                                    </v-icon>
                            </template>
                            <span>Eliminar</span>
                        </v-tooltip>
                    </td>
                </tr>
            </tbody>
        </template>
    </v-simple-table>

    <v-dialog v-model="confirmarEliminarModal" max-width="320">
      <v-card>
        <v-card-title class="headline">Desea borrar la relacion?</v-card-title>
        <v-card-text>Esta acción se puede revertir volviendo a cargar la relación. ¿Desea continuar?.</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" outlined @click="[confirmarEliminarModal = false]">CANCELAR</v-btn>
          <v-btn color="error" @click="[eliminarRelacion(),confirmarEliminarModal = false]">BORRAR</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
</div>

</template>

<script>
export default {
    props: ["usuario", "nuevaRelacion"],

    data() {
        return {
            relaciones: [],
            relacionAEliminar : [],
            confirmarEliminarModal : false
        };
    },
    methods: {
        relacionesExistentes() {
            this.relaciones = []
            axios.get(`/usuario/${this.usuario.id}/relaciones`).then(res => {
                this.relaciones = res.data;
            });
        },

        eliminarRelacion() {
            
            axios.delete(`/usuario/relacion/${this.relacionAEliminar.id}`).then(res => {
                this.relaciones = this.relaciones.filter(
                    relacion => relacion.id != res.data.id
                );
            });
        }
    },
    watch: {
        usuario: function() {
            this.relacionesExistentes();
        },
        nuevaRelacion: function() {
            this.relaciones.push(this.nuevaRelacion);
        }
    },
    computed: {
        existenRelaciones(){
            return this.relaciones.length !== 0
        }
    },

    created() {
        this.relacionesExistentes();
    }
};
</script>
