<script>
import { mapState } from 'vuex'
import { Bar } from 'vue-chartjs'
import { SCOPES } from '@/mijn-ee/globals/constants'

export default {
  name: 'BarChart',
  extends: Bar,
  props: ['height', 'data', 'month', 'year', 'scope'],
  data () {
    return {
      renderData: {
        datasets: []
      },
      options: {
        tooltips: {
          intersect: false,
          mode: 'index',
          axis: 'x',
          callbacks: {
            title: (item) => this.$options.filters.ee_date(item[0].xLabel),
            label: (item) => isNaN(item.yLabel) ? ' n.v.t.' : ' € ' + item.yLabel.toFixed(2),
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
      },
      colors: [
        '#7b4397', '#ed2f3c', '#ff9226', '#a35d19', '#64b72d',
      ],
    }
  },
  computed: {
    ...mapState({
      accounts: state => state.filters.accounts,
      selectedAccounts$: state => state.filters.selectedAccounts$,
    }),
  },
  mounted () {
    this.selectedAccounts$.subscribe(this.render);
  },
  methods: {
    setRenderData () {
      let i = 0;
      this.renderData.datasets = [];

      for (let accountId in this.data) {
        accountId = parseInt(accountId, 10);
        const account = this.accounts.filter(item => item.id === accountId)[0];
        if (!account) continue;

        this.renderData.datasets.push({
          label: account.name || this.$options.filters.ee_iban(account.iban),
          backgroundColor: this.colors[i],
          data: this.data[accountId],
          accountId: accountId,
        });
        
        i++;
      }
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
    render () {
      this.setRenderData();
      this.setXAxisTimeUnit();
      this.renderChart(this.renderData, this.options);
    },
  },
  watch: {
    data: function (newValue, oldValue) {
      this.turnOnAxes();
      this.render();
    },
  },
}
</script>
