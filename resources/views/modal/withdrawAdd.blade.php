<!-- MODAL ADD WITHDRAW -->
<div id="addModal"
    class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center z-50 overflow-y-auto px-4 py-6">
    <div class="bg-white rounded-2xl w-full max-w-2xl shadow-2xl relative my-5 flex flex-col max-h-[90vh]">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-2xl">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center text-orange-600">
                    <i class="fas fa-money-bill-transfer"></i>
                </div>
                Ajukan Withdraw
            </h2>
            <button id="closeAddModal"
                class="text-gray-400 hover:text-red-500 transition text-2xl leading-none">&times;</button>
        </div>

        <div class="p-8 overflow-y-auto">
            <form id="addForm" method="post" action="{{ route('postwithdraw') }}" novalidate data-validate class="space-y-6">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Atas Nama
                        <span class="text-red-500">*</span></label>
                    <input type="text" name="name" placeholder="John Doe" value="{{ old('name') }}"
                        class="w-full rounded-lg border-gray-300 shadow-sm p-2.5 border focus:ring-2 focus:ring-orange-500"
                        required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">No Rekening
                        <span class="text-red-500">*</span></label>
                    <input type="number" name="no_rek" placeholder="4620XXXX" value="{{ old('no_rek') }}"
                        class="w-full rounded-lg border-gray-300 shadow-sm p-2.5 border focus:ring-2 focus:ring-orange-500"
                        required>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Payment Type
                            <span class="text-red-500">*</span></label>
                        <select name="payment_type"
                            class="w-full rounded-lg border-gray-300 shadow-sm p-2.5 border focus:ring-2 focus:ring-orange-500"
                            required>
                            <option value="">-- Pilih Bank --</option>
                            <option value="BCA" {{ old('payment_type') == 'BCA' ? 'selected' : '' }}>Bank BCA</option>
                            <option value="BNI" {{ old('payment_type') == 'BNI' ? 'selected' : '' }}>Bank BNI</option>
                            <option value="MANDIRI" {{ old('payment_type') == 'MANDIRI' ? 'selected' : '' }}>Bank MANDIRI</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Amount
                            <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm pointer-events-none">Rp</span>
                            <input type="text" inputmode="numeric" name="amount" id="addAmount" placeholder="0" value="{{ old('amount') }}"
                                data-label="Amount"
                                class="currency w-full rounded-lg border-gray-300 shadow-sm p-2.5 pl-10 border focus:ring-2 focus:ring-orange-500"
                                required>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Note
                        <span class="text-red-500">*</span></label>
                    <textarea name="note" id="addNote" data-required data-label="Note" class="w-full">{{ old('note') }}</textarea>
                </div>

                <x-button type="submit" variant="primary" icon="paper-plane"
                    class="w-full justify-center">Ajukan</x-button>
            </form>
        </div>
    </div>
</div>
