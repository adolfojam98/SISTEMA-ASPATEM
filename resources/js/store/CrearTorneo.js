import Axios from "axios";


export default {
    namespaced: true,

    state: {
        nombreTorneo: null,
        torneos: null,
        arrayCategorias: [],
        listaJugadores: [],
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

    mutations: {
        setNombreTorneo(state, data) {
            console.log("Estoy entrando");
            state.nombreTorneo = data;
        },
        setStep(state, data) {
            state.e6 = 0;
            state.e6 = data;
        },
        pushJugadorTorneo(state, data) {
            state.listaJugadores.push(data);
        },
        eliminarJugador(state, jugador) {
            state.listaJugadores.splice(
                state.listaJugadores.indexOf(jugador),
                1
            );
        },
        getTorneos(state) {
            console.log("Estoy entrando");
            Axios.get("/torneos").then(e => {
                state.torneos = e.data;
            });
        }
    },
    getters: {
        existeNombreTorneo: state => nombreTorneo => {

            return !state.torneos.every((torneo)=>{
                    return torneo.nombre != nombreTorneo
            })
           
        }
    },
    actions: {
        setStep(action,data){
            action.commit('setStep',data);
        }
    }
};
