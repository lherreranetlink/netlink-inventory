<?php

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


Auth::routes();

Route::get('/user-deleted', 'HomeController@userDeleted');

Route::get('/upload', 'UploadController@uploadForm');
Route::post('/upload', 'UploadController@uploadSubmit');

Route::resource('/excel-upload-spreadsheet', 'ExcelController');

Route::match(['get', 'post'],'/tag-user/login', [
    'uses'=>'TagController@tagLogin'
]);

Route::group(['middleware' => ['tag-auth'],'prefix' => 'tag-user'], function () {
    Route::get('/home', 'TagController@index');
    Route::get('/logout', 'TagController@logout');

    //AJAX JSON Response URL................................................
    Route::get('/manufacturers-json', 'Admin\ModelController@getManufacturers');
    Route::get('/categories-json', 'Admin\ModelController@getCategories');
    Route::post('/subcategories-by-category-json', 'Admin\ModelController@getSubCategoriesbyCat');
    Route::post('/childcategories-by-subcategory-json', 'Admin\ModelController@getChildCategoriesbyCat');

    //AJAX JSON Location Response URL................................................
    //Route::get('/manufacturers-json', 'Admin\ModelController@getManufacturers');
    Route::get('/locations-json', 'Admin\ModelController@getLocations');
    Route::get('/customers-json', 'Admin\ModelController@getCustomers');
    Route::post('/sublocations-by-location-json', 'Admin\ModelController@getSubLocationsbyCat');
    Route::post('/childlocations-by-sublocation-json', 'Admin\ModelController@getChildLocationsbyCat');
    Route::post('/models/get-models-by-mfg-ajax', 'Admin\ModelController@getModelByMfg');
    
    Route::post('/get-checkin-barcode-ajax', 'HomeController@getCheckinByBarcode');
    
    //Route::post('/checkouts/add-bulk-process-ajax', 'TagController@processBulkCheckoutAdd');
    //Route::post('/checkouts/change-bulk-location-process-ajax', 'TagController@bulkLocationChange');
    
    
    //CHeck in...................................
    //Route::post('/checkins/add-process-ajax', 'HomeController@processCheckinAdd');
    Route::post('/checkins/add-bulk-process-ajax', 'HomeController@processBulkCheckinAdd');
    //Route::get('/checkins', 'HomeController@checkinHistory');
    
    //check out.................................
    //Route::post('/checkouts/add-process-ajax', 'HomeController@processCheckoutAdd');
    //Route::post('/checkouts/change-location-process-ajax', 'HomeController@locationChange');
    Route::post('/checkouts/add-bulk-process-ajax', 'HomeController@processBulkCheckoutAdd');
    Route::post('/checkouts/change-bulk-location-process-ajax', 'HomeController@bulkLocationChange');
    //Route::get('/checkouts', 'HomeController@checkoutHistory');
    
    //Route::get('/location-changes', 'HomeController@locationChangeHistory');


    Route::post('/get-checkin-barcode-ajax', 'HomeController@getCheckinByBarcode');
    
    
    //Bulk...........................
    Route::get('/bulk-checkin', 'TagController@bulkCheckin');
    Route::get('/bulk-checkout', 'TagController@bulkCheckout');
    
});
















Route::group(['middleware' => ['auth','active-user']], function () {
    Route::get('/home', 'HomeController@index');
    Route::get('/', 'HomeController@index');
    Route::get('/search', 'HomeController@search');
    Route::get('/access-denied', 'HomeController@accessDenied');
    Route::get('/check-in/{barcode?}', 'HomeController@checkIn');
    Route::get('/check-in-back', 'HomeController@checkinBack');
    Route::post('/checkin-back-ajax-process', 'HomeController@checkinBackProcess');
    
    Route::get('/check-out', 'HomeController@checkOut');
    
    //AJAX JSON Response URL................................................
    Route::get('/manufacturers-json', 'Admin\ModelController@getManufacturers');
    Route::get('/categories-json', 'Admin\ModelController@getCategories');
    Route::post('/subcategories-by-category-json', 'Admin\ModelController@getSubCategoriesbyCat');
    Route::post('/childcategories-by-subcategory-json', 'Admin\ModelController@getChildCategoriesbyCat');
    
    
    //AJAX JSON Location Response URL................................................
    //Route::get('/manufacturers-json', 'Admin\ModelController@getManufacturers');
    Route::get('/locations-json', 'Admin\ModelController@getLocations');
    Route::get('/customers-json', 'Admin\ModelController@getCustomers');
    Route::post('/sublocations-by-location-json', 'Admin\ModelController@getSubLocationsbyCat');
    Route::post('/childlocations-by-sublocation-json', 'Admin\ModelController@getChildLocationsbyCat');
    Route::post('/models/get-models-by-mfg-ajax', 'Admin\ModelController@getModelByMfg');
    
    //CHeck in...................................
    Route::post('/checkins/add-process-ajax', 'HomeController@processCheckinAdd');
    Route::post('/checkins/add-bulk-process-ajax', 'HomeController@processBulkCheckinAdd');
    Route::get('/checkins', 'HomeController@checkinHistory');
    
    //check out.................................
    Route::post('/checkouts/add-process-ajax', 'HomeController@processCheckoutAdd');
    Route::post('/checkouts/change-location-process-ajax', 'HomeController@locationChange');
    Route::post('/checkouts/add-bulk-process-ajax', 'HomeController@processBulkCheckoutAdd');
    Route::post('/checkouts/change-bulk-location-process-ajax', 'HomeController@bulkLocationChange');
    Route::get('/checkouts', 'HomeController@checkoutHistory');
    
    Route::get('/location-changes', 'HomeController@locationChangeHistory');
    
    
    Route::post('/get-checkin-barcode-ajax', 'HomeController@getCheckinByBarcode');
    
    
    //Bulk...........................
    Route::get('/bulk-checkin', 'HomeController@bulkCheckin');
    Route::get('/bulk-checkout', 'HomeController@bulkCheckout');
    
    //Profile...............................................................
    Route::get('/profile', 'ProfileController@profile');
    Route::post('/profile/change-password', 'ProfileController@changePassword');
    Route::post('/profile/change_pic_ajax', 'ProfileController@changeProfilePic');
    Route::post('/profile/checkins', 'ProfileController@checkins');
    Route::post('/profile/checkouts', 'ProfileController@checkouts');
    Route::post('/profile/location-change', 'ProfileController@locationChange');
    
    //Stock....................................................................
    Route::get('/stock', 'HomeController@stock');
    Route::post('/stock/search-ajax', 'HomeController@searchStock');
    
    //Reports and summary dashboard...........................................
    //All JSON Data...........................................................
    Route::get('/items-by-manufacturer-json', 'ReportController@getItemsByManufacturers');
    Route::get('/items-by-category-json', 'ReportController@getItemsByCategories');
    
});

















Route::group(['middleware' => ['auth','admin','active-user']], function () {
    Route::get('/admin', 'Admin\AdminController@index');
    
    //Manufacturer..............................................................
    Route::match(['get', 'post'],'/manufacturers', [
        'uses'=>'Admin\AdminController@manufacturers',
        'as'=>'/manufacturers'
    ]);
    Route::match(['get', 'post'],'/manufacturers/edit', [
            'uses'=>'Admin\AdminController@manufacturersEdit',
            'as'=>'/manufacturers/edit'
        ]);
    Route::post('/delete-manufacturer', 'Admin\AdminController@deleteManufacturer');
    
    
    //Customer..............................................................
    Route::match(['get', 'post'],'/customers', [
        'uses'=>'Admin\AdminController@customers',
        'as'=>'/customers'
    ]);
    Route::match(['get', 'post'],'/customers/edit', [
            'uses'=>'Admin\AdminController@customersEdit',
            'as'=>'/customers/edit'
        ]);
    Route::post('/delete-customer', 'Admin\AdminController@deleteCustomer');
    
    //Category..............................................................
    Route::match(['get', 'post'],'/categories', [
        'uses'=>'Admin\AdminController@categories',
        'as'=>'/categories'
    ]);
    Route::match(['get', 'post'],'/categories/edit', [
            'uses'=>'Admin\AdminController@categoriesEdit',
            'as'=>'/categories/edit'
        ]);
    Route::post('/delete-category', 'Admin\AdminController@deleteCategory');
    //Route::post('/delete-category', 'Admin\AdminController@deleteCategory');
    
    
    
    //Location..............................................................
    Route::match(['get', 'post'],'/locations', [
        'uses'=>'Admin\AdminController@locations',
        'as'=>'/locations'
    ]);
    Route::match(['get', 'post'],'/locations/edit', [
            'uses'=>'Admin\AdminController@locationsEdit',
            'as'=>'/locations/edit'
        ]);
    Route::post('/delete-location', 'Admin\AdminController@deleteLocation');
    
    //Users..............................................................
    Route::match(['get', 'post'],'/users', [
        'uses'=>'Admin\AdminController@users',
        'as'=>'/users'
    ]);
    Route::post('/delete-user', 'Admin\AdminController@deleteUser');
    Route::post('/user/edit', 'Admin\AdminController@editUser');
    
    
    
    
    //SubCategory..............................................................
    Route::match(['get', 'post'],'/subcategories', [
        'uses'=>'Admin\AdminController@subcategories',
        'as'=>'/subcategories'
    ]);
    Route::match(['get', 'post'],'/subcategories/edit', [
            'uses'=>'Admin\AdminController@subcategoriesEdit',
            'as'=>'/subcategories/edit'
        ]);
    Route::post('/delete-subcategory', 'Admin\AdminController@deleteSubCategory');
    Route::match(['get', 'post'],'categories/subcategories/{category_id?}', [
        'uses'=>'Admin\AdminController@subcategoriesByCat',
        'as'=>'categories/subcategories/{category_id?}'
    ]);
    
    
    
    //ChildCategory..............................................................
    Route::match(['get', 'post'],'/childcategories', [
        'uses'=>'Admin\AdminController@childcategories',
        'as'=>'/childcategories'
    ]);
    Route::match(['get', 'post'],'/childcategories/edit', [
            'uses'=>'Admin\AdminController@childcategoriesEdit',
            'as'=>'/childcategories/edit'
        ]);
    Route::post('/delete-childcategory', 'Admin\AdminController@deleteChildCategory');
    Route::match(['get', 'post'],'categories/subcategories/childcategories/{subcategory_id?}', [
        'uses'=>'Admin\AdminController@childcategoriesBySub',
        'as'=>'categories/subcategories/childcategories/{subcategory_id?}'
    ]); 
    Route::post('/childcategory-by-sub', 'Admin\AdminController@childcategoriesBySub');
    
    //SubLocation..............................................................
    Route::match(['get', 'post'],'/sublocations', [
        'uses'=>'Admin\AdminController@sublocations',
        'as'=>'/sublocations'
    ]);
    Route::match(['get', 'post'],'/sublocations/edit', [
            'uses'=>'Admin\AdminController@sublocationsEdit',
            'as'=>'/sublocations/edit'
        ]);
    Route::post('/delete-sublocation', 'Admin\AdminController@deleteSubLocation');
    Route::match(['get', 'post'],'/locations/sublocations/{location_id?}', [
        'uses'=>'Admin\AdminController@sublocationsByLoc',
        'as'=>'/locations/sublocations/{location_id?}'
    ]);
    
    
    //ChildLOcation..............................................................
    Route::match(['get', 'post'],'/childlocations', [
        'uses'=>'Admin\AdminController@childlocations',
        'as'=>'/childlocations'
    ]);
    Route::match(['get', 'post'],'/childlocations/edit', [
            'uses'=>'Admin\AdminController@childlocationsEdit',
            'as'=>'/childlocations/edit'
    ]);
    Route::post('/delete-childlocation', 'Admin\AdminController@deleteChildLocation');
    Route::match(['get', 'post'],'/locations/sublocations/childlocations/{sublocation_id?}', [
        'uses'=>'Admin\AdminController@childlocationsBySub',
        'as'=>'/locations/sublocations/childlocations/{sublocation_id?}'
    ]); 
    Route::post('/childlocation-by-sub', 'Admin\AdminController@childlocationsBySub');
    
    
    
    //Models..............................................................
    Route::match(['get', 'post'],'/models', [
        'uses'=>'Admin\ModelController@models',
        'as'=>'/models'
    ]);
    Route::get('//models/add', 'Admin\ModelController@modelAdd');
    Route::post('/delete-model', 'Admin\ModelController@deleteModel');
    Route::post('/models/add-process-ajax', 'Admin\ModelController@processModelAdd');
    Route::post('/models/edit-process-ajax', 'Admin\ModelController@processModelEdit');
    
    Route::post('/models/edit', 'Admin\ModelController@modelEdit');
    Route::post('/get-model-by-id-ajax', 'Admin\ModelController@getModelByIDAjax');
    
    //.....................................................................
    
    
    
    Route::post('/customer/add-ajax', 'Admin\AdminController@addCustomerAjax');
    
    
    
    
    
    
    
    

});
