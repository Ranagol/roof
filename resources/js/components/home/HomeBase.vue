<template>
	<div>

		<!--        SEARCH BAR-->
		<home-search-bar
			@search="search"
		/>

		<!--        AD LIST LOOPER-->
		<home-ad-list-looper
			v-if="reactiveFilterCounting"
			:filters="filters"
			:filter-count="filterCount"
		/>

	</div>
</template>

<script>
import HomeSearchBar from './HomeSearchBar';
import HomeAdListLooper from './HomeAdListLooper';
import _ from 'lodash';
export default {
	name: 'HomeBase',
	components: {
		HomeSearchBar,
		HomeAdListLooper
	},
	props: {
		filters: {
			type: Array,
			required: false,
			default () {
				return [];
			}
		},
		filterCount: {
			type: [Object, Array],
			required: false,
			default () {
				return {};
			}
		}
	},
	computed: {
		reactiveFilterCounting(){
			if(this.filters === null) {
				return false;
			} else if (this.filters.length > 0) {
				return true;
			} 
			return false;
		}
	},
	methods: {
		search (filters) {
			let url = '/ad-list/non-processed?';
			_.forEach(filters, (value, key) => {
				if (value !== null && value !== undefined) {
					url += `${key}=${value}&`;
				}
			});

			window.location.href = url;
		}
	},
	mounted() {
		console.log('Mounted activated');
		// console.dir(this.filters);
		// console.log(this.filters.length);
	},
};
</script>

<style scoped>

</style>
