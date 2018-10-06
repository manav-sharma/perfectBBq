<?php 
//echo "<pre>";
//print_r($detail->countryId);
//exit;

$data['scripts'][] = 'admin/addeditusers.js';
$data['validate']='frmHotel';
$this->load->view('admin/includes/header.php', $data);
if (empty($detail)) {
    $attributes = array('id' => 'frmHotel', 'name' => 'frmAddUser');
    echo form_open_multipart('admin/users/addHotel', $attributes);
} else {
	$attributes = array('id' => 'frmHotel', 'name' => 'frmEditUser');
    echo form_open_multipart('admin/users/editHotel/' . $detail->id, $attributes); ?>
    <input type ="hidden" name="userid" value ="<?php echo $detail->id; ?>" /> <?php 
}  
?> 
  <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 100%;
		width: 450px;
		right: 20px;
        top: 160px;
		position: absolute;
      }
      .controls {
        margin-top: 10px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
      }

      #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 300px;
      }

      #pac-input:focus {
        border-color: #4d90fe;
      }

      .pac-container {
        font-family: Roboto;
      }

      #type-selector {
        color: #fff;
        display: inline-flex;
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
      }

      #type-selector label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
      }
    </style>
<div class="clear"></div>
<div id="pageHeading"> 
    <div class="column1">
        <h1><?php echo ucwords(isset($title) ? $title : ''); ?></h1>
        <div id="breadcrumb">
            <a href="<?php echo site_url('admin'); ?>">Home</a>
            <span class="pipe">&#187;</span> <a href="<?php echo site_url('admin/users'); ?>">Users</a>
            <span class="pipe">&#187;</span> <?php echo ucwords(isset($title) ? $title : ""); ?>
        </div>
    </div>
	<div class="column2"> 
		<a class="btn" href="<?php echo site_url('admin/users'); ?>">Back</a>  
		<input type="submit" value="Save" class="btn" name="btnSubmit1" id="btnSubmit1" />                    
	</div>   
</div>
<div class="clear"></div>
</div>   
<div id="pageContent">
    <?php
    $value = $this->session->flashdata('item');
    if (!empty($value)) {
        echo $value;
    } else {
        if (isset($msg) && isset($msgType) && !empty($msg) && !empty($msgType)) {
            echo $this->utility->show_message($msg, $msgType);
        } else {
            echo $this->utility->show_message();
        }
    }
    ?>
    <div style="width:796px;" id="tab1" class="column2">   
        <dl class="formBox">
            <dt><?php echo ucwords(isset($title) ? $title : ''); ?></dt>
            <dd>
                <!--LOGIN DETAIL -->
                <h3>Login Details</h3>
                <table cellspacing="0" cellpadding="0" border="0" class="form">
                    <colgroup>
                        <col width="170"/>
                        <col width="180"/>
                        <col width="50"/>
                        <col width="170"/>
                        <col width="180"/>
                    </colgroup>
                    <tbody>
                        <tr>
                           <td scope="col">
                                <label id="lb_username">Username</label>
                            </td>
                            <td scope="col">
                                <input type="text" data-errormessage-value-missing="User name is required!" class="validate[required,minSize[6]]" maxlength="100" size="26" name="username" id="username" value="<?php if(isset($detail->usrUsername)){ echo $detail->usrUsername;} else { echo"";}?>"/> 
                            </td>
							 <td scope="col">&nbsp;</td>
                            
                        </tr>
                        <tr>
							
                        </tr>
                    </tbody>
                </table>
                <h3 style="margin-top:20px;">Basic Information</h3>
                <table border="0" cellspacing="0" cellpadding="0" style=" margin-top:18px" class="form" >
                    <colgroup>
                        <col width="170"/>
                        <col width="180"/>
                        <col width="50"/>
                        <col width="170"/>
                        <col width="180"/>
                    </colgroup>
                    <tr>
                        <td scope="col"><label>Hotel Name</label></td>
                        <td scope="col"><input type="text" data-errormessage-value-missing="Hotel Name is required!" class="validate[required,custom[onlyLetterSp]]" size="26" name="hotelName" id="hotelName" maxlength="60" value="<?php echo isset($detail->usrFirstName) ? $detail->usrFirstName : ''; ?>" class="validate" /></td>
                        <td scope="col">&nbsp;</td>
                        <td scope="col"><label>Commission(%)</label></td>
                        <td scope="col"><input type="text" size="26" data-errormessage-value-missing="Commission is required!" class="validate[required,custom[onlyNumber]]" name="commision" id="commision" maxlength="60" value="<?php echo isset($detail->admincommission) ? $detail->admincommission : ''; ?>" class="validate" /></td>
                    </tr>
                    
                    <tr>
                        <td scope="col"><label>Hotel Name (Arabic)</label></td>
                        <td scope="col"><input type="text" data-errormessage-value-missing="Hotel Name Arabic is required!" class="validate[required]" size="26" name="hotelNamearabic" id="hotelNamearabic" maxlength="60" value="<?php echo isset($detail->hotelNameArabic) ? $detail->hotelNameArabic : ''; ?>" class="validate" /></td>
                        <td scope="col">&nbsp;</td>
                        
                    </tr>
                    <tr>
						<td scope="col">
                                <label>Room Type</label>
                            </td>
                            <td scope="col">
                                <select class="validate[required]" data-errormessage-value-missing="Room type is required!" style="width:155px;" name="roomtype[]" id="roomtype" multiple="multiple">
									<!--<option <?php //echo set_select('roomtype', '', TRUE); ?> value="">Select Room Type</option>-->
									<?php
									if (isset($roomcatsarr) && !empty($roomcatsarr)) {
										foreach ($roomcatsarr as $roomcat) {?>
										<option value="<?php echo $roomcat->roomCatId;?>" <?php if(isset($detail->roomCategoryId)){if(in_array($roomcat->roomCatId,explode(',',$detail->roomCategoryId))){?>selected<?php }}?>> <?php echo ucfirst($roomcat->roomCategory);?> </option>
										<?php }
									}
									?>                                    
								</select>
                            </td>
                            <td scope="col">&nbsp;</td>
                            
                            <td scope="col">
                                <label>Hotel Type</label>
                            </td>
                            <td scope="col">
                                <select class="validate[required]" data-errormessage-value-missing="Hotel type is required!" style="width:155px;" name="hoteltype" id="hoteltype" >
									<option <?php echo set_select('hoteltype', '', TRUE); ?> value="">Select Hotel Type</option>
									<?php
									if (isset($hotelTypesarr) && !empty($hotelTypesarr)) {
										foreach ($hotelTypesarr as $hotelType) {?>
										<option value = "<?php echo $hotelType->hotelTypeId;?>" <?php if(isset($detail->hotelTypeId) && $detail->hotelTypeId==$hotelType->hotelTypeId){?> selected<?php }?>><?php echo ucfirst($hotelType->hotelType);?></option>
										<?php }
									}
									?>                                    
								</select>
                            </td>
                           </td>
                        </tr>      
					  <tr>
							<td scope="col"><label>Country</label></td>
							<td scope="col">
								<select class="validate[required]" data-errormessage-value-missing="Country is required!" style="width:155px;" name="cmbCountry" id="cmbCountry" >
									<option <?php echo set_select('cmbCountry', '', TRUE); ?> value="">Select Country</option>
									<?php
									if (isset($countries) && !empty($countries)) {
										foreach ($countries as $country) {
											if (isset($detail->countryId) && $detail->countryId == $country->geoId) {
												$sel = 'selected="selected"';
											}
											else {
												$sel = '';
											}
											echo '<option value="' . $country->geoId . '" ' . $sel . ' '.set_select('cmbCountry',$country->geoId ).'>' . ucfirst($country->geoName) . '</option>';
										}
									}
									?>                                    
								</select>
							</td>
							<td scope="col">&nbsp;</td>
<td scope="col"><label>State</label></td>
							<td scope="col">
								<select class="validate[required]" data-errormessage-value-missing="State is required!" style="width:155px;" name="cmbState" id="cmbState" >
									<option <?php echo set_select('cmbState', '', TRUE); ?> value="">Select State</option>
									<?php
									if (isset($stateList) && !empty($stateList)) {
										foreach ($stateList as $state) {
											if (isset($detail->stateId) && $detail->stateId == $state->geoId) {
												$sel = 'selected="selected"';
											}
											else {
												$sel = '';
											}
											echo '<option value="' . $state->geoId . '" ' . $sel . ' '.set_select('cmbState',$state->geoId ).'>' . ucfirst($state->geoName) . '</option>';
										}
									}
									?>  
								</select>
							</td>				

						</tr>
						
						<tr>
							<td scope="col"><label id="lb_cmbCity">City</label></td>
							<td scope="col">
							<select class="validate[required]" data-errormessage-value-missing="City is required!" style="width:155px;" name="cmbCity" id="cmbCity" >
									<option <?php echo set_select('cmbCity', '', TRUE); ?> value="">Select City</option>
									<?php
 
									if (isset($cityList) && !empty($cityList)) {
										foreach ($cityList as $city) {
											if (isset($detail->city) && $detail->city == $city->geoId) {
												$sel = 'selected="selected"';
											}
											else {
												$sel = '';
											}
											echo '<option value="' . $city->geoId . '" ' . $sel . ' '.set_select('cmbCity',$city->geoId ).'>' . ucfirst($city->geoName) . '</option>';
										}
									}
									?> 
								</select>
							  <div id="tt_cmbCity" class="hidden">City is <strong>required.</strong></div>
							 </td>
							<td scope="col">&nbsp;</td>
                                                        <td scope="col"><label>Contact Number:</label></td>
							<td scope="col">
								<input type="text" size="26" class="validate[required,custom[onlyNumber]]" data-prompt-position="bottomRight:-60" name="usrContact" id="usrContact" maxlength="15" value="<?php echo set_value('usrContact', (isset($detail->hotelContact) ? $detail->hotelContact : '')); ?>" class="validate[required]" />
							</td>						
						</tr>
	                                         <tr>
							<td scope="col"><label>Hotel Email</label></td>
							<td scope="col">
								<input type="text" size="26" class="validate[required,custom[email]]" data-errormessage-value-missing="Email is required!" name="email" id="email" maxlength="100" value="<?php echo set_value('email', (isset($detail->usrEmail) ? $detail->usrEmail : '')); ?>" class="validate[required]" />
							</td>
							
						</tr>
						<tr>
							<td scope="col"><label>Address:</label></td>
							<td scope="col">
								<textarea name="usraddress" name="usraddress" cols="24" rows="3" class="twocolumninputsize" ><?php echo set_value('usraddress', (isset($detail->hotelAddress) ? $detail->hotelAddress : '')); ?></textarea>
							</td>
							
						</tr>
						
						<tr>
							<td scope="col">
								<label id="lb_aboutyourself" >Gallery (optional)</label>
							</td>
							<td scope="col" colspan="4">
								<?php if(isset($detail->hotelMainpic) && $detail->hotelMainpic!==''){ ?>
									
									<img src="<?php echo base_url(); ?>hotelImages/<?php echo str_replace(' ','_',$detail->hotelMainpic)?>" alt="" width="100" height="100" />
									<input type = 'hidden' name ="hotelprofilePic" value = "<?php echo isset($detail->hotelMainpic) ? $detail->hotelMainpic : ''; ?>">
									<input type = 'file' name ="hotelprofilePic">
									
									<?php } else {?>
										
									<input type = 'file' name ="hotelprofilePic">
										
									<?php	} ?>
								
							</td>
						</tr>
						 
						<tr>
							<td scope="col">
								<label id="lb_aboutyourself" >About Hotel</label>
							</td>
							<td scope="col" colspan="4">
								<textarea style="width:545px;" name="aboutyourself" id="aboutyourself" cols="50" rows="7" class="" ><?php echo set_value('aboutyourself', (isset($detail->aboutHotel) ? $detail->aboutHotel : '')); ?></textarea> 
							</td>
						</tr>
						
						<tr>
							<td scope="col">
								<label id="lb_aboutyourself" >About Hotel (Arabic)</label>
							</td>
							<td scope="col" colspan="4">
								<textarea style="width:545px;" name="aboutyourselfarabic" id="aboutyourselfarabic" cols="50" rows="7" class="" ><?php echo set_value('aboutyourselfarabic', (isset($detail->aboutHotelArabic) ? $detail->aboutHotelArabic : '')); ?></textarea> 
							</td>
						</tr>
						<input type ="hidden" name = "user_group_id" value="1">
						<input type ="hidden" class="validate[required]" id = "nearByLocation" name = "nearByLocation" value="<?php echo isset($detail->nearByLocation) ? $detail->nearByLocation : ''; ?>">
						
                </table>                                
            </dd>
            <div class="clear"></div>
        </dl>
        <div class="baseStrip">
            <a class="btn" href="<?php echo site_url('admin/users'); ?>">Back</a>
                <input type="submit" value="Save" class="btn" name="btnSubmit" id="btnSubmit" />    
        </div>    
	</div>
</div>
</form>
<input id="pac-input" class="controls" name="nearByLocation" type="text" placeholder="Enter a location"/>
  <div id="map"></div>
<script>
      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: -33.8688, lng: 151.2195},
          zoom: 13
        });
        var input = /** @type {!HTMLInputElement} */(
            document.getElementById('pac-input'));

        var types = document.getElementById('type-selector');
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);

        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);
        autocomplete.addListener('place_changed', fillInSearchForm);
       autocomplete.addListener('place_changed', fillInSearchForm);
  	
	function fillInSearchForm() {
	var palce = $('#pac-input').val();
        $('#nearByLocation').val(palce);
	//return;
        }


        var infowindow = new google.maps.InfoWindow();
        var marker = new google.maps.Marker({
          map: map,
          anchorPoint: new google.maps.Point(0, -29)
        });

        autocomplete.addListener('place_changed', function() {
          infowindow.close();
          marker.setVisible(false);
          var place = autocomplete.getPlace();
          if (!place.geometry) {
            window.alert("No Location matches your Search");
            return;
          }

          // If the place has a geometry, then present it on a map.
          if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
          } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);  // Why 17? Because it looks good.
          }
          marker.setIcon(/** @type {google.maps.Icon} */({
            url: place.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(35, 35)
          }));
          marker.setPosition(place.geometry.location);
          marker.setVisible(true);

          var address = '';
          if (place.address_components) {
            address = [
              (place.address_components[0] && place.address_components[0].short_name || ''),
              (place.address_components[1] && place.address_components[1].short_name || ''),
              (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
          }

          infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
          infowindow.open(map, marker);
        });

        // Sets a listener on a radio button to change the filter type on Places
        /* Autocomplete.
        function setupClickListener(id, types) {
          var radioButton = document.getElementById(id);
          radioButton.addEventListener('click', function() {
            autocomplete.setTypes(types);
          });
        }

        setupClickListener('changetype-all', []);
        setupClickListener('changetype-address', ['address']);
        setupClickListener('changetype-establishment', ['establishment']);
        setupClickListener('changetype-geocode', ['geocode']);*/
      }
    </script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCcr03YGDnf8LFXqHHk_PFr5jGwt4zc3wU&libraries=places&callback=initMap"
        async defer></script>

<script>
	 $(document).ready(function(){
		var v = $('#nearByLocation').val();
		
        $('#pac-input').val(v);
});
/*	$('#pac-input').bind('keypress keyup blur', function() {
    $('#nearByLocation').val($(this).val());
});*/
</script>
<?php $this->load->view("admin/includes/footer"); ?>
