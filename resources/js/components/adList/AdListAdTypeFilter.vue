<template>
	<div>
		<div
			class="d-flex flex-row transparent-background justify-content-around align-items-center"
		>
			<a :href="`/ad-list/non-processed/${this.savedFilterId ? this.savedFilterId : ''}`" class="mt-2">
				<el-tooltip 
					class="item" 
					effect="light" 
					content="Displays all your non-starred and non-dismissed ads." 
					placement="bottom"
				>
					<p :class="showSelectedClass('non-processed') ? 'selected' : 'not-selected' ">
						<span class="h4">
							<i class="far fa-clock"></i>
							Pending ads
						</span>
					</p>
				</el-tooltip>	
			</a>

			<a :href="`/ad-list/starred/${this.savedFilterId ? this.savedFilterId : ''}`" class="mt-2">
				<el-tooltip 
					class="item" 
					effect="light" 
					content="Displays all your starred ads." 
					placement="bottom"
				>
					<p :class="showSelectedClass('starred') ? 'selected' : 'not-selected' ">
						<span class="h4">
							<i class="far fa-star"></i>
							Starred ads
						</span>
					</p>
				</el-tooltip>
			</a>

			<a :href="`/ad-list/dismissed/${this.savedFilterId ? this.savedFilterId : ''}`" class="mt-2">
				<el-tooltip 
					class="item" 
					effect="light" 
					content="Displays all your dismissed ads." 
					placement="bottom"
				>
					<p :class="showSelectedClass('dismissed') ? 'selected' : 'not-selected' ">
						<span class="h4">
							<i class="far fa-trash-alt" />
							Dismissed ads
						</span>
					</p>
				</el-tooltip>
			</a>

		</div>
	</div>
	
	
</template>

<script>
export default {
	name: 'AdListAdTypeFilter',
	props: {
		savedFilterId: {
			required: false,
			type: String,
			default () {
				return '';
			}
		},
		adTypeForUrl: {
			type: String,
			required: false,
			default () {
				return '';
			}
		}
	},
	data () {
		return {
			tabs: {
				'non-processed': 'Pending ads',
				'starred': 'Starred ads',
				'dismissed': 'Dismissed ads'
			}
		};
	},
	methods: {
		showSelectedClass (adType) {
			return adType === this.adTypeForUrl;
		},
		getHref (adType) {
			return `/ad-list/${adType}/${this.savedFilterId ? this.savedFilterId : ''}`;
		}
	}
};
</script>

<style scoped>

.selected {
	color: yellow;
	font-weight: 1200;
	font-size: large;
	padding-bottom: 10px;
	text-decoration: underline;
}

.selected:hover {
	text-decoration: underline;
}

.not-selected {
	color: white;
	font-weight: 700;
	font-size: large;
	padding-bottom: 10px;
}

.not-selected:hover {
	text-decoration: underline;
}

.transparent-background {
	background-color: rgba(255, 255, 255, 0.1);
}




</style>
