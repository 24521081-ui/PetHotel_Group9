<div class="branch-list" data-branch-list>
    @forelse ($branches as $branch)
        <x-branch-card :branch="$branch" />
    @empty
        <div class="branch-empty">
            <i class="fa-regular fa-map"></i>
            <h3>Không tìm thấy chi nhánh phù hợp</h3>
            <p>Hãy thử đổi từ khóa hoặc chọn khu vực khác.</p>
        </div>
    @endforelse
</div>
