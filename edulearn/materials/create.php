<?php
require_once '../config/database.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = $_POST['category'];
    
    $file = $_FILES['material_file'] ?? null;
    $allowed_exts = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt', 'zip'];

    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
        $error = "Pilih file untuk diupload.";
    } else {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($ext, $allowed_exts)) {
            $error = "Format file tidak diizinkan. Gunakan PDF, Office Doc, atau ZIP.";
        } else {
            $new_file_name = uniqid() . '.' . $ext;
            $upload_path = '../assets/uploads/' . $new_file_name;
            
            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                $stmt = $pdo->prepare("INSERT INTO materials (user_id, title, description, category, file_name) VALUES (?, ?, ?, ?, ?)");
                if ($stmt->execute([$_SESSION['user_id'], $title, $description, $category, $new_file_name])) {
                    $success = "Materi berhasil diupload!";
                } else {
                    $error = "Terjadi kesalahan database.";
                }
            } else {
                $error = "Gagal menyimpan file yang diupload.";
            }
        }
    }
}
?>

<div class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50">
            <h1 class="text-2xl font-bold text-gray-900">Upload Materi Baru</h1>
            <p class="mt-1 text-sm text-gray-500">Bagikan pengetahuanmu dengan komunitas.</p>
        </div>
        
        <div class="p-8">
            <?php if ($error): ?>
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                    <span class="block sm:inline"><?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                    <span class="block sm:inline"><?php echo $success; ?> <a href="../index.php" class="font-bold underline">Lihat di Home</a>.</span>
                </div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Judul Materi</label>
                    <input type="text" name="title" id="title" required class="form-input mt-1">
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select id="category" name="category" class="form-input mt-1">
                        <option value="Sains">Sains</option>
                        <option value="Teknologi">Teknologi</option>
                        <option value="Sosial">Sosial</option>
                        <option value="Bahasa">Bahasa</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea id="description" name="description" rows="4" class="form-input mt-1"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">File Materi (PDF, DOCX, ZIP)</label>
                    <label for="file-upload" class="upload-area">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <div class="relative bg-transparent rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none">
                                    <span>Upload a file</span>
                                    <input id="file-upload" name="material_file" type="file" class="sr-only" onchange="document.getElementById('file-name-display').textContent = this.files[0].name">
                                </div>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PDF, DOCX, ZIP up to 10MB</p>
                            <p id="file-name-display" class="text-sm font-semibold text-primary-600 mt-2"></p>
                        </div>
                    </label>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="btn btn-primary px-6">
                        Upload Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
