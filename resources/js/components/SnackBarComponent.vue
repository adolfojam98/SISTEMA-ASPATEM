<template>
 <v-snackbar :value="snackbar" :color="colorSnackBar" top multi-line>
            <div v-bind.prop="headingProps">
                <!-- v-text="message"> -->
            </div>

            <template v-slot:action="{ attrs }">
                <v-btn
                    
                    text
                    v-bind="attrs"
                    @click="setSnackBar(false)"
                    
                >
                    Cerrar
                </v-btn>
            </template>
        </v-snackbar>
</template>

<script>
import { mapActions, mapMutations, mapState } from 'vuex'

export default {
   computed: {
        ...mapState(['message', 'snackbar','colorSnackBar']),
        headingProps() {
            return { innerHTML: this.message };
        },
    },
    methods: {
        ...mapMutations(['setSnackBar'])
    },
}
</script>