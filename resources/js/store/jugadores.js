import { mapMutations, mapState } from "vuex";

//jugadores de la nueva fecha y los que ya hayan jugado en el mismo torneo
export default{

    namespaced: true,
    state: {
        apellidoJugador: "",
        nombreJugador: "",
        dniJugador: null,
        puntosJugador: null,
        montoPagado: 0,
        nuevoJugador: false,
    },
    

    mutations: {
        setApellidoJugador(state, data){
            state.apellidoJugador = data;
        },

        setNombreJugador(state, data){
            state.nombreJugador = data;
        },

        setDniJugador(state, data){
            state.dniJugador = data;
        },

        setPuntosJugador(state, data){
            state.puntosJugador = data;
        },

        changeBooleanValueNuevoJugador(state){
            state.nuevoJugador = !state.nuevoJugador;
        },
    },

    actions: {
        
    }
}