<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Blogcategory;
use App\Models\Blogtag;
use App\Models\Category;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\TryCatch;

class AdminController extends Controller
{

  public function index(Request $request){
    //return Auth::check();
    if(!Auth::check() && $request->path() != 'login'){
      return redirect("/login");
    }
    if(!Auth::check() && $request->path() == 'login'){
      return view('welcome');
    }

    $user = Auth::user();
    if($user->userType == 'User'){
      return redirect("/login");
    }
    if($request->path() == 'login'){
      return redirect("/");
    }

    return $this->checkPermission($user,$request);
      // return view('notFound');
      // return $request->path();
      // return view('welcome');
  }

  public function checkPermission($user,$request){

    $permission = json_decode($user->role->permission);

    $hasPermission = false;

    if(!$permission){
      return view('welcome');
    }

    foreach($permission as $p){
      if($p->name  == $request->path()){
        if($p->read){
          $hasPermission = true;
        }
      }
    }
    if($hasPermission){
      return view('welcome');
    }
    if (Auth::check()) {
      return view('welcome');
    }

    return view('notFound');
    // echo $permission[0]->name;
    // echo $request->path();
  }

  public function logout(){
    Auth::logout();
    return redirect("/login");
  }

 //Tag 
    public function addTag (Request $request){

      $this->validate($request,[
        'tagName' => 'required']);

      return Tag::create([
         'tagName' => $request->tagName
      ]);
    }
    
    public function getTag (){
      return Tag::orderBy('id', 'desc')->get();
    }

    public function editTag (Request $request){

      $this->validate($request,[
        'tagName' => 'required',
        'id' => 'required'
      ]);

      return Tag:: where('id',$request->id)->update([

        'tagName' => $request->tagName

      ]);
    }

    public function deleteTag (Request $request){
      return Tag:: where('id',$request->id)->delete();
    }





    
//Category 
    public function upload (Request $request){  
      $this->validate($request,[
        // 'categoryName' => 'required',
        'file' => 'required|mimes:jpg,png,jpeg'
      ]);

       $picName = time().'.'.$request->file->extension();
       $request->file->move(public_path('uploads'),$picName);
       return $picName;
      //  return response()->json([
      //   'msg' => "okkkkk"
      //  ]);
    }

    public function deleteImage (Request $request){  
      $fileName = $request-> imageName;
      $this->deleteImageServer($fileName,False);
      return 'done';
    }

    public function deleteImageServer ($fileName, $hasFullPath = false){  
      if(!$hasFullPath) {
      $filePath = public_path() .'/uploads/'. $fileName;
      }
      if(file_exists($filePath)){
        @unlink($filePath);
      }
      return;
    }


    public function addCategory (Request $request){

      // return response() -> json([
      //   'msg' => "okkkkkk"
      // ]);

      $this->validate($request,[
        'categoryName' => 'required',
        'iconImage' => 'required'
      ]);

      return Category::create([
         'categoryName' => $request->categoryName,
         'iconImage' => $request->iconImage
      ]);
    }

    public function getCategory(){
      return Category::orderBy('id', 'desc')->get();
    }

    public function editCategory(Request $request){
      $this->validate($request,[
        'categoryName' => 'required',
        'iconImage' => 'required'
      ]);

      return Category:: where('id',$request->id)->update([

        'categoryName' => $request->categoryName,
        'iconImage' => $request->iconImage

      ]);
    }

  public function deleteCategory(Request $request){

    $this->deleteImageServer($request->iconImage);
    
    return Category::where('id', $request->id)->delete();
  }







 //Admin 
  public function addAdminUser (Request $request){

    $this->validate($request,[
      'fullName' => 'required',
      'email' => 'bail|required|email|unique:users',
      'password' => 'bail|required|min:6',
      'role_id' => 'required',
    ]);

    $password = bcrypt($request->password);

    $user = User::create([
      'fullName' => $request->fullName,
      'email' => $request->email,
      'password' => $password,
      'role_id' => $request->role_id,
    ]);
    return $user;
  }
  
  public function getAdmin (){
    return User::all();
  }

  public function editAdmin (Request $request){
    
   
  
   

    $this->validate($request,[
      'fullName' => 'required',
      'email' => "bail|required|email|unique:users,email,$request->id",
      'password' => 'min:6',
      'oldPassword' => 'min:6|required',
      'role_id' => 'required',
    ]);

    // $pass = User:: where('id', $request->id)->get();

    // if($pass->password != bcrypt($request->oldPassword)){
    //       return response()->json ([
    //         'msg' => 'Old password dose not match...'
    //       ],401);
    //     }

    $data = [
      'fullName' => $request->fullName,
      'email' => $request->email,
      'role_id' => $request->role_id,
    ];
    if($request->password){
      $password = bcrypt($request->password);
      $data['password'] = $password;
    }
    

    return User:: where('id', $request->id)->update($data);
     
  }

  public function deleteAdmin (Request $request){
    return User:: where('id',$request->id)->delete();
  }


//Login
 public function adminLogin(Request $request){
  $this->validate($request,[
    'email' => 'bail|required|email',
    'password' => 'bail|required|min:6'
  ]);

  if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
    $user = Auth::user();
    
    // return $user->role;
    // Log::info($user->role);



    if($user->role->isAdmin == 0){
      Auth::logout();
      return response()->json ([
        'msg' => 'Incorrent Log in Info...'
      ],401);
    }

    return response()->json ([
      'msg' => 'You are logged in..',
      'user' => $user
    ]);

  }else {
    return response()->json ([
      'msg' => 'Incorrent Log in Info.'
    ],401);
  }

 }


 //Role 
 public function addRole (Request $request){

  $this->validate($request,[
    'roleName' => 'required']);

  return Role::create([
     'roleName' => $request->roleName
  ]);
}

public function getRole (){
  return Role::all();
}

public function editRole (Request $request){
  $this->validate($request,[
    'roleName' => 'required',
    'id' => 'required'
  ]);
  return Role:: where('id',$request->id)->update([
    'roleName' => $request->roleName
  ]);
}

public function deleteRole (Request $request){
  return Role:: where('id',$request->id)->delete();
}


 //Role Assigning
 public function roleAssing (Request $request){

  $this->validate($request,[
    'permission' => 'required',
    'id' => 'required'
  ]);

  return Role::where('id', $request->id)->update([
    'permission' => $request->permission
  ]);
}




//Blog Image
public function blogImage(Request $request){
//   $this->validate($request,[
//     // 'categoryName' => 'required',
//     'file' => 'required|mimes:jpg,png,jpeg'
//   ]);

//    $picName = time().'.'.$request->file->extension();
//    $request->file->move(public_path('uploads'),$picName);
//    //return $picName;
//   //  return response()->json([
//   //   'success' => 1,
    
//   //     'url' => "http://localhost:8000/uploads/$picName"
    
//   //  ]);
//   //  return $picName;
// $returnArray = array();
// $returnArray["uploaded"] = true;
// $returnArray["url"] = "http://localhost:8000/uploads/".$picName;
// header('Content-type: application/json');
// $this->sendJson($returnArray);
// $this->terminate();
if($request->hasFile('upload')) {
  $originalName = $request->file('upload')->getClientOriginalName();
  $fileName = pathinfo($originalName,PATHINFO_FILENAME);
  $extension = $request->file('upload')->getClientOriginalExtension();
  $fileName = $fileName.'_'.time().'.'.$extension;

  $request->file('upload')->move(public_path('uploads'),$fileName);

  $url = asset('uploads/' . $fileName);

  return response()->json([
    'fileName' => $fileName,
    'uploaded' => 1,
    'url' => $url
  ]);
}

}

//Blog item

// public function slug(){

//   return Blog::create([
//     'title' => 'post one cng',
//     'post' => 'some ppost',
//     'post_excerpt' => 'some ppost',
//     'user_id' => 1,
//     'featuredImage' => 'some ppost',
//     'metaDescription' => 'some ppost',
//     'views' => 1,
//     //'slug' => 'some ppost',
//   ]);

// }

public function createBlog(Request $request){

  $this->validate($request,[
    'title' => 'required',
    'featuredImage' => 'required',
    'post' => 'required',
    'post_excerpt' => 'required',
    'metaDescription' => 'required',
    'jsonData' => 'required',
    'category_id' => 'required',
    'tag_id' => 'required'
  ]);

  $category_id = $request-> category_id;
  $tag_id = $request-> tag_id;
  
  $blogCategories = [];
  $blogTags = [];
  DB::beginTransaction();

  try {
    //code..
    $blog =  Blog::create([
      'title' => $request->title,
      'slug' => $request->title,
      'post' => $request->post,
      'post_excerpt' => $request->post_excerpt,
      'user_id' => Auth::user()->id,
      'featuredImage' => $request->featuredImage,
      'metaDescription' => $request->metaDescription,
      'jsonData' => $request->jsonData,
      'views' => 1,
    ]);
  
    foreach ($category_id as $c){
      array_push($blogCategories, ['category_id' => $c, 'blog_id' => $blog->id]);
    }
    Blogcategory::insert($blogCategories);
  
    foreach ($tag_id as $t){
      array_push($blogTags, ['tag_id' => $t, 'blog_id' => $blog->id]);
    }
    Blogtag::insert($blogTags);
    DB::commit();
    return 'done';

  } catch (\Throwable $th) {
    //throw $th;
    DB::rollBack();
    return 'not done';
  }
  
}

public function getBlog(){
  return Blog::with(['tag','cat'])->orderBy('id','desc')->get();
}

public function deleteBlog(Request $request){
  Blog::with(['tag','cat'])->where('id', $request->id)->delete();
  Blogcategory::where('blog_id', $request->id)->delete();
  Blogtag::where('blog_id', $request->id)->delete();
  return 'done';
}

public function singleBlog(Request $request,$id){
  return Blog::with(['tag','cat'])->where('id', $id)->first();
}

public function updateBlog(Request $request, $id){

  $this->validate($request,[
    'title' => 'required',
    'featuredImage' => 'required',
    'post' => 'required',
    'post_excerpt' => 'required',
    'metaDescription' => 'required',
    'jsonData' => 'required',
    'category_id' => 'required',
    'tag_id' => 'required'
  ]);

  $category_id = $request-> category_id;
  $tag_id = $request-> tag_id;
  
  $blogCategories = [];
  $blogTags = [];
  DB::beginTransaction();

  try {
    //code..
    $blog =  Blog::where('id', $id)->update([
      'title' => $request->title,
      'slug' => $request->title,
      'post' => $request->post,
      'post_excerpt' => $request->post_excerpt,
      'user_id' => Auth::user()->id,
      'featuredImage' => $request->featuredImage,
      'metaDescription' => $request->metaDescription,
      'jsonData' => $request->jsonData,
      'views' => 1,
    ]);
  
    foreach ($category_id as $c){
      array_push($blogCategories, ['category_id' => $c, 'blog_id' => $id]);
    }
    Blogcategory::where('blog_id', $id)->delete();
    Blogcategory::insert($blogCategories);
  


    foreach ($tag_id as $t){
      array_push($blogTags, ['tag_id' => $t, 'blog_id' => $id]);
    }
    Blogtag::where('blog_id', $id)->delete();
    Blogtag::insert($blogTags);

    DB::commit();
    return 'done';

  } catch (\Throwable $th) {
    //throw $th;
    DB::rollBack();
    return 'not done';
  }
  
}






}
