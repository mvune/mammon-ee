<script>
import { Line } from 'vue-chartjs'
import { SCOPES } from '../filters.js'

export default {
  name: 'LineChart',
  extends: Line,
  props: ['height', 'data', 'month', 'year', 'scope'],
  data () {
    return {
      chartData: [],
      renderData: {
        datasets: [
          {
            label: 'Totaal',
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
            ticks: {},
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
    dataToChartData (data) {
      const chartData = [];

      for (let item of data) {
        chartData.push({
          x: item.date,
          y: item.balance,
        });
      }

      return chartData;
    },
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
    setXAxisMinMax () {
      const [fromDate, toDate] = this.getBoundaryDates();

      this.options.scales.xAxes[0].ticks.min = fromDate.toString();
      this.options.scales.xAxes[0].ticks.max = toDate.toString();
    },
    scopeChartData () {
      const [fromDate, toDate] = this.getBoundaryDates();

      this.renderData.datasets[0].data = this.chartData.filter(item => {
        const d = new Date(item.x);

        return d >= fromDate && d <= toDate;
      });

      // Add empty items with `from-` and `toDate` to stretch scope to full period.
      this.renderData.datasets[0].data.unshift({x: fromDate, y: undefined});
      this.renderData.datasets[0].data.push({x: toDate, y: undefined});
    },
    getBoundaryDates () {
      const fromDate = new Date(this.year.toString());
      const toDate = new Date(this.year.toString());

      if (this.scope === SCOPES.YEAR) {
        toDate.setFullYear(this.year + 1);
        toDate.setDate(0); // Set to last day of previous month.
      }
      
      if (this.scope === SCOPES.MONTH) {
        fromDate.setMonth(this.month - 1);
        toDate.setMonth(this.month);
        toDate.setDate(0); // Set to last day of previous month.
      }

      return [fromDate, toDate];
    },
    render () {
      this.renderChart(this.renderData, this.options);
    },
    scopeAndRender () {
      this.scopeChartData();
      this.setXAxisTimeUnit();
      this.setXAxisMinMax();
      this.render();
    },
  },
  watch: {
    data: function (newValue, oldValue) {
      this.chartData = this.dataToChartData(newValue);
      this.turnOnAxes();
      this.scopeAndRender();
    },
    month: function () {
      this.scopeAndRender();
    },
    year: function () {
      this.scopeAndRender();
    },
    scope: function () {
      this.scopeAndRender();
    },
  },
}
</script>
