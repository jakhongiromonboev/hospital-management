@extends('layouts.app')
@section('page-title', '의사 관리')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">전체 의사</h5>
    <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> 의사 등록</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>이름</th><th>이메일</th><th>전문과목</th><th>전화</th><th>작업</th></tr>
            </thead>
            <tbody>
                @forelse($doctors as $doctor)
                <tr>
                    <td class="fw-semibold">{{ $doctor->name }} 의사</td>
                    <td>{{ $doctor->email }}</td>
                    <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $doctor->specialization }}</span></td>
                    <td>{{ $doctor->phone ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.doctors.destroy', $doctor) }}" method="POST" class="d-inline" onsubmit="return confirm('삭제할까요?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">등록된 의사가 없습니다</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $doctors->links() }}</div>
@endsection
