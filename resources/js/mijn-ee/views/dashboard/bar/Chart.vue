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
        legend: {
          onClick: e => e.stopPropagation(),
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
      selectedAccounts$: state => state.filters.selectedAccounts$,
      selectedAccounts: state => state.filters.selectedAccounts,
      accounts: state => state.filters.accounts,
    }),
  },
  mounted () {
    this.selectedAccounts$.subscribe(() => this.render());
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
    toggleSelected () {
      for (let dataset of this.renderData.datasets) {
        if (this.selectedAccounts.includes(dataset.accountId)) {
          dataset.hidden = false;
        } else {
          dataset.hidden = true;
        }
      }
    },
    render () {
      this.setXAxisTimeUnit();
      this.toggleSelected();
      this.renderChart(this.renderData, this.options);
    },
  },
  watch: {
    data: function (newValue, oldValue) {
      let i = 0;
      this.renderData.datasets = [];

      for (let accountId in newValue) {
        accountId = parseInt(accountId, 10);
        const account = this.accounts.filter(item => item.id === accountId)[0];
        if (!account) continue;

        this.renderData.datasets.push({
          label: account.name || this.$options.filters.ee_iban(account.iban),
          backgroundColor: this.colors[i],
          data: newValue[accountId],
          accountId: accountId,
        });
        
        i++;
      }
      this.turnOnAxes();
      this.render();
    },
  },
}
</script>
