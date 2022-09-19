<?php

use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminCheck;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('app')->middleware([AdminCheck::class])->group(function () {

    Route::post('/create_tag',[AdminController::class, 'addTag']);
    Route::get('/get_tags',[AdminController::class, 'getTag']);
    Route::post('/edit_tag',[AdminController::class, 'editTag']);
    Route::post('/delete_tag',[AdminController::class, 'deleteTag']);
    
    
    
    Route::post('/create_category',[AdminController::class, 'addCategory']);
    Route::get('/get_category',[AdminController::class, 'getCategory']);
    Route::post('/edit_category',[AdminController::class, 'editCategory']);
    Route::post('/delete_category',[AdminController::class, 'deleteCategory']);
    
    Route::post('/uploads',[AdminController::class, 'upload']);
    Route::post('/delete_image',[AdminController::class, 'deleteImage']);
    
    
    Route::post('/create_user',[AdminController::class, 'addAdminUser']);
    Route::get('/get_admins',[AdminController::class, 'getAdmin']);
    Route::post('/edit_admin',[AdminController::class, 'editAdmin']);
    Route::post('/delete_user',[AdminController::class, 'deleteAdmin']);
    
    Route::post('/admin_login',[AdminController::class, 'adminLogin']);


    Route::post('/create_role',[AdminController::class, 'addRole']);
    Route::get('/get_roles',[AdminController::class, 'getRole']);
    Route::post('/edit_role',[AdminController::class, 'editRole']);
    Route::post('/delete_role',[AdminController::class, 'deleteRole']);


    Route::post('/assing_role',[AdminController::class, 'roleAssing']);

    Route::post('/createBlog/upload',[AdminController::class, 'blogImage'])->name('createBlog.upload');

    Route::post('/create-blog',[AdminController::class, 'createBlog']);
    Route::get('/blog-data',[AdminController::class, 'getBlog']);
    Route::post('/delete_blog',[AdminController::class, 'deleteBlog']);
    Route::get('/get_single_blog/{id}',[AdminController::class, 'singleBlog']);
    Route::post('/update-blog/{id}',[AdminController::class, 'updateBlog']);

});

//Route::get('/get-blog',[AdminController::class, 'getBlog']);


//Route::post('app/edit_admin',[AdminController::class, 'editAdmin']);
Route::get('slug',[AdminController::class, 'slug']);


Route::get('/logout',[AdminController::class, 'logout']);
Route::get('/',[AdminController::class, 'index']);
//Route::any('{slug}',[AdminController::class, 'index'])->where('slug','([A-z\d/_.]+)?');
Route::get('/{pathMatch}',[AdminController::class, 'index'])->where('pathMacth',".*");







// Route::get('/', function () {
//     return view('welcome');
// });
//Route::get('/{pathMatch}',function(){return view ('welcome');})->where('pathMacth',".*");
// Route::any('{slug}',function (){
//   return view('welcome');
// });

