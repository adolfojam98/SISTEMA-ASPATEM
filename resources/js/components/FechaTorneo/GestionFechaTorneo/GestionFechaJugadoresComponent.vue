
<template>
  <div class="ma-9 mt-0">
    <div class="d-flex mx-auto mb-9" style="justify-content: center;">
      <h2>Gestion de fechas</h2>
    </div>

    <v-row class="align-center">
      <div class="mr-6" style="width: 300px;">
        <v-autocomplete return-object :items="torneos" :item-text="nombreTorneo" v-model="torneoSeleccionado"
          label="Buscar Torneo" @change="getFechas()"></v-autocomplete>
      </div>
      <div class="mx-6" style="width: 300px;">
        <v-autocomplete return-object :items="fechas" :item-text="nombreFecha" v-model="fechaSeleccionada"
          label="Buscar Fecha"></v-autocomplete>
      </div>
      <v-btn class="mx-6" @click="cargarFecha()" color="primary">BUSCAR</v-btn>
      <agregar-jugador-torneo-fecha-modal
        @agregar-jugador="agregarNuevoJugadorTorneo" v-if="busquedaRealizada"></agregar-jugador-torneo-fecha-modal>
    </v-row>

    <div class="d-flex" style="justify-content: center;">
        <v-card class="mt-6" style="width: max-content;">
          <v-data-table dense :headers="headers" :items="listaJugadores" item-key="dni" :items-per-page="15" :search="search">
            <template v-slot:[`item.actions`]="{ item }">
                <v-tooltip bottom v-if="mensajeAgregarCategoria(item) !== ''">
                  <template v-slot:activator="{ on, attrs }">
                    <small class="mt-1" v-bind="attrs" v-on="on" @click="[agregarJugadorEnSuCategoria(item)]" :class="{'add-category': mensajeAgregarCategoria(item).includes('Agregar'), 'remove-category': mensajeAgregarCategoria(item).includes('Quitar')}">{{mensajeAgregarCategoria(item).replace('Quitar de la categoria: ','').replace('Agregar a la categoria: ','')}}</small>                  </template>
                  <span>{{ mensajeAgregarCategoria(item) }}</span>
                </v-tooltip>

                <v-tooltip bottom v-if="!mensajeAgregarCategoriaSuperior(item).includes('No hay una categoria superior')">
                  <template v-slot:activator="{ on, attrs }">
                    <small class="mt-1" v-bind="attrs" v-on="on" @click="[agregarJugadorEnLaCategoriaSuperior(item)]" :class="{'add-category': mensajeAgregarCategoriaSuperior(item).includes('Agregar'), 'remove-category': mensajeAgregarCategoriaSuperior(item).includes('Quitar')}">{{mensajeAgregarCategoriaSuperior(item).replace('Quitar de la categoria superior: ','').replace('Agregar en la categoria superior: ','')}}</small>
                  </template>
                  <span>{{ mensajeAgregarCategoriaSuperior(item)}}</span>
                </v-tooltip>
              </template>
          </v-data-table>
        </v-card>
      </div>

    <div class="mt-6" v-if="busquedaRealizada">
      <grupos-categorias :categorias="listaCategorias" :listaJugadores="listaJugadores"></grupos-categorias>

      <v-btn class="mt-6" color="primary" dark @click="confirmarGuardarFecha = true">
        GUARDAR FECHA
      </v-btn>
    </div>

    <!-- DIALOGS -->
    <v-dialog persist v-model="confirmarGuardarFecha" max-width="290">
      <v-card>
        <v-card-title>
          Confirmación
        </v-card-title>
        <v-card-text>Se guardará la fecha y no se podra modificar los partidos jugados.</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn class="error" @click="confirmarGuardarFecha = false">
            CANCELAR
          </v-btn>
          <v-btn class="success" @click="[confirmarGuardarFecha = false, guardarFecha()]">
            ACEPTAR
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>


<script>
import { mapActions } from 'vuex';
export default {
  data() {
    return {
      busquedaRealizada: false,
      torneos: [],
      torneoSeleccionado: null,
      fechas: [],
      fechaSeleccionada: null,
      search: '',
      listaJugadores: [],
      listaCategorias: [],
      jugadoresAnotados: [],
      confirmarGuardarFecha: false,
      agregarNuevoJugadorModel: false,

      headers: [
        { text: "Apellido", value: "apellido", width: '175px' },
        { text: "Nombre", value: "nombre", width: '175px' },
        { text: "DNI", value: "dni", width: '175px' },
        {
          text: "Categorias",
          value: "actions",
          sortable: false,
          filterable: false,
          width: '325px'
        },
        { text: "Puntos", value: "puntos", width: '175px' },

        { text: "Monto", value: "monto_pagado" },
      ],

    }
  },
  created() {
    axios.get("/torneos").then((res) => {
      this.torneos = res.data;
    });
  },
  methods: {
    ...mapActions(['callSnackbar']),
    nombreTorneo: (item) => item.nombre,
    nombreFecha: (item) => item.nombre,
    getFechas() {
      this.vaciarVariables();
      if (this.torneoSeleccionado) {
        axios.get(`/torneo/${this.torneoSeleccionado.id}/fechas`).then((res) => {
          this.fechas = this.filtarFechasCerradas(res.data);
        })
        axios.get(`/torneos/${this.torneoSeleccionado.id}/categorias`).then((res) => {
          this.listaCategorias = res.data;
        })
      }
    },
    filtarFechasCerradas(fechas) {
      return fechas.filter(fecha => fecha.vigencia == 1);
    },
    guardarFecha() {
      try {
        this.validarGuardarFecha();

        axios.post(`/fechas/${this.fechaSeleccionada.id}`,
          { "vigencia": false }
        ).then((res) => {
          this.vaciarVariables();
          this.callSnackbar(['Fecha guardada correctamente', 'success']);
          this.getFechas();

        })
      } catch (e) {
        this.callSnackbar([e, 'error'])
      }

    },
    validarGuardarFecha() {
      this.listaCategorias.forEach(categoria => {
        if (!categoria.jugadoresAnotados || !categoria.jugadoresAnotados.length > 0) {
          return;
        }
        if (categoria.jugadoresAnotados.length > 0 && categoria.listaGrupos.length === 0) {
          console.log('categoria a medio llenar: ', categoria)
          throw `La categoria ${categoria.nombre} tiene jugadores anotados pero le faltan partidos`;
        }
        const isPartidosGruposValid = categoria.listaGrupos.every(grupo => grupo.partidos.every(partido => this.validarPartido(partido)));

        console.log(categoria);
        if (!isPartidosGruposValid) {
          throw `Hay al menos un partido sin cargar o en empate en los grupos en la categoria ${categoria.nombre}`;
        }

        //validar partidos llaves
        const isPartidosLlaveValid = categoria.partidosLlaves.every(partido => this.validarPartido(partido));
        if (!isPartidosLlaveValid) {
          throw `Hay al menos un partido sin cargar o en empate en la llave en la categoria ${categoria.nombre}`;
        }

      });
    },

    validarPartido(partido) {
      if (!partido.jugador1 || !partido.jugador2) {
        return false;
      }
      if (!partido.setsJugador1 || !partido.setsJugador2) {
        return false;
      }
      const setsJugador1 = parseInt(partido.setsJugador1);
      const setsJugador2 = parseInt(partido.setsJugador2);

      return setsJugador1 !== setsJugador2;
    },
    vaciarVariables() {
      this.fechas = [];
      this.busquedaRealizada = false;
      this.listaJugadores = [];
      this.listaCategorias = [];
      this.jugadoresAnotados = [];
    },
    async agregarNuevoJugadorTorneo(jugador) {
      try {
        const jugadores = await axios.post("/jugador", {
          id_torneo: this.torneoSeleccionado.id,
          jugadores: [jugador]
        });
        this.callSnackbar(['jugador se anotó correctamente', 'success']);
        this.cargarFecha();
      } catch (error) {
        this.callSnackbar([error.response.data.message, 'error'])
      }
    },

    async cargarFecha() {
      try {
        const [fechaResponse, jugadoresAnotadosResponse] = await Promise.all([
          axios.get(`/fechas/${this.fechaSeleccionada.id}`),
          axios.get(`/fechas/${this.fechaSeleccionada.id}/usuarios`)
        ]);
        this.busquedaRealizada = true;

        this.listaJugadores = fechaResponse.data.resumen_jugadores.ranking;

        this.listaJugadores = this.listaJugadores.map(jugador => { return { ...jugador, monto_pagado: 0 } });
        this.listaCategorias = fechaResponse.data.resumen_jugadores.categorias;
        const listaJugadoresAnotados = jugadoresAnotadosResponse.data.body;

        this.listaCategorias = this.listaCategorias.map(categoria => {
          return {
            ...categoria,
            jugadoresAnotados: [],
            cantidadGrupos: 0,
            gruposConEliminatoria: false,
            listaGrupos: [],
            partidosLlaves: [],
            fecha_id: this.fechaSeleccionada.id
          }
        });
        this.agregarJugadoresAnotados(listaJugadoresAnotados);
        this.mapearPartidosJugadosCategorias();
      } catch (error) {
        console.error(error);
      }
    },

    async mapearPartidosJugadosCategorias() {
      this.listaCategorias.forEach(async (categoria) => {
        const res = await axios.get(`/fechas/${categoria.fecha_id}/categoria/${categoria.id}/partidos`);
        const partidos = res.data.body;
        this.categoriaSeleccionada = categoria
        if (partidos.length > 0) {
          this.mapearPartidosDeCategoria(partidos);
        }
      });
    },
    mapearJugadoresDePartido(partido, listaJugadores) {
      const { jugador1, jugador2 } = partido.jugadores;
      partido.setsJugador1 = jugador1?.sets;
      partido.setsJugador2 = jugador2?.sets;
      partido.jugadores.jugador1 = listaJugadores.find(jugador => jugador.usuario_id == jugador1?.id);
      partido.jugadores.jugador2 = listaJugadores.find(jugador => jugador.usuario_id == jugador2?.id);
    },

    mapearPartidosDeCategoria(partidos) {
      for (const partido of partidos) {
        this.mapearJugadoresDePartido(partido, this.listaJugadores);
      }

      const gruposDePartidos = partidos.reduce((grupos, partido) => {
        if (partido.grupo) {
          const { id, jugadores, setsJugador1, setsJugador2 } = partido;
          const { nombre } = partido.grupo;
          const nuevoPartido = { id, jugador1: jugadores.jugador1, jugador2: jugadores.jugador2, setsJugador1, setsJugador2 };

          const grupoExistente = grupos.find(grupo => grupo.nombre === nombre);

          if (!grupoExistente) {
            const nuevoGrupo = { nombre, partidos: [nuevoPartido], jugadoresDelGrupo: [jugadores.jugador1, jugadores.jugador2] };
            grupos.push(nuevoGrupo);
          } else {
            grupoExistente.partidos.push(nuevoPartido);

            if (!grupoExistente.jugadoresDelGrupo.some(jugador => jugador.usuario_id === jugadores.jugador1.usuario_id)) {
              grupoExistente.jugadoresDelGrupo.push(jugadores.jugador1);
            }

            if (!grupoExistente.jugadoresDelGrupo.some(jugador => jugador.usuario_id === jugadores.jugador2.usuario_id)) {
              grupoExistente.jugadoresDelGrupo.push(jugadores.jugador2);
            }
          }
        }

        return grupos;
      }, []);

      if (gruposDePartidos.length > 0) {
        this.categoriaSeleccionada.listaGrupos = gruposDePartidos;
      }

      let partidosLlaves = [];
      partidos.forEach((partido) => {
        if (!partido.grupo) {
          const { id, jugadores, setsJugador1, setsJugador2, sig_partido_id, fase } = partido;
          const nuevoPartido = { id, jugador1: jugadores.jugador1, jugador2: jugadores.jugador2, setsJugador1, setsJugador2, idPartidoPadre: sig_partido_id, fase: fase.nombre };
          partidosLlaves.push(nuevoPartido);
        }
      });
      if (partidosLlaves.length > 0) {
        this.categoriaSeleccionada.partidosLlaves = partidosLlaves;
      }

    },

    agregarJugadoresAnotados(listaJugadoresAnotados) {
      this.listaCategorias.forEach(categoria => {
        listaJugadoresAnotados.forEach(jugador => {

          if (categoria.id == jugador.categoria_mayor_id || categoria.id == jugador.categoria_menor_id) {
            const jugadorEncontrado = this.listaJugadores.find(j => j.usuario_id == jugador.usuario_id);
            jugadorEncontrado.monto_pagado = jugador.monto_pagado;
            categoria.jugadoresAnotados.push(jugadorEncontrado);
          }
        });
      });
    },


    getCategoriaJugador(jugador) {
      const categoria = this.listaCategorias.find((categoria) => { return categoria.nombre == jugador.categoria });
      return categoria;
    },

    getCategoriaSuperiorJugador(jugador) {
      const categoriaJugador = this.getCategoriaJugador(jugador);
      return this.listaCategorias.find(categoria => parseInt(categoria.puntos_minimos) > parseInt(categoriaJugador.puntos_minimos));
    },


    mensajeAgregarCategoria(jugador) {
      const categoria = this.getCategoriaJugador(jugador);
      if (!categoria) {
        return "No hay una categoria superior";
      }
      if (!this.estaAnotadoEnCategoria(categoria, jugador)) {
        return "Agregar a la categoria: " + categoria.nombre;
      } else {
        return "Quitar de la categoria: " + categoria.nombre;
      }
    },

    mensajeAgregarCategoriaSuperior(jugador) {
      const categoria = this.getCategoriaSuperiorJugador(jugador);
      if (!categoria) {
        return "No hay una categoria superior";
      }
      if (!this.estaAnotadoEnCategoria(categoria, jugador)) {
        return "Agregar en la categoria superior: " + categoria.nombre;
      } else {
        return "Quitar de la categoria superior: " + categoria.nombre;
      }

    },

    agregarJugadorEnCategoria(infoJugador) {
      axios.post(`/fechas/${this.fechaSeleccionada.id}/usuarios/${infoJugador.id}`, { ...infoJugador }).then(
        (res) => {
          this.actualizarEstadoJugador(res.data.body);
        });
    },

    actualizarEstadoJugador(estadoJugador) {
      const jugador = this.listaJugadores.find(j => j.usuario_id == estadoJugador.usuario_id);
      jugador.monto_pagado = estadoJugador.monto_pagado;
      const categoriaSuperior = this.getCategoriaSuperiorJugador(jugador);
      const categoriaMenor = this.getCategoriaJugador(jugador);

      if (estadoJugador.categoria_mayor_id) {
        const jugadorEncontrado = categoriaSuperior.jugadoresAnotados.find(j => j.usuario_id == estadoJugador.usuario_id);
        if (!jugadorEncontrado) {
          categoriaSuperior.jugadoresAnotados.push(jugador);
        }
      } else if (categoriaSuperior) {
        categoriaSuperior.jugadoresAnotados = categoriaSuperior.jugadoresAnotados.filter(j => j.usuario_id != jugador.usuario_id);
      }

      if (estadoJugador.categoria_menor_id) {

        const jugadorEncontrado = categoriaMenor.jugadoresAnotados.find(j => j.usuario_id == estadoJugador.usuario_id);
        if (!jugadorEncontrado) {
          categoriaMenor.jugadoresAnotados.push(jugador);
        }
      } else {

        if (categoriaMenor) {
          categoriaMenor.jugadoresAnotados = categoriaMenor.jugadoresAnotados.filter(j => j.usuario_id != jugador.usuario_id);
        }
      }
    },

    agregarJugadorEnSuCategoria(jugador) {
      const estadoJugador = this.getEstadoJugador(jugador);
      const categoriaJugador = this.getCategoriaJugador(jugador);
      console.log('categoriaJugaodor: ', categoriaJugador);

      if (categoriaJugador.listaGrupos.length > 0) {
        this.callSnackbar(['No se puede realizar el cambio ya que se esta jugando la categoria', 'error']);
        return;
      }

      if (estadoJugador.categoria_menor_id) {
        estadoJugador.categoria_menor_id = null
      } else {
        estadoJugador.categoria_menor_id = categoriaJugador.id;
      }

      this.agregarJugadorEnCategoria(estadoJugador);

    },

    agregarJugadorEnLaCategoriaSuperior(jugador) {
      const estadoJugador = this.getEstadoJugador(jugador);
      const categoriaSuperiorJugador = this.getCategoriaSuperiorJugador(jugador);

      if (categoriaSuperiorJugador.listaGrupos.length > 0) {
        this.callSnackbar(['No se puede realizar el cambio ya que se esta jugando la categoria', 'error']);
        return;
      }

      if (estadoJugador.categoria_mayor_id) {
        estadoJugador.categoria_mayor_id = null
      } else {
        estadoJugador.categoria_mayor_id = categoriaSuperiorJugador.id;
      }
      this.agregarJugadorEnCategoria(estadoJugador);

    },
    getEstadoJugador(jugador) {
      const categoriaActual = this.getCategoriaJugador(jugador);
      const categoriaSuperior = this.getCategoriaSuperiorJugador(jugador);
      const categoriaActualId = categoriaActual && this.buscarJugadorEnCategoria(jugador, categoriaActual) ? categoriaActual.id : null;
      const categoriaSuperiorId = categoriaSuperior && this.buscarJugadorEnCategoria(jugador, categoriaSuperior) ? categoriaSuperior.id : null;

      return {
        id: jugador.usuario_id,
        categoria_menor_id: categoriaActualId,
        categoria_mayor_id: categoriaSuperiorId,
        monto: 124
      }
    },


    buscarJugadorEnCategoria(jugador, categoria) {
      return categoria.jugadoresAnotados.find(j => j.usuario_id == jugador.usuario_id);
    },

    estaAnotadoEnCategoria(categoria, jugador) {

      return categoria.jugadoresAnotados.some((elemento) => elemento.usuario_id == jugador.usuario_id);
    },

  }
}
</script>


<style>
.remove-category {
  cursor: pointer;
  background-color: lightseagreen;
  color: white;
  padding: 4px;
  border-radius: 10px;
}

.add-category {
  cursor: pointer;
  background-color: gray;
  color: white;
  padding: 4px;
  border-radius: 10px;
}
</style>