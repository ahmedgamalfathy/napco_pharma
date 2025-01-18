<?php

namespace App\Services\Career;

use App\Enums\Candidte\CandidateStatus;
use App\Filters\Candidte\CandidateTranslatableFilter;
use App\Filters\Career\FilterCandidate;
use App\Models\Career\Candidate;
use App\Services\Upload\UploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CandidateService{
    private $uploadService;
    public function __construct( UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function all()
    {
        $candidates = QueryBuilder::for(Candidate::class)
        ->allowedFilters([
            AllowedFilter::custom('search', new FilterCandidate()), // Add a custom search filter
        ])
        ->get();

        return $candidates;

    }

    public function create(array $data): Candidate
    {
        $path = null;
        if(isset($data['cv']) && $data['cv'] instanceof UploadedFile){
            $path =  $this->uploadService->uploadFile($data['cv'], 'careers');
        }
        $candidate = Candidate::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'cv' => $path,
            'cover_letter' => $data['coverLetter'],
            'career_id' => $data['careerId'],

        ]);

        return $candidate;



    }

    public function edit(int $id)
    {
        return Candidate::find($id);
    }

//    public function updateCandidate(array $candidateData): Candidate
//     {

//         $blogCategory = Candidate::find($candidateData['blogCategoryId']);

//         $blogCategory->update([
//             'title' => $candidateData['title'],
//             'description' => $candidateData['description'],
//             'slug' => $candidateData['slug'],
//             'is_active' => CandidateStatus::from($candidateData['is_active'])->value,
//         ]);

//         $blogCategory->country()->attach($candidateData['countryIds']);

//         return $blogCategory;


//     }


    public function delete(int $id)
    {

        $candidate = Candidate::find($id);

        if($candidate->cv){
            Storage::delete($candidate->cv);
        }

        $candidate->delete();
    }

}
