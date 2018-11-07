<!--suppress ALL -->
<template>
	<div>
		<h2>Graphs</h2>

		<div class="graphs clearfix">
			<div class="graph">
				<chart style="width: 100%;" :options="votesPerWeek"></chart>
			</div>
			<div class="graph">
				<chart style="width: 100%;" :options="votesOverTime"></chart>
			</div>
		</div>
	</div>
</template>

<script>

import _ from 'lodash';
import { Chart } from 'highcharts-vue';

/**
 * Charts
 * 1. Rank Over Time
 * 2. Votes Over Time
 * 3. Total Score Over Time
 */

export default {

	props: [ 'weeklyData', 'names' ],

	data() {
		return {
		};
	},

	computed: {
		votesPerWeek() {

			const seriesByName = {};
			const categories = [];

			this.names.forEach(name => seriesByName[ name.name ] = [])

			Object.entries(this.weeklyData).forEach(([ weekName, week ]) => {
				categories.push(weekName);

				this.names.forEach(({ name }) => {
					let value = 0;
					week.forEach(vote => {
						if (vote.name === name) value = vote.votes;
					});

					seriesByName[ name ].push(parseInt(value, 10));
				});

			});

			const series = [];
			Object.entries(seriesByName).forEach(([ name, data ]) => {
				series.push({ name, data });
			});

			return {
				title: { text: 'Votes Over Time' },
				legend: { layout: 'vertical', align: 'right', verticalAlign: 'middle' },
				xAxis: {
					categories,
				},

				series,

			};
		},

		votesOverTime() {

			const seriesByName = {};
			const categories = [];

			this.names.forEach(name => seriesByName[ name.name ] = [])

			Object.entries(this.weeklyData).forEach(([ weekName, week ]) => {
				categories.push(weekName);

				this.names.forEach(({ name }) => {
					let value = 0;
					week.forEach(vote => {
						if (vote.name === name) value = vote.votes;
					});

					seriesByName[ name ].push(seriesByName[ name ][ seriesByName[ name ].length - 1 ] + parseInt(value, 10));
				});

			});

			const series = [];
			Object.entries(seriesByName).forEach(([ name, data ]) => {
				series.push({ name, data });
			});

			return {
				title: { text: 'Votes Over Time' },
				legend: { layout: 'vertical', align: 'right', verticalAlign: 'middle' },
				xAxis: {
					categories,
				},

				series,

			};
		},
	},

	components: {
		Chart,
	},

};

</script>
