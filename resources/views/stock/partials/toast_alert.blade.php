<!-- Live Toast Alert Banner (Hidden by default) -->
<div id="toast-alert" class="hidden p-4 rounded-2xl border transition-all duration-300 transform scale-95 shadow-xl flex items-center justify-between">
    <div class="flex items-center space-x-3">
        <div id="toast-icon-bg" class="w-10 h-10 rounded-xl flex items-center justify-center text-lg font-bold">
            <i id="toast-icon" class="fa-solid"></i>
        </div>
        <div>
            <h4 id="toast-title" class="font-bold text-sm"></h4>
            <p id="toast-message" class="text-xs opacity-90"></p>
        </div>
    </div>
    <button onclick="closeToast()" type="button" class="text-xs opacity-70 hover:opacity-100 p-1"><i class="fa-solid fa-xmark text-base"></i></button>
</div>
