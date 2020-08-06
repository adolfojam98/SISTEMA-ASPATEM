<template>
    <div>
        <h1>Ruta pagos</h1>
        <v-container>
            <v-autocomplete
                v-model="value"
                :items="usuarios"
                :item-text="nombreCompleto"
                filled
                label="Ingrese el nombre del socio"
            ></v-autocomplete>
        </v-container>

        <v-container>
            <template>
                <v-simple-table>
                    <template v-slot:default>
                        <thead>
                            <tr>
                                <th class="text-left">Mes</th>
                                <th class="text-left">Año</th>
                                <th class="text-left">Monto</th>
                                <th class="text-left"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>03</td>
                                <td>2020</td>
                                <td>500</td>
                                <td>
                                    <v-chip color="error" dark>Pagar</v-chip>
                                </td>
                            </tr>
                            <tr color="pink">
                                <td color="red">02</td>
                                <td>2020</td>
                                <td>500</td>
                                <td>
                                    <v-chip color="success" dark>Pagada</v-chip>
                                </td>
                            </tr>
                            <tr color="success">
                                <td>01</td>
                                <td>2020</td>
                                <td>500</td>
                                <td>
                                    <v-chip color="success" dark>Pagada</v-chip>
                                </td>
                            </tr>
                        </tbody>
                    </template>
                </v-simple-table>
            </template>
        </v-container>
    </div>
</template>

<script>
export default {
    data() {
        return {
            value: "",
            usuarios: ""
        };
    },
    methods: {
        nombreCompleto: item => item.apellido + " " + item.nombre
    },
    created() {
        axios.get("/usuario").then(res => {
            this.usuarios = res.data;
        });
        
    }
};
</script>
