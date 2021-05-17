<template>
    <div>
        <mostrar-partidos :partidos = "categoria.partidosLlaves" :jugadores = "categoria.jugadoresAnotados" ></mostrar-partidos>
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

            //TODO calcular jugadores extras (es igual a la cantidad de partidos de ajuste)
            var cantidadJugadoresExtras = 0;
            if(cantidadJugadores > 2 && cantidadJugadores <4){cantidadJugadoresExtras = 1; cantidadJugadores = cantidadJugadores-cantidadJugadoresExtras;}
            else if(cantidadJugadores > 4 && cantidadJugadores < 8){cantidadJugadoresExtras = cantidadJugadores - 4; cantidadJugadores = cantidadJugadores-cantidadJugadoresExtras;}
            else if(cantidadJugadores > 8 && cantidadJugadores < 16){cantidadJugadoresExtras = cantidadJugadores - 8; cantidadJugadores = cantidadJugadores-cantidadJugadoresExtras;}
            else if(cantidadJugadores > 16 && cantidadJugadores < 32){cantidadJugadoresExtras = cantidadJugadores - 16; cantidadJugadores = cantidadJugadores-cantidadJugadoresExtras;}
            else if(cantidadJugadores > 32 && cantidadJugadores < 64){cantidadJugadoresExtras = cantidadJugadores - 32; cantidadJugadores = cantidadJugadores-cantidadJugadoresExtras;}
            else if(cantidadJugadores > 64 && cantidadJugadores < 126){cantidadJugadoresExtras = cantidadJugadores - 64; cantidadJugadores = cantidadJugadores-cantidadJugadoresExtras;}

            if(cantidadJugadores == 2 | cantidadJugadores == 4 | cantidadJugadores == 8 | cantidadJugadores == 16 | cantidadJugadores == 32 | cantidadJugadores == 64){
                this.llavesPerfectas(cantidadJugadores, cantidadJugadoresExtras);
                
            }
        },


        llavesPerfectas(cantidadJugadores, cantidadJugadoresExtras){
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

                this.generarDosPartidos(cantidadPartidos, cantidadJugadoresExtras);
        },

        generarDosPartidos(cantidadPartidos, cantidadJugadoresExtras){

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
            this.generarPartidosDeAjuste(cantidadJugadoresExtras);
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

        generarPartidosDeAjuste(cantidadJugadoresExtras){
            if(cantidadJugadoresExtras > 0 && this.categoria.gruposConEliminatoria == false){
            //TODO calcular id mayor
            var mayorID = 0;

            this.categoria.partidosLlaves.forEach(partido =>{
                if(partido.id > mayorID) mayorID = partido.id;
            });

            while(cantidadJugadoresExtras != 0){
                mayorID++;

                this.categoria.partidosLlaves.push({
                    id : mayorID,
                    jugador1 : null,
                    jugador2 : null,
                    set1 : null,
                    set2 : null,
                    fase : 'ajuste',
                    sigPartidoID : null,
                });

                cantidadJugadoresExtras--;
            }
            }
        }

    },



};
</script>
