export default{
    namespaced: true,

state :{
nombreTorneo: '',
arrayCategorias: [],
listaJugadores : [],
gestionPuntos: {
    mismaCat_MayorNivel_Ganador: null,
    mismaCat_MayorNivel_Perdedor: null,
    mismaCat_MenorNivel_Ganador: null,
    mismaCat_MenorNivel_Perdedor: null,
    diferenteCat_MayorNivel_Ganador: null,
    diferenteCat_MayorNivel_Perdedor: null,
    diferenteCat_MenorNivel_Ganador: null,
    diferenteCat_MenorNivel_Perdedor: null
},
e6: 1
},
mutations :{
    setNombreTorneo(state, data){
        console.log("Estoy entrando")
        state.nombreTorneo = data;
    },
    setStep(state,data){
        
        state.e6 = 0
        state.e6 = data
    },
    pushJugadorTorneo(state,data){
        state.listaJugadores.push(data)
    },
    eliminarJugador(state,jugador) {
        state.listaJugadores.splice(state.listaJugadores.indexOf(jugador), 1);
    },

    
},



}