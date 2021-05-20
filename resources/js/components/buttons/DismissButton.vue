<template>
	<div class="col">
		<button
			style="width: 7rem"
			class="btn btn-outline-info btn-sm"
			@click="dismissAd"
		>
			<i class="far fa-trash-alt" />
		</button>
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


