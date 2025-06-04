<?php
$response = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = htmlspecialchars($_POST["fullName"]);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars($_POST["phone"]);
    $position = htmlspecialchars($_POST["position"]);
    $education = htmlspecialchars($_POST["education"]);
    $experience = htmlspecialchars($_POST["experience"]);
    $skills = htmlspecialchars($_POST["skills"]);
    $coverLetter = htmlspecialchars($_POST["coverLetter"]);

    $to = "example@gmail.com";
    $subject = "New Job Application from $fullName";

    $message = "New job application submitted:\n\n";
    $message .= "Full Name: $fullName\n";
    $message .= "Email: $email\n";
    $message .= "Phone: $phone\n";
    $message .= "Position: $position\n";
    $message .= "Education: $education\n";
    $message .= "Experience: $experience years\n";
    $message .= "Skills: $skills\n\n";
    $message .= "Cover Letter:\n$coverLetter\n";

    if (isset($_FILES['cvUpload']) && $_FILES['cvUpload']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['cvUpload']['tmp_name'];
        $fileName = $_FILES['cvUpload']['name'];
        $fileType = $_FILES['cvUpload']['type'];
        $fileContent = file_get_contents($fileTmp);
        $encodedContent = chunk_split(base64_encode($fileContent));
        $boundary = md5(time());

        $headers = "From: $fullName <$email>\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";

        $body = "--{$boundary}\r\n";
        $body .= "Content-Type: text/plain; charset=\"utf-8\"\r\n";
        $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $body .= $message . "\r\n";

        $body .= "--{$boundary}\r\n";
        $body .= "Content-Type: {$fileType}; name=\"{$fileName}\"\r\n";
        $body .= "Content-Disposition: attachment; filename=\"{$fileName}\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $body .= $encodedContent . "\r\n";
        $body .= "--{$boundary}--";

        if (mail($to, $subject, $body, $headers)) {
            $response = "success";
        } else {
            $response = "error";
        }
    } else {
        $response = "nofile";
    }
}
?>
