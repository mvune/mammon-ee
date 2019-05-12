<script>
import { Bar } from 'vue-chartjs'
import { SCOPES } from '../filters.js'

export default {
  name: 'SaldoChart',
  extends: Bar,
  props: ['height', 'data', 'month', 'year', 'scope'],
  data () {
    return {
      chartData: [],
      renderData: {
        datasets: [
          {
            label: 'Totaal',
            backgroundColor: 'green',
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
            barPercentage: 0.3,
            categoryPercentage: 0.1,
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
        this.options.scales.xAxes[0].barPercentage = 0.11;
      }
      
      if (this.scope === SCOPES.MONTH) {
        this.options.scales.xAxes[0].time.unit = 'day';
        this.options.scales.xAxes[0].barPercentage = 0.3;
      }
    },
    scopeChartData () {
      const [fromDate, toDate] = this.getBoundaryDates();
      const fromDateExcl = new Date(fromDate);
      const toDateExcl = new Date(toDate);
      fromDateExcl.setDate(fromDateExcl.getDate() - 1);
      toDateExcl.setDate(toDateExcl.getDate() + 1);

      this.renderData.datasets[0].data = this.chartData.filter(item => {
        const d = new Date(item.x);
        d.setHours(6);
        
        return d > fromDateExcl && d < toDateExcl;
      });

      const renderData = this.renderData.datasets[0].data;
      const cursor = new Date(fromDate);
      const betweens = [];
      const lastDate = new Date(this.chartData[this.chartData.length - 1].x);
      let previousBalance;

      const firstItemIndex = this.chartData.findIndex(item => {
        return renderData[0] ? item.x === renderData[0].x : false;
      });
      
      if (firstItemIndex > 0) {
        previousBalance = this.chartData[firstItemIndex - 1].y;
      }

      while (cursor < toDateExcl) {
        let rememberItem = renderData.filter(item => {
          return (new Date(item.x)).toISOString().substring(0, 10) === cursor.toISOString().substring(0, 10);
        });

        if (rememberItem.length > 0) {
          previousBalance = rememberItem[0].y;
        } else {
          if (cursor > lastDate) {
            betweens.push({x: cursor.toISOString().substring(0, 10), y: undefined});
          } else {
            betweens.push({x: cursor.toISOString().substring(0, 10), y: previousBalance});
          }
        }

        cursor.setDate(cursor.getDate() + 1);
      }

      renderData.push(...betweens);
    },
    getBoundaryDates () {
      const fromDate = new Date(this.year.toString());
      const toDate = new Date(this.year.toString());
      fromDate.setHours(6);
      toDate.setHours(6);

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
    sortByDate (renderData) {
      return renderData.sort((first, second) => {
        const fd = new Date(first.x);
        const sd = new Date(second.x);

        if (fd < sd) return -1;
        if (fd > sd) return 1;
        if (fd.getTime() === sd.getTime()) return 0;
      });
    },
    render () {
      this.renderChart(this.renderData, this.options);
    },
    scopeAndRender () {
      this.scopeChartData();
      this.setXAxisTimeUnit();
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
