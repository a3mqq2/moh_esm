@extends('layouts.app')

@section('title', 'إدارة المستشفيات')

@section('page-title')
    <div class="page-title-head d-flex align-items-center">
        <div class="flex-grow-1">
            <h4 class="page-main-title m-0">إدارة المستشفيات</h4>
        </div>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="ti ti-plus me-1"></i> إضافة مستشفى
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
                        <strong>إضافة مستشفى</strong>
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
                    <p style="margin:0; font-size:0.9rem; color:#666;">اكتب اسم المستشفى ثم اضغط <kbd style="background:#000000; padding:2px 8px; border-radius:4px; font-size:0.85rem;">Enter</kbd> للحفظ مباشرة</p>
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
                            <input type="text" class="form-control border-start-0 ps-0" id="hospitals-search" placeholder="ابحث في الاسم، البريد، الموقع، الحالة..." />
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="hospitals-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الشعار</th>
                                    <th>اسم المستشفى</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>الموقع</th>
                                    <th>الحالة</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th class="text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($hospitals as $hospital)
                                    <tr id="row-{{ $hospital->id }}">
                                        <td>{{ ($hospitals->firstItem() ?? 1) + $loop->index }}</td>
                                        <td class="hospital-logo">
                                            @if($hospital->logo)
                                                <img src="{{ asset('storage/' . $hospital->logo) }}" alt="{{ $hospital->name }}" class="rounded" width="40" height="40" style="object-fit:cover;" />
                                            @else
                                                <span class="avatar-sm d-inline-flex align-items-center justify-content-center rounded bg-primary-subtle text-primary">
                                                    <i class="ti ti-building-hospital fs-4"></i>
                                                </span>
                                            @endif
                                        </td>
                                        <td class="hospital-name">{{ $hospital->name }}</td>
                                        <td class="hospital-email">{{ $hospital->email ?? '-' }}</td>
                                        <td class="hospital-location">{{ $hospital->location ?? '-' }}</td>
                                        <td>
                                            @if($hospital->is_active)
                                                <span class="badge bg-success-subtle text-success">نشط</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">غير نشط</span>
                                            @endif
                                        </td>
                                        <td>{{ $hospital->created_at->format('Y-m-d') }}</td>
                                        <td class="text-center">
                                            @if($hospital->latitude && $hospital->longitude)
                                                <a href="https://www.google.com/maps?q={{ $hospital->latitude }},{{ $hospital->longitude }}" target="_blank" class="btn btn-sm btn-soft-info">
                                                    <i class="ti ti-map-pin me-1"></i> الخريطة
                                                </a>
                                            @endif
                                            <button class="btn btn-sm btn-soft-primary btn-units" data-id="{{ $hospital->id }}" data-name="{{ $hospital->name }}">
                                                <i class="ti ti-building-community me-1"></i> الوحدات
                                            </button>
                                            <button class="btn btn-sm btn-soft-warning btn-edit"
                                                data-id="{{ $hospital->id }}"
                                                data-name="{{ $hospital->name }}"
                                                data-location="{{ $hospital->location }}"
                                                data-email="{{ $hospital->email }}"
                                                data-latitude="{{ $hospital->latitude }}"
                                                data-longitude="{{ $hospital->longitude }}"
                                                data-is-active="{{ $hospital->is_active ? '1' : '0' }}"
                                                data-notes="{{ $hospital->notes }}"
                                                data-logo="{{ $hospital->logo ? asset('storage/' . $hospital->logo) : '' }}">
                                                <i class="ti ti-pencil me-1"></i> تعديل
                                            </button>
                                            <button class="btn btn-sm btn-soft-danger btn-delete" data-id="{{ $hospital->id }}" data-name="{{ $hospital->name }}">
                                                <i class="ti ti-trash me-1"></i> حذف
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="no-data">
                                        <td colspan="8" class="text-center text-muted py-4">لا توجد مستشفيات حتى الآن</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($hospitals->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted small">
                                عرض {{ $hospitals->firstItem() }} إلى {{ $hospitals->lastItem() }} من {{ $hospitals->total() }}
                            </div>
                            {{ $hospitals->links() }}
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
                    <h5 class="modal-title">إضافة مستشفى جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="add-name" class="form-label">اسم المستشفى <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add-name" name="name" placeholder="أدخل اسم المستشفى" required autofocus />
                            <div class="invalid-feedback" id="add-name-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="add-email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control" id="add-email" name="email" placeholder="example@hospital.com" dir="ltr" />
                            <div class="invalid-feedback" id="add-email-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="add-location" class="form-label">الموقع</label>
                            <input type="text" class="form-control" id="add-location" name="location" placeholder="أدخل موقع المستشفى" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الموقع على الخريطة</label>
                            <div id="add-map" class="map-container"></div>
                            <div class="row mt-2">
                                <div class="col-6">
                                    <input type="text" class="form-control form-control-sm" id="add-latitude" name="latitude" placeholder="خط العرض" dir="ltr" readonly />
                                </div>
                                <div class="col-6">
                                    <input type="text" class="form-control form-control-sm" id="add-longitude" name="longitude" placeholder="خط الطول" dir="ltr" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الحالة</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="add-is-active" name="is_active" checked />
                                <label class="form-check-label" for="add-is-active">نشط</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="add-notes" class="form-label">ملاحظات</label>
                            <textarea class="form-control" id="add-notes" name="notes" rows="2" placeholder="ملاحظات إضافية..."></textarea>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">شعار المستشفى</label>
                            <div class="logo-dropzone" id="add-dropzone">
                                <input type="file" id="add-logo" name="logo" accept="image/*" style="display:none;" />
                                <div class="dropzone-placeholder" id="add-placeholder">
                                    <i class="ti ti-cloud-upload fs-1 text-muted"></i>
                                    <p class="mb-1 text-muted">اسحب الصورة وأفلتها هنا</p>
                                    <span class="text-muted fs-xs">أو اضغط لاختيار ملف</span>
                                </div>
                                <div class="dropzone-preview d-none" id="add-preview">
                                    <img id="add-preview-img" src="" alt="preview" />
                                    <button type="button" class="dropzone-remove" id="add-remove" title="إزالة"><i class="ti ti-x"></i></button>
                                </div>
                            </div>
                            <div class="text-danger mt-1 fs-13" id="add-logo-error"></div>
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
                    <h5 class="modal-title">تعديل المستشفى</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm" enctype="multipart/form-data">
                    <input type="hidden" id="edit-id" />
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-name" class="form-label">اسم المستشفى <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-name" name="name" placeholder="أدخل اسم المستشفى" required />
                            <div class="invalid-feedback" id="edit-name-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit-email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control" id="edit-email" name="email" placeholder="example@hospital.com" dir="ltr" />
                            <div class="invalid-feedback" id="edit-email-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit-location" class="form-label">الموقع</label>
                            <input type="text" class="form-control" id="edit-location" name="location" placeholder="أدخل موقع المستشفى" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الموقع على الخريطة</label>
                            <div id="edit-map" class="map-container"></div>
                            <div class="row mt-2">
                                <div class="col-6">
                                    <input type="text" class="form-control form-control-sm" id="edit-latitude" name="latitude" placeholder="خط العرض" dir="ltr" readonly />
                                </div>
                                <div class="col-6">
                                    <input type="text" class="form-control form-control-sm" id="edit-longitude" name="longitude" placeholder="خط الطول" dir="ltr" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الحالة</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="edit-is-active" name="is_active" />
                                <label class="form-check-label" for="edit-is-active">نشط</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit-notes" class="form-label">ملاحظات</label>
                            <textarea class="form-control" id="edit-notes" name="notes" rows="2" placeholder="ملاحظات إضافية..."></textarea>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">شعار المستشفى</label>
                            <div class="logo-dropzone" id="edit-dropzone">
                                <input type="file" id="edit-logo" name="logo" accept="image/*" style="display:none;" />
                                <div class="dropzone-placeholder" id="edit-placeholder">
                                    <i class="ti ti-cloud-upload fs-1 text-muted"></i>
                                    <p class="mb-1 text-muted">اسحب الصورة وأفلتها هنا</p>
                                    <span class="text-muted fs-xs">أو اضغط لاختيار ملف</span>
                                </div>
                                <div class="dropzone-preview d-none" id="edit-preview">
                                    <img id="edit-preview-img" src="" alt="preview" />
                                    <button type="button" class="dropzone-remove" id="edit-remove" title="إزالة"><i class="ti ti-x"></i></button>
                                </div>
                            </div>
                            <div class="text-danger mt-1 fs-13" id="edit-logo-error"></div>
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

    {{-- Units Modal --}}
    <div class="modal fade" id="unitsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="modal-title fw-bold mb-1">
                            <i class="ti ti-building-community me-2 text-primary"></i>
                            <span id="units-hospital-name"></span>
                        </h5>
                        <p class="text-muted mb-0 fs-13">حدد الأقسام والعنايات مع عدد الأسرّة لكل منها</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-3">
                    <input type="hidden" id="units-hospital-id" />

                    {{-- Summary bar --}}
                    <div class="d-flex gap-3 mb-3">
                        <div class="flex-fill rounded-3 p-2 px-3 bg-primary-subtle text-center">
                            <div class="fs-5 fw-bold text-primary" id="summary-depts">0</div>
                            <div class="fs-13 text-muted">قسم</div>
                        </div>
                        <div class="flex-fill rounded-3 p-2 px-3 bg-info-subtle text-center">
                            <div class="fs-5 fw-bold text-info" id="summary-wards">0</div>
                            <div class="fs-13 text-muted">عناية</div>
                        </div>
                        <div class="flex-fill rounded-3 p-2 px-3 bg-success-subtle text-center">
                            <div class="fs-5 fw-bold text-success" id="summary-beds">0</div>
                            <div class="fs-13 text-muted">سرير</div>
                        </div>
                    </div>

                    {{-- Tabs --}}
                    <ul class="nav nav-tabs nav-fill" id="units-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tab-depts" data-bs-toggle="tab" data-bs-target="#pane-depts" type="button" role="tab">
                                <i class="ti ti-category me-1"></i> الأقسام
                                <span class="badge bg-primary-subtle text-primary ms-1" id="badge-depts">0</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-wards" data-bs-toggle="tab" data-bs-target="#pane-wards" type="button" role="tab">
                                <i class="ti ti-heartbeat me-1"></i> العنايات
                                <span class="badge bg-info-subtle text-info ms-1" id="badge-wards">0</span>
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        {{-- Departments pane --}}
                        <div class="tab-pane fade show active" id="pane-depts" role="tabpanel">
                            <div class="d-flex align-items-center justify-content-between py-2 px-1 border-bottom">
                                <div class="form-check mb-0">
                                    <input type="checkbox" class="form-check-input" id="check-all-depts" />
                                    <label class="form-check-label text-muted fs-13" for="check-all-depts">تحديد الكل</label>
                                </div>
                                <span class="text-muted fs-13"><kbd class="bg-dark px-1">↑↓</kbd> تنقل &nbsp; <kbd class="bg-dark px-1">Space</kbd> تحديد &nbsp; <kbd class="bg-dark px-1">Tab</kbd> أسرّة</span>
                            </div>
                            <div class="units-list" id="depts-list">
                                @foreach($departments as $dept)
                                    <div class="unit-row" data-type="dept" data-id="{{ $dept->id }}" tabindex="0">
                                        <div class="unit-row-inner">
                                            <div class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input dept-check" value="{{ $dept->id }}" id="dept-{{ $dept->id }}" />
                                            </div>
                                            <label class="unit-name flex-grow-1" for="dept-{{ $dept->id }}">
                                                <span class="unit-icon"><i class="ti ti-category"></i></span>
                                                {{ $dept->name }}
                                            </label>
                                            <div class="unit-beds">
                                                <button type="button" class="beds-btn beds-minus" tabindex="-1"><i class="ti ti-minus"></i></button>
                                                <input type="number" class="beds-input dept-beds" data-id="{{ $dept->id }}" min="0" value="0" disabled />
                                                <button type="button" class="beds-btn beds-plus" tabindex="-1"><i class="ti ti-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Wards pane --}}
                        <div class="tab-pane fade" id="pane-wards" role="tabpanel">
                            <div class="d-flex align-items-center justify-content-between py-2 px-1 border-bottom">
                                <div class="form-check mb-0">
                                    <input type="checkbox" class="form-check-input" id="check-all-wards" />
                                    <label class="form-check-label text-muted fs-13" for="check-all-wards">تحديد الكل</label>
                                </div>
                                <span class="text-muted fs-13"><kbd class="bg-dark px-1">↑↓</kbd> تنقل &nbsp; <kbd class="bg-dark px-1">Space</kbd> تحديد &nbsp; <kbd class="bg-dark px-1">Tab</kbd> أسرّة</span>
                            </div>
                            <div class="units-list" id="wards-list">
                                @foreach($wards as $ward)
                                    <div class="unit-row" data-type="ward" data-id="{{ $ward->id }}" tabindex="0">
                                        <div class="unit-row-inner">
                                            <div class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input ward-check" value="{{ $ward->id }}" id="ward-{{ $ward->id }}" />
                                            </div>
                                            <label class="unit-name flex-grow-1" for="ward-{{ $ward->id }}">
                                                <span class="unit-icon ward-icon"><i class="ti ti-heartbeat"></i></span>
                                                {{ $ward->name }}
                                            </label>
                                            <div class="unit-beds">
                                                <button type="button" class="beds-btn beds-minus" tabindex="-1"><i class="ti ti-minus"></i></button>
                                                <input type="number" class="beds-input ward-beds" data-id="{{ $ward->id }}" min="0" value="0" disabled />
                                                <button type="button" class="beds-btn beds-plus" tabindex="-1"><i class="ti ti-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-primary px-4" id="save-units-btn">
                        <span class="spinner-border spinner-border-sm d-none me-1" id="units-spinner"></span>
                        <i class="ti ti-device-floppy me-1"></i> حفظ
                    </button>
                </div>
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
                    <p>هل أنت متأكد من حذف المستشفى <strong id="delete-name"></strong>؟</p>
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
        .logo-dropzone {
            border: 2px dashed var(--bs-border-color);
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: var(--bs-tertiary-bg);
        }
        .logo-dropzone:hover, .logo-dropzone.dragover {
            border-color: var(--bs-primary);
            background: rgba(var(--bs-primary-rgb), 0.05);
        }
        .logo-dropzone.dragover { transform: scale(1.02); }
        .dropzone-placeholder { pointer-events: none; }
        .dropzone-placeholder i { display: block; margin-bottom: 8px; }
        .dropzone-preview { position: relative; display: inline-block; }
        .dropzone-preview img {
            width: 100px; height: 100px; object-fit: cover;
            border-radius: 12px; border: 3px solid var(--bs-border-color);
        }
        .dropzone-remove {
            position: absolute; top: -8px; right: -8px;
            width: 26px; height: 26px; border-radius: 50%;
            background: var(--bs-danger); color: #fff;
            border: 2px solid #fff; display: flex;
            align-items: center; justify-content: center;
            cursor: pointer; font-size: 14px; padding: 0;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }
        .dropzone-remove:hover { background: #c0392b; }
        .map-container {
            width: 100%;
            height: 250px;
            border-radius: 12px;
            border: 2px solid var(--bs-border-color);
            overflow: hidden;
        }
        /* Units Modal */
        .units-list {
            max-height: 340px;
            overflow-y: auto;
            scrollbar-width: thin;
        }
        .unit-row {
            outline: none;
            border-radius: 10px;
            margin: 4px 0;
            transition: all 0.15s ease;
        }
        .unit-row:hover, .unit-row:focus {
            background: rgba(var(--bs-primary-rgb), 0.06);
        }
        .unit-row:focus {
            box-shadow: inset 0 0 0 2px rgba(var(--bs-primary-rgb), 0.25);
        }
        .unit-row.selected {
            background: rgba(var(--bs-primary-rgb), 0.08);
        }
        .unit-row-inner {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
        }
        .unit-name {
            cursor: pointer;
            font-size: 0.92rem;
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
            user-select: none;
        }
        .unit-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: var(--bs-primary-subtle);
            color: var(--bs-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }
        .unit-icon.ward-icon {
            background: var(--bs-info-subtle);
            color: var(--bs-info);
        }
        .unit-beds {
            display: flex;
            align-items: center;
            gap: 2px;
            opacity: 0.35;
            transition: opacity 0.2s;
        }
        .unit-row.selected .unit-beds {
            opacity: 1;
        }
        .beds-input {
            width: 58px;
            text-align: center;
            border: 1px solid var(--bs-border-color);
            border-radius: 6px;
            padding: 4px 2px;
            font-size: 0.9rem;
            font-weight: 600;
            background: var(--bs-body-bg);
            -moz-appearance: textfield;
        }
        .beds-input::-webkit-outer-spin-button,
        .beds-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .beds-input:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 2px rgba(var(--bs-primary-rgb), 0.15);
            outline: none;
        }
        .beds-btn {
            width: 28px; height: 28px;
            border-radius: 6px;
            border: 1px solid var(--bs-border-color);
            background: var(--bs-body-bg);
            color: var(--bs-body-color);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.75rem;
            transition: all 0.15s;
            padding: 0;
        }
        .beds-btn:hover {
            background: var(--bs-primary);
            color: #fff;
            border-color: var(--bs-primary);
        }
        #units-tabs .nav-link {
            font-weight: 500;
            padding: 10px 16px;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var token = '{{ csrf_token() }}';
        var baseUrl = '{{ url("hospitals") }}';

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
                if (input && input.type !== 'file') input.classList.add('is-invalid');
                if (errorDiv) errorDiv.textContent = errors[field][0];
            });
        }

        // Dropzone setup
        function setupDropzone(prefix) {
            var dropzone = document.getElementById(prefix + '-dropzone');
            var input = document.getElementById(prefix + '-logo');
            var placeholder = document.getElementById(prefix + '-placeholder');
            var preview = document.getElementById(prefix + '-preview');
            var previewImg = document.getElementById(prefix + '-preview-img');
            var removeBtn = document.getElementById(prefix + '-remove');

            function showPreview(src) {
                previewImg.src = src;
                placeholder.classList.add('d-none');
                preview.classList.remove('d-none');
            }

            function resetDropzone() {
                input.value = '';
                placeholder.classList.remove('d-none');
                preview.classList.add('d-none');
                previewImg.src = '';
            }

            function handleFile(file) {
                if (!file || !file.type.startsWith('image/')) return;
                var dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
                var reader = new FileReader();
                reader.onload = function(e) { showPreview(e.target.result); };
                reader.readAsDataURL(file);
            }

            dropzone.addEventListener('click', function(e) {
                if (e.target.closest('.dropzone-remove')) return;
                input.click();
            });

            input.addEventListener('change', function() {
                if (this.files && this.files[0]) handleFile(this.files[0]);
            });

            dropzone.addEventListener('dragover', function(e) {
                e.preventDefault();
                dropzone.classList.add('dragover');
            });

            dropzone.addEventListener('dragleave', function() {
                dropzone.classList.remove('dragover');
            });

            dropzone.addEventListener('drop', function(e) {
                e.preventDefault();
                dropzone.classList.remove('dragover');
                if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                    handleFile(e.dataTransfer.files[0]);
                }
            });

            removeBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                resetDropzone();
            });

            return { showPreview: showPreview, reset: resetDropzone };
        }

        var addDropzone = setupDropzone('add');
        var editDropzone = setupDropzone('edit');

        // Map setup
        var defaultLat = 32.9;
        var defaultLng = 13.18;
        var defaultZoom = 7;

        function setupMap(containerId, latInput, lngInput, initialLat, initialLng) {
            var map = L.map(containerId).setView([initialLat || defaultLat, initialLng || defaultLng], initialLat ? 13 : defaultZoom);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);
            var marker = null;
            if (initialLat && initialLng) {
                marker = L.marker([initialLat, initialLng]).addTo(map);
                document.getElementById(latInput).value = initialLat;
                document.getElementById(lngInput).value = initialLng;
            }
            map.on('click', function(e) {
                var lat = e.latlng.lat.toFixed(7);
                var lng = e.latlng.lng.toFixed(7);
                if (marker) map.removeLayer(marker);
                marker = L.marker([lat, lng]).addTo(map);
                document.getElementById(latInput).value = lat;
                document.getElementById(lngInput).value = lng;
            });
            return map;
        }

        var addMap = null;
        var editMap = null;

        document.getElementById('addModal').addEventListener('shown.bs.modal', function () {
            document.getElementById('add-name').focus();
            if (!addMap) {
                addMap = setupMap('add-map', 'add-latitude', 'add-longitude');
            } else {
                addMap.invalidateSize();
            }
        });

        document.getElementById('editModal').addEventListener('shown.bs.modal', function () {
            document.getElementById('edit-name').focus();
            setTimeout(function() {
                if (editMap) editMap.invalidateSize();
            }, 200);
        });

        // Add
        document.getElementById('addForm').addEventListener('submit', function (e) {
            e.preventDefault();
            clearValidation('add');
            var spinner = document.getElementById('add-spinner');
            var btn = document.getElementById('add-btn');
            spinner.classList.remove('d-none');
            btn.disabled = true;

            var formData = new FormData();
            formData.append('name', document.getElementById('add-name').value);
            formData.append('email', document.getElementById('add-email').value);
            formData.append('location', document.getElementById('add-location').value);
            formData.append('latitude', document.getElementById('add-latitude').value);
            formData.append('longitude', document.getElementById('add-longitude').value);
            formData.append('is_active', document.getElementById('add-is-active').checked ? '1' : '0');
            formData.append('notes', document.getElementById('add-notes').value);
            var logoFile = document.getElementById('add-logo').files[0];
            if (logoFile) formData.append('logo', logoFile);

            fetch(baseUrl, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                body: formData
            })
            .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d }; }); })
            .then(function (res) {
                spinner.classList.add('d-none');
                btn.disabled = false;
                if (res.ok) {
                    var h = res.data.hospital;
                    var noData = document.getElementById('no-data');
                    if (noData) noData.remove();
                    var tbody = document.querySelector('#hospitals-table tbody');
                    var count = tbody.querySelectorAll('tr[id^="row-"]').length + 1;
                    var logoHtml = res.data.logo_url
                        ? '<img src="' + res.data.logo_url + '" class="rounded" width="40" height="40" style="object-fit:cover;" />'
                        : '<span class="avatar-sm d-inline-flex align-items-center justify-content-center rounded bg-primary-subtle text-primary"><i class="ti ti-building-hospital fs-4"></i></span>';
                    var statusBadge = h.is_active
                        ? '<span class="badge bg-success-subtle text-success">نشط</span>'
                        : '<span class="badge bg-danger-subtle text-danger">غير نشط</span>';
                    var mapBtn = (h.latitude && h.longitude)
                        ? '<a href="https://www.google.com/maps?q=' + h.latitude + ',' + h.longitude + '" target="_blank" class="btn btn-sm btn-soft-info"><i class="ti ti-map-pin me-1"></i> الخريطة</a> '
                        : '';
                    var tr = document.createElement('tr');
                    tr.id = 'row-' + h.id;
                    tr.innerHTML = '<td>' + count + '</td>'
                        + '<td class="hospital-logo">' + logoHtml + '</td>'
                        + '<td class="hospital-name">' + h.name + '</td>'
                        + '<td class="hospital-email">' + (h.email || '-') + '</td>'
                        + '<td class="hospital-location">' + (h.location || '-') + '</td>'
                        + '<td>' + statusBadge + '</td>'
                        + '<td>' + h.created_at.substring(0, 10) + '</td>'
                        + '<td class="text-center">'
                        + mapBtn
                        + '<button class="btn btn-sm btn-soft-primary btn-units" data-id="' + h.id + '" data-name="' + h.name + '"><i class="ti ti-building-community me-1"></i> الوحدات</button> '
                        + '<button class="btn btn-sm btn-soft-warning btn-edit" data-id="' + h.id + '" data-name="' + h.name + '" data-location="' + (h.location || '') + '" data-email="' + (h.email || '') + '" data-latitude="' + (h.latitude || '') + '" data-longitude="' + (h.longitude || '') + '" data-is-active="' + (h.is_active ? '1' : '0') + '" data-notes="' + (h.notes || '') + '" data-logo="' + (res.data.logo_url || '') + '"><i class="ti ti-pencil me-1"></i> تعديل</button> '
                        + '<button class="btn btn-sm btn-soft-danger btn-delete" data-id="' + h.id + '" data-name="' + h.name + '"><i class="ti ti-trash me-1"></i> حذف</button>'
                        + '</td>';
                    tbody.appendChild(tr);
                    document.getElementById('addForm').reset();
                    addDropzone.reset();
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
                document.getElementById('edit-email').value = btn.dataset.email || '';
                document.getElementById('edit-location').value = btn.dataset.location || '';
                document.getElementById('edit-latitude').value = btn.dataset.latitude || '';
                document.getElementById('edit-longitude').value = btn.dataset.longitude || '';
                document.getElementById('edit-is-active').checked = btn.dataset.isActive === '1';
                document.getElementById('edit-notes').value = btn.dataset.notes || '';
                document.getElementById('edit-logo').value = '';
                clearValidation('edit');
                if (btn.dataset.logo) {
                    editDropzone.showPreview(btn.dataset.logo);
                } else {
                    editDropzone.reset();
                }
                // Rebuild edit map
                if (editMap) { editMap.remove(); editMap = null; }
                var lat = parseFloat(btn.dataset.latitude) || defaultLat;
                var lng = parseFloat(btn.dataset.longitude) || defaultLng;
                setTimeout(function() {
                    editMap = setupMap('edit-map', 'edit-latitude', 'edit-longitude', btn.dataset.latitude ? parseFloat(btn.dataset.latitude) : null, btn.dataset.longitude ? parseFloat(btn.dataset.longitude) : null);
                }, 300);
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

            var formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('name', document.getElementById('edit-name').value);
            formData.append('email', document.getElementById('edit-email').value);
            formData.append('location', document.getElementById('edit-location').value);
            formData.append('latitude', document.getElementById('edit-latitude').value);
            formData.append('longitude', document.getElementById('edit-longitude').value);
            formData.append('is_active', document.getElementById('edit-is-active').checked ? '1' : '0');
            formData.append('notes', document.getElementById('edit-notes').value);
            var logoFile = document.getElementById('edit-logo').files[0];
            if (logoFile) formData.append('logo', logoFile);

            fetch(baseUrl + '/' + id, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                body: formData
            })
            .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d }; }); })
            .then(function (res) {
                spinner.classList.add('d-none');
                btn.disabled = false;
                if (res.ok) {
                    var row = document.getElementById('row-' + id);
                    var h = res.data.hospital;
                    row.querySelector('.hospital-name').textContent = h.name;
                    row.querySelector('.hospital-email').textContent = h.email || '-';
                    row.querySelector('.hospital-location').textContent = h.location || '-';
                    if (res.data.logo_url) {
                        row.querySelector('.hospital-logo').innerHTML = '<img src="' + res.data.logo_url + '" class="rounded" width="40" height="40" style="object-fit:cover;" />';
                    }
                    var editBtn = row.querySelector('.btn-edit');
                    editBtn.dataset.name = h.name;
                    editBtn.dataset.email = h.email || '';
                    editBtn.dataset.location = h.location || '';
                    editBtn.dataset.latitude = h.latitude || '';
                    editBtn.dataset.longitude = h.longitude || '';
                    editBtn.dataset.isActive = h.is_active ? '1' : '0';
                    editBtn.dataset.notes = h.notes || '';
                    if (res.data.logo_url) editBtn.dataset.logo = res.data.logo_url;
                    row.querySelector('.btn-delete').dataset.name = h.name;
                    bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                    showToast(res.data.message, 'success');
                } else if (res.data.errors) {
                    showErrors('edit', res.data.errors);
                }
            });
        });

        // ===== Units Modal =====
        function updateSummary() {
            var deptCount = document.querySelectorAll('.dept-check:checked').length;
            var wardCount = document.querySelectorAll('.ward-check:checked').length;
            var totalBeds = 0;
            document.querySelectorAll('.dept-check:checked').forEach(function(c) {
                totalBeds += parseInt(document.querySelector('.dept-beds[data-id="' + c.value + '"]').value) || 0;
            });
            document.querySelectorAll('.ward-check:checked').forEach(function(c) {
                totalBeds += parseInt(document.querySelector('.ward-beds[data-id="' + c.value + '"]').value) || 0;
            });
            document.getElementById('summary-depts').textContent = deptCount;
            document.getElementById('summary-wards').textContent = wardCount;
            document.getElementById('summary-beds').textContent = totalBeds;
            document.getElementById('badge-depts').textContent = deptCount;
            document.getElementById('badge-wards').textContent = wardCount;
        }

        function toggleRow(row, forceState) {
            var check = row.querySelector('.dept-check, .ward-check');
            if (!check) return;
            check.checked = forceState !== undefined ? forceState : !check.checked;
            var beds = row.querySelector('.beds-input');
            beds.disabled = !check.checked;
            if (!check.checked) beds.value = 0;
            row.classList.toggle('selected', check.checked);
            updateSummary();
        }

        // Open modal
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.btn-units');
            if (!btn) return;
            var id = btn.dataset.id;
            document.getElementById('units-hospital-id').value = id;
            document.getElementById('units-hospital-name').textContent = btn.dataset.name;

            // Reset
            document.querySelectorAll('.unit-row').forEach(function(r) { r.classList.remove('selected'); });
            document.querySelectorAll('.dept-check, .ward-check').forEach(function(c) { c.checked = false; });
            document.querySelectorAll('.dept-beds, .ward-beds').forEach(function(i) { i.value = 0; i.disabled = true; });
            document.getElementById('check-all-depts').checked = false;
            document.getElementById('check-all-wards').checked = false;

            // Switch to first tab
            var deptsTab = document.getElementById('tab-depts');
            if (deptsTab) new bootstrap.Tab(deptsTab).show();

            // Load data
            fetch(baseUrl + '/' + id + '/units', { headers: { 'Accept': 'application/json' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                data.departments.forEach(function(d) {
                    var row = document.querySelector('.unit-row[data-type="dept"][data-id="' + d.id + '"]');
                    if (row) {
                        var check = row.querySelector('.dept-check');
                        var beds = row.querySelector('.beds-input');
                        check.checked = true;
                        beds.value = d.beds;
                        beds.disabled = false;
                        row.classList.add('selected');
                    }
                });
                data.wards.forEach(function(w) {
                    var row = document.querySelector('.unit-row[data-type="ward"][data-id="' + w.id + '"]');
                    if (row) {
                        var check = row.querySelector('.ward-check');
                        var beds = row.querySelector('.beds-input');
                        check.checked = true;
                        beds.value = w.beds;
                        beds.disabled = false;
                        row.classList.add('selected');
                    }
                });
                updateSummary();
            });

            new bootstrap.Modal(document.getElementById('unitsModal')).show();
        });

        // Focus first row when modal opens
        document.getElementById('unitsModal').addEventListener('shown.bs.modal', function() {
            var first = document.querySelector('#pane-depts .unit-row');
            if (first) first.focus();
        });

        // Click on row to toggle
        document.querySelectorAll('.unit-row').forEach(function(row) {
            row.addEventListener('click', function(e) {
                if (e.target.closest('.beds-btn') || e.target.closest('.beds-input')) return;
                toggleRow(row);
                row.focus();
            });
        });

        // +/- buttons
        document.querySelectorAll('.beds-plus').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var row = this.closest('.unit-row');
                var input = row.querySelector('.beds-input');
                if (input.disabled) { toggleRow(row, true); }
                input.value = parseInt(input.value || 0) + 1;
                updateSummary();
            });
        });
        document.querySelectorAll('.beds-minus').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var input = this.closest('.unit-row').querySelector('.beds-input');
                if (!input.disabled && parseInt(input.value) > 0) {
                    input.value = parseInt(input.value) - 1;
                    updateSummary();
                }
            });
        });

        // Beds input change
        document.querySelectorAll('.beds-input').forEach(function(input) {
            input.addEventListener('input', updateSummary);
        });

        // Keyboard navigation on unit rows
        document.getElementById('unitsModal').addEventListener('keydown', function(e) {
            var row = document.activeElement.closest('.unit-row');
            // If focus is on beds-input, handle arrows for value
            if (document.activeElement.classList.contains('beds-input')) {
                if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    document.activeElement.value = parseInt(document.activeElement.value || 0) + 1;
                    updateSummary();
                    return;
                }
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (parseInt(document.activeElement.value) > 0) {
                        document.activeElement.value = parseInt(document.activeElement.value) - 1;
                        updateSummary();
                    }
                    return;
                }
                if (e.key === 'Escape') {
                    e.preventDefault();
                    e.stopPropagation();
                    if (row) row.focus();
                    return;
                }
                return;
            }

            if (!row) return;

            var activePane = document.querySelector('.tab-pane.active');
            if (!activePane) return;
            var rows = Array.from(activePane.querySelectorAll('.unit-row'));
            var idx = rows.indexOf(row);

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (idx < rows.length - 1) rows[idx + 1].focus();
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (idx > 0) rows[idx - 1].focus();
            } else if (e.key === ' ') {
                e.preventDefault();
                toggleRow(row);
            } else if (e.key === 'Tab' && !e.shiftKey) {
                var check = row.querySelector('.dept-check, .ward-check');
                if (check && check.checked) {
                    e.preventDefault();
                    row.querySelector('.beds-input').focus();
                    row.querySelector('.beds-input').select();
                }
            } else if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
                // Switch tabs with left/right
                e.preventDefault();
                var otherTab = e.key === 'ArrowLeft' ? 'tab-wards' : 'tab-depts';
                var tab = document.getElementById(otherTab);
                new bootstrap.Tab(tab).show();
                setTimeout(function() {
                    var pane = document.querySelector('.tab-pane.active');
                    var first = pane.querySelector('.unit-row');
                    if (first) first.focus();
                }, 150);
            }
        });

        // Check all
        document.getElementById('check-all-depts').addEventListener('change', function() {
            var checked = this.checked;
            document.querySelectorAll('#depts-list .unit-row').forEach(function(row) { toggleRow(row, checked); });
        });
        document.getElementById('check-all-wards').addEventListener('change', function() {
            var checked = this.checked;
            document.querySelectorAll('#wards-list .unit-row').forEach(function(row) { toggleRow(row, checked); });
        });

        // Save units
        document.getElementById('save-units-btn').addEventListener('click', function() {
            var id = document.getElementById('units-hospital-id').value;
            var spinner = document.getElementById('units-spinner');
            var btn = this;
            spinner.classList.remove('d-none');
            btn.disabled = true;

            var departments = [];
            document.querySelectorAll('.dept-check:checked').forEach(function(c) {
                departments.push({ id: parseInt(c.value), beds: parseInt(document.querySelector('.dept-beds[data-id="' + c.value + '"]').value) || 0 });
            });
            var wards = [];
            document.querySelectorAll('.ward-check:checked').forEach(function(c) {
                wards.push({ id: parseInt(c.value), beds: parseInt(document.querySelector('.ward-beds[data-id="' + c.value + '"]').value) || 0 });
            });

            fetch(baseUrl + '/' + id + '/units', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                body: JSON.stringify({ departments: departments, wards: wards })
            })
            .then(function(r) { return r.json().then(function(d) { return { ok: r.ok, data: d }; }); })
            .then(function(res) {
                spinner.classList.add('d-none');
                btn.disabled = false;
                if (res.ok) {
                    bootstrap.Modal.getInstance(document.getElementById('unitsModal')).hide();
                    showToast(res.data.message, 'success');
                } else {
                    showToast('حدث خطأ أثناء الحفظ', 'danger');
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
                var rows = document.querySelectorAll('#hospitals-table tbody tr[id^="row-"]');
                if (rows.length === 0) {
                    document.querySelector('#hospitals-table tbody').innerHTML = '<tr id="no-data"><td colspan="8" class="text-center text-muted py-4">لا توجد مستشفيات حتى الآن</td></tr>';
                } else {
                    rows.forEach(function (r, i) { r.children[0].textContent = i + 1; });
                }
            });
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

            // Get focusable fields inside the open modal (inputs + textareas, skip hidden/file/readonly)
            var fields = Array.from(openModal.querySelectorAll('input:not([type="hidden"]):not([type="file"]):not([readonly]):not([type="checkbox"]), textarea'));
            var idx = fields.indexOf(active);
            if (idx === -1) return;

            e.preventDefault();
            if (idx < fields.length - 1) {
                fields[idx + 1].focus();
            } else {
                // Last field - submit
                var form = openModal.querySelector('form');
                if (form) form.requestSubmit();
            }
        });

        // Help tour
        var helpStep = 0;
        if (!localStorage.getItem('hospitals_help_seen')) {
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
            localStorage.setItem('hospitals_help_seen', '1');
            helpStep = 0;
        };

        // Search filter
        document.getElementById('hospitals-search').addEventListener('input', function() {
            var query = this.value.toLowerCase();
            document.querySelectorAll('#hospitals-table tbody tr[id^="row-"]').forEach(function(row) {
                var text = row.textContent.toLowerCase();
                row.style.display = text.indexOf(query) !== -1 ? '' : 'none';
            });
            var noData = document.getElementById('no-data');
            if (noData) noData.style.display = query ? 'none' : '';
        });
    });
</script>
@endsection
