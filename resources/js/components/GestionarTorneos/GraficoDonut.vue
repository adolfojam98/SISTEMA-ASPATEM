 <template>
  <div>
    <v-row>
      <v-col md="4" offset="4">
        <div>
          <center class="my-5">
            <span><strong>{{ info.title }}</strong></span>
          </center>
          <apexcharts
            width="450"
            :options="options"
            :series="series"
          ></apexcharts>
        </div>
      </v-col>
    </v-row>
  </div>
</template>
<script>
import VueApexCharts from "vue-apexcharts";

export default {
  components: {
    apexcharts: VueApexCharts,
  },
  props: ["info"],
  data() {
    return {
      options: {
        chart: {
          type: "donut",
        },
        labels: this.info.labels,
        plotOptions: {
          pie: {
            startAngle: -90,
            endAngle: 270,
            donut: {
              size: "65%",
              labels: {
                show: true,
                name: { show: true },
                value: { show: true },
                total: { show: true },
              },
            },
          },
        },
        dataLabels: {
          enabled: true,
        },
        fill: {
          type: "gradient",
        },
        // title: {
        //   text: "Cantidad de jugadores por categorias",
        //   align: 'left'
        // },
        legend: {
          formatter: function (val, opts) {
            return (
              val +
              " - " +
              (opts.w.globals.series[opts.seriesIndex]
                ? opts.w.globals.series[opts.seriesIndex]
                : 0)
            );
          },
        },
        responsive: [
          {
            breakpoint: 480,
            options: {
              chart: {
                width: 200,
              },
              legend: {
                position: "bottom",
              },
            },
          },
        ],
      },
      series: this.info.series,
    };
  },
  methods: {
    open(link) {
      this.$electron.shell.openExternal(link);
    },
  },
  mounted() {
    console.log(this.info);
  },
};
</script>

<style>
</style>