<?php

include('StoreOperations.php');
include('TapidoOperation/TapidoOperations.php');
 //Make sure that it is a POST request.
 

 // Check if the request is for the getCarsModels endpoint
 if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getCarsModels') {
	$filename = 'CarsModel.json';
	
	// Check if the file exists
	if (file_exists($filename)) {
		// Read the contents of the file
		$data = file_get_contents($filename);
		$phpArray = json_decode($data, true);

		$years = ["2008","2009", "2010","2011","2012","2013","2014","2015","2016","2017","2019","2020","2021","2022","2023","2024", "2025"];
		$doors = ["2 doors", "3 doors", "4 doors", "5+ doors"];
		$carcolors = ["Black", "Blue", "Gold", "Burgundy", "Gray", "Green", "Orange", "Purple", "Red", "Silver", "Tan", "Teal", "White", "Yellow"];
		$responsedata = ["cars" => $phpArray, "carcolors" => $carcolors, "years"=>$years, "doors" => $doors];
		// Output the data (you can perform further processing here)
		$response = [
            "status" => "success",
			"data" => $responsedata
        ];
        echo json_encode($response);
	} else {
		echo 'File not found.';
	}
	return;
 }
 

 if ($_SERVER["REQUEST_METHOD"] == "GET") {
	header('Content-Type: application/json');

    // Get the path from the URL
    $path = $_SERVER['REQUEST_URI'];

    // Check if the path ends with "/test"
    if (substr($path, -5) === "/test") {
        $response = [
            "status" => "success",
			"data" => ["status" => "success"]
        ];
        echo json_encode($response);
		return;
    } 

	if (substr($path, -5) === "/countries") {
		$filename = 'countries.json';

		// Check if the file exists
		if (file_exists($filename)) {
			// Read the contents of the file
			$data = file_get_contents($filename);
		
			// Output the data (you can perform further processing here)
			echo $json_encode($data);
		} else {
			echo 'File not found.';
		}
		return;
    } 

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $storeOpration = new TapidoOperations();
        $storeOpration->loadTapidoImage($id);
    } else {
        echo json_encode(["error" => "Please provide an image ID."]);
    }
}
 if($_SERVER['REQUEST_METHOD'] === 'POST'){

	$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';		
	$content = trim(file_get_contents("php://input"));

	//Attempt to decode the incoming RAW post data from JSON.
	$decoded = json_decode($content, true);
	$storeOpration = new StoreOperations();
	$tapidoOperations = new TapidoOperations();
	//If json_decode failed, the JSON is invalid.
	if ($_POST["method"] == Constants::$uploadTapidoImage) {		
		$tapidoOperations->uploadImage($_POST["image"]);
	}

	if($decoded["params"]["method"] == Constants::$ownerRegister) {
		$storeOpration->registerOwner($decoded["params"]["data"]);
	} else if($decoded["params"]["method"] == Constants::$ownerlogin) {
		$storeOpration->loginOwnerUSer($decoded["params"]["data"]);
	} else if($decoded['method'] == Constants::$addImages) {
		$storeOpration->uploadImage($decoded['image']);
	} else if($decoded["params"]["method"] == Constants::$addProduct) {
		$storeOpration->addProduct($decoded["params"]["data"]);
	} else if($decoded["params"]["method"] == Constants::$registertapido) {
		$tapidoOperations->registerTapido($decoded["params"]["data"]);
	} else if($decoded["params"]["method"] == Constants::$uploadImage) {
		$tapidoOperations->uploadImage($decoded["params"]["data"]);
	} else if($decoded["params"]["method"] == Constants::$loginDriver) {
		$tapidoOperations->loginDriver($decoded["params"]["data"]);
	} else if($decoded["params"]["method"] == Constants::$updateDriverVerification) {
		$tapidoOperations->updateDriverVerification($decoded["params"]["data"]);
	} else if($decoded["params"]["method"] == Constants::$addDrivingLicense) {
		$tapidoOperations->addDrivingLicence($decoded["params"]["data"]);
	} else if($decoded["params"]["method"] == Constants::$addSocialDocument) {
		$tapidoOperations->addSocialDocument($decoded["params"]["data"]);
	} else if($decoded["params"]["method"] == Constants::$addVehicleDetails) {
		$tapidoOperations->addVehicleTable($decoded["params"]["data"]);
	} else if($decoded["params"]["method"] == Constants::$addProfilePhoto) {
		$tapidoOperations->addProfilePhoto($decoded["params"]["data"]);
	} else if($decoded["params"]["method"] == Constants::$userDoumentpending) {
		$tapidoOperations->getDocumentsubmitionDetails($decoded["params"]["data"]);
	} else if($decoded["params"]["method"] == Constants::$signintapido) {
		$tapidoOperations->signinTapido($decoded["params"]["data"]);
	} else if($decoded["params"]["method"] == Constants::$updateDriverTable) {
		$tapidoOperations->updateDriverTable($decoded["params"]["data"]);
	} else if($decoded["params"]["method"] == Constants::$getDriverStatus) {
		$tapidoOperations->getDriverStatus($decoded["params"]["data"]);
	} else if($decoded["params"]["method"] == Constants::$updatedrivertableNotification) {
		$tapidoOperations->updatedrivertableNotification($decoded["params"]["data"]);
	} else if($decoded["params"]["method"] == Constants::$updateRidesTable) {
		$tapidoOperations->updateRidesTable($decoded["params"]["data"]);
	}
	
} 


class Constants
{
	
	 public static $Customerregister = "customerregister";
	 public static $ownerRegister = "registerOwner";
	 public static $addProduct = "addProduct";
	 public static $addAddress = "addAddress";
	 public static $placeOrder = "placeOrder";
	 public static $addoffers = "addoffers";
	 public static $addImages = "addImages";
	 public static $customerlogin = "customerlogin";
	 public static $ownerlogin = "ownerlogin";
	 public static $getAddress = "getAddress";
	 public static $getoffers = "getoffers";
	 public static $getProducts = "getProducts";
	 public static $getImagedata = "getImagedata";
	 public static $getImage = "getImage";
	 public static $getOrders = "getOrders";
	 public static $getCustomerOrders = "getCustomerOrders";
	 public static $deleteOwnerProduct = "deleteOwnerProduct";
	 public static $addcategory = "addcategory";
	 public static $addsubcategory = "addsubcategory";
	 public static $getcategories = "getcategories";
	 public static $getSubcategories = "getSubcategories";
	 public static $getcatProducts = "getcatProducts";
	 public static $deleteImage = "deleteImage";
	 public static $getAllCategories = "getAllCategories";
	 public static $updateProduct = "updateProduct";
	 public static $updatetoCompleteOrder = "updatetoCompleteOrder";
	 public static $updatePasword = "updatePasword";
	 public static $updatetoCancelOrder = "updatetoCancelOrder";
	 public static $updatetoOrderStatus = "updatetoOrderStatus";
	 public static $registertapido = "registertapido";
	 public static $uploadImage = "uploadImage";
	 public static $uploadTapidoImage = "uploadTapidoImage";
	 public static $loginDriver = "loginDriver";
	 public static $updateDriverVerification = "updateDriverVerification";
	 public static $addDrivingLicense = "addDrivingLicense";
	 public static $addSocialDocument = "addSocialDocument";
	 public static $addVehicleDetails = "addVehicleDetails";
	 public static $addProfilePhoto = "addProfilePhoto";
	 public static $userDoumentpending = "userDoumentpending";
	 public static $signintapido = "singintapido";
	 public static $updateDriverTable = "updateDriverTable";
	 public static $getDriverStatus = "GetDriverStatus";
	 public static $updatedrivertableNotification = "updatedrivertableNotification";
	 public static $updateRidesTable = "updateRidesTable";
}

?>