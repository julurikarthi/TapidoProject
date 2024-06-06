<?php
include('StoreOperations.php');


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {

	$storeOpration = new StoreOperations();
	if (strpos($_SERVER["CONTENT_TYPE"], "multipart/form-data") !== false) {
		$uploadedFile = $_FILES["image"];
        $imageData = file_get_contents($uploadedFile["tmp_name"]);
		if ($imageData == null) {
			echo "imahe nill";
		} else {
			$storeOpration -> uploadImage($imageData);
		}
	}
	return ;
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		$storeOpration = new StoreOperations();
		$storeOpration->loadImage($id);
	} else {
		echo "Please provide an image ID.";
	}
	return;
}

 ?>