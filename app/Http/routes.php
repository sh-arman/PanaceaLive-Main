<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\App;

/*
|--------------------------------------------------------------------------
| Company Admin Dashboard - codes.panacea.live
|--------------------------------------------------------------------------
*/
Route::domain('codes.panacea.live')->group(function () {
    
    // Public login routes
    // Route::get('/', 'CodeGenerationPanelNewController@showLogin')->name('generationPanel.login');
    Route::post('/verifyLogin', 'CodeGenerationPanelNewController@processLogin');
    Route::get('/verify', 'CodeGenerationPanelNewController@showVerify')->name('generationPanel.verify');
    Route::post('/confirmLogin', 'CodeGenerationPanelNewController@processVerify');
    Route::post('/resend', 'CodeGenerationPanelNewController@resendLogin');
    
    // Protected routes requiring authentication
    Route::middleware(['checksession', 'auth.codegeneration'])->group(function () {
        Route::post('generationPanel/medicines', 'CodeGenerationPanelNewController@showMedicines')->name('generation.code.medicines');
        Route::post('generationPanel/medicineType', 'CodeGenerationPanelNewController@showMedicineType')->name('generation.code.medicineType');
        Route::post('generationPanel/medicineDosage', 'CodeGenerationPanelNewController@showMedicineDosage')->name('generation.code.medicineDosage');
        Route::post('generationPanel/loadMore', 'CodeGenerationPanelNewController@showMoreData')->name('generation.code.loadMore');
        Route::post('generationPanel/loadLog', 'CodeGenerationPanelNewController@showMoreLog')->name('generation.code.loadLog');
        Route::post('generationPanel/searchActivityLog', 'CodeGenerationPanelNewController@searchActivityLog')->name('generation.code.searchActivityLog');
        Route::get('code/generate', 'CodeGenerationPanelNewController@showForm')->name('generationPanel.code.order');
        Route::post('code/generate', 'CodeGenerationPanelNewController@orderCode');
        Route::post('code/confirm', 'CodeGenerationPanelNewController@ConfrimArman')->name('generationPanel.code.confirm');
        Route::post('order/{order}/cancel', 'CodeGenerationPanelNewController@cancelOrder')->name('generationPanel.order.cancel');
        Route::get('code/download/{id}', 'CodeGenerationPanelNewController@downloadGeneratedCsv')->name('generationPanel.code.download');
        Route::post('code/orderBack', 'CodeGenerationPanelNewController@orderBackForConfirm')->name('generationPanel.code.orderBack');
        Route::get('logout', 'CodeGenerationPanelNewController@logout')->name('generationPanel.logout');
        Route::get('order', 'CodeGenerationPanelNewController@indexOrder');
        Route::get('log', 'CodeGenerationPanelNewController@showLog')->name('generationPanel.log');
        Route::get('templates', 'CodeGenerationPanelNewController@showTemplate')->name('generationPanel.template');
        Route::post('addtemplate', 'CodeGenerationPanelNewController@addTemplate');
        Route::get('confirmAddTemplate', 'CodeGenerationPanelNewController@confirmAddTemplate');
        Route::get('deleteTemplate/{id}', 'CodeGenerationPanelNewController@deleteTemplate');
        Route::get('choosemenu', 'CodeGenerationPanelNewController@chooseMenu')->name('generationPanel.choosemenu');
        Route::get('choose/{company}', 'CodeGenerationPanelNewController@chooseCompany')->name('generationPanel.choose');
    });
});

/*
|--------------------------------------------------------------------------
| Mobile Site - m.panacea.live
|--------------------------------------------------------------------------
*/
// Route::domain('m.panacea.live')->group(function () {
//     Route::get('mups', 'livecheckproControllerMups@page')->name('mups');
    // Route::get('/', function () {
    //     return Redirect::to('https://renata.panacea.live/');
    // });
// });

// Route::get('/', function () {
//     return Redirect::to('https://renata.panacea.live/');
// });





/*
|--------------------------------------------------------------------------
| Panalytics Dashboard - analytics.panacea.live
|--------------------------------------------------------------------------
*/
Route::domain('analytics.panacea.live')->group(function () {
    Route::get('/', 'PanalyticsController@showLanding')->name('panalytics_home');
    Route::get('home', 'PanalyticsController@index')->name('panalytics_view');
    Route::post('panalytics_login', 'PanalyticsController@login');
    Route::post('panalytics_registration', 'PanalyticsController@registration');
    Route::get('panalytics_activation/{id}', 'PanalyticsController@activation');
    Route::post('panalytics_activation/{id}', 'PanalyticsController@processActivation');
    Route::post('panalytics_password/forgot', 'PanalyticsController@forgotPassword');
    Route::post('panalytics_password/reset', 'PanalyticsController@resetPassword');
    Route::post('stats', 'PanalyticsController@analysis')->name('stats');
    Route::get('Panalyticslogout', 'PanalyticsController@processLogout')->name('Panalyticslogout');
});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/


Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

Route::get('/', 'FrontendController@showLanding')->name('home');
Route::get('optout/{phone_number}', 'FrontendController@optoutCampaign')->name('optout');
Route::post('optout/{phone_number}', 'FrontendController@optoutCampaign');
Route::post('response', 'FrontendController@verifyCode')->name('response');
Route::get('response', 'FrontendController@verifyCode');
Route::get('report', 'FrontendController@showReport')->name('report');
Route::post('reportSubmit', 'FrontendController@submitReport')->name('submit');
Route::get('press', 'FrontendController@showMedia')->name('press');
Route::get('contact', 'FrontendController@showContact')->name('contact');
Route::post('contact', 'FrontendController@sendEmail')->name('contactEmail');
Route::get('logout', 'AuthController@processLogout')->name('logout');
Route::get('legal', 'FrontendController@showLegal')->name('legal');
Route::get('faq', 'FrontendController@showFaq')->name('faq');
Route::get('platforms', 'FrontendController@platformLink')->name('platforms');
Route::get('probmodel', 'ProbabilisticModelController@index')->name('probmodel');
Route::get('digitalwarranty', 'FrontendController@dw')->name('dw');

/*
|--------------------------------------------------------------------------
| User Dashboard
|--------------------------------------------------------------------------
*/
Route::middleware('auth.user')->prefix('user')->group(function () {
    Route::get('dashboard', 'UserController@showDashboard')->name('user.dashboard');
    Route::get('profile', 'UserController@showProfile')->name('user.profile');
    Route::get('profile/update', 'UserController@showProfileForm')->name('user.profile.form');
    Route::post('profile/update', 'UserController@updateProfile')->name('user.profile.update');
    Route::get('verify', 'UserController@showVerifyForm')->name('user.verify');
    Route::post('verify', 'UserController@verifyCode');
});



/*
|--------------------------------------------------------------------------
| API Routes v1 & v2
|--------------------------------------------------------------------------
*/
Route::prefix('api/v1')->group(function () {
    Route::post('login', 'ApiController@login');
    Route::post('registration', 'ApiController@registration');
    Route::post('password/forgot', 'ApiController@forgotPassword');
    Route::post('password/reset', 'ApiController@resetPassword');
    Route::get('sms/verify', 'ApiController@verifySmsCode');
    Route::post('sms/verifytest', 'ApiController@verifytestSmsCode');
    Route::get('sms/verifytest', 'ApiController@verifytestSmsCode');
    Route::get('activate/{id}', 'ApiController@sendActivation');
    Route::post('activate/{id}', 'ApiController@processActivation');
});

Route::prefix('api/v2')->group(function () {
    Route::get('sms/verifytest', 'ApiController@verifySSLSmsCode');
});

/*
|--------------------------------------------------------------------------
| LiveCheck Pro Routes
|--------------------------------------------------------------------------
*/
Route::get('v/{code}', 'livecheckproController@urlCode');
Route::post('/codeverify', 'livecheckproController@IsValidCode');
Route::post('/phoneverify', 'livecheckproController@IsValidPhone');
Route::post('/livecheck', 'livecheckproController@LiveCheck');
Route::post('/resendcode', 'livecheckproController@ResendCode');

/*
|--------------------------------------------------------------------------
| MUPS LiveCheck Pro Routes
|--------------------------------------------------------------------------
*/
Route::get('mups', 'livecheckproControllerMups@page')->name('mups');
Route::get('mups-leaflet', 'livecheckproControllerMups@leaflet')->name('leaflet');
Route::post('/mupslivecheck', 'livecheckproControllerMups@mlivecheck')->name('mupslivecheck');

/*
|--------------------------------------------------------------------------
| Locale Setting
|--------------------------------------------------------------------------
*/
Route::get('set-locale/{locale}', function ($locale) {
    App::setLocale($locale);
    session()->put('locale', $locale);
    return redirect()->back();
})->name('locale.setting');
