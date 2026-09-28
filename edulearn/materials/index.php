<?php
require_once '../config/database.php';
require_once '../includes/header.php';

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

$query = "SELECT m.*, u.full_name FROM materials m JOIN users u ON m.user_id = u.id WHERE 1=1";
$params = [];

if ($search) {
    $query .= " AND (m.title LIKE :search OR m.description LIKE :search)";
    $params[':search'] = "%$search%";
}

if ($category) {
    $query .= " AND m.category = :category";
    $params[':category'] = $category;
}

$query .= " ORDER BY m.uploaded_at DESC";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $materials = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching materials: " . $e->getMessage());
}
?>


<div class="relative overflow-hidden mb-8">
    <div class="container mx-auto py-12 px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Perpustakaan Materi</h1>
            <p class="text-gray-500 text-lg">Jelajahi koleksi materi pembelajaran dari berbagai kategori untuk meningkatkan wawasanmu.</p>
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <div class="mt-6">
                    <a href="create.php" class="btn btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Upload Materi Baru
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <div class="search-bar-container max-w-4xl mx-auto">
            <div class="flex-grow">
                <form action="" method="GET" class="w-full">
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="clean-input" placeholder="Cari materi pelajaran...">
                </form>
            </div>
            <div class="divider hidden sm:block"></div>
            <div class="flex-shrink-0 sm:w-64">
                <select onchange="window.location.href='index.php?category='+this.value + '<?php echo $search ? "&search=$search" : ""; ?>'" class="clean-select">
                    <option value="">Semua Kategori</option>
                    <option value="Sains" <?php echo $category == 'Sains' ? 'selected' : ''; ?>>Sains</option>
                    <option value="Teknologi" <?php echo $category == 'Teknologi' ? 'selected' : ''; ?>>Teknologi</option>
                    <option value="Sosial" <?php echo $category == 'Sosial' ? 'selected' : ''; ?>>Sosial</option>
                    <option value="Bahasa" <?php echo $category == 'Bahasa' ? 'selected' : ''; ?>>Bahasa</option>
                    <option value="Lainnya" <?php echo $category == 'Lainnya' ? 'selected' : ''; ?>>Lainnya</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Materials Grid -->
<div class="bg-slate-50 py-12 min-h-content">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <?php if (count($materials) > 0): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($materials as $material): ?>
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100 overflow-hidden flex flex-col h-full">
                        <div class="p-6 flex-grow">
                            <div class="flex items-center justify-between mb-4">
                                <span class="px-3 py-1 bg-primary-50 text-primary-600 text-xs font-semibold rounded-full"><?php echo htmlspecialchars($material['category'] ?? 'General'); ?></span>
                                <span class="text-xs text-slate-400"><?php echo date('d M Y', strtotime($material['uploaded_at'])); ?></span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2"><?php echo htmlspecialchars($material['title']); ?></h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3"><?php echo htmlspecialchars($material['description']); ?></p>
                            
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600">
                                    <?php echo strtoupper(substr($material['full_name'], 0, 2)); ?>
                                </div>
                                <span class="text-xs text-slate-500">Oleh <?php echo htmlspecialchars($material['full_name']); ?></span>
                            </div>
                        </div>
                        <div class="p-6 pt-0 mt-auto border-t border-slate-50">
                            <div class="flex items-center justify-between pt-4">
                                <div class="flex items-center gap-1 text-slate-400 text-xs">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                    <span>--</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <a href="view.php?id=<?php echo $material['id']; ?>" class="text-primary-600 font-semibold text-sm hover:text-primary-700 flex items-center gap-1 group">
                                        Detail
                                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                    </a>
                                    <?php if(isset($_SESSION['user_id']) && ($material['user_id'] == $_SESSION['user_id'] || (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'))): ?>
                                        <button @click.prevent="$dispatch('open-delete-modal', { url: 'delete.php?id=<?php echo $material['id']; ?>' })" class="text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-20">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-6">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Belum ada materi ditemukan</h3>
                <p class="mt-2 text-gray-500 max-w-sm mx-auto">Coba cari dengan kata kunci lain atau jadilah yang pertama mengupload materi!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
