<!-- MODAL ADD CATEGORY -->
<div id="addModal"
    class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center z-50 overflow-y-auto px-4 py-6">
    <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl relative my-5 flex flex-col max-h-[90vh]">
        <!-- Modal Header -->
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-2xl">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center text-orange-600">
                    <i class="fas fa-plus"></i>
                </div>
                Tambah Category
            </h2>
            <button id="closeAddModal"
                class="text-gray-400 hover:text-red-500 transition text-2xl leading-none">&times;</button>
        </div>

        <!-- Modal Body -->
        <div class="p-8 overflow-y-auto">
            <form id="addForm" method="post" action="{{ route('postcategory') }}" novalidate data-validate class="space-y-6">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Category
                        <span class="text-red-500">*</span></label>
                    <input type="text" name="name" placeholder="Konser" value="{{ old('name') }}"
                        class="w-full rounded-lg border-gray-300 shadow-sm p-2.5 border focus:ring-2 focus:ring-orange-500"
                        required>
                </div>

                <x-button type="submit" variant="primary" icon="save"
                    class="w-full justify-center">Simpan</x-button>
            </form>
        </div>
    </div>
</div>
