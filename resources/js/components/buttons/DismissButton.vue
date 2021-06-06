<template>
	<div class="col">
		<el-tooltip class="item" effect="light" content="Dismiss this ad" placement="bottom">
			<button
				style="width: 7rem"
				class="btn btn-outline-info btn-sm"
				@click="dismissAd"
			>
				<i class="far fa-trash-alt" />
			</button>
		</el-tooltip>
	</div>
</template>

<script>
export default {
	name: 'DismissButton',
	methods: {
		dismissAd () {
			this.$emit('dismiss');
		}
	}
};
</script>

<style scoped>

</style>


