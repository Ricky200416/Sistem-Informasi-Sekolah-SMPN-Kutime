{{-- resources/views/admin/kelas/_modal_edit.blade.php --}}
<div id="modalEditKelas"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     role="dialog" aria-modal="true">

    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
         onclick="closeModal('modalEditKelas')"></div>

    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-lg
                max-h-[92vh] flex flex-col animate-modal">

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100
                    dark:border-slate-700 shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-amber-100 dark:bg-amber-900/40
                            flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                                 m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100 leading-tight">
                        Edit Kelas
                    </h2>
                    <p class="text-[10px] text-slate-400 mt-0.5">
                        Perubahan akan tercermin di Data Akademik &amp; Kelola User
                    </p>
                </div>
            </div>
            <button onclick="closeModal('modalEditKelas')"
                    class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600
                           hover:bg-slate-100 dark:hover:bg-slate-700 transition shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="overflow-y-auto px-5 py-4 flex-1">
            {{--
                Action form diisi oleh JS: document.getElementById('formEditKelas').action = '/admin/kelas/' + id
                Method PUT dikirim via _method hidden field.
            --}}
            <form id="formEditKelas" method="POST" class="space-y-3">
                @csrf
                @method('PUT')

                {{-- Row 1: Nama Kelas + Tingkat --}}
                <div class="grid grid-cols-2 gap-3">
                    <div class="col-span-2 sm:col-span-1">
                        <label for="editKelasName"
                               class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Nama Kelas <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="editKelasName" required
                               placeholder="mis. VII A"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2 text-xs transition
                                      focus:outline-none focus:ring-2 focus:ring-indigo-300
                                      bg-white dark:bg-slate-700 dark:text-slate-200">
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="editKelasGrade"
                               class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Tingkat <span class="text-red-500">*</span>
                        </label>
                        <select name="grade" id="editKelasGrade" required
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                       px-3 py-2 text-xs transition
                                       focus:outline-none focus:ring-2 focus:ring-indigo-300
                                       bg-white dark:bg-slate-700 dark:text-slate-200">
                            <option value="">— Pilih Tingkat —</option>
                            <option value="7">VII (Kelas 7)</option>
                            <option value="8">VIII (Kelas 8)</option>
                            <option value="9">IX (Kelas 9)</option>
                        </select>
                    </div>
                </div>

                {{-- Row 2: Tahun Ajaran + Semester --}}
                {{--
                    ID elemen harus SAMA PERSIS dengan yang ada di JS openEditKelasModal():
                    editKelasAcademicYear  → name="academic_year"
                    editKelasSemester      → name="semester"
                --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="editKelasAcademicYear"
                               class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Tahun Ajaran <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="academic_year"
                               id="editKelasAcademicYear"
                               required
                               placeholder="2025/2026"
                               maxlength="9"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2 text-xs transition
                                      focus:outline-none focus:ring-2 focus:ring-indigo-300
                                      bg-white dark:bg-slate-700 dark:text-slate-200">
                        <p class="text-[9px] text-slate-400 mt-0.5">Format: YYYY/YYYY</p>
                    </div>

                    <div>
                        <label for="editKelasSemester"
                               class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Semester <span class="text-red-500">*</span>
                        </label>
                        <select name="semester"
                                id="editKelasSemester"
                                required
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                       px-3 py-2 text-xs transition
                                       focus:outline-none focus:ring-2 focus:ring-indigo-300
                                       bg-white dark:bg-slate-700 dark:text-slate-200">
                            <option value="">— Pilih —</option>
                            <option value="1">Semester 1 (Ganjil)</option>
                            <option value="2">Semester 2 (Genap)</option>
                        </select>
                    </div>
                </div>

                {{-- Wali Kelas --}}
                <div>
                    <label for="editKelasHomeroomTeacher"
                           class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                  uppercase tracking-wide mb-1">
                        Wali Kelas
                        <span class="normal-case font-normal text-slate-400">(opsional)</span>
                    </label>
                    <select name="homeroom_teacher_id"
                            id="editKelasHomeroomTeacher"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                   px-3 py-2 text-xs transition
                                   focus:outline-none focus:ring-2 focus:ring-indigo-300
                                   bg-white dark:bg-slate-700 dark:text-slate-200">
                        <option value="">— Pilih wali kelas —</option>
                        @foreach($gurus as $guru)
                            <option value="{{ $guru->id }}">
                                {{ $guru->name }}
                                @if($guru->guru && $guru->guru->nip)
                                    — NIP: {{ $guru->guru->nip }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[9px] text-slate-400 mt-0.5">
                        Memilih wali kelas akan otomatis memperbarui data guru tersebut.
                    </p>
                </div>

                {{-- Row 3: Jurusan/Seksi + Ruang --}}
                {{--
                    ID editKelasRoom → name="room" harus sesuai kolom study_groups.room
                    dan ada di $fillable model StudyGroup.
                --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="editKelasSection"
                               class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Jurusan / Seksi
                            <span class="normal-case font-normal text-slate-400">(opsional)</span>
                        </label>
                        <input type="text"
                               name="section"
                               id="editKelasSection"
                               placeholder="mis. A, IPA, IPS"
                               maxlength="10"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2 text-xs transition
                                      focus:outline-none focus:ring-2 focus:ring-indigo-300
                                      bg-white dark:bg-slate-700 dark:text-slate-200">
                    </div>

                    <div>
                        <label for="editKelasRoom"
                               class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Ruang Kelas
                            <span class="normal-case font-normal text-slate-400">(opsional)</span>
                        </label>
                        {{-- name="room" sesuai kolom study_groups.room --}}
                        <input type="text"
                               name="room"
                               id="editKelasRoom"
                               placeholder="mis. Lab Komputer, R.12"
                               maxlength="50"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2 text-xs transition
                                      focus:outline-none focus:ring-2 focus:ring-indigo-300
                                      bg-white dark:bg-slate-700 dark:text-slate-200">
                    </div>
                </div>

                {{-- Kapasitas + Status Aktif --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="editKelasCapacity"
                               class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                      uppercase tracking-wide mb-1">
                            Kapasitas Siswa
                        </label>
                        <input type="number"
                               name="capacity"
                               id="editKelasCapacity"
                               min="1" max="60"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2 text-xs transition
                                      focus:outline-none focus:ring-2 focus:ring-indigo-300
                                      bg-white dark:bg-slate-700 dark:text-slate-200">
                    </div>

                    <div class="flex items-end pb-2">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <div class="relative">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox"
                                       name="is_active"
                                       id="editKelasIsActive"
                                       value="1"
                                       class="sr-only peer">
                                <div class="w-8 h-4 rounded-full bg-slate-200 dark:bg-slate-600
                                            peer-checked:bg-indigo-500 transition-colors cursor-pointer
                                            relative">
                                    <div class="absolute top-0.5 left-0.5 w-3 h-3 rounded-full
                                                bg-white shadow transition-transform
                                                peer-checked:translate-x-4"></div>
                                </div>
                            </div>
                            <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                                Kelas Aktif
                            </span>
                        </label>
                    </div>
                </div>

            </form>
        </div>

        {{-- Footer --}}
        <div class="flex gap-2 px-5 py-3.5 border-t border-slate-100 dark:border-slate-700
                    bg-slate-50/50 dark:bg-slate-900/20 rounded-b-2xl shrink-0">
            <button type="button" onclick="closeModal('modalEditKelas')"
                    class="flex-1 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                           text-slate-600 dark:text-slate-400 text-xs font-medium
                           hover:bg-white dark:hover:bg-slate-700 transition">
                Batal
            </button>
            <button type="submit" form="formEditKelas"
                    class="flex-1 px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs
                           font-semibold hover:bg-indigo-700 active:scale-95 transition">
                Simpan Perubahan
            </button>
        </div>

    </div>
</div>