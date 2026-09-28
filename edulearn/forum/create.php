<?php
require_once '../config/database.php';
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}


$material_id = $_GET['material_id'] ?? null;

$is_material_discussion = ($material_id !== null);

$material_title = '';
$material_category = '';
if ($is_material_discussion) {
    $stmt = $pdo->prepare("SELECT title, category FROM materials WHERE id = ?");
    $stmt->execute([$material_id]);
    $mat = $stmt->fetch();
    if ($mat) {
        $material_title = $mat['title'];
        $material_category = $mat['category'];
    } else {
        header("Location: index.php");
        exit;
    }
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $category = $_POST['category'];
    $content = trim($_POST['content']);
    $form_material_id = $_POST['material_id'] ?? null; 

    if (empty($title) || empty($content)) {
        $error = "Judul dan konten harus diisi.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO forum_threads (user_id, title, category, content, material_id) VALUES (?, ?, ?, ?, ?)");
        $mat_id_to_insert = !empty($form_material_id) ? $form_material_id : null;
        
        if ($stmt->execute([$_SESSION['user_id'], $title, $category, $content, $mat_id_to_insert])) {
            if ($mat_id_to_insert) {
                header("Location: ../materials/view.php?id=" . $mat_id_to_insert);
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Gagal membuat topik.";
        }
    }
}

require_once '../includes/header.php';
?>

<div class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50">
            <h1 class="text-2xl font-bold text-gray-900">
                <?php echo $is_material_discussion ? 'Diskusi Materi' : 'Buat Topik Diskusi Baru'; ?>
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                <?php echo $is_material_discussion ? 'Tanyakan atau diskusikan tentang <span class="font-medium text-gray-900">' . htmlspecialchars($material_title) . '</span>' : 'Mulai diskusi menarik dengan komunitas.'; ?>
            </p>
        </div>
        
        <div class="p-8">
            <?php if ($error): ?>
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                    <span class="block sm:inline"><?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-6">
                
                <?php if ($is_material_discussion): ?>
                    <input type="hidden" name="material_id" value="<?php echo htmlspecialchars($material_id); ?>">
                <?php endif; ?>

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Judul Topik / Pertanyaan</label>
                    <input type="text" name="title" id="title" required placeholder="<?php echo $is_material_discussion ? 'Contoh: Saya kesulitan memahami bagian...' : ''; ?>" class="form-input mt-1">
                </div>

                <?php if (!$is_material_discussion): ?>
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
                        <select id="category" name="category" class="form-input mt-1">
                            <option value="Sains">Sains</option>
                            <option value="Teknologi">Teknologi</option>
                            <option value="Sosial">Sosial</option>
                            <option value="Umum">Umum</option>
                        </select>
                    </div>
                <?php else: ?>
                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($material_category); ?>">
                <?php endif; ?>

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700">Konten Diskusi</label>
                    <textarea id="content" name="content" rows="8" required placeholder="Tulis detail pertanyaan atau diskusimu..." class="form-input mt-1"></textarea>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                    <a href="<?php echo $is_material_discussion ? '../materials/view.php?id='.$material_id : 'index.php'; ?>" class="btn btn-outline bg-white px-4">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary px-6">
                        Posting Diskusi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
