//jugadores de la nueva fecha y los que ya hayan jugado en el mismo torneo
export default{

    state: {
        apellidoJugador: "",
        nombreJugador: "",
        dniJugador: null,
        puntosJugador: null,
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