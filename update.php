<!DOCTYPE HTML>
<html>
<head>
    <title>PDO - Update a Record - PHP CRUD Tutorial</title>
     
    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="libs/bootstrap-3.3.7/css/bootstrap.min.css" />
  
    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
         
</head>
<body>
 
    <!-- container -->
    <div class="container">
  
        <div class="page-header">
            <h1>Update Product</h1>
        </div>
     
		<?php
		// get passed parameter value, in this case, the record ID
		// isset() is a PHP function used to verify if a value is there or not
		$id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');
		 
		//include database connection
		include 'config/database.php';
		 
		// read current record's data
		try {
		    // prepare select query
		    $query = "SELECT id, name, description, price, image FROM products WHERE id = ? LIMIT 0,1";
		    $stmt = $con->prepare( $query );
		     
		    // this is the first question mark
		    $stmt->bindParam(1, $id);
		     
		    // execute our query
		    $stmt->execute();
		     
		    // store retrieved row to a variable
		    $row = $stmt->fetch(PDO::FETCH_ASSOC);
		     
		    // values to fill up our form
		    $name = $row['name'];
		    $description = $row['description'];
		    $price = $row['price'];
		    $image = $row['image'];
		}
		 
		// show error
		catch(PDOException $exception){
		    die('ERROR: ' . $exception->getMessage());
		}
		?>


		<?php
		// get passed parameter value, in this case, the record ID
		// isset() is a PHP function used to verify if a value is there or not
		$id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');
		 
		//include database connection
		include 'config/database.php';
		 
		// check if form was submitted
		if($_POST){
		     
		    try{
		     
		        // write update query
		        // in this case, it seemed like we have so many fields to pass and 
		        // it is better to label them and not use question marks
		        $query = "UPDATE products 
		                    SET name=:name, description=:description, price=:price, image=:image  
		                    WHERE id = :id";
		 
		        // prepare query for excecution
		        $stmt = $con->prepare($query);
		 
		        // posted values
		        $name=htmlspecialchars(strip_tags($_POST['name']));
		        $description=htmlspecialchars(strip_tags($_POST['description']));
		        $price=htmlspecialchars(strip_tags($_POST['price']));
		 		// new 'image' field
		        $image=!empty($_FILES["image"]["name"])
		                ? sha1_file($_FILES['image']['tmp_name']) . "-" . basename($_FILES["image"]["name"])
		                : "";
		        $image=htmlspecialchars(strip_tags($image));
		        // bind the parameters
		        $stmt->bindParam(':name', $name);
		        $stmt->bindParam(':description', $description);
		        $stmt->bindParam(':price', $price);
		        $stmt->bindParam(':id', $id);
		        if($image) $stmt->bindParam(':image', $image);
		        else $stmt->bindParam(':image', $row['image']);
		        // Execute the query
		        if($stmt->execute()){
		            echo "<div class='alert alert-success'>Record was updated.</div>";
		            // now, if image is not empty, try to upload the image
		            if($image){
		             
		                // sha1_file() function is used to make a unique file name
		                $target_directory = "uploads/";
		                $target_file = $target_directory . $image;
		                $file_type = pathinfo($target_file, PATHINFO_EXTENSION);
		             
		                // error message is empty
		                $file_upload_error_messages="";

		                // make sure that file is a real image
		                $check = getimagesize($_FILES["image"]["tmp_name"]);
		                if($check!==false){
		                    // submitted file is an image
		                }else{
		                    $file_upload_error_messages.="<div>Submitted file is not an image.</div>";
		                }
		                // make sure certain file types are allowed
		                $allowed_file_types=array("jpg", "jpeg", "png", "gif");
		                if(!in_array($file_type, $allowed_file_types)){
		                    $file_upload_error_messages.="<div>Only JPG, JPEG, PNG, GIF files are allowed.</div>";
		                }
		                // make sure file does not exist
		                if(file_exists($target_file)){
		                    $file_upload_error_messages.="<div>Image already exists. Try to change file name.</div>";
		                }
		                // make sure submitted file is not too large, can't be larger than 1 MB
		                if($_FILES['image']['size'] > (1024000)){
		                    $file_upload_error_messages.="<div>Image must be less than 1 MB in size.</div>";
		                }
		                // make sure the 'uploads' folder exists
		                // if not, create it
		                if(!is_dir($target_directory)){
		                    mkdir($target_directory, 0777, true);
		                }
		                
		                // if $file_upload_error_messages is still empty
		                if(empty($file_upload_error_messages)){
		                    // it means there are no errors, so try to upload the file
		                    if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){
		                        // it means photo was uploaded
		                    }else{
		                        echo "<div class='alert alert-danger'>";
		                            echo "<div>Unable to upload photo.</div>";
		                            echo "<div>Update the record to upload photo.</div>";
		                        echo "</div>";
		                    }
		                }
		                 
		                // if $file_upload_error_messages is NOT empty
		                else{
		                    // it means there are some errors, so show them to user
		                    echo "<div class='alert alert-danger'>";
		                        echo "<div>{$file_upload_error_messages}</div>";
		                        echo "<div>Update the record to upload photo.</div>";
		                    echo "</div>";
		                }


		            }
		            if(!$image) $image = $row['image'];
		        }else{
		            echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
		        }
		         
		    }
		     
		    // show errors
		    catch(PDOException $exception){
		        die('ERROR: ' . $exception->getMessage());
		    }
		}
		?>
		
		<!--we have our html form here where new user information will be entered-->
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}");?>" method="post" enctype="multipart/form-data">
		    <table class='table table-hover table-responsive table-bordered'>
		        <tr>
		            <td>Name</td>
		            <td><input type='text' name='name' value="<?php echo htmlspecialchars($name, ENT_QUOTES);  ?>" class='form-control' /></td>
		        </tr>
		        <tr>
		            <td>Description</td>
		            <td><textarea name='description' class='form-control'><?php echo htmlspecialchars($description, ENT_QUOTES);  ?></textarea></td>
		        </tr>
		        <tr>
		            <td>Price</td>
		            <td><input type='text' name='price' value="<?php echo htmlspecialchars($price, ENT_QUOTES);  ?>" class='form-control' /></td>
		        </tr>
		        <tr>
                    <td>Photo</td>
                    <td><?php echo $image ? "<img src='uploads/{$image}' style='width:300px;' />" : "No image found.";  ?>
                    	<br/><input type="file" name="image" /></td>
                </tr>
		        <tr>
		            <td></td>
		            <td>
		                <input type='submit' value='Save Changes' class='btn btn-primary' />
		                <a href='read.php' class='btn btn-danger'>Back to read products</a>
		            </td>
		        </tr>
		    </table>
		</form>
         
    </div> <!-- end .container -->
     
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="libs/jquery-3.1.1/jquery-3.1.1.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="libs/bootstrap-3.3.7/js/bootstrap.min.js"></script>
 
</body>
</html>