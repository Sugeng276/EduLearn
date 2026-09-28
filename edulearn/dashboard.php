<?php
require_once 'config/database.php';
require_once 'includes/header.php';


$stats = [
    'materials' => 0,
    'threads' => 0,
    'users' => 0
];

try {
    $stats['materials'] = $pdo->query("SELECT COUNT(*) FROM materials")->fetchColumn();
    $stats['threads'] = $pdo->query("SELECT COUNT(*) FROM forum_threads")->fetchColumn();
    $stats['users'] = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

    $stmt = $pdo->query("SELECT m.*, u.full_name FROM materials m JOIN users u ON m.user_id = u.id ORDER BY m.uploaded_at DESC LIMIT 3");
    $recent_materials = $stmt->fetchAll();
} catch (PDOException $e) {
  
    $recent_materials = [];
}
?>


<div class="bg-white">
    <div class="relative isolate px-6 pt-14 lg:px-8">
        <div class="mx-auto max-w-2xl py-12 sm:py-20 lg:py-24">
            <div class="hidden sm:mb-8 sm:flex sm:justify-center">
                <div class="relative rounded-full px-3 py-1 text-sm leading-6 text-gray-600 ring-1 ring-gray-900/10 hover:ring-gray-900/20">
                    Bergabung dengan <?php echo $stats['users']; ?> pembelajar lainnya. <a href="materials/index.php" class="font-semibold text-primary-600"><span class="absolute inset-0" aria-hidden="true"></span>Mulai Belajar <span aria-hidden="true">&rarr;</span></a>
                </div>
            </div>
            <div class="text-center">
                <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">Dashboard Pembelajaran Masa Depan</h1>
                <p class="mt-6 text-lg leading-8 text-gray-600">Akses ribuan materi, berdiskusi dengan ahli, dan kembangkan potensimu bersama EduLearn.</p>
                
                <div class="mt-10 flex items-center justify-center gap-6">
                    <a href="materials/index.php" class="btn btn-outline bg-white px-5 py-3">Cari Materi</a>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="materials/create.php" class="btn btn-primary px-5 py-3 shadow-lg">Upload Materi</a>
                    <?php endif; ?>
                    <a href="forum/index.php" class="text-sm font-semibold leading-6 text-gray-900 flex items-center hover:text-primary-600 transition-colors">
                        Diskusi Forum <span aria-hidden="true" class="ml-1">→</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="bg-slate-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <dl class="grid grid-cols-1 gap-x-8 gap-y-16 text-center lg:grid-cols-3">
            <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                <dt class="text-base leading-7 text-gray-600">Total Materi</dt>
                <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900 sm:text-5xl"><?php echo $stats['materials']; ?></dd>
            </div>
            <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                <dt class="text-base leading-7 text-gray-600">Topik Diskusi</dt>
                <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900 sm:text-5xl"><?php echo $stats['threads']; ?></dd>
            </div>
            <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                <dt class="text-base leading-7 text-gray-600">Pengguna Aktif</dt>
                <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900 sm:text-5xl"><?php echo $stats['users']; ?></dd>
            </div>
        </dl>
    </div>
</div>

<div class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Materi Terbaru Diupload</h2>
            <a href="materials/index.php" class="text-sm font-medium text-primary-600 hover:text-primary-500">Lihat Semua &rarr;</a>
        </div>
        
        <?php if (count($recent_materials) > 0): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($recent_materials as $material): ?>
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100 overflow-hidden flex flex-col h-full">
                        <div class="p-6 flex-grow">
                            <div class="flex items-center justify-between mb-4">
                                <span class="px-3 py-1 bg-primary-50 text-primary-600 text-xs font-semibold rounded-full"><?php echo htmlspecialchars($material['category'] ?? 'General'); ?></span>
                                <span class="text-xs text-slate-400"><?php echo date('d M Y', strtotime($material['uploaded_at'])); ?></span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2"><?php echo htmlspecialchars($material['title']); ?></h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3"><?php echo htmlspecialchars($material['description']); ?></p>
                        </div>
                        <div class="p-6 pt-0 mt-auto border-t border-slate-50">
                            <div class="flex items-center justify-between pt-4">
                                <span class="text-xs text-slate-500">Oleh <?php echo htmlspecialchars($material['full_name']); ?></span>
                                <a href="materials/view.php?id=<?php echo $material['id']; ?>" class="text-primary-600 font-semibold text-sm hover:text-primary-700">Lihat</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-500">Belum ada materi.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
