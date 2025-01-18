<?php
namespace App\Services\Faq;
use App\Enums\Faq\FaqStatus;
use App\Models\Faq\Faq;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\Faq\FaqSearchTranslatableFilter;

class FaqService{
    public function all()
    {
        $faqs = QueryBuilder::for(Faq::class)
            ->withTranslation() // Fetch translations if applicable
            ->allowedFilters([
                AllowedFilter::custom('search', new FaqSearchTranslatableFilter() ), // Add a custom search filter
            ])->get();

        return $faqs;

    }
    public function create(array $data)
    {
        $faq=new Faq();
        $faq->order=$data['order'];
        $faq->is_published =FaqStatus::from($data['isPublished'])->value;
        if(!empty($data['questionAr'])){
            $faq->translateOrNew('ar')->question = $data['questionAr'];
            $faq->translateOrNew('ar')->answer = $data['answerAr'];
        }
        if(!empty($data['questionEn'])){
            $faq->translateOrNew('en')->question = $data['questionEn'];
            $faq->translateOrNew('en')->answer = $data['answerEn'];
        }
        $faq->save();
        return $faq;
    }
    public function edit(int $id)
    {
        return Faq::with('translations')->find($id);
    }
    public function update(array $data)
    {
      $faq =Faq::find($data['faqId']);
      $faq->order =$data['order'];
      $faq->is_published =FaqStatus::from($data['isPublished'])->value;
      if(!empty($data['questionAr'])){
        $faq->translateOrNew('ar')->question = $data['questionAr'];
        $faq->translateOrNew('ar')->answer = $data['answerAr'];
    }
    if(!empty($data['questionEn'])){
        $faq->translateOrNew('en')->question = $data['questionEn'];
        $faq->translateOrNew('en')->answer = $data['answerEn'];
    }

    $faq->save();

    return $faq;
    }
    public function delete(int $id)
    {
      $faq =Faq::find($id);
      $faq->delete();
    }
    public function changeStatus(int $id,bool $isPublished)
    {
        $faq = Faq::find($id);
        $faq->is_published = $isPublished;
        $faq->save();
    }
}

?>
