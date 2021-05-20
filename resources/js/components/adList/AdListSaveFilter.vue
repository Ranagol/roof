<template>
	<div v-loading="loading">

		<!--        FILTER NAME INPUT FIELD-->
		<div v-if="show">
			<input
				v-model="filterName"
				placeholder="Filter name"
				type="text"
				class="form-control mt-3"
			>

			<!--    VALIDATION -->
			<div
				v-if="errors.length > 0"
				class="alert alert-danger"
			>
				<div>Please correct the following error(s):</div>
				<ul>
					<li v-for="error in errors">
						{{ error }}
					</li>
				</ul>
			</div>

			<!--        FINAL SAVE BUTTON "OK"-->
			<button
				class="theme-btn btn-outline-success w-100 hide_menu mt-3"
				@click="save"
			>
				OK
			</button>

			<!--                    CANCEL BUTTON -->
			<button
				class="theme-btn btn-outline-warning w-100 hide_menu mt-3"
				@click="show = false"
			>
				Cancel
			</button>
		</div>

		<!--    SAVE BUTTON, DISPLAYS THE SAVE RELATED ELEMENTS -->
		<button
			v-if="!show"
			class="theme-btn w-100 btn-outline-info mt-3"
			@click="show = true"
		>
			Save your ad list
		</button>
	</div>

</template>

<script>
export default {
	name: 'AdListSaveFilter',
	props: {
		savedFilter: {
			required: false,
			// type: Array,
			default () {
				return {};
			}
		}
	},
	data () {
		return {
			show: false,
			filterName: '',
			loading: false,
			errors: []
		};
	},
	watch: {
		show () {
			this.errors = [];
		}
	},
	methods: {
		save () {
			this.validateFilterName();
			if (this.errors.length === 0) {
				this.loading = true;
				window.axios.post(
					'/filters/create',
					{
						name: this.filterName,
						...this.savedFilter
					}
				)
					  .then(() => {
						  this.loading = false;
						  this.$notify({
							  type: 'success',
							  title: 'Ad list saved',
							  message: `Ad list with name ${this.filterName} has been saved.`
						  });
						  this.show = false;
					  })
					  .catch((error) => {
						  this.loading = false;
						  const collectAllErrors = [];
						  _.forEach(error.response.data.errors, (key1) => {
							  _.forEach(key1, (value2) => {
								  collectAllErrors.push(value2);
							  });
						  });
						  this.errors = collectAllErrors;
					  });
			}
		},

		validateFilterName () {
			this.errors = [];
			if (this.filterName.length < 3 || this.filterName.length > 30) {
				this.errors.push('The ad list name must be between 3 and 30 characters.');
			}

		}
	}
};
</script>

<style scoped>

</style>
