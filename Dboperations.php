<?php
include('DBValues.php');
class DbOperations
{

    private function dbConnection()
    {
        $servername = DBValues::$servername;
        $username = DBValues::$username;
        $password = DBValues::$password;
        $dbname = DBValues::$dbname;
		
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
		
        // Create connection
        $con = mysqli_connect($servername, $username, $password, $dbname);

        if (mysqli_connect_errno()) {
            // Handle connection error
            die("Failed to connect to MySQL: " . mysqli_connect_error());
        }

        return $con;
    }

    public function insertintoregisterTable($customerid, $firstName, $lastName, $dob, $gender, $phoneNumber, $houseNumber,
                                            $area, $city, $Zipcode, $adharcardnumber, $photo, $drivinglicenseimage, $verficationStatus, $date, $validityDate) { 
        $con = $this->dbTapidoConnection();
        $insertQuery = "INSERT INTO Registration(customerid, firstName, lastName, dob, gender, phoneNumber, houseNumber, area, city, Zipcode, adharcardnumber, photo, drivinglicenseimage, verficationStatus, date, validityDate)
        VALUES ('$customerid', '$firstName', '$lastName', '$dob', '$gender', '$phoneNumber', '$houseNumber', '$area', '$city', '$Zipcode', '$adharcardnumber', '$photo', '$drivinglicenseimage', '$verficationStatus', '$date', '$validityDate')";
        
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
    
    
}

?>
