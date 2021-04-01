//fecha

export default{
    modules: {
    },

    state: {
        torneoSeleccionado : null,
        nombreFecha : "",
        listaJugadores: [],
        listaCategorias: [],
        montoSociosUnaCategoria : null,
        montoSociosDosCategorias : null,
        montoNoSociosUnaCategoria : null,
        montoNoSociosDosCategorias : null,
        torneos : [],
    },

    mutations: {
        setTorneoSeleccionado(state, data){
            state.torneoSeleccionado = data;
        },
        
        setNombreFecha(state, data){
            state.nombreFecha = data;
        },

        setMontoSociosUnaCategoria(state, data){
            state.montoSociosUnaCategoria = data;
        },

        setMontoSociosDosCategorias(state, data){
            state.montoSociosDosCategorias = data;
        },

        setMontoNoSociosUnaCategoria(state, data){
            state.montoNoSociosUnaCategoria = data;
        },

        setMontoNoSociosDosCategorias(state, data){
            state.montoNoSociosDosCategorias = data;
        },

    },

    getters: {
        
    }

}