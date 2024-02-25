<?php
// include('Dboperations.php');
// include('UserIDOperations.php');
class TapidoOperations
{

    function registerTapido($data) {
        $phoneNumber = $data["phoneNumber"];
        $dbOperation = new DbOperations();
      
        $isRegistered = $dbOperation->isRegisterDriver($phoneNumber);
		if ($isRegistered) {
			$array = [
				"status" => "falied",
				"message" => "already registerd User"
			];
			echo json_encode($array);
            return;
		}
        $firstName = $data["firstName"];
        $lastName = $data["lastName"];
        $dob = $data["dob"];
        $gender = $data["gender"];
        $houseNumber = $data["houseNumber"];
        $area = $data["area"];
        $city = $data["city"];
        $Zipcode = $data["Zipcode"];
        $adharcardnumber = $data["adharcardnumber"];
        $photo = $data["photo"];
        $drivinglicenseimage = $data["drivinglicenseimage"];
        $verficationStatus = $data["verficationStatus"];
        $date = $data["date"];
        $validityDate = $data["validityDate"];
        header('Content-type: application/json');
        $customerid = UserIDOperations::createUserid();
        $status = $dbOperation->insertintoregisterTable($customerid, $firstName,  $lastName, $dob, $gender, $phoneNumber, $houseNumber, $area, $city,
                                                        $Zipcode, $adharcardnumber, $photo, $drivinglicenseimage, $verficationStatus, $date, $validityDate);
       
        $response = [
            "status" => ($status) ? "success" : "failed",
            "driverId" => $customerid
        ];
        echo json_encode($response);
    }

    public function uploadImage($data)
    {
		$dbOperation = new DbOperations();
        $imageid = UserIDOperations::createUserid();
		$status = $dbOperation->insertIntotapidoimagetable($imageid, $data);
		
		if ($status) {
			$response = [
				"status" => "success",
				"imageId" => $imageid
			];
			header('Content-type: multipart/form-data');
			echo json_encode($response);
		} else {
			$response = [
				"status" =>  "failed"
			];
			header('Content-type: multipart/form-data');
			echo json_encode($response);
		}
       
    }


    function createtripBooking($data) {
        $dbOperation = new DbOperations();
        $bookingId = UserIDOperations::createUserid();
        $bookingDateTime = $data["bookingDateTime"];
        $bookingdistance = $data["bookingdistance"];
        $pickuplocation = $data["pickuplocation"];
        $driverstartlocation = $data["driverstartlocation"];
        $address = $data["address"];
        $customerName = $data["customerName"];
        $customerNumber = $data["customerNumber"];
        $billingAmount = $data["billingAmount"];
        $zipCode = $data["zipCode"];
        $driverId = $data["driverId"];
        $tripStatus = $data["tripStatus"];
        $rewardPoints = $data["rewardPoints"];
        $bookingStatus = $data["bookingStatus"];
        $status = $dbOperation->insertintoBookingTable($bookingId, $bookingDateTime, $bookingdistance, $pickuplocation, 
                                                      $driverstartlocation, $address, $customerName, $customerNumber, 
                                                      $billingAmount, $zipCode, $driverId, $tripStatus, $rewardPoints, $bookingStatus);
       
        $response = [
            "status" => ($status) ? "success" : "failed",
            "bookingId" => $bookingId
        ];
        echo json_encode($response);
    }

    function loginDriver($data) {
        $phoneNumber = $data["phoneNumber"];
        $dbOperation = new DbOperations();
        $isRegistered = $dbOperation->isRegisterDriver($phoneNumber);
		if (!$isRegistered) {
			$array = [
				"status" => "falied",
				"message" => "User is not registered"
			];
			echo json_encode($array);
            return;
		}
        $isVerified = $dbOperation->isDriverVerified($phoneNumber);
        if (!$isVerified) {
			$array = [
				"status" => "falied",
				"message" => "User is not verified"
			];
			echo json_encode($array);
            return;
		}
        $result = $dbOperation->getDriverDetails($phoneNumber);
        $jsonResult = json_encode($result);
        $array = [
            "status" => "success",
            "driverDetails" => $jsonResult
        ];
        echo json_encode($array);
    }
    
    function loginCustomer($data) {
        $phoneNumber = $data["phoneNumber"];
        $dbOperation = new DbOperations();
        $iscustomerExist = $dbOperation->isCustomerExist($phoneNumber);
        if (!$iscustomerExist) {
            $customerid = UserIDOperations::createUserid();
            $dbOperation->loginCustomer($customerid, $phoneNumber);
            $array = [
				"status" => "success",
				"customerid" => $customerid
			];
			echo json_encode($array);
        }
    }

    function updateDriverVerification($data) {
        $phoneNumber = $data["phoneNumber"];
        $dbOperation = new DbOperations();
        $isupdateDriverVerification = $dbOperation->updateDriverVerification($phoneNumber);
        if ($isupdateDriverVerification) {
            $array = [
				"status" => "success"
			];
			echo json_encode($array);
        } else {
            $array = [
				"status" => "failed"
			];
			echo json_encode($array);
        }
    }

}
?>