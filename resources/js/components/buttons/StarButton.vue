<template>
	<div class="col">
		<button
			@click="starThisAd"
			style="width: 7rem"
			class="btn btn-outline-info btn-sm"
		>
			<i class="far fa-star"></i>
		</button>
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
