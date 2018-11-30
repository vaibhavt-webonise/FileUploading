<?php
      require_once 'index2.php';
      require_once 'DocxConversion.php';
    
      $file_info = finfo_open(FILEINFO_MIME_TYPE);
      $errors= array();
      $title=$_POST['title'];
      $image_file_name = $_FILES['image']['name'];
      $image_file_size =$_FILES['image']['size'];
      $image_file_tmp =$_FILES['image']['tmp_name'];
      $image_file_type=finfo_file($file_info,$_FILES['image']['tmp_name']);

    
      $doc_file_name = $_FILES['document']['name'];
      $doc_file_size =$_FILES['document']['size'];
      $doc_file_tmp =$_FILES['document']['tmp_name'];
      $doc_file_type=finfo_file($file_info,$_FILES['document']['tmp_name']);
      $doc_file_type=explode(".",$doc_file_type)[3];
      


      $image_extensions= array("image/jpeg","image/jpg","image/png");
      $doc_extensions= "document";

      if(in_array($image_file_type,$image_extensions)=== false){
        $errors=" extension not allowed, please choose a .JPEG or .PNG file.";
      }
      if(!($doc_file_type===$doc_extensions)){
        $errors=" extension not allowed, please choose a .doc file.";
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

         $fp = fopen($doc_file_name, 'a+');
         fwrite($fp, $data);
         fclose($fp);
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
          }
          catch(PDOException $e)
          {
              echo "Connection failed: " . $e->getMessage();
          }
        }
?>