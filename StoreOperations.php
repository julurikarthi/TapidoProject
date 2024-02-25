<?php
include('Dboperations.php');
include('UserIDOperations.php');
class StoreOperations
{
	
	
     function registerOwner($data)
    {
		$dbOperation = new DbOperations();
		
        $phoneNumber = $data["phoneNumber"];
        $isRegistered = $dbOperation->isRegisterOwner($phoneNumber);
	
        if ($isRegistered) {
            $response = [
                "status" => "failed",
                "message" => "User already registered"
            ];
        } else {
            $customerName = $data["customerName"];
            $storeName = $data["storeName"];
            $address = $data["address"];
            $area = $data["area"];
            $city = $data["city"];
            $pincode = $data["pincode"];
            $password = $data["password"];
            $rating = "0";
            $storeId = UserIDOperations::createUserid();

            $status = $dbOperation->insertintoStoreTable($customerName, $storeName, $phoneNumber, $storeId, $address, $area, $city, $pincode, $rating, $password);

            $response = [
                "status" => ($status) ? "success" : "failed",
                "storeId" => $storeId
            ];
        }

        header('Content-type: application/json');
        echo json_encode($response);
    }

	public function loginOwnerUSer($data) {
		$dbOperation = new DbOperations();
		$phoneNumber = $data["phoneNumber"];
		$password = $data["password"];
		$isRegistered = $dbOperation->isRegisterOwner($phoneNumber);
		if (!$isRegistered) {
			$array = [
				"status" => "falied",
				"storeId" => "store not registered"
			];
			echo json_encode($array);
		} else {
			$ownerData = $dbOperation -> getOwnerDetails($phoneNumber, $password);
			header('Content-type: application/json');
			if(count($ownerData) > 0) {
				$array["status"] = "success";
				echo json_encode($ownerData);
			} else {
				$array = [
					"status" => "falied",
					"storeId" => "login failed please check password"
				];
				echo json_encode($array);
			}
			
		}
		
	}

    public function uploadImage($data)
    {
		$dbOperation = new DbOperations();
        $imageid = UserIDOperations::createUserid();
		$status = $dbOperation->insertIntoImagesTable($imageid, $data);
		
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
	
    public function loadImage($id)
    {
		$dbOperation = new DbOperations();
		$dbOperation->loadImage($id);	
    }

	public function addProduct($data) {
		$dbOperation = new DbOperations();
        $producid = UserIDOperations::createUserid();
		$imgs = $data["images"];
		$addmoreOptions = $data["addmoreOptions"];
		$addmoreOptionsString = "ewwew";
		$stringimagesData = implode(', ', $imgs);
		$status = $dbOperation->insertintoAllProducts($producid, $data["name"], $data["brand"], $stringimagesData, $data["instock"], $data["details"], $addmoreOptionsString, $data["category"]);
		if ($status) {
			$response = [
				"status" => "success"
			];
			header('Content-type: application/json');
			echo json_encode($response);
		} else {
			$response = [
				"status" =>  "failed"
			];
			header('Content-type: application/json');
			echo json_encode($response);
		}
	}
	
}

?>
