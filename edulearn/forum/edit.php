<?php
require_once '../config/database.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM forum_threads WHERE id = ?");
$stmt->execute([$id]);
$thread = $stmt->fetch();

if (!$thread) {
    echo "<div class='text-center py-20 text-xl'>Topik tidak ditemukan.</div>";
    require_once '../includes/footer.php';
    exit;
}


if ($thread['user_id'] != $_SESSION['user_id'] && (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')) {
    echo "<div class='text-center py-20 text-xl text-red-500'>Anda tidak memiliki izin.</div>";
    require_once '../includes/footer.php';
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category = $_POST['category'];

    if (empty($title) || empty($content)) {
        $error = "Judul dan konten wajib diisi.";
    } else {
        $stmt = $pdo->prepare("UPDATE forum_threads SET title = ?, content = ?, category = ? WHERE id = ?");
        if ($stmt->execute([$title, $content, $category, $id])) {
            $success = "Topik berhasil diperbarui!";
            $thread['title'] = $title;
            $thread['content'] = $content;
            $thread['category'] = $category;
        } else {
            $error = "Terjadi kesalahan database.";
        }
    }
}
?>

<div class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50">
            <h1 class="text-2xl font-bold text-gray-900">Edit Topik Diskusi</h1>
        </div>
        
        <div class="p-8">
            <?php if ($error): ?>
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                    <span class="block sm:inline"><?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                    <span class="block sm:inline"><?php echo $success; ?> <a href="view.php?id=<?php echo $id; ?>" class="font-bold underline">Lihat Topik</a>.</span>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Judul Topik</label>
                    <input type="text" name="title" id="title" required class="form-input mt-1" value="<?php echo htmlspecialchars($thread['title']); ?>">
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select id="category" name="category" class="form-input mt-1">
                        <option value="Umum" <?php echo $thread['category'] == 'Umum' ? 'selected' : ''; ?>>Umum</option>
                        <option value="Tanya Jawab" <?php echo $thread['category'] == 'Tanya Jawab' ? 'selected' : ''; ?>>Tanya Jawab</option>
                        <option value="Tips & Trik" <?php echo $thread['category'] == 'Tips & Trik' ? 'selected' : ''; ?>>Tips & Trik</option>
                        <option value="Diskusi Materi" <?php echo $thread['category'] == 'Diskusi Materi' ? 'selected' : ''; ?>>Diskusi Materi</option>
                    </select>
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700">Konten</label>
                    <textarea id="content" name="content" rows="6" required class="form-input mt-1"><?php echo htmlspecialchars($thread['content']); ?></textarea>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                    <a href="view.php?id=<?php echo $id; ?>" class="btn bg-white border border-slate-300 text-slate-700 hover:bg-slate-50">Batal</a>
                    <button type="submit" class="btn btn-primary px-6">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
