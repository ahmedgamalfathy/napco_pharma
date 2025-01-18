<?php
namespace App\Services\User;
use App\Models\User;
use App\Enums\User\UserStatus;
use App\Filters\User\FilterUser;
use Spatie\Permission\Models\Role;
use App\Filters\Role\FilterUserRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Enum;
use Spatie\QueryBuilder\QueryBuilder;
use App\Services\Upload\UploadService;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserService
{
     protected $uploadService;

     public function __construct( UploadService $uploadService)
     {
         $this->uploadService = $uploadService;
     }
   public function all()
   {
     $users=QueryBuilder::for(User::class)
     ->AllowedFilters([
        AllowedFilter::custom('search', new FilterUser()),
        AllowedFilter::custom('role', new FilterUserRole()),
        AllowedFilter::exact('status'),
     ])
     ->get();
     return $users;
   }
   public function create(array $data)
   {
     if(isset($data['avatar']) && $data['avatar'] instanceof UploadedFile){
        $avatarPath= $this->uploadService->uploadFile($data['avatar'],'avatars');
     }
     $user =User::create([
        'name' => $data['name'],
        'username' => $data['username'],
        'email' => $data['email'],
        'phone' => $data['phone'],
        'address'=>$data['address'],
        'password' =>Hash::make($data['password']),
        'status' =>UserStatus::from($data['status'])->value,
        'avatar' => $avatarPath
     ]);
     $role = Role::find($data['roleId']);
     $user->assignRole($role->id);
     return $user;

   }
   public function edit(int $id)
   {
      return User::where('id',$id)->with('roles')->first();
   }
   public function update(array $data)
   {
    $avatarPath = null;

    if(isset($data['avatar']) && $data['avatar'] instanceof UploadedFile){
        $avatarPath =  $this->uploadService->uploadFile($data['avatar'],'avatars');
    }
      $user =User::where('id',$data['userId'])->first();
      $user->name =$data['name'];
      $user->username = $data['username'];
      $user->phone = $data['phone'];
      $user->address = $data['address'];
      $user->email =$data['email'];
      if($data['password']){
          $user->password =Hash::make($data['password']);
      }
      if($avatarPath){
        Storage::disk('public')->delete($user->avatar);
        $user->avatar = $avatarPath;
    }
      $user->status = UserStatus::from($data['status'])->value;
      $user->save();
      $role = Role::find($data['roleId']);
      $user->syncRoles($role->id);
      return $user;
   }
   public function delete(int $id)
   {
     $user= User::findOrFail($id);
     if($user->avatar){
        Storage::disk('public')->delete($user->avatar);
    }
    $user->delete();
   }
   public function changeUserStatus(int $id, int $status)
   {
       return User::where('id', $id)->update(['status' => UserStatus::from($status)->value]);
   }

}
?>
