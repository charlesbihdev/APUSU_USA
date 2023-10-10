<?php

//CREATING THE ADMIN CLASS
class Admin
{
    //DECLARE $conn AS A PRIVATE VARIABLE
    private $conn;
    //DECLARE $table AS A PRIVATE VARIABLE
    private $table;

    //DECLARING CONSTRUCTOR
    public function __construct($conn, $table)
    {
        $this->conn = $conn;
        $this->table = $table;
    }

    //THE CREATE FUNCTION
    public function create($data)
    {
        //DECARE EMPTY ARRAYS
        $fields = array();
        $values = array();
        $params = array();

        //BREAK THE $data OBJECTS AND LOOP THEM TO VARIOUS ARRAYS BASED ON HOW YOU WILL USE IT
        foreach ($data as $key => $value) {
            $fields[] = $key;
            $values[] = ":$key";
            $params[":$key"] = $value;
        }
        //CONCATINATE THE FIELD ARRAY TO CREATE A QUERY
        $sql = "INSERT INTO " . $this->table . "(" . implode(", ", $fields) . ") VALUES(" . implode(", ", $values) . ")";
        $stmt = $this->conn->prepare($sql);
        // BIND PARAMETERS WITH THE HELP OF $param OBJECTS

        foreach ($params as $param => $paramvalue) {
            $stmt->bindValue($param, $paramvalue);
        }

        // EXECUTE THE QUERY
        $result = $stmt->execute();
        if ($result === false) {
            $errorInfo = $stmt->errorInfo();
            echo "Error: " . $errorInfo[2];
        }
        return true;
    }
    //THE READ FUNCTION WITH A WHERE CLAUSE
    public function read($searchField, $searchValue)
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE $searchField = :$searchField";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":$searchField", $searchValue);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    //THE READ FUNCTION WITHOUT A LIMIT
    public function readAll($primaryKey)
    {
        $sql = "SELECT * FROM " . $this->table . " ORDER BY " . $primaryKey . " DESC ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    //THE READ FUNCTION WITH NEWEST AT TOP
    public function readWithLimit($limit, $primaryKey)
    {
        $sql = "SELECT * FROM " . $this->table . " ORDER BY " . $primaryKey . " DESC LIMIT " . $limit;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readWithNoRestriction()
    {
        $sql = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //THE UPDATE FUNCTION
    public function update($id, $data, $whereColumn = 'id')
    {
        // Declare empty arrays
        $fields = array();
        $params = array(':' . $whereColumn => $id);

        // Break the $data objects and loop them to various arrays based on how you will use it
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
            $params[":$key"] = $value;
        }

        // Construct the SQL UPDATE query
        $sql = "UPDATE " . $this->table . " SET " . implode(", ", $fields) . " WHERE $whereColumn = :$whereColumn";
        $stmt = $this->conn->prepare($sql);

        // Bind parameters with the help of $params objects
        foreach ($params as $param => $paramvalue) {
            $stmt->bindValue($param, $paramvalue);
        }

        // Execute the query
        $result = $stmt->execute();
        if ($result === false) {
            $errorInfo = $stmt->errorInfo();
            echo "Error: " . $errorInfo[2];
            return false; // Return false on error
        }
        return true;
    }


    //MULTIPLE IMAGE INSERT STATEMENT
    function insertImage($image_url, $farmer_id)
    {
        $sql = "INSERT INTO farmimage (image_url, farmer_id) VALUES (:image_url, :farmer_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':image_url', $image_url, PDO::PARAM_STR);
        $stmt->bindParam(':farmer_id', $farmer_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
function uploadAndInsertMultipleImages($pdo, $destinationDirectory, $farmer_id)
{
    // Check if files were uploaded
    if (isset($_FILES['additional_images']) && is_array($_FILES['additional_images']['name'])) {
        $fileCount = count($_FILES['additional_images']['name']);
        $uploadResults = array();

        for ($i = 0; $i < $fileCount; $i++) {
            $file = array(
                'name' => $_FILES['additional_images']['name'][$i],
                'tmp_name' => $_FILES['additional_images']['tmp_name'][$i],
                'error' => $_FILES['additional_images']['error'][$i],
            );

            // Extract file information
            $name = $file['name'];
            $tempname = $file['tmp_name'];

            // Check file extension
            $validExtensions = array('jpg', 'jpeg', 'png', 'gif');
            $fileExtension = pathinfo($name, PATHINFO_EXTENSION);

            if (in_array($fileExtension, $validExtensions)) {
                // Generate a unique image URL
                $image_url = uniqid('image_') . '.' . $fileExtension;
                $destinationPath = $destinationDirectory . $image_url;

                // Move the uploaded file to the destination directory
                if (move_uploaded_file($tempname, $destinationPath)) {
                    // Insert image data into the database
                    $admin = new Admin($pdo, 'farmimage');
                    if ($admin->insertImage($image_url, $farmer_id)) {
                        $uploadResults[] = "Image '$name' uploaded and saved to the database.";
                    } else {
                        $uploadResults[] = "Image '$name' uploaded but failed to save to the database.";
                    }
                } else {
                    $uploadResults[] = "Failed to move the uploaded file '$name'.";
                }
            } else {
                $uploadResults[] = "Invalid file extension for '$name'. Allowed extensions: jpg, jpeg, png, gif.";
            }
        }

        return $uploadResults;
    } else {
        return "No files were uploaded or an error occurred.";
    }
}

// Example usage:
// $results = uploadAndInsertMultipleImages($pdo, './assets/uploads/', 1);
// foreach ($results as $result) {
//     echo $result . '<br>';
// }
function uploadImage($file, $destinationDirectory)
{
    $validExtensions = array('jpg', 'jpeg', 'png', 'gif');
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

    if (!in_array($fileExtension, $validExtensions)) {
        return false; // Invalid file extension
    }

    $fileName = uniqid('image_') . '.' . $fileExtension;
    $destinationPath = $destinationDirectory . '/' . $fileName;

    if (move_uploaded_file($file['tmp_name'], $destinationPath)) {
        return $fileName; // Return the generated file name
    }

    return false; // File upload failed
};




// Function to generate a random combination of letters and numbers
function generateRandomString()
{
    $firstString = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $secondString = '123456789';
    $randomString1 = '';
    $randomString2 = '';
    for ($i = 0; $i < 5; $i++) {
        $randomString2 .= $secondString[rand(0, strlen($secondString) - 1)];
    }
    for ($i = 0; $i < 2; $i++) {
        $randomString1 .= $firstString[rand(0, strlen($firstString) - 1)];
    }
    return $randomString1 . "-" . $randomString2;
};

function genWitnessRandom($length = 6)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
};
