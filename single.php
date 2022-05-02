<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package gant
 */

defined( 'ABSPATH' ) || exit;


get_header();


$group_values = get_field('dynamic_product_related');
//print_r($group_values);
//print_r($product);

//echo get_field('color',615);
?>
<main id="primary" class="site-main">
	<?php 
		while ( have_posts() ) :
			the_post();
			global $product;
			//$parent_cat_id = wdo_get_product_top_level_category($product->ID);
			//$term = get_term_by( 'id', $parent_cat_id, 'product_cat' );
			//$parent_term_name = $term->name;
			$sub_cat_name = get_field('sub_cat');
			if(!empty($sub_cat_name)):
				$term = get_term_by('name',$sub_cat_name,'product_cat'); 
				$parent_tag_id =  $term->term_id;
				$parent_term_name = $term->name;
				$parent_term_slug = get_term_link ($parent_tag_id, 'product_cat');
			endif;
			//$attimages = get_attached_media('image', $product->ID);
            $attachment_ids = $product->get_gallery_image_ids();

			/**
			 * Hook: woocommerce_before_single_product.
			 *
			 * @hooked woocommerce_output_all_notices - 10
			 */
			do_action( 'woocommerce_before_single_product' ); ?>
			<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
				<div class="single_product_wrapper">
					<div class="top_details">
						<div class="r_side">
                            <?php if(isset($parent_tag_id)): ?>
								<nav class="breadcrumb">
									<div class="arrow_btn">
										<a href="<?php  echo  $parent_term_slug; ?>" title="<?php echo $parent_term_name; ?>" class="button-secondary">
											<span class="btn_icon">
												<svg focusable="false" class="c-icon icon--arrow-button" viewBox="0 0 42 10" width="15px" height="15px">
													<path fill-rule="evenodd" clip-rule="evenodd" d="M40.0829 5.5H0V4.5H40.0829L36.9364 1.35359L37.6436 0.646484L41.9971 5.00004L37.6436 9.35359L36.9364 8.64649L40.0829 5.5Z" fill="currentColor"></path>
												</svg>
											</span>
											<span class="button_label"> <?php esc_html_e( $sub_cat_name, 'gant' ); ?></span>
										</a>
									</div>
									
								</nav>
							<?php endif; ?>
							<?php if(wp_is_mobile()): ?>
								
								<div class="product_detail_badge">
									<?php if(has_term( '29', 'product_tag' )){ 
										$term_data = get_term_by('id', '29', 'product_tag');
									?>
									<a class="tag_item" href="#sustainable-choice" title="<?php echo $term_data->name ?>">
										<?php echo $term_data->name ?>
									</a>
									<?php }?>
								</div>
						
								<h1 class="product_title_mobile entry-title"><?php the_title(); ?></h1>
							<?php  endif;?>
							<div class="slider_gallery_wrapper">
								<div class="gallery-thumbnail">
									<a href="<?php echo get_permalink( $product->ID ).'#zoom_1'?>">
										<img src="<?php echo wp_get_attachment_url( $product->get_image_id() ); ?>" data-slide="1" alt="">
									</a>
								</div> 
								<?php 
								$attimages_key = 2;
								foreach ($attachment_ids as $image_id){ 
									$attach_url = wp_get_attachment_url( $image_id);
									if(strpos( $attach_url, 'thumb-fv-1') == false){?>
										<div class="gallery-thumbnail">
											<a href="<?php echo get_permalink( $product->ID ).'#zoom_'.$attimages_key;?>">
												<img src="<?php echo  wp_get_attachment_url( $image_id ); ?>" data-slide="<?php echo $attimages_key;?>" alt="">
											</a>
										</div>
									<?php $attimages_key++;
									}
								} ?>
							</div>
						</div>
						<div class="l_side">
							<div class="product_detail_side_bar ">
								<?php if(!wp_is_mobile()): ?>
									<div class="product_detail_badge">
										<?php if(has_term( '29', 'product_tag' )){ 
											$term_data = get_term_by('id', '29', 'product_tag');
										?>
										<a class="tag_item" href="#sustainable-choice" title="<?php echo $term_data->name ?>">
											<?php echo $term_data->name ?>
										</a>
										<?php }?>
									</div>
								<?php endif; ?>
								<div class="product_detail_body">
									<div class="product_detail_description">
					
										<?php do_action( 'woocommerce_single_product_summary' ); ?>
								
									</div>
								</div>
								<?php if(false): ?>
									<div class="product_detail_side_bar ">
										<div class="product_detail_badge">
											<?php if(has_term( '29', 'product_tag' )){ 
												$term_data = get_term_by('id', '29', 'product_tag');
											?>
											<a class="tag_item" href="#sustainable-choice" title="<?php echo $term_data->name ?>">
												<?php echo $term_data->name ?>
											</a>
											<?php }?>
										</div>
										<div class="product_detail_body">
											<h1><?php the_title(); ?></h1>
											<div class="product_detail_description">
												<ul>
													<li></li>
												</ul>
											</div>
											<a class="product_detail_more_details" href="#product-description-accordion">
												<?php esc_html_e( 'מידע מפורט', 'gant' ); ?>
												<svg focusable="false" class="c-icon icon--arrow-short" viewBox="0 0 11 12" width="10" height="10">
													<path d="m5 11 5-5-5-5M10 6H0" stroke="currentColor" fill="none"></path>
												</svg>
											</a>
											<div class="product_attribute_wrapper">
												<div class="product_attribute product_attribute_color">
													<h3 class="attribute_title">
														<span><?php esc_html_e( 'צבע: ', 'gant' ); ?></span>
														<span><?php echo get_field('color'); ?></span>
													</h3>
													<?php
													if(!empty($group_values)) {?>
														<div class="attributes_values_wrapper">
															<?php foreach($group_values as $product_id){
																$product = wc_get_product($product_id);
																$slug = $product->get_slug();
																$title = $product->get_name();
																$sku = $product->get_sku();
																?>
																<a href="<?php echo $slug?>" class="checkbox_color_wrapper" >
																	<p class="row_radio_wrapper">
																		<span class="radio_wrapper">
																			<input class="radio_price" id="radio_color_<?php echo $sku;?>" type="radio" name="checkbox_price" value="<?php echo $range ?>">	
																			<label for="radio_color_<?php echo $sku;?>"><?php echo $val ?></label>
																		</span>
																	</p>
																</a>
															<?php } ?>
														</div>
													<?php }
													
													
													?>
													<div class="pdt_related_color">

													</div>
												</div>
												<div class="product_attribute product_attribute_color"></div>
											</div>
											<?php  do_action( 'woocommerce_' . $product->get_type() . '_add_to_cart' ); ?>
										</div>

									</div>
								<?php endif; ?>
							
							</div>
						</div>
					</div>
					<div class="accordion_wrapper">
						<div class="accordion">
							<div id="product-description-accordion" class="section">
								<a class="section-title" href="#accordion-full-desc" title="<?php echo get_field('title_full_desc','option'); ?>"><?php echo get_field('title_full_desc','option'); ?></a>
								<div id="accordion-full-desc" class="section-content">
									<?php if(wp_is_mobile()):?>
										<div class="excerpt_mobile">
										<?php echo apply_filters( 'the_excerpt', $product->post->post_excerpt ); ?>
										</div>
										
									<?php endif;?>
									<?php echo get_field('full_desc',$product->ID); ?>
									<?php if(has_term( '29', 'product_tag' )){ ?>
										<div id="sustainable-choice">
											<?php echo  get_field('full_desc_substainaibility','option');?>
										</div>
									<?php } ?>
								</div>
							</div>
							<div class="section">
								<a class="section-title " href="#accordion-instruction-care" title="<?php echo get_field('title_care_instructions','option'); ?>"><?php echo get_field('title_care_instructions','option'); ?></a>
								<div id="accordion-instruction-care" class="section-content">
									<p class=""><?php echo get_field('instruction_care_desc'); ?></p>
									<?php 
									$related_icon_id  = get_field('related_care_sku',$product->ID);?>
								
										<div class="c-icon-list">
											<?php if( have_rows('instructions', 'option') ):
												// Loop through rows.
												while( have_rows('instructions', 'option') ) : the_row();
											
													// Load sub field value.
													$care_id = get_sub_field('icon_id');
												
													if(strpos($related_icon_id, $care_id) !== false){
														$care_icon = get_sub_field('care_icon');
														$care_desc = get_sub_field('icon_desc');?>
														<div class="icon_item tooltip">
															<img src="<?php echo $care_icon; ?>" alt=""/>
															<div class="tooltip_txt">
																<?php echo $care_desc; ?>
															</div>
														</div>
													<?php }
												endwhile;
											endif;?>
										</div>
									<?php?>
								</div>
							</div>	
								
							<div class="section">
								<a class="section-title" href="#accordion-instruction-delivery" title="<?php echo get_field('title_pdt_delivery','option'); ?>"><?php echo get_field('title_pdt_delivery','option'); ?></a>
								<div id="accordion-instruction-delivery" class="section-content">
									<p class=""><?php echo get_field('desc_pdt_delivery','option'); ?></p>
								</div>
							</div>		
						</div>
					</div>
					<div class="pdts_related_wrapper">
						<?php 
						$related_categories = get_field('related_pdt',$term->taxonomy . '_' . $term->term_id);
						//print_r($related_categories );
						$categorie_title = $related_categories['title'];
						$radio_selected = $related_categories['select_cat_or_pdt'];
						?>
						<section class="slider_section section_wrap">
							<?php if(!empty($radio_selected)): ?>
								<div class="section_header">
									<h3><?php echo $categorie_title; ?></h3>
								</div>
							<?php endif; ?>
							<?php if($radio_selected == 'select_pdts'):
								$featured_pdts =  $related_categories['select_products'];
							else:
								$selected_cat =  $related_categories['select_category'];
								
								$term = get_term( $selected_cat, 'product_cat' );
								$slug = $term->slug;
		
								$args_cat = array(
									'post_type' =>  array('product', 'product_variation'),
									'post_status' => array('publish'),
									'product_cat' => $slug,
									'posts_per_page' => 10,
									// 'meta_query' => array(
									// 	array(
									// 		'key' => '_stock_status',
									// 		'value' => 'instock',
									// 		'compare' => '=',
									// 	)
									// )
								);
								$featured_pdts = get_posts( $args_cat );
							endif;
							if( $featured_pdts ):?>
								<div class="slider_pdts slider_wrap">
									<?php 
									foreach( $featured_pdts as $product ):
										setup_postdata( $product );
										get_template_part('page-templates/box-product'); 
										wp_reset_postdata(); 
									endforeach;
									?>
								</div>
							<?php endif; ?>
						</section>		
					</div>
				</div>
			</div>
		<?php endwhile; // End of the loop.
		?>
		<?//php woocommerce_mini_cart(); ?>
		<div class="modal" id="modal_mini_cart">
			<div class="modal_container_minicart">
				<header class="section_header">
					<div class="title_wrapper">
						<svg focusable="false" class="c-icon icon--check" viewBox="0 0 16 16" width="15px" height="15px"> <path fill="currentColor" fill-rule="evenodd" d="M14.92 2.273L7.082 14.29 1.646 8.854l.707-.707 4.564 4.564L14.08 1.727l.838.546z"></path> </svg>
						<h3><?php echo __( 'נוסף לעגלה', 'gant' ) ?></h3>
					</div>
					<button type="button" tabindex="0" aria-label="סגור" class="close">
						<svg focusable="false" class="c-icon icon--close" viewBox="0 0 26 27" width="12" height="12">
							<path fill-rule="evenodd" clip-rule="evenodd" d="M13 14.348l11.445 11.685L26 24.445 14.555 12.761 25.5 1.588 23.944 0 13 11.173 2.056 0 .501 1.588 11.445 12.76 0 24.444l1.555 1.588L13 14.348z" fill="currentColor"></path>
						</svg>
					</button>
				</header>
				<div class="modal_content" role="dialog">
					<div class="widget_shopping_cart_content">
					<?php woocommerce_mini_cart(); ?>
					</div>
					
				</div>
			</div>
			<div class="modal_bg"></div>
		</div>
		<!-- The Modal/Lightbox -->
	<div class="product_detail_image_zoom">
		<div id="gallery_modal" class="">
			<div class="gallery_content">
				<div class="product_zoom_thumbnails">
					<div class="slider_wrap slider_gallery">
                        <div class="product_zoom_thumbnail_item">
                            <a href="<?php echo get_permalink( $product->ID ).'#zoom_1';?>">
                                <img class="thumbnail_img" src="<?php echo wp_get_attachment_url($product->get_image_id())?>"  alt="">
                            </a>            
                                    
                        </div> 
                        <?php 
                        $key_thumb = 2;
                        foreach ($attachment_ids as $image_id){ 
							$attach_url = wp_get_attachment_url( $image_id);
							//dont display thumb image
							if(strpos( $attach_url, 'thumb-fv-1') == false){?>
								<div class="product_zoom_thumbnail_item">
									<a href="<?php echo get_permalink( $product->ID ).'#zoom_'.$key_thumb;?>">
										<img class="thumbnail_img" src="<?php echo  wp_get_attachment_url( $image_id ); ?>" alt="">
									</a>
								</div>
                        	<?php $key_thumb++;
							}
						} ?>
					</div>
				</div>
				<button type="button" class="modal_button_close close" tabindex="0" aria-label="סגור modalbox">
					<svg focusable="false" class="c-icon icon--close" viewBox="0 0 26 27" width="20px" height="20px">
					<path fill-rule="evenodd" clip-rule="evenodd" d="M13 14.348l11.445 11.685L26 24.445 14.555 12.761 25.5 1.588 23.944 0 13 11.173 2.056 0 .501 1.588 11.445 12.76 0 24.444l1.555 1.588L13 14.348z" fill="currentColor"></path>
					</svg>
				</button>
			
				<div class="modal-content">
                    <div class="product_zoom_image_wrapper" id="<?php echo 'zoom_1';?>" data-slide="1">
                        <img src="<?php echo wp_get_attachment_url($product->get_image_id())?>" alt="">
                    </div>
					<?php $key_img = 2; foreach ($attachment_ids as $image_id) { 
						$attach_url = wp_get_attachment_url( $image_id);
						//dont display thumb image
						if(strpos( $attach_url, 'thumb-fv-1') == false){?>
							<div class="product_zoom_image_wrapper" id="<?php echo 'zoom_'.$key_img;?>" data-slide="<?php echo $key_img?>">
								<img src="<?php echo wp_get_attachment_url($image_id)?>" alt="">
							</div>
						<?php $key_img++;
						}
					} ?>													
				</div>
		
			</div>
		</div>
	</div>
	
</main>


<?php
get_footer();
