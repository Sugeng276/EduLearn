</main>

<footer class="bg-white border-t border-slate-200 mt-auto">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 md:flex md:items-center md:justify-between lg:px-8">
        <div class="flex justify-center space-x-6 md:order-2">
            <a href="#" class="text-slate-400 hover:text-slate-500">
                <span class="sr-only">Facebook</span>
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                </svg>
            </a>
            <a href="#" class="text-slate-400 hover:text-slate-500">
                <span class="sr-only">Instagram</span>
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772 4.902 4.902 0 011.772-1.153c.636-.247 1.363-.416 2.427-.465 1.067-.047 1.407-.06 3.808-.06h.63zm2.595 16.88c-1.363.048-1.407.06-4.043.06h-.08c-2.643 0-2.687-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a2.409 2.409 0 01-1 1 2.409-2.409 0 01-.465-2.427c.247-.636.416-1.363.465-2.427.048-1.363.06-1.407.06-4.043v-.08c0-2.643-.012-2.687-.06-4.043-.049-1.064-.218-1.791-.465-2.427a2.409 2.409 0 011-1 2.409-2.409 0 012.427-.465c.636.247 1.363.416 2.427.465 1.363.048 1.407.06 4.043.06h.08c2.643 0 2.687.012 4.043.06 1.064.049 1.791.218 2.427.465a2.409 2.409 0 011 1 2.409-2.409 0 01.465 2.427c-.247.636-.416 1.363-.465 2.427-.048 1.363-.06 1.407-.06 4.043v.08c0 2.643.012 2.687.06 4.043.049 1.064.218 1.791.465 2.427a2.409 2.409 0 01-1 1 2.409-2.409 0 01-2.427.465zM12 7a5 5 0 110 10 5 5 0 010-10zm0 2.5a2.5 2.5 0 100 5 2.5 2.5 0 000-5z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
        <div class="mt-8 md:mt-0 md:order-1">
            <p class="text-center text-sm text-slate-400">&copy; <?php echo date('Y'); ?> EduLearn. All rights reserved.</p>
        </div>
    </div>
</footer>

<!-- Global Delete Modal -->
<div x-data="{ open: false, url: '' }"
     @open-delete-modal.window="open = true; url = $event.detail.url"
     x-show="open"
     style="display: none;"
     class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  
  <div x-show="open" 
       x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
       x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
       class="modal-backdrop"></div>

  <div class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
      <div x-show="open"
           @click.away="open = false"
           x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
           x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
           class="modal-panel transform transition-all sm:w-full sm:max-w-md p-6 text-center">
        
          <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-50 mb-6">
            <svg class="h-10 w-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
          </div>
          
          <h3 class="text-xl font-bold text-slate-900 mb-2" id="modal-title">Konfirmasi Hapus</h3>
          <p class="text-sm text-slate-500 mb-8 leading-relaxed">
              Apakah Anda yakin ingin menghapus item ini?<br>Tindakan ini permanen dan tidak dapat dibatalkan.
          </p>

          <div class="flex flex-row gap-3 justify-center">
             <button @click="open = false" type="button" class="btn btn-outline w-full sm:w-auto px-6 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold hover:bg-slate-50 hover:text-slate-900 transition-all">
                Batal
             </button>
             <button @click="window.location.href = url" type="button" class="btn btn-primary w-full sm:w-auto px-6 py-2.5 rounded-xl bg-red-600 text-white font-semibold hover:bg-red-700 shadow-lg hover:shadow-red-500/30 transition-all border-none">
                Hapus Permanen
             </button>
          </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
