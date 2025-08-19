<div>
    @if($showPopup)
        <!-- Overlay full screen -->
        <div style="z-index:9999;"
             class="fixed inset-0 flex bg-black p-1 rounded shadow max-h-[300px] overflow-y-auto space-y-4 justify-center">

            <!-- Popup Box -->
            <div class="bg-white 
                        w-12/12 sm:w-5/6 md:w-2/3 lg:w-1/2 
                        h-[90vh] sm:max-h-[300px] 
                        rounded-lg shadow-lg flex flex-col">
                
                <!-- Header -->
                <div class="p-4 border-b flex-shrink-0">
                    <h2 class="text-lg sm:text-xl font-bold text-center text-gray-900">
                        Syarat & Ketentuan E-Konsultasi <br class="hidden sm:block"/> 
                        Klinik Utama PKBI Jatim
                    </h2>
                </div>

                <!-- Konten scrollable -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4 text-sm text-gray-700 leading-relaxed">
                    <p>
                        Dengan menggunakan layanan E-Konsultasi, pasien menyatakan setuju untuk:
                    </p>

                    <h3 class="font-semibold text-gray-900">1. Kewajiban Pasien</h3>
                    <ul class="list-disc ml-5 space-y-1">
                        <li><span class="font-semibold">Kebenaran Informasi:</span> Memberikan data pribadi & informasi kesehatan yang jujur, lengkap, dan tidak menggunakan identitas orang lain tanpa izin.</li>
                        <li><span class="font-semibold">Kepatuhan:</span> Mengikuti arahan tenaga kesehatan serta menghargai keterbatasan layanan E-Konsultasi.</li>
                        <li><span class="font-semibold">Etika Komunikasi:</span> Menggunakan bahasa sopan, tidak melakukan pelecehan, diskriminasi, atau ancaman dalam bentuk apapun.</li>
                        <li><span class="font-semibold">Privasi:</span> Menjaga kerahasiaan konsultasi (tidak membagikan rekaman/chat tanpa izin) serta tidak menyebarkan informasi tenaga kesehatan di luar konteks layanan.</li>
                        <li><span class="font-semibold">Tanggung Jawab:</span> Melakukan pembayaran sesuai ketentuan (jika berbayar) dan memahami bahwa E-Konsultasi bukan layanan darurat. Untuk kondisi gawat darurat, segera hubungi IGD terdekat.</li>
                    </ul>

                    <h3 class="font-semibold text-gray-900">2. Persetujuan Penggunaan Data</h3>
                    <p>
                        Pasien menyetujui bahwa data yang diberikan dapat digunakan untuk keperluan rekam medis dan pelaporan internal PKBI Jatim, sesuai ketentuan perlindungan data pribadi.
                    </p>

                    <h3 class="font-semibold text-gray-900">3. Sanksi & Tindakan</h3>
                    <ul class="list-disc ml-5 space-y-1">
                        <li>Pelanggaran oleh tenaga kesehatan akan ditindak sesuai regulasi profesi dan aturan PKBI Jatim.</li>
                        <li>Pelanggaran oleh pasien dapat mengakibatkan penghentian layanan, pembatasan akses, atau pelaporan ke pihak berwenang sesuai hukum yang berlaku.</li>
                    </ul>
                </div>

                <!-- Footer -->
                <div class="p-4 border-t flex-shrink-0 space-y-4 bg-white">
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="confirm" wire:model.live="confirmed"
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                        <label for="confirm" class="text-xs sm:text-sm text-gray-700">
                            Saya sudah membaca & memahami syarat dan ketentuan di atas
                        </label>
                    </div>

                    <div class="flex justify-end gap-2">
                        {{-- <button wire:click="$set('showPopup', false)" 
                                class="px-3 sm:px-4 py-2 rounded bg-red-400 hover:bg-red-500 text-white text-sm sm:text-base">
                            Tutup
                        </button> --}}
                        <button wire:click="agree"
                            class="px-3 sm:px-4 py-2 rounded bg-green-500 text-white hover:bg-green-600 disabled:opacity-50 disabled:cursor-not-allowed text-sm sm:text-base"
                            @disabled(!$confirmed)>
                            Saya Setuju
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
