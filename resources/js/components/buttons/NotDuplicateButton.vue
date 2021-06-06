<template>
	<div class="col">
		<el-tooltip class="item" effect="light" content="Removes ad from duplicates." placement="bottom">
			<button
				class="btn btn-outline-success btn-sm"
				style="width: 7rem"
				@click="notDuplicate"
			>
				Not duplicate
			</button>
		</el-tooltip>
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


