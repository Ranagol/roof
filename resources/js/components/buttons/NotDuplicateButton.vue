<template>
	<div class="col">
		<button
			class="btn btn-outline-success btn-sm"
			style="width: 7rem"
			@click="notDuplicate"
		>
			Not duplicate
		</button>
	</div>
</template>

<script>
export default {
	name: 'NotDuplicateButton',
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
		notDuplicate () {
			this.$emit('notDuplicate', this.ad);
		}
	}
};
</script>

<style scoped>

</style>


