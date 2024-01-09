<?php
// ข้อมูลการเชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "product_management";

// สร้างการเชื่อมต่อ
$conn = new mysqli("localhost", "root", "", "product_management");

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$productName = $_POST['productName'];
$productCode = $_POST['productCode'];
$productQuantity = $_POST['productQuantity'];

// จัดการกับภาพ
$targetDir = "uploads/";
$targetFile = $targetDir . basename($_FILES["productImage"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// ตรวจสอบว่าไฟล์เป็นภาพหรือไม่
$check = getimagesize($_FILES["productImage"]["tmp_name"]);
if ($check !== false) {
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
} else {
    echo "File is not an image.";
    $uploadOk = 0;
}

// ตรวจสอบว่าไฟล์มีอยู่หรือไม่
if (file_exists($targetFile)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

// ตรวจสอบขนาดไฟล์
if ($_FILES["productImage"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// ตรวจสอบประเภทไฟล์
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
    echo "Sorry, only JPG, JPEG, PNG files are allowed.";
    $uploadOk = 0;
}

// ตรวจสอบว่า $uploadOk เป็น 0 หรือไม่
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// ถ้าทุกอย่างถูกต้อง พยายามอัปโหลดไฟล์
} else {
    if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $targetFile)) {
        echo "The file " . htmlspecialchars(basename($_FILES["productImage"]["name"])) . " has been uploaded.";

        // เพิ่มข้อมูลลงในฐานข้อมูล
        $sql = "INSERT INTO products (name, code, quantity, image_path) VALUES ('$productName', '$productCode', '$productQuantity', '$targetFile')";
        $result = $conn->query($sql);
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

$conn->close();
?>