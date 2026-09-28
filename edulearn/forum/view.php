<?php
require_once '../config/database.php';
require_once '../includes/header.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT f.*, u.full_name FROM forum_threads f JOIN users u ON f.user_id = u.id WHERE f.id = ?");
$stmt->execute([$id]);
$thread = $stmt->fetch();

if (!$thread) {
    echo "<div class='text-center py-20 text-xl'>Topik tidak ditemukan.</div>";
    require_once '../includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $content = trim($_POST['content']);
    if (!empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO forum_replies (thread_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->execute([$id, $_SESSION['user_id'], $content]);
        echo "<script>window.location.href='view.php?id=$id';</script>";
    }
}

$stmt = $pdo->prepare("SELECT r.*, u.full_name FROM forum_replies r JOIN users u ON r.user_id = u.id WHERE r.thread_id = ? ORDER BY r.created_at ASC");
$stmt->execute([$id]);
$replies = $stmt->fetchAll();
?>

<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="flex items-center gap-4 mb-6">
        <a href="index.php" class="inline-flex items-center text-sm text-slate-500 hover:text-primary-600">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Forum
        </a>
        <?php if (!empty($thread['material_id'])): ?>
            <span class="text-slate-300">|</span>
            <a href="../materials/view.php?id=<?php echo $thread['material_id']; ?>" class="inline-flex items-center text-sm text-primary-600 hover:text-primary-700 font-medium">
                Lihat Materi Terkait
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        <?php endif; ?>
    </div>

   
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="p-8">
            <div class="flex items-center gap-3 mb-4">
                <span class="px-3 py-1 bg-primary-50 text-primary-600 text-xs font-semibold rounded-full"><?php echo htmlspecialchars($thread['category']); ?></span>
                <span class="text-sm text-slate-500"><?php echo date('d M Y H:i', strtotime($thread['created_at'])); ?></span>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-900 mb-6"><?php echo htmlspecialchars($thread['title']); ?></h1>
            
            <div class="prose max-w-none text-slate-700 mb-8 whitespace-pre-wrap leading-relaxed"><?php echo htmlspecialchars($thread['content']); ?></div>
            
            <div class="flex items-center gap-3 pt-6 border-t border-slate-100">
                <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-600 text-xs">
                    <?php echo strtoupper(substr($thread['full_name'], 0, 2)); ?>
                </div>
                <div class="text-sm">
                    <span class="font-medium text-gray-900"><?php echo htmlspecialchars($thread['full_name']); ?></span>
                    <span class="text-slate-500">Penulis</span>
                </div>
                <?php if (isset($_SESSION['user_id']) && ($thread['user_id'] == $_SESSION['user_id'] || (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'))): ?>
                    <div class="ml-auto flex items-center gap-3">
                         <?php if ($thread['user_id'] == $_SESSION['user_id']): ?>
                            <a href="edit.php?id=<?php echo $thread['id']; ?>" class="text-slate-500 hover:text-primary-600 text-sm font-medium flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit
                            </a>
                        <?php endif; ?>
                        <button @click.prevent="$dispatch('open-delete-modal', { url: 'delete.php?id=<?php echo $thread['id']; ?>' })" class="text-red-500 hover:text-red-700 text-sm font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Hapus Topik
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

   
    <div class="space-y-6">
        <h3 class="text-lg font-bold text-gray-900"><?php echo count($replies); ?> Balasan</h3>

        <?php foreach ($replies as $reply): ?>
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 ml-4 sm:ml-8 border-l-4 <?php echo $reply['user_id'] == $thread['user_id'] ? 'border-l-primary-500' : 'border-l-transparent'; ?>">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-500 text-xs">
                            <?php echo strtoupper(substr($reply['full_name'], 0, 2)); ?>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                <?php echo htmlspecialchars($reply['full_name']); ?>
                                <?php if($reply['user_id'] == $thread['user_id']): ?>
                                    <span class="ml-2 px-2 py-0.5 bg-primary-50 text-primary-600 text-xs rounded-full">Penulis</span>
                                <?php endif; ?>
                            </p>
                            <p class="text-xs text-slate-500"><?php echo date('d M Y H:i', strtotime($reply['created_at'])); ?></p>
                        </div>
                    </div>
                </div>
                <div class="text-slate-700 text-sm leading-relaxed whitespace-pre-wrap"><?php echo htmlspecialchars($reply['content']); ?></div>
                
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <div class="mt-2 text-right">
                         <button @click.prevent="$dispatch('open-delete-modal', { url: 'delete_reply.php?id=<?php echo $reply['id']; ?>' })" class="text-xs text-red-500 hover:text-red-700 hover:underline">
                            Hapus Balasan
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

  
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="mt-8 bg-white rounded-xl shadow-sm border border-slate-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Balas Diskusi</h3>
            <form action="" method="POST">
                <div class="mb-4">
                    <textarea name="content" rows="4" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm p-3 border" placeholder="Tulis balasanmu disini..."></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Kirim Balasan
                    </button>
                </div>
            </form>
        </div>
    <?php else: ?>
        <div class="mt-8 text-center p-6 bg-slate-50 rounded-xl border border-dashed border-slate-300">
            <p class="text-slate-500 text-sm">Silakan <a href="../login.php" class="text-primary-600 font-medium hover:underline">login</a> untuk membalas diskusi ini.</p>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
