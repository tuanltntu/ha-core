<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://tuanltntu.com
 * @since      1.0.0
 *
 * @package    Ha_Core
 * @subpackage Ha_Core/admin/partials
 */
Ha_Helpers::get_header(); ?>
<a-row :gutter="16">
	<a-col :sm="{ span: 12, offset: 6 }" :md="{ span: 12, offset: 6 }" :xs="{ span: 24}">
        <div class="mar-bottom-20">
		    <a-input-search class="card-item" v-model="params.purchase_code" placeholder="Purchase code" @search="find" enter-button="<?php _e('Accept', 'ha-core'); ?>"></a-input-search>
        </div>
	</a-col>
	<a-col :sm="{ span: 6 }" :md="{ span: 8}" :xs="{ span: 24}" v-for="item in HA.data.items">
		<a-card hoverable>
			<img :alt="item.name" :src="item.image" slot="cover" />
			<a-card-meta :title="item.name">
				<template slot="description">
					{{ item.description }}
				</template>
				<template class="ant-card-actions" slot="actions">
					<a-button type="link"><a-icon type="eye" /> <?php _e('View', 'ha-core'); ?></a-button>
					<a-button type="link" ><a-icon type="download" /> <?php _e('Setup', 'ha-core'); ?></a-button>
				</template>
			</a-card-meta>
		</a-card>
	</a-col>
</a-row>
<?php Ha_Helpers::get_footer(); ?>		