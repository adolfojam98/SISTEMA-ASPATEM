<template>
    <v-simple-table>
        <template v-slot:default>
            <thead>
                <tr>
                    <th class="text-left">Usuario</th>
                    <th class="text-left">Tipo de Relacion</th>
                    <th class="text-left">Eliminar</th>
                </tr>
            </thead>
            <tbody>
            
            
                <tr v-for="item in relaciones" :key="item.id">
                    <td>
                        {{ item.usuario.nombre }} , {{ item.usuario.apellido }}
                    </td>
                    <td>{{ item.relacion }}</td>
                    <td>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon
                                    v-bind="attrs"
                                    v-on="on"
                                    right
                                    @click="eliminarRelacion(item)"
                                    color="error"
                                    >mdi-delete</v-icon
                                >
                            </template>
                            <span>Eliminar</span>
                        </v-tooltip>
                    </td>
                </tr>
            </tbody>
        </template>
    </v-simple-table>
</template>

<script>
export default {
    props: ["usuario", "nuevaRelacion"],

    data() {
        return {
            relaciones: []
        };
    },
    methods: {
        relacionesExistentes() {
            axios.get(`/usuario/${this.usuario.id}/relaciones`).then(res => {
                this.relaciones = res.data;
            });
        },

        eliminarRelacion(relacion) {
            axios.delete(`/usuario/relacion/${relacion.id}`).then(res => {
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

    created() {
        this.relacionesExistentes();
    }
};
</script>
