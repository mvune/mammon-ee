<template>
  <b-form-group>
    <!-- From date -->
    <v-menu
      v-model="menuFrom"
      :close-on-content-click="false"
      transition="scale-transition"
      offset-y
      full-width
    >
      <template v-slot:activator="{ on }">
        <v-text-field
          v-model="dateFromFormatted"
          label="Van"
          prepend-icon="event"
          readonly
          v-on="on"
        ></v-text-field>
      </template>
      <v-date-picker
        v-model="dateFrom"
        no-title
        @input="menuFrom = false"
        :min="minDate"
        :max="maxDate"
        :picker-date.sync="pickerDateMin"
      >
        <v-spacer></v-spacer>
        <v-btn text color="primary" @click="dateFrom = null; menuFrom = false">Wissen</v-btn>
      </v-date-picker>
    </v-menu>

    <!-- To date -->
    <v-menu
      v-model="menuTo"
      :close-on-content-click="false"
      transition="scale-transition"
      offset-y
      full-width
    >
      <template v-slot:activator="{ on }">
        <v-text-field
          v-model="dateToFormatted"
          label="Tot"
          prepend-icon="event"
          readonly
          v-on="on"
        ></v-text-field>
      </template>
      <v-date-picker
        v-model="dateTo"
        no-title
        @input="menuTo = false"
        :min="minDate"
        :max="maxDate"
        :picker-date.sync="pickerDateMax"
      >
        <v-spacer></v-spacer>
        <v-btn text color="primary" @click="dateTo = null; menuTo = false">Wissen</v-btn>
      </v-date-picker>
    </v-menu>
  </b-form-group>
</template>

<script>
import * as ChartService from '@/mijn-ee/services/ChartService'

export default {
  name: 'DateScopeFilter',
  data () {
    return {
      menuFrom: false,
      menuTo: false,
      dateFrom: null,
      dateTo: null,
      minDate: undefined,
      maxDate: undefined,
      pickerDateMin: undefined,
      pickerDateMax: undefined,
    }
  },
  computed: {
    dateFromFormatted () {
      return this.$options.filters.ee_date(this.dateFrom);
    },
    dateToFormatted () {      
      return this.$options.filters.ee_date(this.dateTo);
    },
  },
  created () {
    ChartService.getBoundaryDates().subscribe(this.handleResponse, this.ee_errorHandler);
  },
  methods: {
    handleResponse (res) {
      this.minDate = res.data.dateFilter.fromDate;
      this.maxDate = res.data.dateFilter.toDate;
      this.pickerDateMin = res.data.dateFilter.fromDate;
      this.pickerDateMax = res.data.dateFilter.toDate;
    },
    isChronological () {
      if (this.dateFrom && this.dateTo) {
        const from = new Date(this.dateFrom);
        const to = new Date(this.dateTo);

        if (from > to) {
          return false;
        }
      }

      return true;
    },
    equalizeTo (type) {
      const otherType = type === 'From' ? 'To' : 'From';
      this['date' + otherType] = this['date' + type];
    },
  },
  watch: {
    dateFrom (newValue) {
      if (!this.isChronological()) this.equalizeTo('From');
      this.pickerDateMin = newValue;
      this.$emit('selectedFrom', newValue);
    },
    dateTo (newValue) {
      if (!this.isChronological()) this.equalizeTo('To');
      this.pickerDateMax = newValue;
      this.$emit('selectedTo', newValue);
    },
  },
}
</script>
