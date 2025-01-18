<?php

namespace App\Services\Blog;

use App\Enums\Blog\BlogCategoryStatus;
use App\Filters\Blog\BlogCategoryTranslatableFilter;
use App\Models\Blog\BlogCategory;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BlogCategoryService{

    private $blogBlogCategory;
    public function __construct(BlogCategory $blogBlogCategory)
    {
        $this->blogBlogCategory = $blogBlogCategory;
    }

    public function all()
    {
        $blogCategories = QueryBuilder::for(BlogCategory::class)
        ->withTranslation() // Fetch translations if applicable
        ->allowedFilters([
            AllowedFilter::custom('search', new BlogCategoryTranslatableFilter()), // Add a custom search filter
        ])
        ->get();

        return $blogCategories;

    }

    public function create(array $data): BlogCategory
    {


        $blogCategory = new BlogCategory();

        $blogCategory->is_active = BlogCategoryStatus::from($data['isActive'])->value;

        if (!empty($data['nameAr'])) {
            $blogCategory->translateOrNew('ar')->name = $data['nameAr'];
            $blogCategory->translateOrNew('ar')->slug = $data['slugAr'];
        }

        if (!empty($data['nameEn'])) {
            $blogCategory->translateOrNew('en')->name = $data['nameEn'];
            $blogCategory->translateOrNew('en')->slug = $data['slugEn'];
        }

        $blogCategory->save();

        return $blogCategory;

    }

    public function edit(int $id)
    {
        return BlogCategory::with('translations')->find($id);
    }

    public function update(array $data): BlogCategory
    {

        $blogCategory = BlogCategory::find($data['blogCategoryId']);

        $blogCategory->is_active = BlogCategoryStatus::from($data['isActive'])->value;

        if (!empty($data['nameAr'])) {
            $blogCategory->translateOrNew('ar')->name = $data['nameAr'];
            $blogCategory->translateOrNew('ar')->slug = $data['slugAr'];
        }

        if (!empty($data['nameEn'])) {
            $blogCategory->translateOrNew('en')->name = $data['nameEn'];
            $blogCategory->translateOrNew('en')->slug = $data['slugEn'];
        }


        return $blogCategory;


    }


    public function delete(int $id)
    {

        return BlogCategory::find($id)->delete();

    }

}
