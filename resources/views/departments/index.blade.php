@extends('layouts.app')

@section('title', 'إدارة الأقسام')

@section('page-title')
    <div class="page-title-head d-flex align-items-center">
        <div class="flex-grow-1">
            <h4 class="page-main-title m-0">إدارة الأقسام</h4>
        </div>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="ti ti-plus me-1"></i> إضافة قسم
            </button>
        </div>
    </div>
@endsection

@section('content')
    {{-- Help Tour --}}
    <div id="help-tour" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; z-index:99990;">
        <div style="position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6);" onclick="nextHelpStep()"></div>
        {{-- Step 1: Arrow pointing to Add button --}}
        <div class="help-step" id="help-step-1" style="display:none;">
            <div id="help-arrow-1" style="position:absolute; z-index:99991;">
                <div style="background:#fff; color:#333; padding:14px 22px; border-radius:12px; box-shadow:0 8px 32px rgba(0,0,0,0.3); max-width:280px; font-family:Almarai,sans-serif;">
                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
                        <span style="background:var(--bs-primary); color:#fff; border-radius:50%; width:28px; height:28px; display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:0.85rem;">1</span>
                        <strong>إضافة قسم</strong>
                    </div>
                    <p style="margin:0; font-size:0.9rem; color:#666;">اضغط <kbd style="background:#040404; padding:2px 8px; border-radius:4px; font-size:0.85rem;">Enter</kbd> لفتح نافذة الإضافة بسرعة</p>
                    <div style="margin-top:12px; display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-size:0.75rem; color:#aaa;">1 / 2</span>
                        <button onclick="nextHelpStep()" style="background:var(--bs-primary); color:#fff; border:none; padding:6px 16px; border-radius:8px; cursor:pointer; font-family:Almarai,sans-serif; font-size:0.85rem;">التالي <i class="ti ti-arrow-left" style="font-size:0.8rem;"></i></button>
                    </div>
                </div>
                <div style="position:absolute; top:-8px; right:30px; width:0; height:0; border-left:8px solid transparent; border-right:8px solid transparent; border-bottom:8px solid #fff;"></div>
            </div>
        </div>
        {{-- Step 2: Arrow pointing to form inside modal --}}
        <div class="help-step" id="help-step-2" style="display:none;">
            <div id="help-arrow-2" style="position:absolute; z-index:99991;">
                <div style="background:#fff; color:#333; padding:14px 22px; border-radius:12px; box-shadow:0 8px 32px rgba(0,0,0,0.3); max-width:280px; font-family:Almarai,sans-serif;">
                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
                        <span style="background:var(--bs-success); color:#fff; border-radius:50%; width:28px; height:28px; display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:0.85rem;">2</span>
                        <strong>حفظ سريع</strong>
                    </div>
                    <p style="margin:0; font-size:0.9rem; color:#666;">اكتب اسم القسم ثم اضغط <kbd style="background:#000000; padding:2px 8px; border-radius:4px; font-size:0.85rem;">Enter</kbd> للحفظ مباشرة</p>
                    <div style="margin-top:12px; display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-size:0.75rem; color:#aaa;">2 / 2</span>
                        <button onclick="dismissHelp()" style="background:var(--bs-success); color:#fff; border:none; padding:6px 16px; border-radius:8px; cursor:pointer; font-family:Almarai,sans-serif; font-size:0.85rem;">فهمت <i class="ti ti-check" style="font-size:0.8rem;"></i></button>
                    </div>
                </div>
                <div style="position:absolute; top:-8px; left:50%; transform:translateX(-50%); width:0; height:0; border-left:8px solid transparent; border-right:8px solid transparent; border-bottom:8px solid #fff;"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="departments-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم القسم</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th class="text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($departments as $department)
                                    <tr id="row-{{ $department->id }}">
                                        <td>{{ ($departments->firstItem() ?? 1) + $loop->index }}</td>
                                        <td class="dept-name">{{ $department->name }}</td>
                                        <td>{{ $department->created_at->format('Y-m-d') }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-soft-warning btn-edit" data-id="{{ $department->id }}" data-name="{{ $department->name }}">
                                                <i class="ti ti-pencil me-1"></i> تعديل
                                            </button>
                                            <button class="btn btn-sm btn-soft-danger btn-delete" data-id="{{ $department->id }}" data-name="{{ $department->name }}">
                                                <i class="ti ti-trash me-1"></i> حذف
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="no-data">
                                        <td colspan="4" class="text-center text-muted py-4">لا توجد أقسام حتى الآن</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($departments->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted small">
                                عرض {{ $departments->firstItem() }} إلى {{ $departments->lastItem() }} من {{ $departments->total() }}
                            </div>
                            {{ $departments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Add Modal --}}
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة قسم جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addForm">
                    <div class="modal-body">
                        <div class="mb-0">
                            <label for="add-name" class="form-label">اسم القسم <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add-name" name="name" placeholder="أدخل اسم القسم" required autofocus />
                            <div class="invalid-feedback" id="add-error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary" id="add-btn">
                            <span class="spinner-border spinner-border-sm d-none me-1" id="add-spinner"></span>
                            إضافة
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تعديل القسم</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm">
                    <input type="hidden" id="edit-id" />
                    <div class="modal-body">
                        <div class="mb-0">
                            <label for="edit-name" class="form-label">اسم القسم <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-name" name="name" placeholder="أدخل اسم القسم" required />
                            <div class="invalid-feedback" id="edit-error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-warning" id="edit-btn">
                            <span class="spinner-border spinner-border-sm d-none me-1" id="edit-spinner"></span>
                            تعديل
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تأكيد الحذف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="ti ti-alert-triangle text-danger fs-1 mb-2 d-block"></i>
                    <p>هل أنت متأكد من حذف القسم <strong id="delete-name"></strong>؟</p>
                    <input type="hidden" id="delete-id" />
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete-btn">
                        <span class="spinner-border spinner-border-sm d-none me-1" id="delete-spinner"></span>
                        حذف
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var token = '{{ csrf_token() }}';
        var baseUrl = '{{ url("departments") }}';

        function showToast(message, type) {
            var toast = document.createElement('div');
            toast.className = 'position-fixed top-0 start-50 translate-middle-x mt-3 alert alert-' + type + ' alert-dismissible fade show';
            toast.style.zIndex = '99999';
            toast.innerHTML = message + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
            document.body.appendChild(toast);
            setTimeout(function () { toast.remove(); }, 3000);
        }

        // Add
        document.getElementById('addForm').addEventListener('submit', function (e) {
            e.preventDefault();
            var nameInput = document.getElementById('add-name');
            var spinner = document.getElementById('add-spinner');
            var btn = document.getElementById('add-btn');

            nameInput.classList.remove('is-invalid');
            spinner.classList.remove('d-none');
            btn.disabled = true;

            fetch(baseUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                body: JSON.stringify({ name: nameInput.value })
            })
            .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d }; }); })
            .then(function (res) {
                spinner.classList.add('d-none');
                btn.disabled = false;
                if (res.ok) {
                    var dept = res.data.department;
                    var noData = document.getElementById('no-data');
                    if (noData) noData.remove();
                    var tbody = document.querySelector('#departments-table tbody');
                    var count = tbody.querySelectorAll('tr[id^="row-"]').length + 1;
                    var tr = document.createElement('tr');
                    tr.id = 'row-' + dept.id;
                    tr.innerHTML = '<td>' + count + '</td><td class="dept-name">' + dept.name + '</td><td>' + dept.created_at.substring(0, 10) + '</td><td class="text-center"><button class="btn btn-sm btn-soft-warning btn-edit" data-id="' + dept.id + '" data-name="' + dept.name + '"><i class="ti ti-pencil me-1"></i> تعديل</button> <button class="btn btn-sm btn-soft-danger btn-delete" data-id="' + dept.id + '" data-name="' + dept.name + '"><i class="ti ti-trash me-1"></i> حذف</button></td>';
                    tbody.appendChild(tr);
                    nameInput.value = '';
                    bootstrap.Modal.getInstance(document.getElementById('addModal')).hide();
                    showToast(res.data.message, 'success');
                } else {
                    var msg = res.data.errors && res.data.errors.name ? res.data.errors.name[0] : 'حدث خطأ';
                    nameInput.classList.add('is-invalid');
                    document.getElementById('add-error').textContent = msg;
                }
            });
        });

        // Edit - open modal
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.btn-edit');
            if (btn) {
                document.getElementById('edit-id').value = btn.dataset.id;
                document.getElementById('edit-name').value = btn.dataset.name;
                document.getElementById('edit-name').classList.remove('is-invalid');
                new bootstrap.Modal(document.getElementById('editModal')).show();
            }
        });

        // Edit - submit
        document.getElementById('editForm').addEventListener('submit', function (e) {
            e.preventDefault();
            var id = document.getElementById('edit-id').value;
            var nameInput = document.getElementById('edit-name');
            var spinner = document.getElementById('edit-spinner');
            var btn = document.getElementById('edit-btn');

            nameInput.classList.remove('is-invalid');
            spinner.classList.remove('d-none');
            btn.disabled = true;

            fetch(baseUrl + '/' + id, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                body: JSON.stringify({ name: nameInput.value })
            })
            .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d }; }); })
            .then(function (res) {
                spinner.classList.add('d-none');
                btn.disabled = false;
                if (res.ok) {
                    var row = document.getElementById('row-' + id);
                    row.querySelector('.dept-name').textContent = res.data.department.name;
                    row.querySelector('.btn-edit').dataset.name = res.data.department.name;
                    row.querySelector('.btn-delete').dataset.name = res.data.department.name;
                    bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                    showToast(res.data.message, 'success');
                } else {
                    var msg = res.data.errors && res.data.errors.name ? res.data.errors.name[0] : 'حدث خطأ';
                    nameInput.classList.add('is-invalid');
                    document.getElementById('edit-error').textContent = msg;
                }
            });
        });

        // Delete - open modal
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.btn-delete');
            if (btn) {
                document.getElementById('delete-id').value = btn.dataset.id;
                document.getElementById('delete-name').textContent = btn.dataset.name;
                new bootstrap.Modal(document.getElementById('deleteModal')).show();
            }
        });

        // Delete - confirm
        document.getElementById('confirm-delete-btn').addEventListener('click', function () {
            var id = document.getElementById('delete-id').value;
            var spinner = document.getElementById('delete-spinner');
            var btn = this;

            spinner.classList.remove('d-none');
            btn.disabled = true;

            fetch(baseUrl + '/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                spinner.classList.add('d-none');
                btn.disabled = false;
                document.getElementById('row-' + id).remove();
                bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
                showToast(data.message, 'success');
                // Re-number rows
                var rows = document.querySelectorAll('#departments-table tbody tr[id^="row-"]');
                if (rows.length === 0) {
                    document.querySelector('#departments-table tbody').innerHTML = '<tr id="no-data"><td colspan="4" class="text-center text-muted py-4">لا توجد أقسام حتى الآن</td></tr>';
                } else {
                    rows.forEach(function (r, i) { r.children[0].textContent = i + 1; });
                }
            });
        });

        // Auto-focus input on modal open
        document.getElementById('addModal').addEventListener('shown.bs.modal', function () {
            document.getElementById('add-name').focus();
        });
        document.getElementById('editModal').addEventListener('shown.bs.modal', function () {
            document.getElementById('edit-name').focus();
        });

        // Enter key: navigate fields inside modal, submit on last field, open modal if none open
        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Enter') return;

            var helpTour = document.getElementById('help-tour');
            if (helpTour.style.display !== 'none') {
                nextHelpStep();
                return;
            }

            var openModal = document.querySelector('.modal.show');
            if (!openModal) {
                e.preventDefault();
                new bootstrap.Modal(document.getElementById('addModal')).show();
                return;
            }

            var active = document.activeElement;
            if (!active || active.tagName === 'BUTTON') return;

            var fields = Array.from(openModal.querySelectorAll('input:not([type="hidden"]):not([type="file"]):not([readonly]):not([type="checkbox"]), textarea'));
            var idx = fields.indexOf(active);
            if (idx === -1) return;

            e.preventDefault();
            if (idx < fields.length - 1) {
                fields[idx + 1].focus();
            } else {
                var form = openModal.querySelector('form');
                if (form) form.requestSubmit();
            }
        });

        // Help tour
        var helpStep = 0;
        if (!localStorage.getItem('departments_help_seen')) {
            showHelpTour();
        }

        function showHelpTour() {
            document.getElementById('help-tour').style.display = 'block';
            helpStep = 1;
            positionStep1();
        }

        function positionStep1() {
            document.querySelectorAll('.help-step').forEach(function(s) { s.style.display = 'none'; });
            var addBtn = document.querySelector('[data-bs-target="#addModal"]');
            if (addBtn) {
                var rect = addBtn.getBoundingClientRect();
                var arrow = document.getElementById('help-arrow-1');
                arrow.style.top = (rect.bottom + 12) + 'px';
                arrow.style.right = (window.innerWidth - rect.right) + 'px';
                arrow.style.left = 'auto';
                // Highlight the button
                addBtn.style.position = 'relative';
                addBtn.style.zIndex = '99992';
                addBtn.style.boxShadow = '0 0 0 4px rgba(var(--bs-primary-rgb),0.4)';
            }
            document.getElementById('help-step-1').style.display = 'block';
        }

        function positionStep2() {
            document.querySelectorAll('.help-step').forEach(function(s) { s.style.display = 'none'; });
            // Remove highlight from add button
            var addBtn = document.querySelector('[data-bs-target="#addModal"]');
            if (addBtn) { addBtn.style.zIndex = ''; addBtn.style.boxShadow = ''; }
            var arrow = document.getElementById('help-arrow-2');
            arrow.style.top = '50%';
            arrow.style.left = '50%';
            arrow.style.transform = 'translate(-50%, -50%)';
            document.getElementById('help-step-2').style.display = 'block';
        }

        window.nextHelpStep = function() {
            if (helpStep === 1) {
                helpStep = 2;
                positionStep2();
            } else {
                dismissHelp();
            }
        };

        window.dismissHelp = function() {
            document.getElementById('help-tour').style.display = 'none';
            var addBtn = document.querySelector('[data-bs-target="#addModal"]');
            if (addBtn) { addBtn.style.zIndex = ''; addBtn.style.boxShadow = ''; }
            localStorage.setItem('departments_help_seen', '1');
            helpStep = 0;
        };
    });
</script>
@endsection
