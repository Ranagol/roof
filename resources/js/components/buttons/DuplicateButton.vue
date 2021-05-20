<template>
	<div class="col">
		<button
			style="width: 7rem"
			class="btn btn-outline-danger btn-sm"
			@click="isDuplicate"
		>
			Duplicate
		</button>
	</div>
</template>

<script>
export default {
	name: 'DuplicateButton',
	props: {
		ad: {
			type: Object,
			required: false,
			default () {
				return {};
			}
		}
	},
	data () {
		return {};
	},
	methods: {
		isDuplicate () {
			this.$emit('isDuplicate', this.ad);
		}
	}
};
</script>

<style scoped>

</style>

