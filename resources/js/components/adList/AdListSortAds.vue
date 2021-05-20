<template>
	<div class="col-lg-12">
		<div class="filter-bar d-flex  justify-content-between align-items-center margin-bottom-30px">
			<p class="result-text font-weight-medium">
				Showing page
				{{ paginationData.current_page }}
				of {{ paginationData.last_page }},
				of {{ paginationData.total }} selected ads.
			</p>

			<!--			Element UI sorter-->
			<el-select
				v-model="selectedSortOrder"
				placeholder="Sort ads"
				class="ad-list-grey-text"
				@change="sortAds"
			>
				<el-option
					v-for="item in options"
					:key="item.value"
					:label="item.label"
					:value="item.value"
				/>
			</el-select>

		</div>
	</div>
</template>

<script>
export default {
	name: 'AdListSortAds',
	props: {
		value: {
			required: false,
			type: String,
			default: ''
		},
		paginationData: {
			type: Object,
			required: false,
			default () {
				return {};
			}
		}
	},
	data () {
		return {
			options: [
				{
					value: 'id.asc',
					label: 'Sort by default'
				}, {
					value: 'price.asc',
					label: 'Price: low to high'
				}, {
					value: 'price.desc',
					label: 'Price: high to low'
				}, {
					value: 'price_by_surface.asc',
					label: 'Price-by-surface: low to high'
				}, {
					value: 'price_by_surface.desc',
					label: 'Price-by-surface: high to low'
				}
			],
			selectedSortOrder: this.value
		};
	},
	methods: {
		sortAds () {
			this.$emit('input', this.selectedSortOrder);
			this.$emit('change', this.selectedSortOrder);
		}
	}
};
</script>

<style scoped>

</style>
