<?php

	$sale_class = str_replace( ' ' , '%20' , $inventory->saleclass );
	$prices = $inventory->prices;
	$use_was_now = $prices->{ 'use_was_now?' };
	$use_price_strike_through = $prices->{ 'use_price_strike_through?' };
	$on_sale = $prices->{ 'on_sale?' };
	$sale_price = isset( $prices->sale_price ) ? $prices->sale_price : NULL;
	$sale_expire = isset( $prices->sale_expire ) ? $prices->sale_expire : NULL;
	$retail_price = $prices->retail_price;
	$default_price_text = $prices->default_price_text;
	$asking_price = $prices->asking_price;
	$vin = $inventory->vin;
	$odometer = empty( $inventory->odometer ) || $inventory->odometer <= 0 ? 'N/A' : $inventory->odometer;
	$stock_number = $inventory->stock_number;
	$exterior_color = empty( $inventory->exterior_color ) ? 'N/A' : $inventory->exterior_color;
	$engine = $inventory->engine;
	$transmission = $inventory->transmission;
	$drivetrain = $inventory->drive_train;
	$dealer_options = $inventory->dealer_options;
	$standard_equipment = $inventory->standard_equipment;
	$year = $inventory->year;
	$make = urldecode( $inventory->make );
	$model = urldecode( $inventory->model_name );
	$trim = urldecode( $inventory->trim );
	$year_make_model = $year . ' ' . $make . ' ' . $model;
	$description = $inventory->description;
	$doors = $inventory->doors;
	$icons = $inventory->icons;
	$fuel_economy = $inventory->fuel_economy;
	$headline = $inventory->headline;
	$body_style = $inventory->body_style;
	$drive_train = $inventory->drive_train;
	$video_url = isset( $inventory->video_url ) ? $inventory->video_url : false;
	$carfax = isset( $inventory->carfax ) ? $inventory->carfax->url : false;
	$contact_information = $inventory->contact_info;
	$greeting = isset( $contact_information->greeting ) ? $contact_information->greeting : NULL;
	$dealer_name = isset( $contact_information->dealer_name ) ? $contact_information->dealer_name : NULL;
	$phone = isset( $contact_information->phone ) ? $contact_information->phone : NULL;

	$primary_price = $sale_price != NULL ? $sale_price : $asking_price;

	$headline = $inventory->headline;
	if(strlen($headline) == 0)
		$headline = $year." ".$make." ".$model;
	$generic_vehicle_title = $year . ' ' . $make . ' ' . $model;

?>
<div class="websitez-container dealertrend-mobile inventory">
	<div class="post">
		<div class="post-wrapper car">
<?php echo !empty( $headline ) ? '<div class="headline"><h2>' . $headline . '</h2></div>' : NULL; ?>
			<div class="images">
				<?php
					if(count($inventory->photos) > 1):
					?>
					<script type="text/javascript">
					var active_image = 1;
					var total_images = "<?php echo count($inventory->photos);?>";
					var car_image_rotator;
					function change_car_image(){
						jQuery(".car-image").each(function(){jQuery(this).hide()});
						if(active_image == total_images){
							jQuery("#car-image-1").show("slow");
							active_image=1;
						}else{
							$("#car-image-"+(active_image+1)).fadeIn('slow', function() {});
							active_image++;
						}
					}
					function change_car_image_previous(){
						change_car_image_stop();
						if(active_image > 2){
							active_image = active_image - 2;
						}else{
							active_image = total_images;
						}
						change_car_image();
						return false;
					}
					function change_car_image_next(){
						change_car_image_stop();
						change_car_image();
						return false;
					}
					function change_car_image_start(){
						car_image_rotator = setInterval('change_car_image()',4000);
						jQuery("#change_car_image_playpause").html("<a href='' onClick='return change_car_image_stop();'><img src=\"<?php echo $this->plugin_information[ 'PluginURL' ] . '/application/views/mobile/websitez/' ?>images/33_24x24.png\" border=\"0\"></a>");
						return false;
					}
					function change_car_image_stop(){
						window.clearInterval(car_image_rotator);
						jQuery("#change_car_image_playpause").html("<a href='' onClick='return change_car_image_start();'><img src=\"<?php echo $this->plugin_information[ 'PluginURL' ] . '/application/views/mobile/websitez/' ?>images/31_24x24.png\" border=\"0\"></a>");
						return false;
					}
					change_car_image_start();
					</script>
					<?php
					endif;
					$i=1;
					foreach( $inventory->photos as $photo ) {
						if($i==1)
							echo '<img class="car-image" id="car-image-'.$i.'" src="' . $photo->small . '"/>';
						else
							echo '<img class="car-image" id="car-image-'.$i.'" src="'.$photo->small .'" style="display: none;"/>';
						$i++;
					}
				?>
				</div>
				<?php
				if(count($inventory->photos) > 1):
				?>
				<div class="images-nav">
					<a href="" onClick="return change_car_image_previous();"><img src="<?php echo $this->plugin_information[ 'PluginURL' ] . '/application/views/mobile/websitez/' ?>images/28_24x24.png" border="0"></a> <span id="change_car_image_playpause"><a href="" onClick="return change_car_image_stop();"><img src="<?php echo $this->plugin_information[ 'PluginURL' ] . '/application/views/mobile/websitez/' ?>images/33_24x24.png" border="0"></a></span> <a href="" onClick="return change_car_image_next();"><img src="<?php echo $this->plugin_information[ 'PluginURL' ] . '/application/views/mobile/websitez/' ?>images/29_24x24.png" border="0"></a>
				</div>
				<?php
				endif;
				?>
			<?php flush(); ?>
			<div class="right-column">
				<div class="details">
					<h3>Vehicle Details</h3>
					<?php
						$ais_incentive = isset( $inventory->ais_incentive->to_s ) ? $inventory->ais_incentive->to_s : NULL;
						$incentive_price = 0;
						if( $ais_incentive != NULL ) {
							preg_match( '/\$\d*(\s)?/' , $ais_incentive , $incentive );
							$incentive_price = isset( $incentive[ 0 ] ) ? str_replace( '$' , NULL, $incentive[ 0 ] ) : 0;
						}
						if( $retail_price > 0 ) {
							echo '<div class="websitez-msrp">MSRP: $' . number_format( $retail_price , 2 , '.' , ',' ) . '</div>';
						}
						if( $on_sale && $sale_price > 0 ) {
							$now_text = 'Price: ';
							if( $use_was_now ) {
								$price_class = ( $use_price_strike_through ) ? 'websitez-strike-through websitez-asking-price' : 'websitez-asking-price';
								if( $incentive_price > 0 ) {
									echo '<div class="' . $price_class . '">Was: $' . number_format( $sale_price , 2 , '.' , ',' ) . '</div>';
								} else {
									echo '<div class="' . $price_class . '">Was: $' . number_format( $asking_price , 2 , '.' , ',' ) . '</div>';
								}
								$now_text = 'Now: ';
							}
							if( $incentive_price > 0 ) {
								echo '<div class="websitez-ais-incentive">Savings: ' . $ais_incentive . '</div>';
								echo '<div class="websitez-sale-price">' . $now_text . '$' . number_format( $sale_price - $incentive_price , 2 , '.' , ',' ) . '</div>';
								if( $sale_expire != NULL ) {
									echo '<div class="websitez-sale-expires">Sale Expires: ' . $sale_expire . '</div>';
								}
							} else {
								if( $ais_incentive != NULL ) {
									echo '<div class="websitez-ais-incentive">Savings: ' . $ais_incentive . '</div>';
								}
								echo '<div class="websitez-sale-price">' . $now_text . '$' . number_format( $sale_price , 2 , '.' , ',' ) . '</div>';
								if( $sale_expire != NULL ) {
									echo '<div class="websitez-sale-expires">Sale Expires: ' . $sale_expire . '</div>';
								}
							}
						} else {
							if( $asking_price > 0 ) {
								if( $incentive_price > 0 ) {
									echo '<div class="websitez-asking-price" style="font-size:12px;">Asking Price: $' . number_format( $asking_price , 2 , '.' , ',' ) . '</div>';
									echo '<div class="websitez-ais-incentive">Savings: ' . $ais_incentive . '</div>';
									echo '<div class="websitez-asking-price" style="font-size:16px;">Your Price: $' . number_format( $asking_price - $incentive_price , 2 , '.' , ',' ) . '</div>';
								} else {
									if( $ais_incentive != NULL ) {
										echo '<div class="websitez-ais-incentive">Savings: ' . $ais_incentive . '</div>';
									}
									echo '<div class="websitez-asking-price">Price: $' . number_format( $asking_price , 2 , '.' , ',' ) . '</div>';
								}
							} else {
								if( $ais_incentive != NULL ) {
									echo '<div class="websitez-ais-incentive">Savings: ' . $ais_incentive . '</div>';
								}
								echo $default_price_text;
							}
						}
					?>
					<p><strong>Stock:</strong> <?php echo $stock_number; ?></p>
					<p><strong>VIN:</strong> <?php echo $vin; ?></p>
					<p><strong>Odometer:</strong> <?php echo number_format($odometer); ?></p>
					<p><strong>Exterior Color:</strong> <?php echo $exterior_color; ?></p>
					<p><strong>Engine:</strong> <?php echo $engine; ?></p>
					<p><strong>Transmission:</strong> <?php echo $transmission; ?></p>
					<p><strong>Drivetrain:</strong> <?php echo $drivetrain; ?></p>
				</div>
				<?php flush(); ?>
				<div class="details form">
					<h3>Vehicle Inquiry</h3>
					<form action="<?php echo $this->options[ 'vehicle_management_system' ][ 'host' ] . '/' . $this->options[ 'vehicle_management_system' ][ 'company_information' ][ 'id' ]; ?>/forms/create/<?php echo strtolower($sale_class); ?>_vehicle_inquiry" method="post" name="vehicle-inquiry">
						<input name="required_fields" type="hidden" value="name,email,privacy" />
						<input name="subject" type="hidden" value="Vehicle Inquiry - <?php echo $headline; ?>" />
						<input name="saleclass" type="hidden" value="<?php echo $sale_class; ?>" />
						<input name="vehicle" type="hidden" value="<?php echo $year_make_model; ?>" />
						<input name="year" type="hidden" value="<?php echo $year; ?>" />
						<input name="make" type="hidden" value="<?php echo $make; ?>" />
						<input name="model_name" type="hidden" value="<?php echo $model; ?>" />
						<input name="trim" type="hidden" value="<?php echo $trim; ?>" />
						<input name="stock" type="hidden" value="<?php echo $stock_number; ?>" />
						<input name="vin" type="hidden" value="<?php echo $vin; ?>" />
						<input name="inventory" type="hidden" value="<?php echo $inventory->id; ?>" />
						<input name="price" type="hidden" value="<?php echo $primary_price; ?>" />
						<input name="name" type="hidden" value="" />
						<table>
							<tr>
								<td class="required" colspan="1">
									<label for="vehicle-inquiry-f-name">Your First Name</label>
									<input maxlength="70" id="vehicle-inquiry-f-name" name="f_name" style="width:90%" tabindex="1" type="text" />
								</td>
								<td class="required" colspan="1">
									<label for="vehicle-inquiry-email">Email Address</label>
									<input maxlength="255" id="vehicle-inquiry-email" name="email" style="width:97%" tabindex="6" type="text" />
								</td>
							</tr>
							<tr>
								<td class="required" colspan="1">
									<label for="vehicle-inquiry-l-name">Your Last Name</label>
									<input maxlength="70" id="vehicle-inquiry-l-name" name="l_name" style="width:90%" tabindex="2" type="text" />
								</td>
								<td colspan="1">
									<label for="vehicle-inquiry-timetocall">Best Time To Call</label>
									<input maxlength="256" name="timetocall" id="vehicle-inquiry-timetocall" style="width:97%" tabindex="5" type="text" />
								</td>
							</tr>
							<tr>
								<td colspan="1" valign="top">
									<label for="vehicle-inquiry-phone">Phone Number</label>
									<input maxlength="256" name="phone" id="vehicle-inquiry-phone" style="width:90%" tabindex="3" type="text" />
								</td>
								<td class="required" rowspan="1">
									<label for="vehicle-inquiry-comments">Comments</label>
									<textarea name="comments" id="vehicle-inquiry-comments" rows="7" style="width:97%" tabindex="7"></textarea>
								</td>
							</tr>
							<tr>
								<td colspan="1">&nbsp;</td>
								<td colspan="1">
									<input onclick="document.forms['vehicle-inquiry']['name'].value = document.forms['vehicle-inquiry']['f_name'].value + ' ' + document.forms['vehicle-inquiry']['l_name'].value; document.forms['vehicle-inquiry']['privacy'].checked = true; document.forms['vehicle-inquiry'].submit();" type="button" value="Send Inquiry" class="submit" />
									<p><small style="float:right;">
										<label for="vehicle-inquiry-privacy"><a href="/privacy" target="_blank">Privacy Policy</a></label>
									</small></p>
									<div style="display:none">
										<input class="privacy" name="privacy" id="vehicle-inquiry-privacy" type="checkbox" value="Yes" />
										<input name="agree_sb" type="checkbox" value="Yes" /> I am a Spam Bot?
									</div>
								</td>
							</tr>
						</table>
					</form>
				</div>
			</div>
			<?php flush(); ?>
		</div>
	</div>
	<div class="post">
		<div class="post-wrapper car">
			<div class="details">
				<center><h3>Additional Information</h3>
				<p><select onchange='jQuery(".options-div").each(function(){ jQuery(this).hide(); }); jQuery(this.value).show("slow");'>
					<option value='#dealer-notes'>Description</option>
					<option value='#dealer-options'>Dealer Options</option>
					<option value='#standard-equipment'>Standard Equipment</option>
					<option value='#test-drive'>Test Drive</option>
					<option value='#trade-in'>Trade In</option>
					<option value='#tell-a-friend'>Share</option>
					<option value='#loan-calculator'>Loan Calculator</option>
				</select></p></center>
			</div>
			<div class="options">
				<div id="dealer-notes" class="options-div">
					<h3>Dealer Notes</h3>
					<p><?php echo ( isset( $description ) && !empty( $description ) ) ? $description : 'Notes are currently unavailable for this vehicle.'; ?></p>
				</div>
				<div id="dealer-options" class="options-div">
					<h3>Dealer Options</h3>
					<?php if( !is_null( $dealer_options ) ): ?>
						<ul>
							<?php
								$counter = 0;
								$split = count( $dealer_options ) / 2;
								foreach( $dealer_options as $option ) {
									echo ( $counter > $split ) ? '</ul><ul>' : NULL;
									$counter = ( $counter <= $split ) ? $counter + 1 : 0;
									echo '<li>' . $option . '</li>';
								}
							?>
						</ul>
					<?php else: ?>
						<p>This information is currently not available.</p>
					<?php endif ?>
					<br class="clear" />
				</div>
				<div id="standard-equipment" class="options-div">
					<h3>Standard Equipment</h3>
					<?php if( !is_null( $standard_equipment ) ): ?>
					<ul>
						<?php
							$previous = null;
							foreach( $standard_equipment as $item ) {
								echo ( is_null( $previous ) ) ? '<li class="no-list"><strong>' . $item->group . '</strong></li>' : NULL;
								$previous = ( is_null( $previous ) ) ? $item->group : $previous;
								echo ( $previous != $item->group ) ? '</ul><ul><li class="no-list"><strong>' . $previous . '</strong></li>' : NULL;
								$previous = ( $previous != $item->group ) ? $item->group : $previous;
								echo '<li>' . $item->name . '</li>';
							}
						?>
					</ul>
					<?php else: ?>
						<p>This information is currently not available.</p>
					<?php endif; ?>
					<br class="clear" />
				</div>
				<div id="test-drive" class="options-div">
					<h3>Test Drive</h3>
					<div class="form">
						<form name="formvehicletestdrive" action="<?php echo $this->options[ 'vehicle_management_system' ][ 'host' ] . '/' . $this->options[ 'vehicle_management_system' ][ 'company_information' ][ 'id' ]; ?>/forms/create/<?php echo strtolower($sale_class); ?>_vehicle_test_drive" method="post">
							<input type="hidden" name="required_fields" value="name,email,privacy"/>
							<input type="hidden" name="saleclass" value="<?php echo strtolower($sale_class); ?>"/>
							<input type="hidden" name="return_url" value="/thank-you-<?php echo strtolower($sale_class); ?>-test-drive/" id="return_url_test_drive"/>
							<input type="hidden" name="vehicle" value="<?php echo $year . ' ' . $make . ' ' . $model; ?>"/>
							<input type="hidden" name="year" value="<?php echo $year; ?>"/>
							<input type="hidden" name="make" value="<?php echo $make; ?>"/>
							<input type="hidden" name="model_name" value="<?php echo $model; ?>"/>
							<input type="hidden" name="trim" value="<?php echo $trim; ?>"/>
							<input type="hidden" name="stock" value="<?php echo $stock_number; ?>"/>
							<input type="hidden" name="vin" value="<?php echo $vin; ?>"/>
							<input type="hidden" name="inventory" value="<?php echo $inventory->id; ?>"/>
							<input type="hidden" name="price" value="<?php echo $primary_price; ?>"/>
							<table style="width:100%">
								<tr>
									<td class="required" style="width:50%;" colspan="1">
										<label for="formvehicletestdrive-name">Your First &amp; Last Name</label>
										<input type="text" maxlength="70" name="name" id="formvehicletestdrive-name" style="width:95%"/>
									</td>
									<td class="required" style="width:50%;" colspan="1">
										<label for="formvehicletestdrive-email">Email Address</label>
										<input type="text" maxlength="255" name="email" id="formvehicletestdrive-email" style="width:98%"/>
									</td>
								</tr>
								<tr>
									<td colspan="1">
										<label for="formvehicletestdrive-phone">Phone Number</label>
										<input type="text" maxlength="256" name="phone" id="formvehicletestdrive-phone" style="width:95%"/>
									</td>
									<td colspan="1">
										<label for="formvehicletestdrive-timetocall">Best Time To Call</label>
										<input type="text" maxlength="256" name="timetocall" id="formvehicletestdrive-timetocall" style="width:98%"/>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<label for="formvehicletestdrive-subject">Subject</label>
										<input type="text" maxlength="256" style="width:99%" name="subject" id="formvehicletestdrive-subject" value="Vehicle Test Drive - <?php echo $year_make_model; ?>"/>
									</td>
								</tr>
								<tr>
									<td colspan="2" class="required">
										<label for="formvehicletestdrive-comments">Comments</label>
										<textarea style="width:99%" rows="10" name="comments" id="formvehicletestdrive-comments"></textarea>
									</td>
								</tr>
								<tr>
									<td class="required" colspan="2">
										<input type="checkbox" class="privacy" id="formvehicletestdrive-privacy" name="privacy" value="Yes" /> <label for="formvehicletestdrive-privacy">Agree to <a target="_blank" href="/privacy">Privacy Policy</a></label>
										<div style="display:none">
											<input type="checkbox" name="agree_sb" value="Yes" /> I am a Spam Bot?
										</div>
									</td>
								</tr>
							</table>
							<div class="buttons" style="float:right">
								<button type="submit">Send Inquiry</button>
							</div>
						</form>
					</div>
				</div>
				<div id="trade-in" class="options-div">
					<h3>Trade In</h3>
					<div class="form">
						<form name="formvehicletradein" action="<?php echo $this->options[ 'vehicle_management_system' ][ 'host' ] . '/' . $this->options[ 'vehicle_management_system' ][ 'company_information' ][ 'id' ]; ?>/forms/create/<?php echo strtolower($sale_class); ?>_vehicle_trade_in" method="post">
							<input type="hidden" name="required_fields" value="name,email,privacy"/>
							<input type="hidden" name="saleclass" value="strtolower($sale_class);"/>
							<input type="hidden" name="return_url" value="/thank-you-trade-inqury/" id="return_url_trade_in"/>
							<input type="hidden" name="vehicle" value="<?php echo $year_make_model; ?>"/>
							<input type="hidden" name="year" value="<?php echo $year; ?>"/>
							<input type="hidden" name="make" value="<?php echo $make; ?>"/>
							<input type="hidden" name="model_name" value="<?php echo $model; ?>"/>
							<input type="hidden" name="trim" value="<?php echo $trim; ?>"/>
							<input type="hidden" name="stock" value="<?php echo $stock_number; ?>"/>
							<input type="hidden" name="vin" value="<?php echo $vin; ?>"/>
							<input type="hidden" name="inventory" value="<?php echo $inventory->id; ?>"/>
							<input type="hidden" name="price" value="<?php echo $primary_price; ?>"/>
							<table style="width:100%">
								<tr>
									<td class="required" colspan="1" style="width:50%;">
										<label for="formvehicletradein-name">Your First &amp; Last Name</label>
										<input type="text" maxlength="70" name="name" id="formvehicletradein-name" style="width:95%"/>
									</td>
									<td class="required" colspan="1" style="width:50%;">
										<label for="formvehicletradein-email">Email Address</label>
										<input type="text" maxlength="255" name="email" id="formvehicletradein-email" style="width:98%"/>
									</td>
								</tr>
								<tr>
									<td colspan="1">
										<label for="formvehicletradein-phone">Phone Number</label>
										<input type="text" maxlength="256" name="phone" id="formvehicletradein-phone" style="width:95%"/>
									</td>
									<td colspan="1">
										<label for="formvehicletradein-timetocall">Best Time To Call</label>
										<input type="text" maxlength="256" name="timetocall" id="formvehicletradein-timetocall" style="width:98%"/>
									</td>
								</tr>
								<tr>
									<td colspan="1" class="required">
										<label for="formvehicletradein-vin">VIN</label>
										<input type="text" size="20" maxlength="256" id="formvehicletradein-vin" name="vin" onKeyUp="update_vehicle(this.value);" onChange="update_vehicle(this.value)" style="width:95%;" />
									</td>
									<td class="required" colspan="1">
										<label for="formvehicletradein-vehicle-desc">Vehicle Year / Make / Model</label>
										<input type="text" maxlength=256 name="vehicle_desc" id="formvehicletradein-vehicle-desc" style="width:98%" />
									</td>
								</tr>
								<tr>
									<td colspan="1">
										<label for="formvehicletradein-engine">Engine</label>
										<select name="engine" id="formvehicletradein-engine">
											<option value="4 Cyl">4 Cyl</option>
											<option value="6 Cyl">6 Cyl</option>
											<option value="V6">V6</option>
											<option value="V8">V8</option>
											<option value="Other">Other</option>
										</select>
										<label for="formvehicletradein-transmission">Transmission</label>
										<select name="transmission" id="formvehicletradein-transmission">
											<option value="Manual">Manual</option>
											<option value="Automatic">Automatic</option>
										</select>
									</td>
									<td colspan="1">
										<label for="formvehicletradein-mileage">Odometer</label>
										<input type="text" maxlength=256 name="mileage" id="formvehicletradein-mileage" style="width:98%" />
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<label for="formvehicletradein-tradein">Description and Comments</label>
										<textarea name="tradein" rows="5" id="formvehicletradein-tradein" style='width:99%'></textarea>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<label>Are you still making payments on this trade-in?</label>
										<input type="radio" name="payoff" value="Yes"> Yes
										<input type="radio" name="payoff" value="No"> No
									</td>
								</tr>
								<tr>
									<td colspan="1">
										<label for="formvehicletradein-payoff-holder">Who is your loan financed by?</label>
										<input type="text" maxlength=256 name="payoff_holder" id="formvehicletradein-payoff-holder" style="width:95%" />
									</td>
									<td colspan="1">
										<label for="formvehicletradein-payoff-amt">If financing, provide loan-payoff amount</label>
										<input type="text" maxlength=256 name="payoff_amt" id="formvehicletradein-payoff-amt" style="width:98%" />
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<label for="formvehicletradein-subject">Subject</label>
										<input type="text" id="formvehicletradein-subject" maxlength="256" style="width:99%" name="subject" value="Vehicle Trade In - <?php echo $year_make_model; ?>"/>
									</td>
								</tr>
								<tr>
									<td colspan="2" class="required">
										<label for="formvehicletradein-comments">Comments</label>
										<textarea style="width:99%" rows="10" name="comments" id="formvehicletradein-comments"></textarea>
									</td>
								</tr>
								<tr>
									<td class="required" colspan="2">
										<input type="checkbox" class="privacy" name="privacy" id="formvehicletradein-privacy" value="Yes" /><label for="formvehicletradein-privacy">Agree to <a target="_blank" href="/privacy">Privacy Policy</a></label>
										<div style="display:none">
											<input type="checkbox" name="agree_sb" value="Yes" /> I am a Spam Bot?
										</div>
									</td>
								</tr>
							</table>
							<div class="buttons" style="float:right">
								<button type="submit">Send Inquiry</button>
							</div>
						</form>
					</div>
					<script id="loader" type="text/javascript"></script>
					<script type="text/javascript">
						function update_vehicle(vin) {
							if (vin == null || vin.length < 11) return false;
							dealertrend('#loader').attr('src', '<?php echo $this->options[ 'vehicle_management_system' ][ 'host' ] . '/' . $this->options[ 'vehicle_management_system' ][ 'company_information' ][ 'id' ]; ?>/widget/check_vin.js?vin=' + vin);
						}
					</script>
				</div>
				<div id="tell-a-friend" class="options-div">
					<h3>Tell a Friend</h3>
					<div class="form">
						<form name="formtellafriend" action="<?php echo $this->options[ 'vehicle_management_system' ][ 'host' ] . '/' . $this->options[ 'vehicle_management_system' ][ 'company_information' ][ 'id' ]; ?>/forms/create/vehicle_tell_a_friend" method="post">
							<input type="hidden" name="required_fields" value="from_name,from_email,friend_name,friend_email,privacy"/>
							<input type="hidden" name="return_url" value="/thank-you-tell-a-friend/" id="return_url_tellafriend"/>
							<input type="hidden" name="vehicle" value="<?php echo $year_make_model; ?>"/>
							<input type="hidden" name="stock" value="<?php echo $stock_number; ?>"/>
							<input type="hidden" name="vin" value="<?php echo $vin; ?>"/>
							<table style="width:100%">
								<tr>
									<td class="required" style="width:50%;" colspan="1">
										<label for="formtellafriend-from-name">Your First &amp; Last Name</label>
										<input type="text" style="width:90%" maxlength="70" name="from_name" id="formtellafriend-from-name"/>
									</td>
									<td class="required" colspan="1">
										<label for="formtellafriend-from-email">Email Address</label>
										<input type="text" style="width:97%" id="formtellafriend-from-email" maxlength="255" name="from_email"/>
									</td>
								</tr>
								<tr>
									<td class="required" style="width:50%;" colspan="1">
										<label for="formtellafriend-friend-name">Your Friend's Name</label>
										<input type="text" style="width:90%" maxlength="70" name="friend_name" id="formtellafriend-friend-name" />
									</td>
									<td class="required" colspan="1">
										<label for="formtellafriend-friend-email">Friend's Email Address</label>
										<input type="text" style="width:90%" maxlength="255" name="friend_email" id="formtellafriend-friend-email" />
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<label for="formtellafriend-subject">Subject</label>
										<input type="text" style="width:97%" maxlength="256" name="subject" id="formtellafriend-subject" value="<?php echo $company_information->name; ?> - Tell-A-Friend - <?php echo $year_make_model; ?>"/>
									</td>
								</tr>
								<tr>
									<td colspan="2" class="required">
										<label for="formtellafriend-comments">Comments</label>
										<textarea style="width:97%" rows="10" name="comments" id="formtellafriend-comments"></textarea>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<div>
											<input id="formtellafriend-notify" type="checkbox" name="notify" value="yes" /><label for="formtellafriend-notify">Request Notification of Receipt when email is opened?</label>
										</div>
										<div class="required">
											<input type="checkbox" name="privacy" id="formtellafriend-privacy" value="Yes" /><label for="formtellafriend-privacy">Agree to <a target="_blank" href="/privacy">Privacy Policy</a></label>
										</div>
										<div style="display:none">
											<input type="checkbox" name="agree_sb" value="Yes" /> I am a Spam Bot?
										</div>
									</td>
								</tr>
							</table>
							<div class="buttons" style="float:right">
								<button type="submit">Send to a Friend</button>
							</div>
						</form>
					</div>
				</div>
				<div id="loan-calculator" class="options-div">
					<?php
						$html_free_price = str_replace( '<span class=\'asking\'>' , null , $primary_price );
						$html_free_price = str_replace( '</span>' , null , $html_free_price );
					?>
					<h3>Loan Calculator</h3>
					<div class="form">
						<form name="loan-calculator" action="#" method="post">
							<table style="width:100%">
								<tr>
									<td colspan="1">
										<label for="loan-calculator-price">Vehicle Price</label>
										<input type="text" style="width:90%" name="price" id="loan-calculator-price" value="<?php echo $html_free_price; ?>" />
									</td>
								</tr>
								<tr>
									<td colspan="1">
										<label for="loan-calculator-interest-rate">Interest Rate</label>
										<input type="text" style="width:90%" name="interest-rate" id="loan-calculator-interest-rate" value="7.35%" />
									</td>
								</tr>
								<tr>
									<td colspan="1">
										<label for="loan-calculator-term">Term (months)</label>
										<input type="text" style="width:90%" name="term" id="loan-calculator-term" value="48" />
									</td>
								</tr>
								<tr>
									<td colspan="1">
										<label for="loan-calculator-trade-in-value">Trade in Value</label>
										<input type="text" style="width:90%" name="trade-in-value" id="loan-calculator-trade-in-value" value="$3,000" />
									</td>
								</tr>
								<tr>
									<td colspan="1">
										<label for="loan-calculator-down-payment">Down Payment</label>
										<input type="text" style="width:90%" name="down-payment" id="loan-calculator-down-payment" value="$5,000" />
									</td>
								</tr>
								<tr>
									<td colspan="1">
										<label for="loan-calculator-sales-tax">Sales Tax</label>
										<input type="text" style="width:90%" name="sales-tax" id="loan-calculator-sales-tax" value="7.375%" />
									</td>
								</tr>
								<tr>
									<td colspan="1">
										<div>Bi-Monthly Cost</div>
										<div id="loan-calculator-bi-monthly-cost"></div>
									</td>
								</tr>
								<tr>
									<td colspan="1">
										<div>Monthly Cost</div>
										<div id="loan-calculator-monthly-cost"></div>
									</td>
								</tr>
								<tr>
									<td colspan="1">
										<div>Total Cost <small>(including taxes)</small></div>
										<div id="loan-calculator-total-cost"></div>
									</td>
								</tr>
								<tr>
									<td colspan="3"><button>Calculate</button></td>
								</tr>
							</table>
						</form>
					</div>
				</div>
				<div style="clear: both;"></div>
			</div>
			</div>
		</div>
	</div>
</div>
