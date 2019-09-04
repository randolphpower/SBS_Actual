<?php

	$fecha = 'N/A';
	if (trim($_GET['fecha']) != "") {
		$arr = explode("/", $_GET['fecha']);
		$fecha = $arr[2].$arr[0].$arr[1];
	}
	//echo $fecha;
	function unzip($src_file, $dest_dir=false, $create_zip_name_dir=true, $overwrite=true) 
	{
	  if ($zip = zip_open($src_file)) 
	  {
		if ($zip) 
		{
		  $splitter = ($create_zip_name_dir === true) ? "." : "/";
		  if ($dest_dir === false) $dest_dir = substr($src_file, 0, strrpos($src_file, $splitter))."/";
		  
		  // Create the directories to the destination dir if they don't already exist
		  create_dirs($dest_dir);

		  // For every file in the zip-packet
		  while ($zip_entry = zip_read($zip)) 
		  {
			// Now we're going to create the directories in the destination directories
			
			// If the file is not in the root dir
			$pos_last_slash = strrpos(zip_entry_name($zip_entry), "/");
			if ($pos_last_slash !== false)
			{
			  // Create the directory where the zip-entry should be saved (with a "/" at the end)
			  create_dirs($dest_dir.substr(zip_entry_name($zip_entry), 0, $pos_last_slash+1));
			}

			// Open the entry
			if (zip_entry_open($zip,$zip_entry,"r")) 
			{
			  
			  // The name of the file to save on the disk
			  $file_name = $dest_dir.zip_entry_name($zip_entry);
			  
			  // Check if the files should be overwritten or not
			  if ($overwrite === true || $overwrite === false && !is_file($file_name))
			  {
				// Get the content of the zip entry
				$fstream = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

				file_put_contents($file_name, $fstream );
				// Set the rights
				chmod($file_name, 0777);
				//echo "save: ".$file_name."<br />";
			  }
			  
			  // Close the entry
			  zip_entry_close($zip_entry);
			}       
		  }
		  // Close the zip-file
		  zip_close($zip);
		}
	  } 
	  else
	  {
		return false;
	  }
	  
	  return true;
	}

	function create_dirs($path)
	{
	  if (!is_dir($path))
	  {
		$directory_path = "";
		$directories = explode("/",$path);
		array_pop($directories);
		
		foreach($directories as $directory)
		{
		  $directory_path .= $directory."/";
		  if (!is_dir($directory_path))
		  {			
			mkdir($directory_path);
			chmod($directory_path, 0777);
		  }
		}
	  }
	}
 
	include 'Net/SFTP.php'; 
	$sftp = new Net_SFTP('200.53.142.68');
	if (!$sftp->login('servicob', '53rv1c0b_2017')) {
		exit('Login Failed');
	}
	$zip_file_name = 'logs_carga_SERVICOB_legal_'. $fecha .'.zip';
	$folder_name = 'logs_carga_SERVICOB_legal_'. $fecha;
	$directory = './';
	$local_file_path=$directory.$zip_file_name;	
	$sftp->get($zip_file_name, $local_file_path);
	
	unzip($local_file_path, false, true, true);
	
	$filelist = glob($directory.$folder_name."/*.bad");
	//$content = "";
	//foreach ($filelist as $value){ 
		
		//echo $value. "<br>";
	//	$content .= $value;
	//	$file902 = fopen($value, 'r') or die('Unable to open file!');
	//	while(!feof($file902)) {
			//echo fgets($file902) . "<br>";
	//		$content .= fgets($file902);
	//	}		
	//} 	
	//echo $content;
	header('Location: /servicobranza/visualizar_logs.php?filelist='.base64_encode(serialize($filelist)));
  ?>