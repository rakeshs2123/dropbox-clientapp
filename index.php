<?php
	$error = "";		//error holder
	if(isset($_POST['createpdf'])){
		$post = $_POST;		

		if(extension_loaded('zip')){
    	// Checking ZIP extension is available
			if(isset($post['files']) and count($post['files']) > 0){	// Checking files are selected
				$zip = new ZipArchive();			// Load zip library	
				$zip_name = time().".zip";			// Zip name
				if($zip->open($zip_name, ZIPARCHIVE::CREATE)!==TRUE){		// Opening zip file to load files
					$error .=  "* Sorry ZIP creation failed at this time<br/>";
				}
				foreach($post['files'] as $file){				
					$zip->addFile($file);			// Adding files into zip
				}
				$zip->close();
      
      // push to download the zip
      header('Content-type: application/zip');
      header('Content-Disposition: attachment; filename="'.$zip_name.'"');
      readfile($zip_name);
      // remove zip file is exists in temp path
      unlink($zip_name);
     
				
				
			}else
				$error .= "* Please select file to zip <br/>";
		}else
			$error .= "* You dont have ZIP extension<br/>";
	}
?>
<?php

require_once 'Dropbox.class.php';

$dropbox = new Dropbox();

$userArray = $dropbox->call('https://api.dropbox.com/1/account/info');

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dropbox Client</title>
</head>
<body>
<center><h3>Dropbox client</h3></center>
<?php 
  if($dropbox->hasAccess())
  {
    echo "<pre>";
   // print_r($userArray);

   // $files = $dropbox->filesGet('sandbox','test/test.html');
    //print_r($files);

    $data = $dropbox->metadata('sandbox','');
   // print_r($data);
  $dummy = $data->contents;
 // print_r($dummy["0"]->path); 

  $count = count($dummy);
   // $shares = $dropbox->shares('sandbox','test/test.html');
    //print_r($shares);
    //$media = $dropbox->media('sandbox','test/test.html');
    //print_r($media);
    
    
    
     // $userArray->display_name $userArray->email 
      
?>

<h4>welcome <? echo $userArray->display_name;?></h4>
      <form name="zips" method="post">
<?php if(!empty($error)) { ?>
<p style=" border:#C10000 1px solid; background-color:#FFA8A8; color:#B00000;padding:8px; width:588px; margin:0 auto 10px;"><?php echo $error; ?></p>
<?php } ?>
<table width="600" border="1" align="center" cellpadding="10" cellspacing="0" style="border-collapse:collapse; border:#ccc 1px solid;">
  <tr>
    <td width="33" align="center">*</td>
    <td width="117" align="center">File Type</td>
    <td width="382">File Name</td>
    <td width="382">Size</td>
  </tr>


  <? for($i=0;$i<$count;$i++){?>
<tr>

  <?
      if($dummy[$i]->mime_type != '')
      {
        $media = $dropbox->media('sandbox',$dummy[$i]->path);
    ?>
    <td align="center"><input type="checkbox" name="files[]" value="<?echo $media->url.'?dl=1'; ?>" /></td>
    <td align="center"><? echo $dummy[$i]->mime_type; ?></td>
    <td><a href="<?echo $media->url.'?dl=1'; ?>" target="_blank"> <?echo $dummy[$i]->path; ?></a></td>
    <td><?echo $dummy[$i]->size; ?></td>
     <? }


    ?>

  
   
  </tr>
  <?}?>


  <tr>
    <td colspan="4" align="">
      <input type="submit" name="createpdf" style="border:0px; background-color:#800040; color:#FFF; padding:10px; cursor:pointer; font-weight:bold; border-radius:5px;" value="Download" />&nbsp;

        <input type="reset" name="reset" style="border:0px; background-color:#D3D3D3; color:#000; font-weight:bold; padding:10px; cursor:pointer; border-radius:5px;" value="Reset" />
    </td>
    </tr>
</table>

</form>

<?
  }
  else
  {
        echo '  <center><h3>Login</h3></center>
      <center> <a href="'.$dropbox->getAccessURL().'">Login with Dropbox</a></center>';

    
  }
  ?>

</body>
</html>
