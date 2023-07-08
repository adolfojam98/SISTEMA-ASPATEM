<template>
    <v-row justify="center">
        


    </v-row>
</template>


<script>
export default {
    data() {
        return {
            dialog: false,
            observacion: ""
        }
    },
    methods: {
        anularCuota() {
            this.$emit('anularCuota')
        }
    }
}
</script>