<?php
error_reporting('E_PARSE');

class upsRate {
    var $AccessLicenseNumber;  
    var $UserId;  
    var $Password;
    var $shipperNumber;
    var $credentials;

    function setCredentials($access,$user,$pass,$shipper) {
	$this->AccessLicenseNumber = $access;
	$this->UserID = $user;
	$this->Password = $pass;	
	$this->shipperNumber = $shipper;
	$this->credentials = 1;
    }

    // Define the function getRate() - no parameters
    function getRate($ShipperCountry,$PostalCode,$DestCountry,$dest_zip,$service,$length,$width,$height,$weight) {
	if ($this->credentials != 1) {
		print 'Please set your credentials with the setCredentials function';
		die();
	}
	if($ShipperCountry == 'US'){
		$weightunit = 'LBS';
		$unitlength = 'IN';
	} else {
		$weightunit = 'KGS';
		$unitlength = 'CM';
	}

	$data ="<?xml version=\"1.0\"?>  
		<AccessRequest xml:lang=\"en-US\">  
		    <AccessLicenseNumber>$this->AccessLicenseNumber</AccessLicenseNumber>  
		    <UserId>$this->UserID</UserId>  
		    <Password>$this->Password</Password>  
		</AccessRequest>  
		<?xml version=\"1.0\"?>  
		<RatingServiceSelectionRequest xml:lang=\"en-US\">  
		    <Request>  
			<TransactionReference>  
			    <CustomerContext>Bare Bones Rate Request</CustomerContext>  
			    <XpciVersion>1.0001</XpciVersion>  
			</TransactionReference>  
			<RequestAction>Rate</RequestAction>  
			<RequestOption>Rate</RequestOption>  
		    </Request>  
		<PickupType>  
		    <Code>03</Code>  
		</PickupType>  
		<Shipment>  
		    <Shipper>  
			<Address>  
			    <PostalCode>$PostalCode</PostalCode>  
			    <CountryCode>$ShipperCountry</CountryCode>  
			</Address>  
		    <ShipperNumber>$this->ShipperNumber</ShipperNumber>  
		    </Shipper>  
		    <ShipTo>  
			<Address>  
			    <PostalCode>$dest_zip</PostalCode>  
			    <CountryCode>$DestCountry</CountryCode>  
			<ResidentialAddressIndicator/>  
			</Address>  
		    </ShipTo>  
		    <ShipFrom>  
			<Address>  
			    <PostalCode>$PostalCode</PostalCode>  
			    <CountryCode>$ShipperCountry</CountryCode>  
			</Address>  
		    </ShipFrom>  
		    <Service>  
			<Code>$service</Code>  
		    </Service> 
		    <Package>  
			<PackagingType>  
			    <Code>02</Code>  
			</PackagingType>  
			<Dimensions>  
			    <UnitOfMeasurement>  
				<Code>$unitlength</Code>  
			    </UnitOfMeasurement>  
			    <Length>$length</Length>  
			    <Width>$width</Width>  
			    <Height>$height</Height>  
			</Dimensions>  
			<PackageWeight>  
			    <UnitOfMeasurement>  
				<Code>$weightunit</Code>  
			    </UnitOfMeasurement>  
			    <Weight>$weight</Weight>  
			</PackageWeight>  
		    </Package>  
		</Shipment>  
		</RatingServiceSelectionRequest>";  
		$ch = curl_init("https://www.ups.com/ups.app/xml/Rate");  
		curl_setopt($ch, CURLOPT_HEADER, 1);  
		curl_setopt($ch,CURLOPT_POST,1);  
		curl_setopt($ch,CURLOPT_TIMEOUT, 60);  
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);  
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
		curl_setopt($ch,CURLOPT_POSTFIELDS,$data);  
		$result=curl_exec ($ch);  
	     //echo "<pre>".'<!-- '. $result. ' -->'; // THIS LINE IS FOR DEBUG PURPOSES ONLY-IT WILL SHOW IN HTML COMMENTS  
		$data = strstr($result, '<?');  
		$xml_parser = xml_parser_create();  
		xml_parse_into_struct($xml_parser, $data, $vals, $index);  
		xml_parser_free($xml_parser);  
		$params = array();  
		$level = array();  
		foreach ($vals as $xml_elem) {  
		 if ($xml_elem['type'] == 'open') {  
		if (array_key_exists('attributes',$xml_elem)) {  
		     list($level[$xml_elem['level']],$extra) = array_values($xml_elem['attributes']);  
		} else {  
		     $level[$xml_elem['level']] = $xml_elem['tag'];  
		}  
		 }  
		 if ($xml_elem['type'] == 'complete') {  
		$start_level = 1;  
		$php_stmt = '$params';  
		while($start_level < $xml_elem['level']) {  
		     $php_stmt .= '[$level['.$start_level.']]';  
		     $start_level++;  
		}  
		$php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';  
		eval($php_stmt);  
		 }  
		}  
		curl_close($ch);  
		
			if($params['RATINGSERVICESELECTIONRESPONSE']['RESPONSE']['RESPONSESTATUSDESCRIPTION']=='Success'){
				return $params['RATINGSERVICESELECTIONRESPONSE']['RATEDSHIPMENT']['TOTALCHARGES']['MONETARYVALUE']; 
			} else {
//				echo "<pre>";
//				print_r($params);
				//echo 'fail';
				//echo "<br/>".$params['RATINGSERVICESELECTIONRESPONSE']['RESPONSE']['ERROR']['ERRORDESCRIPTION']; 
			}
	    }  
}



?>