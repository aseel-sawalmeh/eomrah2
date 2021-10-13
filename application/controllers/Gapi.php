<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Gapi extends MY_Controller
{
    public function  __construct()
    {
        parent::__construct();
        $this->load->model('lotsofhotels/manageData', 'lotsData');
    }
    public function index()
    {
        $this->load->model('translate/Translate_model');
        $data['title'] = "Google translation test";
        $text = $this->input->post('transtxt');
        $from = $this->input->post('fromlang');
        $to = $this->input->post('tolang');
        $data['txtsource'] = $text;
        //$data['langs'] = langs_codes();
        $data['translated'] = trans($text, $from, $to);
        $data['cols'] = $this->Translate_model->get_cols();
        $data['langs'] = $this->Translate_model->get_langs();
        $this->load->view('api_view', $data);
    }

    public function lots()
    {
        // print_r(XMLReader());
        // exit();
        // $xml = new XMLReader();
        // $param = array('http' => array(
        //     'method' => 'POST',
        //     'header' => "Content-type: text/xml\r\n",
        //     'content' => http_build_query(array(
        //         'username' => 'medhat85',
        //         'password' => md5('Rayada@123'),
        //         'id' => '1742725',
        //     )),
        // ));
        // libxml_set_streams_context(stream_context_create($param));
        // $reader = $xml->open('https://xmldev.dotwconnect.com/gatewayV4.dotw');
        // var_dump($reader);
        // var_dump($xml->read());
        // die();
        ////

        //The XML string that you want to send.
        $xmlh = '<customer>  
                    <username>medhat85</username>  
                    <password>' . md5("Rayada@123") . '</password>  
                    <id>1742725</id>  
                    <source>1</source>  
                    <product>hotel</product>
                        <language>en</language>
                        <request command="searchhotels">  
                            <bookingDetails>  
                                <fromDate>2020-12-01</fromDate>
                                <toDate>2020-12-10</toDate>
                                <currency>520</currency>
                                <rooms no="1">  
                                    <room runno="0">  
                                        <adultsCode>1</adultsCode>
                                        <children no="0"> </children> 
                                        <rateBasis>1</rateBasis>  
                                    </room>    
                                </rooms>  
                            </bookingDetails>  
                            <return>  
                                <filters xmlns:a="http://us.dotwconnect.com/xsd/atomicCondition/" xmlns:c="http://us.dotwconnect.com/xsd/complexCondition">
                                    <city>174</city> 
                                </filters>
                                <resultsPerPage>100</resultsPerPage>  
                            </return>  
                        </request>  
                    </customer> ';
        //The XML string that you want to send.
        $xml = '<customer>  
                    <username>medhat85</username>  
                    <password>' . md5("Rayada@123") . '</password>  
                    <id>1742725</id>  
                    <source>1</source>  
                    <product>hotel</product>
                        <language>en</language>
    <request command="searchhotels">  
        <bookingDetails>  
            <fromDate>2020-12-10</fromDate>  
            <toDate>2020-12-15</toDate>  
            <currency>04</currency>  
            <rooms no="1">  
                <room runno="0">  
                    <adultsCode>1</adultsCode>  
                    <children no="0"></children>  
                    <rateBasis>-1</rateBasis>  
                </room>  
            </rooms>  
        </bookingDetails>  
        <return>  
            <getRooms>true</getRooms>  
            <filters xmlns:a="http://us.dotwconnect.com/xsd/atomicCondition/" xmlns:c="http://us.dotwconnect.com/xsd/complexCondition">  
                <city>164</city>  
                <noPrice>true</noPrice>  
            </filters>
            <fields>  
                <field>preferred</field>  
                <field>builtYear</field>  
                <field>renovationYear</field>  
                <field>floors</field>  
                <field>noOfRooms</field>  
                <field>preferred</field>  
                <field>luxury</field>  
                <field>fullAddress</field>  
                <field>description1</field>  
                <field>description2</field>  
                <field>hotelName</field>  
                <field>address</field>  
                <field>zipCode</field>  
                <field>location</field>  
                <field>locationId</field>  
                <field>location1</field>  
                <field>location2</field>  
                <field>location3</field>  
                <field>cityName</field>  
                <field>cityCode</field>  
                <field>stateName</field>  
                <field>stateCode</field>  
                <field>countryName</field>  
                <field>countryCode</field>  
                <field>regionName</field>  
                <field>regionCode</field>  
                <field>attraction</field>  
                <field>amenitie</field>  
                <field>leisure</field>  
                <field>business</field>  
                <field>transportation</field>  
                <field>hotelPhone</field>  
                <field>hotelCheckIn</field>  
                <field>hotelCheckOut</field>  
                <field>minAge</field>  
                <field>rating</field>  
                <field>images</field>  
                <field>fireSafety</field>  
                <field>hotelPreference</field>  
                <field>direct</field>  
                <field>geoPoint</field>  
                <field>leftToSell</field>  
                <field>chain</field>  
                <field>lastUpdated</field>  
                <field>priority</field>  
                <roomField>name</roomField>  
                <roomField>roomInfo</roomField>  
                <roomField>roomAmenities</roomField>  
                <roomField>twin</roomField>  
            </fields>  
        </return>  
    </request>    
        </customer> ';

        //The URL that you want to send your XML to.
        $url = 'https://xmldev.dotwconnect.com/gatewayV4.dotw';
        //Initiate cURL
        $curl = curl_init($url);
        //Set the Content-Type to text/xml.
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
        //Set CURLOPT_POST to true to send a POST request.
        curl_setopt($curl, CURLOPT_POST, true);
        //Attach the XML string to the body of our request.
        curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
        //Tell cURL that we want the response to be returned as
        //a string instead of being dumped to the output.
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //Execute the POST request and send our XML.
        $result = curl_exec($curl);
        //Do some basic error checking.
        if (curl_errno($curl)) {
            throw new Exception(curl_error($curl));
        }
        //Close the cURL handle.
        curl_close($curl);
        // save xml to file
        // $this->wx($result);
        $res_data = simplexml_load_string($result);
        //Print out the response output.
        echo "<h1>all hotels count : " . count($res_data->hotels->hotel) ?? "no results" . "</h1>";
        echo "<br>";
        echo "<pre>";
        print_r($res_data);
        echo "</pre>";
        echo "<br>";
    }

    public function oflots()
    {
        $filepath = APPPATH . "/filedb/lots.xml";
        $res_data = file_exists($filepath) ? simplexml_load_file($filepath)
            : exit('Failed to open test.xml.');
        //Print out the response output.
        echo "<h1>all hotels count : " . count($res_data->hotels->hotel) ?? "no results" . "</h1>";
        echo "<br>";
        echo "<pre>";
        $hotel = $res_data->hotels->hotel[15];
        print_r($res_data->attributes()->command);
        echo "<br>";
        // var_dump($hotel->children('@attributes'));
        var_dump((array)$hotel->attributes()['hotelid']);
        var_dump($hotel->attributes()->hotelid);
        var_dump(_stringify_attributes((array)$hotel));
        echo "<br>";
        echo ($hotel->hotelName);
        echo "<br>";
        echo ($hotel->address);
        echo "<br>";
        echo ($hotel->fullAddress->hotelZipCode);
        echo "<br>";
        echo ($hotel->fullAddress->hotelCountry);
        echo "<br>";
        echo ($hotel->fullAddress->hotelStreetAddress);
        echo "<br>";
        echo $hotel->fullAddress->hotelState ? $hotel->fullAddress->hotelState : " Not Available ";
        echo ($hotel->fullAddress->hotelCity);
        echo "<br>";
        var_dump((array)$hotel->description1->language->attributes()->name);
    }

    public function wx($xdata)
    {
        $xmlfile = fopen(APPPATH . "/filedb/lots.xml", 'w');
        echo fwrite($xmlfile, $xdata);
        fclose($xmlfile);
        // echo "..".__DIR__."/filedb";
    }


    public function pullData()
    {
        // pulling the data from lots static internal codes






    }

    public function lotsPull($command, $table = null)
    {
        // helper function to pull data from losts to local Database
        // <product>hotel</product>
        $requestBody = ' <customer>
                            <username>medhat85</username>
                            <password>53ee645b8f03f74dabd24cac552ce2ff</password>
                            <id>1742725</id>
                            <source>1</source>
                            <product>hotel</product>
                            <request command="' . $command . '">
                            </request>
                        </customer> ';
        // requesting the xml data
        $response = $this->lotsRequest($requestBody);
        // print_r($response->amenities);
        $cities = $response->infoTypes->option;
        $data_amenities = [];
        //	infotype_code 	infotype
        foreach ($cities as $ct) {
            $data_amenities[] = ['infotype_code' => (int)$ct->attributes()->value, 'infotype' => (string)$ct];
        }
        $data_inserted = $this->lotsData->fillTable($data_amenities, 'infotypes');

        if ($data_inserted) {
            echo "data of $command inserted , successfully";
        }
        echo "<pre>";
        // print_r($response);
        print_r($data_amenities);
        echo "</pre>";
        // print_r($response->amenities->option[2]->attributes()->value);
        // echo($response->amenities->option[2]);

    }

    public function pullcities()
    {
        $citiesFile = simplexml_load_file(APPPATH . "/filedb/allcities.xml", 'SimpleXmlElement');
        // helper function to pull data from losts to local Database
        // <product>hotel</product>
        // $requestBody = ' <customer>
        //                     <username>medhat85</username>
        //                     <password>53ee645b8f03f74dabd24cac552ce2ff</password>
        //                     <id>1742725</id>
        //                     <source>1</source>
        //                     <product>hotel</product>
        //                     <request command="' . $command . '">
        //                     </request>
        //                 </customer> ';
        // requesting the xml data
        // $response = $this->lotsRequest($requestBody);
        // print_r($response->amenities);
            // <name>100 MILE HOUSE</name>
            // <code>247728</code>
            // <countryName>CANADA</countryName>
            // <countryCode>100</countryCode>
//city_code	city_name Ascending 1	city_name_ar	country_code
        $cities = $citiesFile->cities->city;
        $data_cities = [];
        //	infotype_code 	infotype
        foreach ($cities as $ct) {
            $data_cities[] = ['city_code' => (int)$ct->code, 'city_name' => (string)$ct->name, 'country_code'=>(int)$ct->countryCode];
        }
        $data_inserted = $this->lotsData->fillTable($data_cities, 'cities');

        if ($data_inserted) {
            echo "data of $command inserted , successfully";
        }
        echo "<pre>";
        // print_r($response);
        // print_r($data_cities);
        echo "</pre>";
        // print_r($response->amenities->option[2]->attributes()->value);
        // echo($response->amenities->option[2]);

    }

    public function lotsRequest($requestBody)
    {
        $url = 'https://xmldev.dotwconnect.com/gatewayV4.dotw';
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $requestBody);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            throw new Exception(curl_error($curl));
        }
        curl_close($curl);
        return simplexml_load_string($result);
    }

    public function hotelprocess($response = null, $cityid = null)
    {
        $requestBody = ' <customer>
                            <username>medhat85</username>
                            <password>53ee645b8f03f74dabd24cac552ce2ff</password>
                            <id>1742725</id>
                            <source>1</source>
                            <product>hotel</product>
                            <request command="searchhotels">  
                                <bookingDetails>  
                                    <fromDate>2021-02-02</fromDate>  
                                    <toDate>2021-02-03</toDate>  
                                    <currency>492</currency>  
                                    <rooms no="1">  
                                        <room runno="0">  
                                            <adultsCode>1</adultsCode>  
                                            <children no="0"></children>  
                                            <rateBasis>-1</rateBasis>  
                                        </room>  
                                    </rooms>  
                                </bookingDetails>  
                                <return>  
                                    <getRooms>true</getRooms>  
                                    <filters xmlns:a="http://us.dotwconnect.com/xsd/atomicCondition/" xmlns:c="http://us.dotwconnect.com/xsd/complexCondition">  
                                        <city>174</city>  
                                        <noPrice>true</noPrice>  
                                    </filters>  
                                    <fields>  
                                        <field>preferred</field>  
                                        <field>builtYear</field>  
                                        <field>renovationYear</field>  
                                        <field>floors</field>  
                                        <field>noOfRooms</field>  
                                        <field>preferred</field>  
                                        <field>luxury</field>  
                                        <field>fullAddress</field>  
                                        <field>description1</field>  
                                        <field>description2</field>  
                                        <field>hotelName</field>  
                                        <field>address</field>  
                                        <field>zipCode</field>  
                                        <field>location</field>  
                                        <field>locationId</field>  
                                        <field>location1</field>  
                                        <field>location2</field>  
                                        <field>location3</field>  
                                        <field>cityName</field>  
                                        <field>cityCode</field>  
                                        <field>stateName</field>  
                                        <field>stateCode</field>  
                                        <field>countryName</field>  
                                        <field>countryCode</field>  
                                        <field>regionName</field>  
                                        <field>regionCode</field>  
                                        <field>attraction</field>  
                                        <field>amenitie</field>  
                                        <field>leisure</field>  
                                        <field>business</field>  
                                        <field>transportation</field>  
                                        <field>hotelPhone</field>  
                                        <field>hotelCheckIn</field>  
                                        <field>hotelCheckOut</field>  
                                        <field>minAge</field>  
                                        <field>rating</field>  
                                        <field>images</field>  
                                        <field>fireSafety</field>  
                                        <field>hotelPreference</field>  
                                        <field>direct</field>  
                                        <field>geoPoint</field>  
                                        <field>leftToSell</field>  
                                        <field>chain</field>  
                                        <field>lastUpdated</field>  
                                        <field>priority</field>  
                                        <roomField>name</roomField>  
                                        <roomField>roomInfo</roomField>  
                                        <roomField>roomAmenities</roomField>  
                                        <roomField>twin</roomField>  
                                    </fields>  
                                </return>  
                            </request>  
                        </customer> ';
        // requesting the xml data
        $hotels = $this->lotsRequest($requestBody)->hotels->hotel;
        //use dynamic call to get the hotels list
        // $filepath = APPPATH . "/filedb/lots.xml";
        // $res_data = file_exists($filepath) ? simplexml_load_file($filepath)
        //     : exit('Failed to open test.xml.');
        // $hotels = [$res_data->hotels->hotel[66]];
echo "looding";
        foreach ($hotels as $hotel) {
            try {

                $hotelInsertid = False;
                $hotelid = (int)$hotel->attributes()->hotelid;
                if (!$this->lotsData->hotelExist($hotelid)) echo "new $hotelid  hotelexist";
                // continue;

                // exit(" hotel passed 21325685");
                $hotel_data = [
                    'hotelLotsId' => (int)$hotel->attributes()->hotelid,
                    'hslug' => slugify((string)$hotel->hotelName),
                    'hotelChain' => (int)$hotel->chain,
                    'H_Sys_User_ID' => 2,
                    'Hotel_Name' => (string)$hotel->hotelName,
                    'Hotel_Description' => htmlentities((string) $hotel->description2->language . ' ' . (string)$hotel->description1->language),
                    'Main_Photo' => !empty($hotel->images->hotelImages->image) ? str_replace("https://xmldev.stage.aws.dotw.com/", "", (string)$hotel->images->hotelImages->image[0]->url) : '',
                    'Hotel_City_ID' => (int)$hotel->cityCode,
                    'Hotel_Address' => (string)$hotel->fullAddress->hotelStreetAddress,
                    'Hotel_Phone' => (string)$hotel->hotelPhone,
                    'minAge' => (int)$hotel->minAge,
                    'preferred' => (string)$hotel->preferred == 'no' ? 0 : 1,
                    'builtYear' => (int)$hotel->builtYear,
                    'renovationYear' => (int)$hotel->renovationYear,
                    'floors' => (int)$hotel->floors,
                    'noOfRooms' => (int)$hotel->noOfRooms,
                    'checkIn' => (string)$hotel->hotelCheckIn,
                    'checkOut' => (string)$hotel->hotelCheckOut,
                    'lastUpdated' => (string)$hotel->lastUpdated,
                    'fireSafety' => (string)$hotel->fireSafety == 'no' ? 0 : 1,
                    'direct' => (string)$hotel->direct == 'no' ? 0 : 1,
                    'priority' => (int)$hotel->priority,
                    'Star_Nums' => (int)$hotel->rating,
                    'State' => 1
                ];

                // $hotelInsertid = $this->lotsData->insertHotel($hotel_data);
                // if (!$hotelInsertid) continue;

                $hotel_amenities = [];
                if (!empty($hotel->amenitie->language->amenitieItem)) {
                    foreach ($hotel->amenitie->language->amenitieItem as $am) {
                        $hotel_amenities[] = ['hotelid' => $hotelid, 'amenityid' => (int)$am->attributes()->id];
                    }
                }

                $hotel_leisures = [];
                if (!empty($hotel->leisure->language->leisureItem)) {
                    foreach ($hotel->leisure->language->leisureItem as $am) {
                        $hotel_leisures[] = ['hotelid' => $hotelid, 'leisureid' => (int)$am->attributes()->id];
                    }
                }

                $hotel_business = [];
                if (!empty($hotel->business->language->businessItem)) {
                    foreach ($hotel->business->language->businessItem as $am) {
                        $hotel_business[] = ['hotelid' => $hotelid, 'businessid' => (int)$am->attributes()->id];
                    }
                }

                $hotel_transportation = [];
                if (!empty((array)$hotel->transportation)) {
                    foreach ((array)$hotel->transportation as $key => $value) {
                        $trnsp['hotelLotsId'] =  $hotelid;
                        $trnsp['trType'] = rtrim($key, 's');
                        $trnsp['name'] = (string)$value->{$trnsp['trType']}->name;
                        if (empty($trnsp['name'])) continue;
                        $trnsp['distance'] = (string)$value->{$trnsp['trType']}->dist[0] . ' ' . (string)$value->{$trnsp['trType']}->dist[0]->attributes()->attr;
                        $trnsp['distanceTime'] = (string)$value->{$trnsp['trType']}->dist[1] . ' ' . (string)$value->{$trnsp['trType']}->dist[1]->attributes()->attr;
                        $trnsp['directions'] = (string)$value->{$trnsp['trType']}->directions;
                        $hotel_transportation[] = $trnsp;
                    }
                }

                $hotelmap[] = ['hotel_id' => $hotelInsertid, 'hotel_lotsid' => $hotelid, 'lat' => (string)$hotel->geoPoint->lat, 'lng' => (string)$hotel->geoPoint->lng, 'placeid' => ''];

                $hotel_photos = [];
                $order = 1;
                if (!empty($hotel->images->hotelImages->image)) {
                    foreach ($hotel->images->hotelImages->image as $image) {
                        $hotel_photos[] = ['Hotel_ID' => $hotelInsertid, 'hotelLotsId' => $hotelid,    'Photo_Name' => str_replace("https://xmldev.stage.aws.dotw.com/", "", (string)$image->url), 'category' => (string)$image->category, 'altVal' => (string)$image->alt, 'Photo_Order' => $order];
                        $order++;
                    }
                }

                $hotel_rooms = [];
                if (!empty($hotel->rooms->room->roomType)) {
                    foreach ($hotel->rooms->room->roomType as $room) {
                        $hotel_rooms[] = ['roomTypeCode' => (int)$room->attributes()->roomtypecode, 'hotelId' => $hotelid,    'name' => (string)$room->name, 'twin' => $room->twin == 'no' ? False : True, 'maxAdult' => (int)$room->roomInfo->maxAdult, 'maxChildren' => (int)$room->roomInfo->maxChildren, 'maxExtraBed' => (int)$room->roomInfo->maxExtraBed, 'maxCapacity' => (int)$room->roomCapacityInfo->roomPaxCapacity, 'allowedAdultsWithChildren' => (int)$room->roomCapacityInfo->allowedAdultsWithChildren, 'allowedAdultsWithoutChildren' => (int)$room->roomCapacityInfo->allowedAdultsWithoutChildren];
                    }
                }

                $hotel_roomsamenities = [];
                if (!empty($hotel->rooms->room->roomType)) {
                    // print_r($hotel);
                    // exit;
                    if($room->roomAmenities->amenity)var_dump($room->roomAmenities->amenity);exit;

                    foreach ($hotel->rooms->room->roomType as $room) {
                        $roomsamenities = [];

                        foreach ($room->roomAmenities->amenity as $rom) {
                            $roomsamenities[] = ['roomtypeid' => (int)$room->attributes()->roomtypecode, 'hrm_code' => $rom->attributes()->id];
                        }
                        $hotel_roomsamenities[] = $roomsamenities;
                    }
                }

                $hotel_locations = [];
                // finding the loaction related elements
                $locations = preg_grep("/^location/i", array_keys((array)$hotel));
                foreach ($locations as $location) {
                    if (empty((string)$hotel->{$location})) continue;
                    $hotel_locations[] = ['hotelId' => $hotelid, 'locationCode' => (string)$hotel->{$location . 'Id'} ?? Null, 'locationName' => (string)$hotel->{$location}];
                }


                // $insertamenities = $this->lotsData->fillTable($hotel_amenities, 'hotel_amenities');

                // $insertleisures = $this->lotsData->fillTable($hotel_leisures, 'hotel_leisures');

                // $insertbusiness = $this->lotsData->fillTable($hotel_business, 'hotel_business');

                // $inserttransportation = $this->lotsData->fillTable($hotel_transportation, 'hotel_transportation');

                // $mapinsert = $this->lotsData->fillTable($hotelmap, 'hotelmap');

                // $photoinsert = $this->lotsData->fillTable($hotel_photos, 'hotel_photos');

                // $roomsinsert = $this->lotsData->fillTable($hotel_rooms, 'hotel_rooms');
                $roomsAmenitiesinsert = $this->lotsData->fillTable($hotel_roomsamenities, 'hotel_rooms_amenities');

                // $locationinsert = $this->lotsData->fillTable($hotel_locations, 'hotel_locations');
            } catch (Exception $ex) {
                echo $ex;
            }
            // break;
        }
    }
}
