<template>
    <v-col cols="12" md="6">
        <v-row>
            <v-col cols="12" md="4">
                <div>
                    <h1>{{categoria.nombre}}</h1>
                    <h1>{{categoria.nombre}}</h1>
                </div>
            </v-col>
            <div class="text-h2 mt-6">}</div>
        </v-row>
        <v-row>
            <v-col cols="12" md="4">
                <div>
                    <h1>{{categoria.nombre}}</h1>
                    <h1>{{categoria.nombre}}</h1>
                </div>
            </v-col>
            <div class="text-h2 mt-6">}</div>
        </v-row>
    </v-col>
</template>


<script>
export default {
    props: ["categoria"],
    data: () => ({
        
    })
};
</script>