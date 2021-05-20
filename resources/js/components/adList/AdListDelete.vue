<template>
    <div>
        <button
            type="submit"
            class="theme-btn w-100 btn-outline-danger mt-3"
            @click="deleteFilter"
		>
			Delete ad list
		</button>
    </div>
</template>

<script>
export default {
	name: 'AdListDelete',
	props: {
		savedFilterId: {
			required: false,
			type: String,
			default () {
				return null;
			}
		}
	},
	methods: {
		deleteFilter () {
			this.$confirm('This will permanently delete the file. Continue?', 'Warning', {
				confirmButtonText: 'OK',
				cancelButtonText: 'Cancel',
				type: 'warning'
			}).then(() => {
				window.axios.delete(`/ad-list/${this.savedFilterId}`).then(() => {
					window.location.href = '/';
				});
				this.$message({
					type: 'success',
					message: 'Delete completed'
				});
			})
				.catch(() => {
					this.$message({
						type: 'info',
						message: 'Delete canceled'
					});
				});
		}
	}
};

</script>

<style scoped>

</style>
