<script>
import { Bar } from 'vue-chartjs'
import { SCOPES } from '@/mijn-ee/globals/constants'
import styles from '@/mijn-ee/variables.scss'

export default {
  name: 'BarChart',
  extends: Bar,
  props: ['height', 'data', 'month', 'year', 'scope'],
  data () {
    return {
      renderData: {
        datasets: [
          {
            label: 'Saldo',
            backgroundColor: styles.primary,
            data: [],
          }
        ]
      },
      options: {
        onClick: (event, elements) => {
          this.$emit('clicked', elements);
        },
        tooltips: {
          intersect: false,
          mode: 'index',
          axis: 'x',
          callbacks: {
            title: (item) => this.$options.filters.ee_date(item[0].xLabel),
            label: (item) => ' € ' + item.yLabel.toFixed(2),
          },
        },
        scales: {
          xAxes: [{
            display: false,
            barPercentage: 1,
            categoryPercentage: 0.85,
            type: 'time',
            time: {
              unit: 'month',
            },
          }],
          yAxes: [{
            display: false,
            ticks: {
              suggestedMin: 0,
              suggestedMax: 1000,
            },
          }]
        }
      }
    }
  },
  mounted () {
    this.render();
  },
  methods: {
    turnOnAxes () {
      this.options.scales.xAxes[0].display = true;
      this.options.scales.yAxes[0].display = true;
    },
    setXAxisTimeUnit () {
      if (this.scope === SCOPES.YEAR) {
        this.options.scales.xAxes[0].time.unit = 'month';
      }
      
      if (this.scope === SCOPES.MONTH) {
        this.options.scales.xAxes[0].time.unit = 'day';
      }
    },
    render () {
      this.setXAxisTimeUnit();
      this.renderChart(this.renderData, this.options);
    },
  },
  watch: {
    data: function (newValue, oldValue) {
      this.renderData.datasets[0].data = newValue;
      this.turnOnAxes();
      this.render();
    },
  },
}
</script>
