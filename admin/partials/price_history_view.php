<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

<form method="post" class="form-group" style="margin-top: 60px">
	<?php wp_nonce_field( 'nonce_action_PH','nonce_PH' ); ?>
	<div class="row">
		<div class="col-lg-1 col-md-1"></div>
		<div class="col-lg-8 col-md-8">
			<label for="product_id" class="col-form-label"><?php _e( 'Choose a product', 'price_history' )?></label>

			<select name = 'product_id' required>
				<?php
				foreach ($all_products as $value) {            
					echo "<option value = '".$value["ID"]."'>".$value["post_title"] ."</option>";
				}
				?>
			</select>
			<button type="submit" class="btn btn-primary btn-sm sl147_btn">
				<?php _e( 'NEXT', 'price_history' )?>
			</button>
		</div>
	</div>
</form>

<?php if($delete_err == 1) :?>
	<div class="row">
		<div class="col-lg-1 col-md-1"></div>
		<div class="col-lg-8 col-md-8 notice inline notice-alt notice-error">
			<h6 class="text-center"><?php _e( 'an error occurred while deleting the price', 'price_history' )?></h6>
		</div>
	</div>
<?php elseif($delete_err == 2) :?>
	<div class="row">
		<div class="col-lg-1 col-md-1"></div>
		<div class="col-lg-8 col-md-8 notice inline notice-alt notice-success is-dismissible">
			<h6 class="text-center">
				<?php _e( 'Price', 'price_history' )?><?php echo " " ?><span style="font-size: 24px;color: red;"><?php echo number_format( $price, 2 ) . " "?></span><?php _e( 'correctly removed for the product', 'price_history' )?><br><?php echo $product_name?>
			</h6>
		</div>
	</div>
<?php endif;?>

<?php if($this->product_selected) :?>
	<?php if(empty($this->arr_price)) :?>
		<div class="row">
			<div class="col-lg-1 col-md-1"></div>
			<div class="col-lg-8 col-md-8 notice inline notice-alt notice-info">
				<h6 class="text-center">
					<?php _e( 'No price history for', 'price_history' )?><br>
					<?php echo $this->product_name?>
				</h6>
			</div>
		</div>

	<?php else:?>
		<h3 class="text-center" style="margin-top: 30px;">
			<?php echo ($this->product_ID == 1) ? _e( 'Change history for all products', 'price_history' ) :  _e( 'Product price change history for', 'price_history' ) . " " . $this->product_name?>
			
		</h3>

		<div class="row">
			<div class="col-lg-1 col-md-1"></div>
			<div class="col-lg-10 col-md-10">
				<table class="table  table-bordered table-hover">
					<thead>
						<?php for ($i = 0; $i < count($this->arr_th); $i++) :?>
							<th style="font-size: 14px;" class="text-center"><?php $this->sl147_PH_display_text( $this->arr_th[$i])?></th>
						<?php endfor;?>	
					</thead>
					<tbody>
						<?php foreach ($this->arr_price as $value): ?>
							<tr>
								<?php for ($i = 0; $i < count($this->arr_td); $i++) :?>
									<td style="font-size: <?php echo ($value['cat_select']) ? $font_size : "14px" ?>;; color:<?php echo ($value['cat_select']) ? $val : "" ?>;" class="<?php echo $this->arr_class[$i]?>">
										<?php echo $value[ $this->arr_td[$i]] ?>
									</td>
								<?php endfor;?>
								<td class="text-center sl147_btn_delete">
 									<form action="" method="post">
										<?php wp_nonce_field( 'nonce_delete_action','nonce_field_delete' ); ?>
										<input type="hidden" name='product_id_del' value="<?php echo $this->product_ID?>">
										<input type="hidden" name='product_name' value="<?php echo $value['product_name']?>">
										<input type="hidden" name='product_price' value="<?php echo $value['product_price']?>">
										<input type="hidden" name='id_post' value="<?php echo $value['ID']?>">
										<button type="submit" class="btn btn-xs" onclick="return confirm('Usunąć. Naciśnij OK, aby usunąć i Cancel aby zatrzymać.')">
											<i class="dashicons dashicons-trash"></i>
										</button>
									</form> 
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			<?php endif;?>
		<?php endif;?>
	</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>