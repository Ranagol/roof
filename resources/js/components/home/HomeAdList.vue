<template>
	<div
		class="m-4 p-4 image-setup"
		style="height: 200px; width: 300px;"
		:style="{'background-image': `url(/images/city_images/${ number }.jpeg)`}"
	>
		<div class="d-flex flex-row justify-content-between">
			<el-tooltip class="item" effect="light" content="Number of your new ads in this list" placement="top">
				<div class="countBadge">{{ filterCount }}</div>
			</el-tooltip>

			<el-tooltip class="item" effect="light" content="Delete this ad list" placement="top">
				<i
					id="delete"
					class="far fa-trash-alt mt-2 mr-2 mb-1 pb-2"
					@click="deleteList"
				></i>
			</el-tooltip>
		</div>
		<div>
			<!-- <a :href="`ad-list/non-processed/${ filter.id }`"> -->
			<a :href="this.currentUrl">
				<div class="cat-content">
					<h6 class="text-white mb-4">{{ filter.name }}</h6>
				</div>
			</a>
		</div>
	</div>
</template>

<script>
export default {
	name: 'HomeAdList',
	props: {
		filter: {
			type: Object,
			required: false,
			default () {
				return {};
			}
		},
		filterCount: {
			type: Number,
			required: false,
			default () {
				return 0;
			}
		},
		number: {
			required: false,
			type: Number,
			default: 0
		}
	},
	methods: {
		deleteList () {
			this.$confirm('This will permanently delete the file. Continue?', 'Warning', {
				confirmButtonText: 'OK',
				cancelButtonText: 'Cancel',
				type: 'warning'
			})
				.then(() => {
					window.axios.delete(
						`/ad-list/${this.filter.id}`
					).then(() => {
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
		},
		cleanFilter(temporaryObject){
			delete temporaryObject.created_at;
			delete temporaryObject.id;
			delete temporaryObject.updated_at;
			delete temporaryObject.user_id;

			return temporaryObject;
		}
	},
	computed: {
		currentUrl() {
			let url = '/ad-list/non-processed?';

			const filterCopy = {...this.filter};
			const filterCopyCleaned = this.cleanFilter(filterCopy);
			console.log('filter object from HomeAdListVue:', filterCopyCleaned);

			_.forEach(filterCopyCleaned, (filterValue, filterKey) => {
				// if (_.isEmpty(filterValue) === false) {
				if (filterValue !== null) {
					url += `${filterKey}=${filterValue}&`;
				}
			});

			return url;
		}
	},
};
</script>

<style scoped>
#delete {
	color: white;
	z-index: 4;
}

.image-setup {
	background-size: cover;
}

.countBadge {
	font-weight: 600;
	background-color: #210F88;
	color: white;
	-webkit-border-radius: 30px;
	-moz-border-radius: 30px;
	border-radius: 30px;
	font-size: 14px;
	width: 50px;
	height: 25px;
	padding-bottom: 20px;
	padding-left: 15px;
}
</style>
