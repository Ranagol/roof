<template>
	<el-pagination
		small
		:total="total"
		:total-page-count="lastPage"
		:current-page="currentPage"
		@current-change="pageChange"
	/>
</template>

<script>
export default {
	name: 'Pagination',
	props: {
		value: {
			required: false,
			type: Number,
			default: 1
		},
		lastPage: {
			required: false,
			type: Number,
			default: 0
		},
		total: {
			required: false,
			type: Number,
			default: 0
		}
	},
	data () {
		return {
			currentPage: this.value
		};
	},
	methods: {
		pageChange (currentPage) {
			this.$emit('input', currentPage);
			this.$emit('current-change', currentPage);
		}
	}
};
</script>

<style scoped>

</style>
