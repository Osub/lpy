<?php 
/**
 * The template for displaying Searchform.
 * @package WordPress
 * @subpackage Javin
 */
?>
	<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<input type="text" class="search_text f_l" name="s" id="search_text" value="<?php esc_attr_e( '输入搜索内容……' ); ?>" onfocus="if(this.value=='<?php esc_attr_e( '输入搜索内容……' ); ?>'){this.value='';}"  onblur="if(this.value==''){this.value='<?php esc_attr_e( '输入搜索内容……' ); ?>';}" />
		<input type="submit" class="search_bon f_l" name="submit" id="search_bon" value="<?php esc_attr_e( 'Search' ); ?>" />
	</form>