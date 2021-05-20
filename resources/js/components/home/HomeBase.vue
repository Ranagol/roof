<template>
	<div>

		<!--        SEARCH BAR-->
		<home-search-bar
			@search="search"
		/>

		<!--        AD LIST LOOPER-->
		<home-ad-list-looper
			:filters="filters"
			:filter-count="filterCount"
		/>

	</div>
</template>

<script>
import HomeSearchBar from './HomeSearchBar';
import HomeAdListLooper from './HomeAdListLooper';

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
			type: Object,
			required: false,
			default () {
				return {};
			}
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
	}
};
</script>

<style scoped>

</style>
