<?php
namespace App\Services\Blog;
use App\Models\Blog\Blog;
use App\Enums\Blog\BlogStatus;
use Spatie\QueryBuilder\QueryBuilder;
use App\Services\Upload\UploadService;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\Storage;
use App\Filters\Blog\BlogSearchTranslatableFilter;

class BlogService{
    private $uploadService;
    public function __construct( UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }
    public function all()
    {
        $blogs =QueryBuilder::for(Blog::class)
        ->withTranslation()
        ->with('blogCategory')
        ->allowedFilters([
            AllowedFilter::custom('search', new BlogSearchTranslatableFilter()),
        ])
        ->get();
        return $blogs;
    }
    public function create(array $data)
    {
       $path=null;
       if (isset($data['thumbnail']) && $data['thumbnail'] instanceof UploadedFile)
       {
         $path = $this->uploadService->uploadFile($data['thumbnail'],'blogs');
       }
       $blog =new Blog();
       $blog->is_published = BlogStatus::from($data['isPublished'])->value;
       $blog->thumbnail = $path;
       $blog->category_id = $data['categoryId'];


    if (!empty($data['titleAr'])) {
        $blog->translateOrNew('ar')->title = $data['titleAr'];
        $blog->translateOrNew('ar')->slug = $data['slugAr'];
        $blog->translateOrNew('ar')->content = $data['contentAr'];
        $blog->translateOrNew('ar')->meta_data = $data['metaDataAr'];
    }
    if (!empty($data['titleEn'])) {
        $blog->translateOrNew('en')->title = $data['titleEn'];
        $blog->translateOrNew('en')->slug = $data['slugEn'];
        $blog->translateOrNew('en')->content = $data['contentEn'];
        $blog->translateOrNew('en')->meta_data = $data['metaDataEn'];
    }
    $blog->save();

    return $blog;

    }
    public function edit(int $id)
    {
        return Blog::with('translations')->find($id);
    }
    public function update(array $data): Blog
    {

        $blog = Blog::find($data['blogId']);

        $blog->is_published = BlogStatus::from($data['isPublished'])->value;
        $blog->category_id = $data['categoryId'];

        $path = null;

        if(isset($data['thumbnail']) && $data['thumbnail'] instanceof UploadedFile){
            $path =  $this->uploadService->uploadFile($data['thumbnail'], 'blogs');
        }

        if($path){
            $blog->thumbnail = $path;
        }

        if (!empty($data['titleAr'])) {
            $blog->translateOrNew('ar')->title = $data['titleAr'];
            $blog->translateOrNew('ar')->slug = $data['slugAr'];
            $blog->translateOrNew('ar')->content = $data['contentAr'];
            $blog->translateOrNew('ar')->meta_data = $data['metaDataAr'];
        }

        if (!empty($data['titleEn'])) {
            $blog->translateOrNew('en')->title = $data['titleEn'];
            $blog->translateOrNew('en')->slug = $data['slugEn'];
            $blog->translateOrNew('en')->content = $data['contentEn'];
            $blog->translateOrNew('en')->meta_data = $data['metaDataEn'];
        }

        if($blog->thumbnail){
            Storage::disk('public')->delete($blog->thumbnail);
            $blog->thumbnail = $path;
        }

        $blog->save();

        return $blog;


    }
    public function delete(int $id)
    {

        $blog  = Blog::find($id);

        if($blog->thumbnail){
            Storage::disk('public')->delete($blog->thumbnail);
        }

        $blog->delete();

    }
    public function changeStatus(int $id, bool $isPublished)
    {
        $blog = Blog::find($id);
        $blog->is_published = $isPublished;
        $blog->save();
    }
}
?>
