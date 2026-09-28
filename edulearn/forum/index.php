<?php
require_once '../config/database.php';
require_once '../includes/header.php';

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

$query = "SELECT f.*, u.full_name, (SELECT COUNT(*) FROM forum_replies r WHERE r.thread_id = f.id) as reply_count FROM forum_threads f JOIN users u ON f.user_id = u.id WHERE 1=1";
$params = [];

if ($search) {
    $query .= " AND (f.title LIKE :search OR f.content LIKE :search)";
    $params[':search'] = "%$search%";
}

if ($category) {
    $query .= " AND f.category = :category";
    $params[':category'] = $category;
}

$query .= " ORDER BY f.created_at DESC";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $threads = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching threads: " . $e->getMessage());
}
?>

<div class="relative overflow-hidden mb-8">

    <div class="container mx-auto py-12 px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Forum Diskusi</h1>
            <p class="text-gray-500 text-lg">Tempat berbagi ilmu, bertanya, dan berdiskusi dengan komunitas EduLearn.</p>
            
            <div class="mt-6">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="create.php" class="btn btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Buat Topik Baru
                    </a>
                <?php else: ?>
                    <a href="../login.php" class="btn btn-outline bg-white">
                        Login untuk Diskusi
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="search-bar-container max-w-4xl mx-auto">
            <div class="flex-grow">
                <form action="" method="GET" class="w-full">
                     <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="clean-input" placeholder="Cari topik diskusi...">
                </form>
            </div>
            <div class="divider hidden sm:block"></div>
            <div class="flex-shrink-0 sm:w-64">
                <select onchange="window.location.href='index.php?category='+this.value + '<?php echo $search ? "&search=$search" : ""; ?>'" class="clean-select">
                    <option value="">Semua Kategori</option>
                    <option value="Sains" <?php echo $category == 'Sains' ? 'selected' : ''; ?>>Sains</option>
                    <option value="Teknologi" <?php echo $category == 'Teknologi' ? 'selected' : ''; ?>>Teknologi</option>
                    <option value="Sosial" <?php echo $category == 'Sosial' ? 'selected' : ''; ?>>Sosial</option>
                    <option value="Umum" <?php echo $category == 'Umum' ? 'selected' : ''; ?>>Umum</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul role="list" class="divide-y divide-gray-200">
            <?php if (count($threads) > 0): ?>
                <?php foreach ($threads as $thread): ?>
                    <li>
                        <a href="view.php?id=<?php echo $thread['id']; ?>" class="block hover:bg-gray-50 transition-colors">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <p class="text-sm font-medium text-primary-600 truncate"><?php echo htmlspecialchars($thread['title']); ?></p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <?php echo htmlspecialchars($thread['category']); ?>
                                        </span>
                                    </div>
                                    <div class="ml-2 flex-shrink-0 flex items-center gap-2">
                                        <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            <?php echo $thread['reply_count']; ?> Balasan
                                        </p>
                                        <?php if (isset($_SESSION['user_id']) && ($thread['user_id'] == $_SESSION['user_id'] || (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'))): ?>
                                            <button @click.prevent.stop="$dispatch('open-delete-modal', { url: 'delete.php?id=<?php echo $thread['id']; ?>' })" class="text-red-400 hover:text-red-600 p-1">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <p class="flex items-center text-sm text-gray-500">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            <?php echo htmlspecialchars($thread['full_name']); ?>
                                        </p>
                                    </div>
                                    <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <p>
                                            Diposting pada <time datetime="<?php echo $thread['created_at']; ?>"><?php echo date('d M Y', strtotime($thread['created_at'])); ?></time>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="px-4 py-8 text-center text-gray-500">
                    Belum ada topik diskusi. Jadilah yang pertama membuatnya!
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
