<?php
/**
 * The file controls the shortcode that is added to the Visual Editor.
 *
 *  
 * @package Bean Plugins
 * @subpackage PricingTables
 * @author ThemeBeans
 * @since PricingTables 1.0
 */
 
 
 
 
/*===================================================================*/
/*  TEXT WIDGET SHORTCODE FILTERS
/*===================================================================*/
add_filter('widget_text', 'shortcode_unautop', 10);
add_filter('widget_text', 'do_shortcode', 10);




/*===================================================================*/
/*  PRICING TABLE SHORTCODE
/*===================================================================*/
function bean_pricing_table( $atts, $content = null ) {
	global $shortcode_pricing_table;
	extract(shortcode_atts(array(
		'columns' => '3'
    ), $atts));

	switch ($columns) {
		case '1':
			$columnsClass = 'one-column-table';
			$widthClass = 'five columns mobile-four centered';
			break;
		case '2':
			$columnsClass = 'two-column-table';
			$widthClass = 'six columns mobile-four';
			break;
		case '3':
			$columnsClass = 'three-column-table';
			$widthClass = 'four columns mobile-two';
			break;
		case '4':
			$columnsClass = 'four-column-table';
			break;
	}

	do_shortcode($content);

	$columnContent = '';

	if (is_array($shortcode_pricing_table)) {

		for ($i = 0; $i < count($shortcode_pricing_table); $i++) {


			$colClass = 'pricing-column'; $n = $i + 1;
			$columnContent .= '<div class="'.$colClass.' '.$widthClass.'">';
			$columnContent .= str_replace(array("\r\n", "\n", "\r"), array("", "", ""), $shortcode_pricing_table[$i]['content']); str_replace('', '', $shortcode_pricing_table[$i]['content']);
			$columnContent .= '</div>';
		}

		$finished_table = '<br><div class="bean-pricing-table '.$columnsClass.'">'.$columnContent.'</div>';
	}
	$shortcode_pricing_table = '';

	return $finished_table;

}
add_shortcode('pricing_table', 'bean_pricing_table');




/*===================================================================*/
/*  SINGLE COLUMN
/*===================================================================*/
function shortcode_pricing_column( $atts, $content = null ) {
	global $shortcode_pricing_table;
	extract(shortcode_atts(array(
    ), $atts));

	if ( isset( $highlight ) ) {
		$highlight = strtolower($highlight);
	}
	$column['content'] = do_shortcode($content);
	$shortcode_pricing_table[] = $column;


}
add_shortcode('pricing_column', 'shortcode_pricing_column');




/*===================================================================*/
/*  PRICE INFO
/*===================================================================*/
function bean_shortcode_price_info( $atts, $content = null ) {
	global $shortcode_pricing_table;
	extract(shortcode_atts(array(
		'title' => '',
		'url' => '',
		'highlighted' => '',
		'cost' => ''
    ), $atts));

	$price_info = '';
	
	if ($highlighted) $price_info = '<div class="pricing-highlighted">'.$highlighted.'</div>';
	if ($url) $price_info .= '<a href="'.$url.'">';
		$price_info .= '<div class="table-mast">';
			if ($title) $price_info .= '<h5 class="title">'.$title.'</h5>';
			if ($cost) $price_info .= '<h6 class="price">'. $cost .'</h6>';
			if ($content) $price_info .= '<div class="details"><p>'. do_shortcode($content) .'</p></div>';
		$price_info .= '</div>';
	if ($url) $price_info .= '</a>';

	return $price_info;

}
add_shortcode('price_info', 'bean_shortcode_price_info');
?>