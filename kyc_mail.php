<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullName = htmlspecialchars($_POST["fullName"] ?? '');
    $email = filter_var($_POST["email"] ?? '', FILTER_SANITIZE_EMAIL);
    $dob = $_POST["dob"] ?? '';
    $gender = $_POST["gender"] ?? '';
    $nationality = $_POST["nationality"] ?? '';
    $phone = $_POST["phone"] ?? '';
    $address = $_POST["address"] ?? '';
    $idType = $_POST["idType"] ?? '';
    $idNumber = $_POST["idNumber"] ?? '';

    if (!$fullName || !$email || !$dob || !$gender || !$nationality || !$phone || !$address || !$idType || !$idNumber) {
        echo json_encode(["status" => "error", "message" => "Missing fields"]);
        exit;
    }

    if (!isset($_FILES["idUpload"]) || $_FILES["idUpload"]["error"] !== UPLOAD_ERR_OK ||
        !isset($_FILES["selfieUpload"]) || $_FILES["selfieUpload"]["error"] !== UPLOAD_ERR_OK) {
        echo json_encode(["status" => "nofile"]);
        exit;
    }

    // Email setup
    $to = "example@gmail.com";
    $subject = "New KYC Submission from $fullName";

    $message = "KYC Form Submission:\n\n";
    $message .= "Full Name: $fullName\nDOB: $dob\nGender: $gender\nNationality: $nationality\n";
    $message .= "Phone: $phone\nEmail: $email\nAddress: $address\nID Type: $idType\nID Number: $idNumber\n";

    $boundary = md5(time());
    $headers = "From: $fullName <$email>\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/plain; charset=\"utf-8\"\r\n\r\n";
    $body .= $message . "\r\n";

    // Attach ID Document
    $idTmp = $_FILES["idUpload"]["tmp_name"];
    $idName = $_FILES["idUpload"]["name"];
    $idType = $_FILES["idUpload"]["type"];
    $idContent = chunk_split(base64_encode(file_get_contents($idTmp)));

    $body .= "--$boundary\r\n";
    $body .= "Content-Type: $idType; name=\"$idName\"\r\n";
    $body .= "Content-Disposition: attachment; filename=\"$idName\"\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $body .= $idContent . "\r\n";

    // Attach Selfie
    $selfieTmp = $_FILES["selfieUpload"]["tmp_name"];
    $selfieName = $_FILES["selfieUpload"]["name"];
    $selfieType = $_FILES["selfieUpload"]["type"];
    $selfieContent = chunk_split(base64_encode(file_get_contents($selfieTmp)));

    $body .= "--$boundary\r\n";
    $body .= "Content-Type: $selfieType; name=\"$selfieName\"\r\n";
    $body .= "Content-Disposition: attachment; filename=\"$selfieName\"\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $body .= $selfieContent . "\r\n";
    $body .= "--$boundary--";

    if (mail($to, $subject, $body, $headers)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
