<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->dataTable($request);
        }

        $data = [
            'title' => 'Pengumuman Desa',
            'totalPost' => Post::count(),
            'totalPublished' => Post::where('status', 'published')->count(),
            'totalDraft' => Post::where('status', 'draft')->count(),
            'totalWithImage' => Post::whereNotNull('image')->where('image', '!=', '')->count(),
        ];

        return view('backend.post.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Buat Pengumuman Baru',
        ];
        return view('backend.post.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required|string|max:10000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:published,draft',
        ]);

        $input = $request->only(['title', 'content', 'status']);
        $input['slug'] = \Illuminate\Support\Str::slug($request->title) . '-' . time();
        $input['user_id'] = auth()->id();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $file->hashName();
            $file->storeAs('posts', $filename, 'public');
            $input['image'] = 'posts/' . $filename;
        }

        Post::create($input);

        return redirect()->route('post.index')->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function edit(Post $post)
    {
        $data = [
            'title' => 'Edit Pengumuman',
            'post' => $post,
        ];
        return view('backend.post.edit', $data);
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required|string|max:10000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:published,draft',
        ]);

        $input = $request->only(['title', 'content', 'status']);
        // Only update slug if title changed significantly or optionally keep it
        $input['slug'] = \Illuminate\Support\Str::slug($request->title) . '-' . time();

        if ($request->hasFile('image')) {
            // Delete old image
            if ($post->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($post->image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($post->image);
            }

            $file = $request->file('image');
            $filename = $file->hashName();
            $file->storeAs('posts', $filename, 'public');
            $input['image'] = 'posts/' . $filename;
        }

        $post->update($input);

        return redirect()->route('post.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Post $post)
    {
        if ($post->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($post->image)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($post->image);
        }
        $post->delete();

        return redirect()->route('post.index')->with('success', 'Pengumuman berhasil dihapus.');
    }

    private function dataTable(Request $request): JsonResponse
    {
        $columns = [
            0 => 'id',
            2 => 'title',
            4 => 'status',
            5 => 'created_at',
        ];

        $baseQuery = Post::query()->with('user');
        $recordsTotal = (clone $baseQuery)->count();
        $search = trim((string) $request->input('search.value'));

        if ($search !== '') {
            $baseQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();
        $orderColumnIndex = (int) $request->input('order.0.column', 5);
        $orderColumn = $columns[$orderColumnIndex] ?? 'created_at';
        $orderDirection = $request->input('order.0.dir') === 'asc' ? 'asc' : 'desc';
        $length = (int) $request->input('length', 10);
        $start = max((int) $request->input('start', 0), 0);
        $length = $length > 0 ? min($length, 100) : 10;

        $rows = $baseQuery
            ->orderBy($orderColumn, $orderDirection)
            ->skip($start)
            ->take($length)
            ->get();

        $canEdit = $request->user()?->can('post-edit') ?? false;
        $canDestroy = $request->user()?->can('post-destroy') ?? false;

        $data = $rows->map(function (Post $row, int $index) use ($start, $canEdit, $canDestroy) {
            $statusClass = $row->status === 'published' ? 'success' : 'secondary';
            $statusLabel = $row->status === 'published' ? 'Published' : 'Draft';
            $image = $row->image
                ? '<img src="' . asset('storage/' . $row->image) . '" alt="' . e($row->title) . '" class="post-thumb" loading="lazy">'
                : '<div class="post-thumb-placeholder"><i class="fas fa-image"></i></div>';

            $actions = '<div class="action-group">';

            if ($canEdit) {
                $actions .= '<a href="' . route('post.edit', $row->id) . '" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>';
            }

            if ($canDestroy) {
                $actions .= '<form action="' . route('post.destroy', $row->id) . '" method="POST" class="d-inline js-confirm-submit" data-confirm-text="Yakin ingin menghapus pengumuman ' . e($row->title) . '?">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></button>'
                    . '</form>';
            }

            $actions .= '</div>';

            return [
                'no' => $start + $index + 1,
                'image' => $image,
                'title' => '<strong>' . e($row->title) . '</strong><small>' . e(str(strip_tags($row->content))->limit(90)) . '</small>',
                'author' => '<span class="author-pill"><i class="fas fa-user"></i>' . e($row->user?->name ?? '-') . '</span>',
                'status' => '<span class="status-pill status-' . $statusClass . '">' . $statusLabel . '</span>',
                'created_at' => $row->created_at?->format('d-m-Y H:i') ?? '-',
                'aksi' => $actions,
            ];
        });

        return response()->json([
            'draw' => (int) $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }
}
