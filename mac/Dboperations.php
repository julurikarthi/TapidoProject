<?php
include('DBValues.php');
class DbOperations
{

    private function dbConnection()
    {
        $servername = DBValues::$servername;
        $username = DBValues::$username;
        $password = DBValues::$password;
        $dbname = DBValues::$dbTapidoname;
        // Create connection
        $con = mysqli_connect($servername, $username, $password, $dbname);
        if (mysqli_connect_errno()) {
            // Handle connection error
            die("Failed to connect to MySQL: " . mysqli_connect_error());
        }

        return $con;
    }

	public function isRegisterOwner($phoneNumber)
    {
		$con = $this->dbConnection();
		$insertQuery = "select * FROM StoresTable WHERE phoneNumber = '$phoneNumber'";
		
		$result = mysqli_query($con, $insertQuery);
		$rowcount = mysqli_num_rows($result);
		mysqli_close($con);
		if ($rowcount > 0) {
			return true;
		}
		return false;
    }
	
	public function insertintoStoreTable($customerName, $storeName,
												 $phoneNumber, $storeId, $address,
												  $area, $city, $pincode, $rating, $password) {
		$con = $this->dbConnection();
		$insertQuery = "INSERT INTO StoresTable(customerName, storeName, phoneNumber, storeId, address, area, city, pincode, rating, password) VALUES ('$customerName', '$storeName', '$phoneNumber', '$storeId', '$address', '$area', '$city','$pincode', '$rating', '$password')";
		if(mysqli_query($con, $insertQuery)){
			return TRUE;
		} else {
			return FALSE;

		} 
		mysqli_close($con);
	}
	
	public function getOwnerDetails($phoneNumber, $password)
    {
		$con = $this->dbConnection();
        $insertQuery = "SELECT * FROM StoresTable WHERE phoneNumber = ?";
        $stmt = mysqli_prepare($con, $insertQuery);

        if ($stmt === false) {
            // Handle statement preparation error
            die("Error in preparing statement: " . mysqli_error($con));
        }

        mysqli_stmt_bind_param($stmt, "s", $phoneNumber);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $array = [];

        if ($row = mysqli_fetch_assoc($result)) {
            if ($row['password'] === $password) {
                $array["customerName"] = $row['customerName'];
                $array["storeName"] = $row['storeName'];
                $array["storeId"] = $row['storeId'];
                $array["area"] = $row['area'];
                $array["city"] = $row['city'];
                $array["pincode"] = $row['pincode'];
                $array["rating"] = $row['rating'];
            } else {
                $array["error"] = "password wrong";
            }
        }

        mysqli_stmt_close($stmt);
        mysqli_close($con);

        return $array;
    }

	function loadImage($id) {
        $con = $this->dbConnection();
        $selectQuery = "SELECT imageData FROM ImagesTable WHERE id = ?";
        $stmt = $con->prepare($selectQuery);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($imageData);
            $stmt->fetch();

            header("Content-Type: image/jpeg"); // Change the content type based on your image type
            echo $imageData;
        } else {
            echo "Image not found.";
        }

        $stmt->close();
        $con->close();
	}

	public function insertIntoImagesTable($imageid, $file) {
		$con = $this->dbConnection();
		
		$sql = "INSERT INTO `ImagesTable` (`id`, `imageData`) VALUES (?, ?)";
		$stmt = $con->prepare($sql);
		$stmt->bind_param("ss", $imageid, $file);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			return false;
		}
	}

    public function insertIntotapidoimagetable($imageid, $file) {
		$con = $this->dbTapidoConnection();
		
		$sql = "INSERT INTO `ImagesTable` (`id`, `imageData`) VALUES (?, ?)";
		$stmt = $con->prepare($sql);
		$stmt->bind_param("ss", $imageid, $file);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			return false;
		}
	}

    public function insertintoAllProducts($pid, $name, $brand, $images, $instock, $details, $addmoreOptions, $category) {
      
		$insertQuery = "INSERT INTO AllProducts(pid, name, brand, images, instock, details, addmoreOptions, category) VALUES ('$pid', '$name', '$brand', '$images', '$instock', '$details', '$addmoreOptions', '$category')";
        $status =  $this->storedatainDB($insertQuery);
        return $status;
	}

    private function storeDataInDB($insertQuery) {
        $con = $this->dbConnection();
    
        $result = mysqli_query($con, $insertQuery);
    
        if ($result === false) {
            // There was an error in the query
            $errorMessage = mysqli_error($con);
            mysqli_close($con);
    
            // You can log the error or handle it in some way
            // For example, you can echo the error message
            echo "Error: " . $errorMessage;
    
            return false;
        } else {
            // Query was successful
            mysqli_close($con);
            return true;
        }
    }

    private function topiconDatastoreInDB($insertQuery) {
        $con = $this->dbTapidoConnection();
    
        $result = mysqli_query($con, $insertQuery);
    
        if ($result === false) {
            // There was an error in the query
            $errorMessage = mysqli_error($con);
            mysqli_close($con);
    
            // You can log the error or handle it in some way
            // For example, you can echo the error message
            echo "Error: " . $errorMessage;
    
            return false;
        } else {
            // Query was successful
            mysqli_close($con);
            return true;
        }
    }

    private function dbTapidoConnection()
    {
        $servername = DBValues::$servername;
        $username = DBValues::$username;
        $password = DBValues::$password;
        $dbname = DBValues::$dbTapidoname;
        $port = 3060;
        // Create connection
      
        $con = mysqli_connect($servername, $username, $password, $dbname, $port);

        if (!$con) {
            die("Failed to connect to MySQL: " . mysqli_connect_error());
        }
        if (mysqli_connect_errno()) {
            // Handle connection error
            die("Failed to connect to MySQL: " . mysqli_connect_error());
        }

        return $con;
    }

    public function insertintoregisterTable($customerid, $firstName, $lastName, $phoneNumber, $verificationStatus) { 
        $con = $this->dbTapidoConnection();
        $currentDate = date('m-d-Y');
        $insertQuery = "INSERT INTO Registration(firstName, lastName, phoneNumber, registrationdate, driverId, verificationStatus)
        VALUES ('$firstName', '$lastName', '$phoneNumber', '$currentDate', '$customerid', '$verificationStatus')";
        
        $status =  $this->topiconDatastoreInDB($insertQuery);
        return $status;
    }

    public function insertintoBookingTable($bookingId, $bookingDateTime, $bookingdistance, $pickuplocation, $driverstartlocation, $address, $customerName,
                                            $customerNumber, $billingAmount, $zipCode, $driverId, $tripStatus, $rewardPoints, $bookingStatus) { 
        $con = $this->dbTapidoConnection();
        $insertQuery = "INSERT INTO BookingsTable(bookingId, bookingDateTime, bookingdistance, pickuplocation, driverstartlocation, phoneNumber, houseNumber, area, city, Zipcode, adharcardnumber, photo, drivinglicenseimage, verficationStatus, date, validityDate)
        VALUES ('$bookingId', '$bookingDateTime', '$bookingdistance', '$pickuplocation', '$driverstartlocation', '$address', '$customerName', '$customerNumber', '$billingAmount', '$zipCode', '$driverId', '$tripStatus', '$rewardPoints', '$bookingStatus')";
        
        $status =  $this->topiconDatastoreInDB($insertQuery);
        return $status;
    }

    public function insertIntoTapidoImagesTable($imageid, $file) {
		$con = $this->dbTapidoConnection();
		
		$sql = "INSERT INTO `ImagesTable` (`id`, `imageData`) VALUES (?, ?)";
		$stmt = $con->prepare($sql);
		$stmt->bind_param("ss", $imageid, $file);

		if ($stmt->execute()) {
			$stmt->close();
			return true;
		} else {
			return false;
		}
	}

    public function isRegisterDriver($phoneNumber)
    {
	    $con = $this->dbTapidoConnection();
		$insertQuery = "select * FROM Registration WHERE phoneNumber = '$phoneNumber'";
		
		$result = mysqli_query($con, $insertQuery);
		$rowcount = mysqli_num_rows($result);
		mysqli_close($con);
		if ($rowcount > 0) {
			return true;
		}
		return false;
    }
    
    public function isDriverVerified($phoneNumber) {
        $con = $this->dbTapidoConnection();
		$insertQuery = "select * FROM Registration WHERE phoneNumber = '$phoneNumber'";
		$result = mysqli_query($con, $insertQuery);
		$rowcount = mysqli_num_rows($result);
		mysqli_close($con);
		if ($rowcount > 0) {
           
            $driverDetails = mysqli_fetch_assoc($result);
            $isVerified = $driverDetails['verficationStatus'];
            if ($isVerified == "true") {
                return true;
            }
			return false;
		}
		return false;
    }

    public function getDriverDetails($phoneNumber) {
        $con = $this->dbTapidoConnection();
		$insertQuery = "select * FROM Registration WHERE phoneNumber = '$phoneNumber'";
		$result = mysqli_query($con, $insertQuery);
        $data = array();

        // Fetch each row and add it to the result array
        while ($row = mysqli_fetch_assoc($result)) {
            $data = $row;
        }
        return $data;
    }
    
    public function updateDriverVerification($phoneNumber) {
        $con = $this->dbTapidoConnection();
    
        // Check if the phone number exists before updating
        $checkQuery = "SELECT * FROM Registration WHERE phoneNumber = '$phoneNumber'";
        $checkResult = mysqli_query($con, $checkQuery);
    
        if ($checkResult && mysqli_num_rows($checkResult) > 0) {
            // The phone number exists, proceed with the update
            $updateQuery = "UPDATE Registration SET verficationStatus = 'true' WHERE phoneNumber = '$phoneNumber'";
            $updateResult =  $this->topiconDatastoreInDB($updateQuery);
            
            if ($updateResult) {
                // Return true to indicate success
                return true;
            } else {
                
                // Handlfe the update query error
                return false;
            }
        } else {
            // The phone number does not exist, handle accordingly
            return false;
        }
    }
    

    function loginCustomer($customerID,$phoneNumber) {
        $con = $this->dbTapidoConnection();
        $insertQuery = "INSERT INTO CustomersTable(customerID, phoneNumber)
        VALUES ('$customerID', '$phoneNumber')";
        $status =  $this->topiconDatastoreInDB($insertQuery);
        return $status;
    }

    public function isCustomerExist($phoneNumber) {
        $con = $this->dbTapidoConnection();
    
        // Sanitize input to prevent SQL injection
        $phoneNumber = mysqli_real_escape_string($con, $phoneNumber);
    
        $checkQuery = "SELECT COUNT(*) as count FROM CustomersTable WHERE phoneNumber = '$phoneNumber'";
        $checkResult = mysqli_query($con, $checkQuery);
    
        // Check if the query was successful
        if ($checkResult) {
            $row = mysqli_fetch_assoc($checkResult);
            $count = $row['count'];
    
            // If count is greater than 0, customer with the given phone number exists
            $exists = ($count > 0);
    
            // Free the result set
            mysqli_free_result($checkResult);
    
            // Close the database connection
            mysqli_close($con);
    
            return $exists;
        } else {
            // Handle the query error
            mysqli_close($con);
            return false;
        }
    }
    
    public function getCustomerDetails($customerID) {
        $con = $this->dbTapidoConnection();
    
        // Sanitize input to prevent SQL injection
        $customerID = mysqli_real_escape_string($con, $customerID);
    
        $selectQuery = "SELECT * FROM CustomersTable WHERE customerID = '$customerID'";
        $result = mysqli_query($con, $selectQuery);
    
        // Check if the query was successful
        if ($result) {
            // Fetch the customer details
            $customerDetails = mysqli_fetch_assoc($result);
    
            // Free the result set
            mysqli_free_result($result);
    
            // Close the database connection
            mysqli_close($con);
    
            return $customerDetails;
        } else {
            // Handle the query error
            mysqli_close($con);
            return false;
        }
    }
    
    
    function updateCustomerLocationAndZipcode($customerID, $newLocation, $newZipcode) {
        $con = $this->dbTapidoConnection();
    
        // Check if the customer exists before updating
        $checkQuery = "SELECT * FROM CustomersTable WHERE customerID = '$customerID'";
        $checkResult = mysqli_query($con, $checkQuery);
    
        if ($checkResult) {
            // Check if a record with the given customerID exists
            if (mysqli_num_rows($checkResult) > 0) {
                // The customer exists, proceed with the update query
                $updateQuery = "UPDATE CustomersTable SET customerActiveLocation = '$newLocation', customerActiveZipcode = '$newZipcode' WHERE customerID = '$customerID'";
                $updateResult = $this->topiconDatastoreInDB($updateQuery);
    
                if ($updateResult) {
                    // Update successful
                    return true;
                } else {
                    // Update failed
                    return false;
                }
            } else {
                // No record found with the given customerID
                return false;
            }
    
            // Free the result set
            mysqli_free_result($checkResult);
        } else {
            // Query error
            return false;
        }
    
        // Close the database connection
        mysqli_close($con);
    }


    public function insertintocarsTable($carID, $carModel, $carNumber, $carregistrationPhoto, $seater, $cartype, $driverID,
                                            $carPhoto, $carverificationStatus) { 
        $con = $this->dbTapidoConnection();
        $insertQuery = "INSERT INTO CarsTable(carID, carModel, carNumber, carregistrationPhoto, seater, cartype, driverID, carPhoto, carverificationStatus)
        VALUES ('$carID', '$carModel', '$carNumber', '$carregistrationPhoto', '$seater', '$cartype', '$driverID', '$carPhoto', '$carverificationStatus'')";
        
        $status =  $this->topiconDatastoreInDB($insertQuery);
        return $status;
    }


    public function updateCarVerification($carID) {
        $con = $this->dbTapidoConnection();
    
        // Check if the phone number exists before updating
        $checkQuery = "SELECT * FROM CarsTable WHERE carID = '$carID'";
        $checkResult = mysqli_query($con, $checkQuery);
    
        if ($checkResult && mysqli_num_rows($checkResult) > 0) {
            // The phone number exists, proceed with the update
            $updateQuery = "UPDATE CarsTable SET carverificationStatus = 'true' WHERE carID = '$carID'";
            $updateResult =  $this->topiconDatastoreInDB($updateQuery);
            
            if ($updateResult) {
                // Return true to indicate success
                return true;
            } else {
                
                // Handlfe the update query error
                return false;
            }
        } else {
            // The phone number does not exist, handle accordingly
            return false;
        }
    }
    

    function loadTapidoImage($id) {
        $con = $this->dbTapidoConnection();
        $selectQuery = "SELECT imageData FROM ImagesTable WHERE id = ?";
        $stmt = $con->prepare($selectQuery);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($imageData);
            $stmt->fetch();

            header("Content-Type: image/png"); // Change the content type based on your image type
            echo $imageData;
        } else {
            echo "Image not found.";
        }

        $stmt->close();
        $con->close();
    }


    public function insertIntodrivinglicence($driverId, $licenseImage) {
		$con = $this->dbTapidoConnection();
		$insertQuery = "INSERT INTO DrivingLicenseTable (driverId, licenseImage) VALUES ('$driverId','$licenseImage')";
        $status =  $this->topiconDatastoreInDB($insertQuery);
		return $status;
	}

    public function insertIntoSocialDocument($driverId, $socialNumber) {
		$con = $this->dbTapidoConnection();
		
		$insertQuery = "INSERT INTO SocialDocument (driverId, socialNumber) VALUES ('$driverId', '$socialNumber')";
        $status =  $this->topiconDatastoreInDB($insertQuery);
		return $status;
	}

    public function insertIntoProfilePhoto($driverId, $fulladdress, $photo) {
		$con = $this->dbTapidoConnection();
		$insertQuery = "INSERT INTO ProfileDetails (driverId, fullAddress, photo) VALUES ('$driverId', '$fulladdress','$photo')";
        $status =  $this->topiconDatastoreInDB($insertQuery);
		return $status;
	}

    public function insertIntoVehicleTable($driverId, $year, $make, $model, $color, $doors, $carNumber) {
		$con = $this->dbTapidoConnection();
		$insertQuery = "INSERT INTO VehicleTable (driverId, year, make, model, color, doors, carNumber) VALUES ('$driverId', '$year', '$make','$model' ,'$color', '$doors', '$carNumber')";
        $status =  $this->topiconDatastoreInDB($insertQuery);
		return $status;
	}

    public function getDocumentsDetails($driverId) {
        $con = $this->dbTapidoConnection();
        $vehiclefound = "SELECT * FROM VehicleTable WHERE driverId = '$driverId'";
        $socialfound = "SELECT * FROM SocialDocument WHERE driverId = '$driverId'";
        $profiledetails = "SELECT * FROM ProfileDetails WHERE driverId = '$driverId'";
        $drivinglicensedetails = "SELECT * FROM DrivingLicenseTable WHERE driverId = '$driverId'";

        $vehiclefoundresult = mysqli_query($con, $vehiclefound);
        $socialfoundresult = mysqli_query($con, $socialfound);
        $profiledetailsresult = mysqli_query($con, $profiledetails);
        $drivingdetailsfoundresult = mysqli_query($con, $drivinglicensedetails);

		$vehicleexist = mysqli_num_rows($vehiclefoundresult);
        $socialdocumentsexist= mysqli_num_rows($socialfoundresult);
        $profiledetailsexist = mysqli_num_rows($profiledetailsresult);
        $drivingdetailsexist = mysqli_num_rows($drivingdetailsfoundresult);
      
		$documentexist = [
            "vehicleexist" => $vehicleexist > 0,
            "socialdocumentsexist" => $socialdocumentsexist > 0,
            "profiledetailsexist" => $profiledetailsexist > 0,
            "drivingdetailsexist" => $drivingdetailsexist > 0,
        ];
        mysqli_close($con);
        return $documentexist;
    }   

    public function isRegisteredUser($phoneNumber) {
        $con = $this->dbTapidoConnection();
        $registration = "SELECT driverId, verificationStatus, firstName, lastName, registrationdate, phoneNumber FROM Registration WHERE phoneNumber = '$phoneNumber'";
       
        $result = mysqli_query($con, $registration);
        $registrationlexist = mysqli_num_rows($result);
        $driverID = null;
        if ($result) {
            // Check if any row is returned
            if (mysqli_num_rows($result) > 0) {
                // Fetch the row
                $row = mysqli_fetch_assoc($result);
                $driverID = $row['driverId'];
                $verificationStatus = $row['verificationStatus'];
                $firstName = $row['firstName'];
                $lastName = $row['lastName'];
                $registrationdate = $row['registrationdate'];
                $phoneNumber = $row['phoneNumber'];
               
            } else {
                $driverID = null;
            }
        } else {
            // Error executing the query
            $driverID = null;
        }
        $userexist = [
            "isRegistered" => true,
            "driverID" =>  $driverID,
            "verificationStatus" => $verificationStatus,
            "firstName" => $firstName,
            "lastName" => $lastName,
            "registrationdate" => $registrationdate,
            "phoneNumber" => $phoneNumber
        ];
        mysqli_close($con);
        return $userexist;
    }

    public function updatedrivertable($driverID, $driverActiveLocation, $driverActiveZipcode, $driverStatus, $ontripStatus, $driverCity, $driverState)  {
		$insertQuery = "INSERT INTO DriversTable (driverId, driverActiveLocation, driverActiveZipcode, driverStatus, ontripStatus, driverCity, driverState) 
        VALUES ('$driverID', '$driverActiveLocation', '$driverActiveZipcode', '$driverStatus', '$ontripStatus', '$driverCity', '$driverState')
        ON DUPLICATE KEY UPDATE 
            driverActiveLocation = VALUES(driverActiveLocation),
            driverActiveZipcode = VALUES(driverActiveZipcode),
            driverStatus = VALUES(driverStatus),
            ontripStatus = VALUES(ontripStatus),
            driverCity = VALUES(driverCity),
            driverState = VALUES(driverState);
        ";
        $status =  $this->topiconDatastoreInDB($insertQuery);
        return $status;
    }


    public function updatedrivertableNotification($driverID, $notificationId, $platform)  {
        $con = $this->dbTapidoConnection();
		$insertQuery = "INSERT INTO DriversNotificationTable (driverId, notificationId, platform) 
        VALUES ('$driverID', '$notificationId', '$platform')
        ON DUPLICATE KEY UPDATE 
            driverId = VALUES(driverId),
            notificationId = VALUES(notificationId),
            platform = VALUES(platform)
        ";
        $status =  $this->topiconDatastoreInDB($insertQuery);
        return $status;
    }

    
    public function getDriverStatus($driverID)  {
        $con = $this->dbTapidoConnection();
        $registration = "SELECT driverStatus FROM DriversTable WHERE driverId = '$driverID'";
        $result = mysqli_query($con, $registration);
        if ($result) {
            // Check if any row is returned
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $driverStatus = $row['driverStatus'];
                if ($driverStatus === null) {
                    $driverStatus = "false";
                } 
            }
        }
        return $driverStatus;
    }

public function updateRidesTable($rideId, $customerState, $customerId, $customerPickupLocation, $customerPickupAddress, $customerPickupZipcode, $customerDestinationLocation,
                                  $customerDestinationAddress, $customerDestinationZipcode, $driverLocation, $driverAddress, $driverZipcode, $rideDistance, $rideDuration, $rideAmount,
                                  $vehicleNumber, $rideCreateTime, $rideEndTime, $driverId, $cancelRideCustomer, $cancelRideDriver) {        
   
    $insertQuery = "
    INSERT INTO RidesTables 
    (rideId, customerId, customerpickuplocation, customerpickupaddress, customerpickupzipcode, customerState, customerdestinationlocation, customerdestinationaddress, customerdestinationzipcode, driverlocation, driveraddress, drivernzipcode, ridedistance, rideduration, rideamount, vehicleNumber, ridecreatetime, rideendtime, driverId, cancelridecustomer, cancelridedriver) 
VALUES 
    ('$rideId', '$customerId', '$customerPickupLocation', '$customerPickupAddress', '$customerPickupZipcode', '$customerState' ,'$customerDestinationLocation', '$customerDestinationAddress', '$customerDestinationZipcode', '$driverLocation', '$driverAddress', '$driverZipcode', '$rideDistance', '$rideDuration', '$rideAmount', '$vehicleNumber', '$rideCreateTime', '$rideEndTime', '$driverId', '$cancelRideCustomer', '$cancelRideDriver')
ON DUPLICATE KEY UPDATE 
    customerId = VALUES(customerId),
    customerpickuplocation = VALUES(customerpickuplocation),
    customerpickupaddress = VALUES(customerpickupaddress),
    customerpickupzipcode = VALUES(customerpickupzipcode),
    customerstate = VALUES(customerstate),
    customerdestinationlocation = VALUES(customerdestinationlocation),
    customerdestinationaddress = VALUES(customerdestinationaddress),
    customerdestinationzipcode = VALUES(customerdestinationzipcode),
    driverlocation = VALUES(driverlocation),
    driveraddress = VALUES(driveraddress),
    drivernzipcode = VALUES(drivernzipcode),
    ridedistance = VALUES(ridedistance),
    rideduration = VALUES(rideduration),
    rideamount = VALUES(rideamount),
    vehicleNumber = VALUES(vehicleNumber),
    ridecreatetime = VALUES(ridecreatetime),
    rideendtime = VALUES(rideendtime),
    driverId = VALUES(driverId),
    cancelridecustomer = VALUES(cancelridecustomer),
    cancelridedriver = VALUES(cancelridedriver);
";
    // Execute the SQL statement
    $status =  $this->topiconDatastoreInDB($insertQuery);
    // Return the status
    return $status;
}


function getdriversByState($state) {
    $insertQuery =   "SELECT driverId, driverActiveLocation, driverActiveZipcode FROM DriversTable WHERE driverState = '$state'";
    $con = $this->dbTapidoConnection();
    $result = mysqli_query($con, $insertQuery);
    $data = array();
    $count = mysqli_num_rows($result); // Get the count of rows
    
    // Fetch each row and add it to the result array
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

function getNotificationIDByDriverID($driverId) {
  
    $insertQuery =   "SELECT driverId, notificationId, platform FROM DriversNotificationTable WHERE driverId = '$driverId'";
    $con = $this->dbTapidoConnection();
    $result = mysqli_query($con, $insertQuery);
    $data = array();
     // Check if any rows are returned
     if ($result && mysqli_num_rows($result) > 0) {
        // Fetch each row and add it to the result array
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    } else {
        $data = null; // or array(), depending on your preference
    }
    return $data;
}

}   

?>
