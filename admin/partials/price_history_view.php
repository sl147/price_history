<form method="post" class="form-group" style="margin-top: 60px">
	<?php wp_nonce_field( 'nonce_action_PH','nonce_PH' ); ?>
	<div class="sl147_ph_8">
			<label for="product_id" class="sl147_label"><?php _e( 'Choose a product', 'price_history' )?></label>

			<select name = 'product_id' required>
				<?php
				foreach ($all_products as $value) {            
					echo "<option value = '".$value["ID"]."'>".$value["post_title"] ."</option>";
				}
				?>
			</select>
			<button type="submit" class="sl147_btn" title="<?php _e('click to display price history','price_history')?>">
				<?php _e( 'NEXT', 'price_history' )?>
			</button>
	</div>
</form>

<?php if($delete_err == 1) :?>
	<div class="sl147_ph_8 sl147_notice notice inline notice-alt notice-error">
		<?php _e( 'an error occurred while deleting the price', 'price_history' )?>
	</div>
<?php elseif($delete_err == 2) :?>
	<div class="sl147_ph_8 sl147_notice notice inline notice-alt notice-success is-dismissible">
		<?php _e( 'Price', 'price_history' )?><?php echo " " ?><span><?php echo number_format( $price, 2 )?></span><?php _e( 'correctly removed for the product', 'price_history' )?><br><?php echo sanitize_text_field($product_name) ?>
	</div>
<?php endif;?>

<?php if($this->product_selected) :?>
	<?php if(empty($this->arr_price)) :?>
		<div class="sl147_ph_8 sl147_notice notice inline notice-alt notice-info is-dismissible">
			<?php _e( 'No price history for', 'price_history' )?><br>
			<?php echo sanitize_text_field($this->product_name) ?>
		</div>
	<?php else:?>
		<h3 class="sl147_ph_8" style="margin-top: 30px;">
			<?php echo ($this->product_ID == 1) ? _e( 'Change history for all products', 'price_history' ) :  _e( 'Product price change history for', 'price_history' ) . " " . $this->product_name?>			
		</h3>

		<div class="sl147_ph_10">
				<table class="sl147_table">
					<thead>
						<tr>
							<?php for ($i = 0; $i < count($this->arr_th); $i++) :?>
								<th><?php echo $this->arr_th[$i]?></th>
							<?php endfor;?>
						</tr>						
					</thead>
					<tbody>
						<?php foreach ($this->arr_price as $value): ?>
							<tr 
								<?php if ($value['category'] == $this->sl147_category) :?>
									style="font-size: <?php echo $font_size ?>; color:<?php echo $option_color?>;"
								<?php endif; ?>
							>
								<?php for ($i = 0; $i < count($this->arr_td); $i++) :?>
									<td style="text-align: <?php echo $this->arr_td_style[$i]?>">
										<?php echo $value[ $this->arr_td[$i]] ?>
									</td>
								<?php endfor;?>
							
								<td class="sl147_tr sl147_btn_delete">
 									<form action="" method="post">
										<?php wp_nonce_field( 'nonce_delete_action','nonce_field_delete' ); ?>
										<input type="hidden" name='product_id_del' value="<?php echo $this->product_ID?>">
										<input type="hidden" name='product_name' value="<?php echo $value['product_name']?>">
										<input type="hidden" name='product_price' value="<?php echo $value['product_price']?>">
										<input type="hidden" name='id_post' value="<?php echo $value['ID']?>">
										<button title="<?php echo $delete_button ?>" type="submit" class="sl147_btn" onclick="return confirm('Usunąć. Naciśnij OK, aby usunąć i Cancel aby zatrzymać.')">
											<i class="dashicons dashicons-trash"></i>
										</button>
									</form> 
								</td>								
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
		</div>
	<?php endif;?>
<?php endif;?>