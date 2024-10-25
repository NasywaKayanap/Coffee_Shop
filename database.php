<?php
header('Content-Type: application/json');

// Koneksi database
$host = 'localhost';
$dbname = 'coffee_shop';
$username = 'root';
$password = '';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Koneksi database gagal']);
    exit;
}

// Terima data JSON
$data = json_decode(file_get_contents('php://input'), true);

// Validasi data
if (!isset($data['nama']) || !isset($data['email']) || !isset($data['telepon']) || 
    !isset($data['alamat']) || !isset($data['produk']) || !isset($data['jumlah'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
    exit;
}

try {
    // Siapkan query
    $query = "INSERT INTO orders (nama, email, telepon, alamat, produk, jumlah, tanggal) 
              VALUES (:nama, :email, :telepon, :alamat, :produk, :jumlah, NOW())";
    
    $stmt = $db->prepare($query);
    
    // Bind parameter
    $stmt->bindParam(':nama', $data['nama']);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':telepon', $data['telepon']);
    $stmt->bindParam(':alamat', $data['alamat']);
    $stmt->bindParam(':produk', $data['produk']);
    $stmt->bindParam(':jumlah', $data['jumlah']);
    
    // Eksekusi query
    $stmt->execute();
    
    // Kirim response sukses
    echo json_encode([
        'status' => 'success',
        'message' => 'Order berhasil disimpan',
        'order_id' => $db->lastInsertId()
    ]);

} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal menyimpan order'
    ]);
}
?>