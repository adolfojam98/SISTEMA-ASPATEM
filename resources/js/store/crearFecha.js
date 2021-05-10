//fecha

import { mapMutations, mapState } from "vuex";

export default{
    modules: {
    },
    namespaced: true,

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

        setListaJugadores(state, data){
            state.listaJugadores = data;
        },

        setTorneos(state, data){
            state.torneos = data;
        },

        setListaCategorias(state, data){
            state.listaCategorias = data;
        },

        pushJugador(state, data){
            state.listaJugadores.push(data);
        },

        pushJugadorCategoria(state, {item, indexCategoria}){
            state.listaCategorias[indexCategoria].jugadoresAnotados.push(item);
        },

        spliceJugadorCategoria(state, {indice, indexCategoria}){
            state.listaCategorias[indexCategoria].jugadoresAnotados.splice(indice,1);
        },

        setMontoPagadoJugador(state, {indiceJugador, monto}){
            state.listaJugadores[indiceJugador].montoPagado = monto;
        },

        
    },

    getters: {
        listaJugadores(state){
            return state.listaJugadores;
        },

        listaCategorias(state){
            return state.listaCategorias;
        }
    },

    actions: {
       calcularMonto( {commit, state}){
          
        state.listaJugadores.forEach(function (jugador, indiceJugador) {
            
            var anotadoEnCategorias = 0;
            var monto = 0;

            state.listaCategorias.forEach(categoria => {
              
                    if(categoria.jugadoresAnotados.includes(jugador)){
                        anotadoEnCategorias++;
                    }
            })
            
            if(parseInt(state.montoSociosUnaCategoria) >= 0 && parseInt(state.montoSociosDosCategorias) >= 0 
            && parseInt(state.montoNoSociosUnaCategoria) >= 0 && parseInt(state.montoNoSociosDosCategorias) >= 0){
                if(anotadoEnCategorias == 0){ jugador.montoPagado = 0; }
                else if(anotadoEnCategorias == 1 && jugador.socio == 1){ monto =  state.montoSociosUnaCategoria; commit('setMontoPagadoJugador',{indiceJugador, monto});}
                    else if(anotadoEnCategorias == 2 && jugador.socio == 1){ monto =  state.montoSociosDosCategorias; commit('setMontoPagadoJugador',{indiceJugador, monto});}
                        else if(anotadoEnCategorias == 1 && jugador.socio == 0){ monto =  state.montoNoSociosUnaCategoria; commit('setMontoPagadoJugador',{indiceJugador, monto});}
                            else if(anotadoEnCategorias == 2 && jugador.socio == 0){ monto =  state.montoNoSociosDosCategorias; commit('setMontoPagadoJugador',{indiceJugador, monto});}
                        }
            })
       }
    }

}