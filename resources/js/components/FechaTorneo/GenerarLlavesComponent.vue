<template>
    <div>
        <mostrar-partidos :partidos = "categoria.partidosLlaves"></mostrar-partidos>
    </div>
</template>



<script>
export default {
    props: ["categoria"],
    data: () => ({
        colaIDs : [],

    }),

    created(){
        if(this.categoria.partidosLlaves.length == 0){
        this.generarPartidos();}
    },

    methods: {
        generarPartidos(){
            var cantidadJugadores = 0;
            this.categoria.listaGrupos.forEach(grupo =>{
                cantidadJugadores = cantidadJugadores + grupo.jugadoresDelGrupo.length;
            });
            
            if(cantidadJugadores == 2 | cantidadJugadores == 4 | cantidadJugadores == 8 | cantidadJugadores == 16 | cantidadJugadores == 32 | cantidadJugadores == 64){
                this.llavesPerfectas(cantidadJugadores);
            }
        },


        llavesPerfectas(cantidadJugadores){
            var cantidadPartidos = (cantidadJugadores - 2);

            var unPartido = {
                id : 0,
                jugador1 : null,
                jugador2 : null,
                set1 : null,
                set2 : null,
                fase : 0,
                sigPartidoID : null,
            }
                this.categoria.partidosLlaves.push(unPartido);

                this.colaIDs.push(unPartido.id);

                this.generarDosPartidos(cantidadPartidos);
        },

        generarDosPartidos(cantidadPartidos){

            while(cantidadPartidos != 0){
                cantidadPartidos = cantidadPartidos - 2;

                this.categoria.partidosLlaves.push({
                    id : (this.colaIDs[this.colaIDs.length - 1] + 1),
                    jugador1 : null,
                    jugador2 : null,
                    set1 : null,
                    set2 : null,
                    fase : 0,
                    sigPartidoID : this.colaIDs[0],
                });

                this.colaIDs.push((this.colaIDs[this.colaIDs.length - 1] + 1));

                this.categoria.partidosLlaves.push({
                    id : (this.colaIDs[this.colaIDs.length - 1] + 1),
                    jugador1 : null,
                    jugador2 : null,
                    set1 : null,
                    set2 : null,
                    fase : 0,
                    sigPartidoID : this.colaIDs[0],
                });

                this.colaIDs.push((this.colaIDs[this.colaIDs.length - 1] + 1));

                this.colaIDs.shift();
            }

            this.calcularFases();
        },

        calcularFases(){
            this.categoria.partidosLlaves.forEach(partido =>{
                if(partido.id == 0) partido.fase = 'final';
                else if(partido.id < 3) partido.fase = 'semifinal';
                else if(partido.id < 7) partido.fase = '4tos de final';
                else if(partido.id < 15) partido.fase = '8vo de final';
                else if(partido.id < 31) partido.fase = '16vo de final';
                else if(partido.id < 63) partido.fase = '32vo de final';
                else if(partido.id < 127) partido.fase = '64vo de final';
            });
        },

    },



};
</script>
