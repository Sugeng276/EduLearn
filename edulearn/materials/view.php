<?php
require_once '../config/database.php';
require_once '../includes/header.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT m.*, u.full_name FROM materials m JOIN users u ON m.user_id = u.id WHERE m.id = ?");
$stmt->execute([$id]);
$material = $stmt->fetch();

if (!$material) {
    echo "<div class='min-h-screen flex items-center justify-center text-slate-500 font-medium'>Materi tidak ditemukan.</div>";
    require_once '../includes/footer.php';
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $stmt = $pdo->prepare("INSERT INTO reviews (user_id, material_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $id, $rating, $comment]);
    echo "<script>window.location.href='view.php?id=$id';</script>";
}

$stmt = $pdo->prepare("SELECT r.*, u.full_name FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.material_id = ? ORDER BY r.created_at DESC");
$stmt->execute([$id]);
$reviews = $stmt->fetchAll();

$avg_rating = 0;
if (count($reviews) > 0) {
    $total = 0;
    foreach ($reviews as $r) $total += $r['rating'];
    $avg_rating = round($total / count($reviews), 1);
}


$thread_stmt = $pdo->prepare("SELECT f.*, u.full_name, (SELECT COUNT(*) FROM forum_replies r WHERE r.thread_id = f.id) as reply_count FROM forum_threads f JOIN users u ON f.user_id = u.id WHERE f.material_id = ? ORDER BY f.created_at DESC");
$thread_stmt->execute([$id]);
$material_threads = $thread_stmt->fetchAll();
?>


<div class="relative bg-white border-b border-slate-100 overflow-hidden">
    
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 opacity-40 pointer-events-none">
        <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-primary-50 blur-3xl"></div>
        <div class="absolute top-1/2 right-0 w-64 h-64 rounded-full bg-slate-50 blur-3xl"></div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">
        <div class="max-w-4xl">
            <div class="flex items-center gap-3 mb-6">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary-50 text-primary-700">
                    <?php echo htmlspecialchars($material['category']); ?>
                </span>
                <span class="text-slate-400 text-sm font-medium">
                    <?php echo date('d M Y', strtotime($material['uploaded_at'])); ?>
                </span>
            </div>
            
            <h1 class="text-4xl md:text-5xl font-bold text-slate-900 tracking-tight leading-tight mb-6">
                <?php echo htmlspecialchars($material['title']); ?>
            </h1>

            <div class="flex flex-wrap items-center gap-6 text-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center text-slate-600 font-bold border border-white shadow-sm ring-2 ring-slate-50">
                        <?php echo strtoupper(substr($material['full_name'], 0, 2)); ?>
                    </div>
                    <div>
                        <p class="text-slate-900 font-semibold"><?php echo htmlspecialchars($material['full_name']); ?></p>
                        <p class="text-slate-500">Author</p>
                    </div>
                </div>
                
                <?php if($avg_rating > 0): ?>
                <div class="flex items-center gap-2 px-4 py-2 bg-yellow-50 rounded-xl">
                    <div class="flex text-yellow-500">
                        <?php for($i=1; $i<=5; $i++): ?>
                            <svg class="w-4 h-4 <?php echo $i <= round($avg_rating) ? 'fill-current' : 'text-yellow-200 fill-current'; ?>" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <?php endfor; ?>
                    </div>
                    <span class="font-bold text-yellow-700"><?php echo $avg_rating; ?></span>
                    <span class="text-yellow-600/70 text-xs">/ 5.0</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <div class="max-w-4xl mx-auto space-y-10">
        
        <div class="bg-white rounded-2xl shadow-lg border border-primary-100 p-6 flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4 text-center sm:text-left">
                <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center text-primary-600 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Download Materi</h3>
                    <p class="text-sm text-slate-500">Dapatkan file lengkap untuk dipelajari secara offline.</p>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                <a href="../assets/uploads/<?php echo $material['file_name']; ?>" download class="btn btn-primary px-8 py-2.5 shadow-md w-full sm:w-auto text-center">
                    Download
                </a>
                
                <?php if(isset($_SESSION['user_id']) && ($_SESSION['role'] === 'admin' || $_SESSION['user_id'] == $material['user_id'])): ?>
                    <a href="edit.php?id=<?php echo $material['id']; ?>" class="btn btn-outline border-slate-200 text-slate-600 hover:bg-slate-50 w-full sm:w-auto text-center px-4">
                        Edit
                    </a>
                    <button @click.prevent="$dispatch('open-delete-modal', { url: 'delete.php?id=<?php echo $material['id']; ?>' })" class="btn btn-outline border-red-200 text-red-600 hover:bg-red-50 w-full sm:w-auto text-center px-4">
                        Hapus
                    </button>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
            <h3 class="text-xl font-bold text-slate-900 mb-4">Deskripsi Materi</h3>
            <div class="prose prose-slate max-w-none prose-lg text-slate-600 leading-relaxed">
                <?php echo nl2br(htmlspecialchars($material['description'])); ?>
            </div>
        </div>

        
        <div class="relative">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-slate-900">Diskusi Terkait</h3>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="../forum/create.php?material_id=<?php echo $id; ?>" class="text-primary-600 text-sm font-semibold hover:text-primary-700">
                        + Mulai Diskusi
                    </a>
                <?php endif; ?>
            </div>

            <?php if (count($material_threads) > 0): ?>
                <div class="grid gap-4">
                    <?php foreach ($material_threads as $thread): ?>
                        <a href="../forum/view.php?id=<?php echo $thread['id']; ?>" class="group block bg-white rounded-xl p-5 border border-slate-100 shadow-sm hover:shadow-md hover:border-primary-100 transition-all">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h4 class="text-lg font-semibold text-slate-800 group-hover:text-primary-600 transition-colors">
                                        <?php echo htmlspecialchars($thread['title']); ?>
                                    </h4>
                                    <p class="text-slate-500 text-sm mt-1">
                                        Oleh <?php echo htmlspecialchars($thread['full_name']); ?>
                                    </p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-slate-50 text-slate-600 text-xs font-semibold group-hover:bg-primary-50 group-hover:text-primary-700">
                                    <?php echo $thread['reply_count']; ?> Balasan
                                </span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="bg-slate-50 rounded-xl p-8 text-center border border-dashed border-slate-200">
                    <p class="text-slate-500">Belum ada diskusi untuk materi ini.</p>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="../forum/create.php?material_id=<?php echo $id; ?>" class="text-primary-600 font-medium text-sm hover:underline mt-2 inline-block">Mulai yang pertama</a>
                    <?php else: ?>
                        <p class="text-sm text-slate-400 mt-2">Login untuk memulai diskusi.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="space-y-6">
            <h3 class="text-xl font-bold text-slate-900">Ulasan Pengguna</h3>
            
            <?php if (count($reviews) > 0): ?>
                <div class="space-y-6">
                    <?php foreach ($reviews as $review): ?>
                        <div class="bg-white rounded-xl p-6 border border-slate-100 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-sm">
                                        <?php echo strtoupper(substr($review['full_name'], 0, 2)); ?>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900"><?php echo htmlspecialchars($review['full_name']); ?></p>
                                        <p class="text-xs text-slate-400"><?php echo date('d M Y', strtotime($review['created_at'])); ?></p>
                                    </div>
                                </div>
                                <div class="flex text-yellow-400 text-sm">
                                    <?php for($i=1; $i<=5; $i++): ?>
                                        <svg class="w-4 h-4" fill="<?php echo $i <= $review['rating'] ? 'currentColor' : 'none'; ?>" stroke="<?php echo $i <= $review['rating'] ? 'none' : 'currentColor'; ?>" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <div class="mt-4 text-slate-600 text-sm leading-relaxed">
                                <?php echo htmlspecialchars($review['comment']); ?>
                                
                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                    <div class="mt-2 text-right">
                                        <button @click.prevent="$dispatch('open-delete-modal', { url: 'delete_review.php?id=<?php echo $review['id']; ?>' })" class="text-xs text-red-500 hover:text-red-700 hover:underline">
                                            Hapus Review
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-slate-500 italic">Belum ada ulasan.</p>
            <?php endif; ?>

            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-200 mt-8">
                    <h4 class="font-bold text-slate-900 mb-4">Tulis Ulasan Anda</h4>
                    <form action="" method="POST" x-data="{ rating: 0, hover: 0 }">
                        <input type="hidden" name="rating" :value="rating" required>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Rating</label>
                            <div class="flex items-center gap-2">
                                <template x-for="i in 5">
                                    <button type="button" 
                                        @mouseenter="hover = i" 
                                        @mouseleave="hover = 0"
                                        @click="rating = i"
                                        class="transition-all duration-200 focus:outline-none transform hover:scale-110"
                                        style="background: none; border: none; padding: 0; cursor: pointer;">
                                        <svg class="w-8 h-8" :class="(hover || rating) >= i ? 'text-yellow-400 fill-current' : 'text-slate-300 fill-current'" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </button>
                                </template>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Komentar</label>
                            <textarea name="comment" rows="4" class="form-input" placeholder="Bagikan pendapatmu tentang materi ini..." required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary shadow-lg" :disabled="rating === 0">
                            Kirim Ulasan
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
