<?php
$partner_key = '378492999ae7be538a6a2917cc30b0cd';

$data = $_POST;
$required = ['status','code','serial','callback_sign'];
foreach ($required as $f) {
  if (!isset($data[$f])) {
    http_response_code(400);
    exit("Thiếu trường: $f");
  }
}

if ($data['callback_sign'] !== md5($partner_key . $data['code'] . $data['serial'])) {
  http_response_code(403);
  exit("Sai chữ ký xác nhận");
}

file_put_contents("log_callback.txt", json_encode($data) . PHP_EOL, FILE_APPEND);

if ($data['status'] == '1') {
  file_put_contents("credit_success.txt", "User {$data['request_id']} nạp {$data['amount']} từ {$data['telco']}" . PHP_EOL, FILE_APPEND);
}
echo "OK";
?>