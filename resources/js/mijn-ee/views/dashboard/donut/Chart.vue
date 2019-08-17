<style lang="scss" scoped>
.chartjs-render-monitor {
  max-width: 420px;
  margin: 0 auto;
}
</style>

<script>
import { Doughnut } from 'vue-chartjs'

export default {
  name: 'DoughnutChart',
  extends: Doughnut,
  props: {
    data: Array,
    labels: Array,
  },
  data () {
    return {
      options: {
        tooltips: {
          callbacks: {
            title: (item, chart) => chart.labels[item[0].index],
            label: this.getLabel,
          },
        },
        legend: {
          display: false,
        },
      },
      backgroundColors: [
        '#ed2f3c', '#971e27', '#ffb974',
        '#ff9226', '#a35d19', '#f9e882',
        '#f7db3b', '#9e8c26', '#9cd179',
        '#64b72d', '#40751d', '#abd2eb',
        '#7bb9e0', '#4f768f', '#ab87bc',
        '#7b4397', '#4f2b61', '#f491e1',
        '#ef53d0', '#993585', '#f37a82',
      ],
    }
  },
  computed: {
    chartData () {
      return {
        labels: this.labels,
        datasets: [
          {
            data: this.data,
            backgroundColor: this.backgroundColors,
            hoverBackgroundColor: this.backgroundColors,
          }
        ],
      }
    },
  },
  mounted () {
    this.render();
  },
  methods: {
    render () {
      this.$emit('legend', this.createCustomLegend());
      this.renderChart(this.chartData, this.options);
    },
    createCustomLegend () {
      const legend = [];

      for (let i = 0; i < this.labels.length; i++) {
        legend.push({
          color: this.backgroundColors[i % this.backgroundColors.length],
          label: this.labels[i],
          value: this.data[i],
        });
      }

      return legend;
    },
    getLabel (item, chart) {
      const value = chart.datasets[0].data[item.index];
      const total = _.sum(chart.datasets[0].data);

      return ' ' + this.$options.filters.ee_valuta(value) + ' (' + (value / total * 100).toFixed(1) + '%)';
    },
  },
  watch: {
    data () {
      this.render();
    },
    labels () {
      this.render();
    },
  },
}
</script>
