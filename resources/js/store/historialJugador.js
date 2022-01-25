
export default{
    strict: false,
    modules: {
    },
    namespaced: true,

    state: {
        jugadorSeleccionado : '',
        jugadores : [],
        history : null
    },

    mutations: {
        setjugadorSeleccionado(state,data){
            state.jugadorSeleccionado = data;
        },

        setjugadores(state,data){
            state.jugadores = data;
        },

        setHistory(state,data){
            state.history = data;
        },
    }
}