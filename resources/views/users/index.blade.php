@extends('layouts.app')

@section('title', 'إدارة المستخدمين')

@section('page-title')
    <div class="page-title-head d-flex align-items-center">
        <div class="flex-grow-1">
            <h4 class="page-main-title m-0">إدارة المستخدمين</h4>
        </div>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="ti ti-plus me-1"></i> إضافة مستخدم
            </button>
        </div>
    </div>
@endsection

@section('content')
    {{-- Help Tour --}}
    <div id="help-tour" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; z-index:99990;">
        <div style="position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6);" onclick="nextHelpStep()"></div>
        <div class="help-step" id="help-step-1" style="display:none;">
            <div id="help-arrow-1" style="position:absolute; z-index:99991;">
                <div style="background:#fff; color:#333; padding:14px 22px; border-radius:12px; box-shadow:0 8px 32px rgba(0,0,0,0.3); max-width:280px; font-family:Almarai,sans-serif;">
                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
                        <span style="background:var(--bs-primary); color:#fff; border-radius:50%; width:28px; height:28px; display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:0.85rem;">1</span>
                        <strong>إضافة مستخدم</strong>
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
        <div class="help-step" id="help-step-2" style="display:none;">
            <div id="help-arrow-2" style="position:absolute; z-index:99991;">
                <div style="background:#fff; color:#333; padding:14px 22px; border-radius:12px; box-shadow:0 8px 32px rgba(0,0,0,0.3); max-width:280px; font-family:Almarai,sans-serif;">
                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
                        <span style="background:var(--bs-success); color:#fff; border-radius:50%; width:28px; height:28px; display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:0.85rem;">2</span>
                        <strong>حفظ سريع</strong>
                    </div>
                    <p style="margin:0; font-size:0.9rem; color:#666;">املأ البيانات ثم اضغط <kbd style="background:#000000; padding:2px 8px; border-radius:4px; font-size:0.85rem;">Enter</kbd> للحفظ مباشرة</p>
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
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0" id="users-search" placeholder="ابحث في الاسم، اسم المستخدم، النوع، المستشفى، الحالة..." />
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="users-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>اسم المستخدم</th>
                                    <th>النوع</th>
                                    <th>المستشفى</th>
                                    <th>الحالة</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th class="text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr id="row-{{ $user->id }}">
                                        <td>{{ ($users->firstItem() ?? 1) + $loop->index }}</td>
                                        <td class="user-name">{{ $user->name }}</td>
                                        <td class="user-username">{{ $user->username }}</td>
                                        <td class="user-role">
                                            @if($user->role === 'admin')
                                                <span class="badge bg-primary-subtle text-primary">مدير نظام</span>
                                            @elseif($user->role === 'hospital_manager')
                                                <span class="badge bg-success-subtle text-success">مسؤول مستشفى</span>
                                            @else
                                                <span class="badge bg-info-subtle text-info">مراقب</span>
                                            @endif
                                        </td>
                                        <td class="user-hospital">{{ $user->hospital->name ?? '-' }}</td>
                                        <td class="user-status">
                                            @if($user->is_active)
                                                <span class="badge bg-success-subtle text-success">مفعل</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">غير مفعل</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-soft-warning btn-edit"
                                                data-id="{{ $user->id }}"
                                                data-name="{{ $user->name }}"
                                                data-username="{{ $user->username }}"
                                                data-role="{{ $user->role }}"
                                                data-hospital-id="{{ $user->hospital_id }}"
                                                data-is-active="{{ $user->is_active ? '1' : '0' }}">
                                                <i class="ti ti-pencil me-1"></i> تعديل
                                            </button>
                                            @if($user->id !== auth()->id())
                                                <button class="btn btn-sm btn-soft-danger btn-delete" data-id="{{ $user->id }}" data-name="{{ $user->name }}">
                                                    <i class="ti ti-trash me-1"></i> حذف
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="no-data">
                                        <td colspan="8" class="text-center text-muted py-4">لا يوجد مستخدمين</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($users->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted small">
                                عرض {{ $users->firstItem() }} إلى {{ $users->lastItem() }} من {{ $users->total() }}
                            </div>
                            {{ $users->links() }}
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
                    <h5 class="modal-title">إضافة مستخدم جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="add-name" class="form-label">الاسم <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add-name" name="name" placeholder="الاسم الكامل" required autofocus />
                            <div class="invalid-feedback" id="add-name-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="add-username" class="form-label">اسم المستخدم <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add-username" name="username" placeholder="اسم المستخدم" required />
                            <div class="invalid-feedback" id="add-username-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="add-password" class="form-label">كلمة المرور <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="add-password" name="password" placeholder="كلمة المرور" required />
                            <div class="invalid-feedback" id="add-password-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="add-role" class="form-label">نوع المستخدم <span class="text-danger">*</span></label>
                            <select class="form-select" id="add-role" name="role" required>
                                <option value="admin">مدير نظام</option>
                                <option value="hospital_manager">مسؤول مستشفى</option>
                                <option value="observer">مراقب</option>
                            </select>
                            <div class="invalid-feedback" id="add-role-error"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الحالة</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="add-is-active" name="is_active" checked />
                                <label class="form-check-label" for="add-is-active">مفعل</label>
                            </div>
                        </div>
                        <div class="mb-0 d-none" id="add-hospital-wrapper">
                            <label for="add-hospital-id" class="form-label">المستشفى <span class="text-danger">*</span></label>
                            <select class="form-select" id="add-hospital-id" name="hospital_id">
                                <option value="">اختر المستشفى...</option>
                                @foreach($hospitals as $hospital)
                                    <option value="{{ $hospital->id }}">{{ $hospital->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="add-hospital_id-error"></div>
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
                    <h5 class="modal-title">تعديل المستخدم</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm">
                    <input type="hidden" id="edit-id" />
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-name" class="form-label">الاسم <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-name" name="name" placeholder="الاسم الكامل" required />
                            <div class="invalid-feedback" id="edit-name-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit-username" class="form-label">اسم المستخدم <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-username" name="username" placeholder="اسم المستخدم" required />
                            <div class="invalid-feedback" id="edit-username-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit-password" class="form-label">كلمة المرور <span class="text-muted">(اتركها فارغة إذا لا تريد تغييرها)</span></label>
                            <input type="password" class="form-control" id="edit-password" name="password" placeholder="كلمة المرور الجديدة" />
                            <div class="invalid-feedback" id="edit-password-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit-role" class="form-label">نوع المستخدم <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit-role" name="role" required>
                                <option value="admin">مدير نظام</option>
                                <option value="hospital_manager">مسؤول مستشفى</option>
                                <option value="observer">مراقب</option>
                            </select>
                            <div class="invalid-feedback" id="edit-role-error"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الحالة</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="edit-is-active" name="is_active" />
                                <label class="form-check-label" for="edit-is-active">مفعل</label>
                            </div>
                        </div>
                        <div class="mb-0 d-none" id="edit-hospital-wrapper">
                            <label for="edit-hospital-id" class="form-label">المستشفى <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit-hospital-id" name="hospital_id">
                                <option value="">اختر المستشفى...</option>
                                @foreach($hospitals as $hospital)
                                    <option value="{{ $hospital->id }}">{{ $hospital->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="edit-hospital_id-error"></div>
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
                    <p>هل أنت متأكد من حذف المستخدم <strong id="delete-name"></strong>؟</p>
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

    <style>
        .select2-container--bootstrap-5 .select2-selection { min-height: 38px; }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var token = '{{ csrf_token() }}';
        var baseUrl = '{{ url("users") }}';
        var currentUserId = {{ auth()->id() }};

        var roleLabels = { admin: 'مدير نظام', hospital_manager: 'مسؤول مستشفى', observer: 'مراقب' };
        var roleColors = { admin: 'primary', hospital_manager: 'success', observer: 'info' };

        function showToast(message, type) {
            var toast = document.createElement('div');
            toast.className = 'position-fixed top-0 start-50 translate-middle-x mt-3 alert alert-' + type + ' alert-dismissible fade show';
            toast.style.zIndex = '99999';
            toast.innerHTML = message + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
            document.body.appendChild(toast);
            setTimeout(function () { toast.remove(); }, 3000);
        }

        function clearValidation(prefix) {
            document.querySelectorAll('#' + prefix + 'Form .is-invalid').forEach(function(el) { el.classList.remove('is-invalid'); });
        }

        function showErrors(prefix, errors) {
            Object.keys(errors).forEach(function(field) {
                var input = document.getElementById(prefix + '-' + field);
                var errorDiv = document.getElementById(prefix + '-' + field + '-error');
                if (input) input.classList.add('is-invalid');
                if (errorDiv) errorDiv.textContent = errors[field][0];
            });
        }

        // Init Select2
        function initSelect2(selector, dropdownParent) {
            try {
                if (typeof $ !== 'undefined' && $.fn.select2) {
                    $(selector).select2({
                        theme: 'bootstrap-5',
                        placeholder: 'ابحث عن مستشفى...',
                        allowClear: true,
                        dir: 'rtl',
                        language: { noResults: function() { return 'لا توجد نتائج'; } },
                        dropdownParent: $(dropdownParent)
                    });
                }
            } catch(e) { console.warn('Select2 init error:', e); }
        }

        document.getElementById('addModal').addEventListener('shown.bs.modal', function () {
            initSelect2('#add-hospital-id', '#addModal');
            document.getElementById('add-name').focus();
        });
        document.getElementById('editModal').addEventListener('shown.bs.modal', function () {
            initSelect2('#edit-hospital-id', '#editModal');
            document.getElementById('edit-name').focus();
        });

        // Toggle hospital select visibility
        function toggleHospitalField(roleSelect, wrapperId) {
            var wrapper = document.getElementById(wrapperId);
            if (roleSelect.value === 'hospital_manager') {
                wrapper.classList.remove('d-none');
            } else {
                wrapper.classList.add('d-none');
            }
        }

        document.getElementById('add-role').addEventListener('change', function() {
            toggleHospitalField(this, 'add-hospital-wrapper');
        });
        document.getElementById('edit-role').addEventListener('change', function() {
            toggleHospitalField(this, 'edit-hospital-wrapper');
        });

        // Add
        document.getElementById('addForm').addEventListener('submit', function (e) {
            e.preventDefault();
            clearValidation('add');
            var spinner = document.getElementById('add-spinner');
            var btn = document.getElementById('add-btn');
            spinner.classList.remove('d-none');
            btn.disabled = true;

            var data = {
                name: document.getElementById('add-name').value,
                username: document.getElementById('add-username').value,
                password: document.getElementById('add-password').value,
                role: document.getElementById('add-role').value,
                is_active: document.getElementById('add-is-active').checked ? 1 : 0,
                hospital_id: document.getElementById('add-hospital-id').value || null
            };

            fetch(baseUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d }; }); })
            .then(function (res) {
                spinner.classList.add('d-none');
                btn.disabled = false;
                if (res.ok) {
                    var u = res.data.user;
                    var noData = document.getElementById('no-data');
                    if (noData) noData.remove();
                    var tbody = document.querySelector('#users-table tbody');
                    var count = tbody.querySelectorAll('tr[id^="row-"]').length + 1;
                    var color = roleColors[u.role];
                    var hospitalName = u.hospital ? u.hospital.name : '-';
                    var deleteBtn = u.id !== currentUserId
                        ? ' <button class="btn btn-sm btn-soft-danger btn-delete" data-id="' + u.id + '" data-name="' + u.name + '"><i class="ti ti-trash me-1"></i> حذف</button>'
                        : '';
                    var tr = document.createElement('tr');
                    tr.id = 'row-' + u.id;
                    var statusBadge = u.is_active
                        ? '<span class="badge bg-success-subtle text-success">مفعل</span>'
                        : '<span class="badge bg-danger-subtle text-danger">غير مفعل</span>';
                    tr.innerHTML = '<td>' + count + '</td>'
                        + '<td class="user-name">' + u.name + '</td>'
                        + '<td class="user-username">' + u.username + '</td>'
                        + '<td class="user-role"><span class="badge bg-' + color + '-subtle text-' + color + '">' + roleLabels[u.role] + '</span></td>'
                        + '<td class="user-hospital">' + hospitalName + '</td>'
                        + '<td class="user-status">' + statusBadge + '</td>'
                        + '<td>' + u.created_at.substring(0, 10) + '</td>'
                        + '<td class="text-center">'
                        + '<button class="btn btn-sm btn-soft-warning btn-edit" data-id="' + u.id + '" data-name="' + u.name + '" data-username="' + u.username + '" data-role="' + u.role + '" data-hospital-id="' + (u.hospital_id || '') + '" data-is-active="' + (u.is_active ? '1' : '0') + '"><i class="ti ti-pencil me-1"></i> تعديل</button>'
                        + deleteBtn
                        + '</td>';
                    tbody.appendChild(tr);
                    document.getElementById('addForm').reset();
                    try { $('#add-hospital-id').val(null).trigger('change'); } catch(ex) {}
                    document.getElementById('add-hospital-wrapper').classList.add('d-none');
                    bootstrap.Modal.getInstance(document.getElementById('addModal')).hide();
                    showToast(res.data.message, 'success');
                } else if (res.data.errors) {
                    showErrors('add', res.data.errors);
                }
            });
        });

        // Edit - open modal
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.btn-edit');
            if (btn) {
                document.getElementById('edit-id').value = btn.dataset.id;
                document.getElementById('edit-name').value = btn.dataset.name;
                document.getElementById('edit-username').value = btn.dataset.username;
                document.getElementById('edit-password').value = '';
                document.getElementById('edit-role').value = btn.dataset.role;
                document.getElementById('edit-is-active').checked = btn.dataset.isActive === '1';
                clearValidation('edit');
                toggleHospitalField(document.getElementById('edit-role'), 'edit-hospital-wrapper');
                var hospitalSelect = document.getElementById('edit-hospital-id');
                hospitalSelect.value = btn.dataset.hospitalId || '';
                try { $('#edit-hospital-id').val(btn.dataset.hospitalId || null).trigger('change'); } catch(ex) {}
                new bootstrap.Modal(document.getElementById('editModal')).show();
            }
        });

        // Edit - submit
        document.getElementById('editForm').addEventListener('submit', function (e) {
            e.preventDefault();
            clearValidation('edit');
            var id = document.getElementById('edit-id').value;
            var spinner = document.getElementById('edit-spinner');
            var btn = document.getElementById('edit-btn');
            spinner.classList.remove('d-none');
            btn.disabled = true;

            var data = {
                name: document.getElementById('edit-name').value,
                username: document.getElementById('edit-username').value,
                role: document.getElementById('edit-role').value,
                is_active: document.getElementById('edit-is-active').checked ? 1 : 0,
                hospital_id: document.getElementById('edit-hospital-id').value || null
            };
            var pw = document.getElementById('edit-password').value;
            if (pw) data.password = pw;

            fetch(baseUrl + '/' + id, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d }; }); })
            .then(function (res) {
                spinner.classList.add('d-none');
                btn.disabled = false;
                if (res.ok) {
                    var u = res.data.user;
                    var row = document.getElementById('row-' + id);
                    var color = roleColors[u.role];
                    row.querySelector('.user-name').textContent = u.name;
                    row.querySelector('.user-username').textContent = u.username;
                    row.querySelector('.user-role').innerHTML = '<span class="badge bg-' + color + '-subtle text-' + color + '">' + roleLabels[u.role] + '</span>';
                    row.querySelector('.user-hospital').textContent = u.hospital ? u.hospital.name : '-';
                    row.querySelector('.user-status').innerHTML = u.is_active
                        ? '<span class="badge bg-success-subtle text-success">مفعل</span>'
                        : '<span class="badge bg-danger-subtle text-danger">غير مفعل</span>';
                    var editBtn = row.querySelector('.btn-edit');
                    editBtn.dataset.name = u.name;
                    editBtn.dataset.username = u.username;
                    editBtn.dataset.role = u.role;
                    editBtn.dataset.isActive = u.is_active ? '1' : '0';
                    editBtn.dataset.hospitalId = u.hospital_id || '';
                    bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                    showToast(res.data.message, 'success');
                } else if (res.data.errors) {
                    showErrors('edit', res.data.errors);
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
                var rows = document.querySelectorAll('#users-table tbody tr[id^="row-"]');
                if (rows.length === 0) {
                    document.querySelector('#users-table tbody').innerHTML = '<tr id="no-data"><td colspan="8" class="text-center text-muted py-4">لا يوجد مستخدمين</td></tr>';
                } else {
                    rows.forEach(function (r, i) { r.children[0].textContent = i + 1; });
                }
            });
        });

        // Enter key: navigate fields inside modal, submit on last, open modal if none open
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

            // Skip if inside select2
            if (active.closest('.select2-container')) return;

            var fields = Array.from(openModal.querySelectorAll('input:not([type="hidden"]):not([type="file"]):not([readonly]):not([type="checkbox"]), select:not(.d-none select), textarea'));
            // Filter out fields inside hidden wrappers
            fields = fields.filter(function(f) {
                var wrapper = f.closest('.d-none');
                return !wrapper;
            });
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
        if (!localStorage.getItem('users_help_seen')) {
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
                addBtn.style.position = 'relative';
                addBtn.style.zIndex = '99992';
                addBtn.style.boxShadow = '0 0 0 4px rgba(var(--bs-primary-rgb),0.4)';
            }
            document.getElementById('help-step-1').style.display = 'block';
        }

        function positionStep2() {
            document.querySelectorAll('.help-step').forEach(function(s) { s.style.display = 'none'; });
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
            localStorage.setItem('users_help_seen', '1');
            helpStep = 0;
        };

        // Search filter
        document.getElementById('users-search').addEventListener('input', function() {
            var query = this.value.toLowerCase();
            document.querySelectorAll('#users-table tbody tr[id^="row-"]').forEach(function(row) {
                var text = row.textContent.toLowerCase();
                row.style.display = text.indexOf(query) !== -1 ? '' : 'none';
            });
            var noData = document.getElementById('no-data');
            if (noData) noData.style.display = query ? 'none' : '';
        });
    });
</script>
@endsection
