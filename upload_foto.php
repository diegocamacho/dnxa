<?php

$output_dir = "files/";


//$upload_id = $_POST['upload_id'];

/*if(file_exists('fotos_autos/original_'.$upload_id.'.jpg')){

	unlink('fotos_autos/original_'.$upload_id.'.jpg');

}*/

if(!empty($_FILES)){

	$ret = array();

//	This is for custom errors;	

/*	$custom_error= array();

	$custom_error['jquery-upload-file-error']="File already exists";

	echo json_encode($custom_error);

	die();

*/

	$error =$_FILES["archivo"]["error"];

	//You need to handle  both cases

	//If Any browser does not support serializing of multiple files using FormData() 

	if(!is_array($_FILES["archivo"]["name"])) //single file

	{
		$ext = pathinfo($_FILES["archivo"]["name"], PATHINFO_EXTENSION);
 	 	$fileName = mt_rand(100,999)."_".date("Ymd").".".$ext;

 	 	//$fileName = "original_".$upload_id.".jpg";

 	 	//$fileName2 = base64_encode($fileName);

 		move_uploaded_file($_FILES["archivo"]["tmp_name"],$output_dir.$fileName);

    	$ret[]= $fileName;

	}

	else  //Multiple files, file[]

	{

	  $fileCount = count($_FILES["archivo"]["name"]);

	  for($i=0; $i < $fileCount; $i++)

	  {

	  	$fileName = mt_rand(100,999)."_".$_FILES["archivo"]["name"][$i];

	  	//$fileName2 = base64_encode($fileName);

		move_uploaded_file($_FILES["archivo"]["tmp_name"][$i],$output_dir.$fileName);

    	$ret[]= $fileName;

	  }

	

	}

	//RESIZE DE LA IMAGEN

	//CHECA SI ES PNG O JPG
	$width=500;

	$width_thumb=250;

	$fileName_thumb = "thumb_".$fileName;

	$size=getimagesize($output_dir.$fileName);

	$height=round($width*$size[1]/$size[0]);
	$height_thumb=round($width_thumb*$size[1]/$size[0]);

	if($ext !== "png"){

		$images_orig = imagecreatefromjpeg($output_dir.$fileName);

		$photoX = ImagesX($images_orig);

		$photoY = ImagesY($images_orig);

		$images_fin = ImageCreateTrueColor($width, $height);

		imagecopyresampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);

		imagejpeg($images_fin,$output_dir.$fileName);

		//THUMB
		$images_orig = imagecreatefromjpeg($output_dir.$fileName);

		$photoX = ImagesX($images_orig);

		$photoY = ImagesY($images_orig);

		$images_fin = ImageCreateTrueColor($width_thumb, $height_thumb);

		imagecopyresampled($images_fin, $images_orig, 0, 0, 0, 0, $width_thumb+1, $height_thumb+1, $photoX, $photoY);

		imagejpeg($images_fin,$output_dir.$fileName_thumb);

	}else{

		$images_orig = imagecreatefrompng($output_dir.$fileName);

		$photoX = ImagesX($images_orig);

		$photoY = ImagesY($images_orig);

		$images_fin = ImageCreateTrueColor($width, $height);

		imagetruecolortopalette($images_fin,false,255); 

		imagecopyresampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);

		imagepng($images_fin,$output_dir.$fileName);

		//THUMB
		$images_orig = imagecreatefrompng($output_dir.$fileName);

		$photoX = ImagesX($images_orig);

		$photoY = ImagesY($images_orig);

		$images_fin = ImageCreateTrueColor($width_thumb, $height_thumb);

		imagetruecolortopalette($images_fin,false,255); 

		imagecopyresampled($images_fin, $images_orig, 0, 0, 0, 0, $width_thumb+1, $height_thumb+1, $photoX, $photoY);

		imagepng($images_fin,$output_dir.$fileName_thumb);

	}

	ImageDestroy($images_orig);

	ImageDestroy($images_fin);

    echo $ret[0];

 }