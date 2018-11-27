<?php
      require_once 'index2.php';
      require_once 'DocxConversion.php';

      $errors= array();
      $title=$_POST['title'];
      $image_file_name = $_FILES['image']['name'];
      $image_file_size =$_FILES['image']['size'];
      $image_file_tmp =$_FILES['image']['tmp_name'];
      $image_file_type=$_FILES['image']['type'];
      $image_file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));

      $doc_file_name = $_FILES['document']['name'];
      $doc_file_size =$_FILES['document']['size'];
      $doc_file_tmp =$_FILES['document']['tmp_name'];
      $doc_file_type=$_FILES['document']['type'];
      $doc_file_ext=strtolower(end(explode('.',$_FILES['document']['name'])));

      $image_extensions= array("jpeg","jpg","png");
      $doc_extensions= "docx";

      if(in_array($image_file_ext,$image_extensions)=== false){
        $errors=$image_file_ext." extension not allowed, please choose a .JPEG or .PNG file.";
      }
      if(!($doc_file_ext===$doc_extensions)){
        $errors=$doc_file_ext." extension not allowed, please choose a .doc file.";
      }
      if($image_file_size > 2097152){
         $errors="Image File size must Not be greater than 2 MB";
      }
      if($doc_file_size > 100000000){
         $errors="Doc File size must Not be greater than 10 MB";
      }
      $image_path="images/".$image_file_name;
      $doc_path="documents/".$doc_file_name;
      if(empty($errors)){

         $data="Together we can change the world, just one random act of kindness at a time.";
         addtext($doc_path,$data);
         move_uploaded_file($image_file_tmp,$image_path);
         move_uploaded_file($doc_file_tmp,$doc_path);
         echo "Files Uploaded Successfully";
         insertData($title,$image_path,$doc_path);
      }
      else
      {
        echo $errors;
      }
      function connect()
      {
        $servername = "localhost";
        $username = "root";
        $password = "";
        try {
            $conn = new PDO("mysql:host=$servername;dbname=test", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
        }
        return $conn;
      }
      function insertData($title,$image_path,$doc_path)
      {
          // $conn=connect();
          $servername = "localhost";
          $username = "root";
          $password = "";
          try {
                $conn = new PDO("mysql:host=$servername;dbname=test", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $conn->prepare("INSERT INTO TestTable (Title,Image,File) VALUES (:title,:image,:doc)");
                $stmt->bindParam(':title',$title);
                $stmt->bindParam(':image',$image_path);
                $stmt->bindParam(':doc',$doc_path);
                $stmt->execute();

                echo "New records created successfully";

                //$stmt->close();
                //$conn->close();
          }
          catch(PDOException $e)
          {
              echo "Connection failed: " . $e->getMessage();
          }
      }
      /*function read_file_docx($filename){
          $striped_content = '';
          $content = '';
            if(!$filename || !file_exists($filename)) return false;
                $zip = zip_open($filename);
            if (!$zip || is_numeric($zip)) return false;

            while ($zip_entry = zip_read($zip)) {

              if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
              if (zip_entry_name($zip_entry) != "word/document.xml") continue;
                  $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
              zip_entry_close($zip_entry);
            }
          zip_close($zip);
          $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
          $content = str_replace('</w:r></w:p>', "\r\n", $content);
          $striped_content = strip_tags($content);
          return $striped_content;
      }*/
?>