<template>
  <div>
    <v-card>
      <v-card class="pa-2" outlined style="background-color: lightgrey" tile>
        <h1 style="color: blue"><center>ASPATEM</center></h1>
      </v-card>

      <div class="ml-3 mt-3">
        <div class="text-h6">Usuario</div>
        <div class="text-body-1 ml-1">
          {{ usuario.nombre }} {{ usuario.apellido }}
        </div>

        <v-divider class="mt-3"></v-divider>

        <div class="text-h6">Mes al que corresponde</div>
        <div class="ml-1 text-body-1">{{ cuota.mes }} del {{ cuota.anio }}</div>
        <v-divider class="mt-3"></v-divider>
        <div class="text-h6">Detalles</div>
        <v-simple-table>
          <thead>
            <tr>
              <th class="text-left">nombre</th>
              <th class="text-left">descripcion</th>
              <th class="text-left">monto</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="detalle in cuota.cuota_detalle" :key="detalle.id">
              <td v-for="detalleTipo in detalle.cuota_detalle_tipo" :key="detalleTipo.id">{{ detalleTipo.nombre }}</td>
              <td>{{ detalle.observacion }}</td>
              <td>{{ detalle.monto }}</td>
            </tr>
          </tbody>
        </v-simple-table>
        <div class="text-h6">Importe total</div>
        <div class="ml-1 text-body-1">${{ cuota.importe }}</div>
        <div v-if="cuota.observacion">
          <h5 class="text--secondary">(*) {{ cuota.observacion }}</h5>
        </div>

        <v-divider class="mt-3"></v-divider>

        <div class="text-h6">Fecha de pago</div>
        <div class="ml-1 text-body-1">{{ cuota.fechaPagoNombre }}</div>

        <br />
      </div>
    </v-card>
  </div>
</template>

<script>
export default {
  props: ["usuario", "cuota"],


  mounted() {
    console.log("->", this.cuota);
    console.log("->cuota detalle", this.cuota);
  
  },
};
</script>
