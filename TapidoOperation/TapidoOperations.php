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
        $phoneNumber = $data["phoneNumber"];
        $veficationStatus = "false";
        
        header('Content-type: application/json');
        $driverID = UserIDOperations::createUserid();
        $status = $dbOperation->insertintoregisterTable($driverID, $firstName,  $lastName, $phoneNumber, $veficationStatus);
       
        $response = [
            "status" => ($status) ? "success" : "failed",
            "data" => ["driverId" => $driverID]
        ];
        echo json_encode($response);
    }

    public function uploadImage($data)
    {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            try {
                // Get the contents of the file
                $imageData = file_get_contents($_FILES['image']['tmp_name']);
    
                // Validate that the data is not empty
                if (empty($imageData)) {
                    throw new Exception('Failed to read image data.');
                }
    
                $dbOperation = new DbOperations();
                $imageid = UserIDOperations::createUserid();
    
                // Insert into the database using parameterized query
                $status = $dbOperation->insertIntotapidoimagetable($imageid, $imageData);
    
                if ($status) {
                    $response = [
                        "status" => "success",
                        "imageId" => $imageid
                    ];
                    echo json_encode($response);
                } else {
                    $response = [
                        "status" => "failed",
                        "error" => "Failed to insert data into the database."
                    ];
                    echo json_encode($response);
                }
            } catch (Exception $e) {
                $response = [
                    "status" => "failed",
                    "error" => $e->getMessage()
                ];
                echo json_encode($response);
            }
        } else {
            $response = [
                "status" => "failed",
                "error" => "Failed to upload the image. Check the file and try again."
            ];
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

    function addCarToDriver($data) {
        $dbOperation = new DbOperations();
        $carID = UserIDOperations::createUserid();
        $carNumber = $data["carNumber"];
        $carModel = $data["carModel"];
        $carregistrationPhoto = $data["carregistrationPhoto"];
        $seater = $data["seater"];
        $cartype = $data["cartype"];
        $driverID = $data["driverID"];
        $carPhoto = $data["carPhoto"];
        $carverificationStatus = "false";
        $status = $dbOperation->insertintocarsTable($carID, $carNumber, $carModel, $carregistrationPhoto,
                                         $seater, $cartype, $driverID, $carPhoto, $carverificationStatus);
          if ($status) {
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

    function updateCarVerification($data) {
        $dbOperation = new DbOperations();
        $carID = $data["carID"];
        $status = $dbOperation->updateCarVerification($carID);
        if ($status) {
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

    public function loadTapidoImage($id)
    {
		$dbOperation = new DbOperations();
		$dbOperation->loadTapidoImage($id);	
    }

    function addDrivingLicence($data) {
        
        $driverId = $data["driverId"];
       
        $licenseImage = $data["licenseImage"];
        $dbOperation = new DbOperations();
        $status = $dbOperation->insertIntodrivinglicence($driverId,$licenseImage);
        if($status) {
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

    function addSocialDocument($data) {
        $driverId = $data["driverId"];
        $socialNumber = $data["socialNumber"];
        $dbOperation = new DbOperations();
        $status = $dbOperation->insertIntoSocialDocument($driverId,$socialNumber);
        if($status) {
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

    function addVehicleTable($data) {
        $driverId = $data["driverId"];
        $year = $data["year"];
        $make = $data["make"];
        $model = $data["model"];
        $color = $data["color"];
        $doors = $data["doors"];
        $carNumber = $data["carNumber"];
        
        $dbOperation = new DbOperations();
        $status = $dbOperation->insertIntoVehicleTable($driverId,$year,$make,$model,$color,$doors,$carNumber);
        if($status) {
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

    function addProfilePhoto($data) {
        $driverId = $data["driverId"];
        $fullAddress = $data["fullAddress"];
        $photo = $data["photo"];
        $dbOperation = new DbOperations();
        $status = $dbOperation->insertIntoProfilePhoto($driverId,$fullAddress,$photo);
        if($status) {
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

    function getDocumentsubmitionDetails($data) {
        $driverId = $data["driverId"];
        $dbOperation = new DbOperations();
        $datavalue = $dbOperation->getDocumentsDetails($driverId);
        $array = [
            "status" => "success",
            "data" => $datavalue
        ];
        echo json_encode($array);
    }

    function signinTapido($data) {
        $phoneNumber = $data["phoneNumber"];
        $dbOperation = new DbOperations();
        $array = [];
        if($dbOperation->isRegisterDriver($phoneNumber)) {
            $datavalue = $dbOperation->isRegisteredUser($phoneNumber);
            $array = [
                "status" => "success",
                "data" => $datavalue
            ];
        } else {
            
            $array = [
                "status" => "failed",
                "message" => "Oops! It looks like you're not a registered user yet. Please sign up to access Thank you!"
            ];
        }
        
        echo json_encode($array);
    }

    function updateDriverTable($data) {
        $driverID = $data["driverID"];
        $driverActiveLocation = $data["driverActiveLocation"];
        $driverActiveZipcode = $data["driverActiveZipcode"];
        $driverStatus = $data["driverStatus"];
        $ontripStatus = $data["ontripStatus"];
        $driverCity = $data["driverCity"];
        $driverState = $data["driverState"];
        $dbOperation = new DbOperations();
        $status = $dbOperation->updatedrivertable($driverID, $driverActiveLocation, $driverActiveZipcode, $driverStatus, $ontripStatus, $driverCity, $driverState);
        $array = [];
        if($status) {
            $array = [
                "status" => "success"
            ];
        } else {
            $array = [
                "status" => "failed",
                "message" => "Server is currently undergoing maintenance. We apologize for the inconvenience."
            ];
        }
        echo json_encode($array);
    }

    function getDriverStatus($data) {
        $driverID = $data["driverId"];
        $dbOperation = new DbOperations();
        $status = $dbOperation->getDriverStatus($driverID);
        $array = [];
        if($status) {
            $array = [
                "status" => "success",
                "driverActiveStatus" => $status
            ];
        } else {
            $array = [
                "status" => "failed",
                "message" => "Server is currently undergoing maintenance. We apologize for the inconvenience."
            ];
        }
        echo json_encode($array);
    }

    function updatedrivertableNotification($data) {
        $driverID = $data["driverId"];
        $notificationToken = $data["notificationToken"];
        $platform = $data["platform"];
        $dbOperation = new DbOperations();
        $status = $dbOperation->updatedrivertableNotification($driverID,$notificationToken,$platform);
        $array = [];
        if($status) {
            $array = [
                "status" => "success",
                "data" => ["token" => $notificationToken]
            ];
        } else {
            $array = [
                "status" => "failed",
                "message" => "Server is currently undergoing maintenance. We apologize for the inconvenience."
            ];
        }
        echo json_encode($array);
    }
}
?>