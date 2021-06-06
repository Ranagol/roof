<template>
	<div class="col">
		<el-tooltip class="item" effect="light" content="Star this ad" placement="bottom">
			<button
				@click="starThisAd"
				style="width: 7rem"
				class="btn btn-outline-info btn-sm"
			>
				<i class="far fa-star"></i>
			</button>
		</el-tooltip>
	</div>
</template>

<script>
export default {
	name: 'StarButton',
	methods: {
		starThisAd () {
			this.$emit('clicked');
		}
	}
};
</script>

<style scoped>

</style>
