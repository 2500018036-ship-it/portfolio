<?php

namespace App\Controllers\Admin;

use App\Models\SkillCategoryModel;
use App\Models\SkillModel;

class SkillController extends AdminController
{
    public function index()
    {
        $skillModel = new SkillModel();

        return view('admin/skills/index', [
            'title'      => 'Skills',
            'breadcrumb' => ['Skills'],
            'skills'     => $skillModel
                ->select('skills.*, skill_categories.name AS category_name')
                ->join('skill_categories', 'skill_categories.id = skills.category_id', 'left')
                ->orderBy('skill_categories.sort_order', 'ASC')
                ->orderBy('skills.sort_order', 'ASC')
                ->findAll(),
            'categories' => (new SkillCategoryModel())->orderBy('sort_order', 'ASC')->orderBy('name', 'ASC')->findAll(),
        ]);
    }

    public function new()
    {
        return view('admin/skills/form', [
            'title'      => 'Create Skill',
            'breadcrumb' => ['Skills', 'Create'],
            'skill'      => null,
            'categories' => (new SkillCategoryModel())->orderBy('name', 'ASC')->findAll(),
            'action'     => site_url('admin/skills'),
        ]);
    }

    public function create()
    {
        if (! $this->validate($this->skillRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        (new SkillModel())->skipValidation(true)->insert($this->skillPayload());

        return redirect()->to(site_url('admin/skills'))->with('success', 'Skill berhasil dibuat.');
    }

    public function edit(int $id)
    {
        $skill = (new SkillModel())->find($id);

        if (! $skill) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Skill tidak ditemukan.');
        }

        return view('admin/skills/form', [
            'title'      => 'Edit Skill',
            'breadcrumb' => ['Skills', 'Edit'],
            'skill'      => $skill,
            'categories' => (new SkillCategoryModel())->orderBy('name', 'ASC')->findAll(),
            'action'     => site_url('admin/skills/' . $id),
        ]);
    }

    public function update(int $id)
    {
        if (! $this->validate($this->skillRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        (new SkillModel())->skipValidation(true)->update($id, $this->skillPayload());

        return redirect()->to(site_url('admin/skills'))->with('success', 'Skill berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        (new SkillModel())->delete($id);

        return redirect()->to(site_url('admin/skills'))->with('success', 'Skill berhasil dihapus.');
    }

    public function newCategory()
    {
        return view('admin/skills/category_form', [
            'title'      => 'Create Skill Category',
            'breadcrumb' => ['Skills', 'Create Category'],
            'category'   => null,
            'action'     => site_url('admin/skills/categories'),
        ]);
    }

    public function createCategory()
    {
        if (! $this->validate($this->categoryRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->categoryPayload();

        if ($this->categorySlugExists($data['slug'])) {
            return redirect()->back()->withInput()->with('error', 'Slug kategori sudah digunakan.');
        }

        (new SkillCategoryModel())->skipValidation(true)->insert($data);

        return redirect()->to(site_url('admin/skills'))->with('success', 'Kategori skill berhasil dibuat.');
    }

    public function editCategory(int $id)
    {
        $category = (new SkillCategoryModel())->find($id);

        if (! $category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kategori skill tidak ditemukan.');
        }

        return view('admin/skills/category_form', [
            'title'      => 'Edit Skill Category',
            'breadcrumb' => ['Skills', 'Edit Category'],
            'category'   => $category,
            'action'     => site_url('admin/skills/categories/' . $id),
        ]);
    }

    public function updateCategory(int $id)
    {
        if (! $this->validate($this->categoryRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->categoryPayload();

        if ($this->categorySlugExists($data['slug'], $id)) {
            return redirect()->back()->withInput()->with('error', 'Slug kategori sudah digunakan.');
        }

        (new SkillCategoryModel())->skipValidation(true)->update($id, $data);

        return redirect()->to(site_url('admin/skills'))->with('success', 'Kategori skill berhasil diperbarui.');
    }

    public function deleteCategory(int $id)
    {
        $count = (new SkillModel())->where('category_id', $id)->countAllResults();

        if ($count > 0) {
            return redirect()->to(site_url('admin/skills'))->with('error', 'Kategori masih digunakan oleh skill.');
        }

        (new SkillCategoryModel())->delete($id);

        return redirect()->to(site_url('admin/skills'))->with('success', 'Kategori skill berhasil dihapus.');
    }

    private function skillRules(): array
    {
        return [
            'name'        => 'required|max_length[120]',
            'category_id' => 'permit_empty|is_natural_no_zero',
            'level'       => 'permit_empty|max_length[50]',
            'icon'        => 'permit_empty|max_length[100]',
            'sort_order'  => 'permit_empty|integer',
            'status'      => 'required|in_list[active,inactive]',
        ];
    }

    private function skillPayload(): array
    {
        $categoryId = $this->request->getPost('category_id');

        return [
            'category_id' => $categoryId ? (int) $categoryId : null,
            'name'        => trim((string) $this->request->getPost('name')),
            'description' => trim((string) $this->request->getPost('description')),
            'icon'        => trim((string) $this->request->getPost('icon')),
            'level'       => trim((string) $this->request->getPost('level')),
            'sort_order'  => (int) ($this->request->getPost('sort_order') ?: 0),
            'status'      => (string) $this->request->getPost('status'),
        ];
    }

    private function categoryRules(): array
    {
        return [
            'name'       => 'required|max_length[100]',
            'slug'       => 'permit_empty|max_length[120]',
            'sort_order' => 'permit_empty|integer',
            'status'     => 'required|in_list[active,inactive]',
        ];
    }

    private function categoryPayload(): array
    {
        $name = trim((string) $this->request->getPost('name'));

        return [
            'name'        => $name,
            'slug'        => $this->slugFromInput($name, $this->request->getPost('slug')),
            'description' => trim((string) $this->request->getPost('description')),
            'sort_order'  => (int) ($this->request->getPost('sort_order') ?: 0),
            'status'      => (string) $this->request->getPost('status'),
        ];
    }

    private function categorySlugExists(string $slug, ?int $ignoreId = null): bool
    {
        $builder = (new SkillCategoryModel())->where('slug', $slug);

        if ($ignoreId) {
            $builder->where('id !=', $ignoreId);
        }

        return (bool) $builder->first();
    }
}
