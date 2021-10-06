<?php

//Payment IPN
Route::post('/ipncoinpaybtc', 'PaymentController@ipnCoinPayBtc')->name('ipn.coinPay.btc');
Route::post('/ipncoinpayeth', 'PaymentController@ipnCoinPayEth')->name('ipn.coinPay.eth');
Route::post('/ipncoinpaydoge', 'PaymentController@ipnCoinPayDoge')->name('ipn.coinPay.doge');
Route::post('/ipncoinpayltc', 'PaymentController@ipnCoinPayLtc')->name('ipn.coinPay.ltc');

Route::get('/city/{city}', 'AdminController@searchCity')->name('search.city');

Route::get('/', 'FrontendController@index')->name('homepage');
Route::get('/{username}/market', 'FrontendController@index')->name('homepage-username');
Route::post('/submit-advice', 'FrontendController@submitAdvice')->name('submit-advice');
Route::get('/methods/guide', 'FrontendController@MethodGuide')->name('payment.guide');
Route::get('menu/{slug}', 'FrontendController@menu')->name('menu.view');
Route::get('{username}/contact', 'FrontendController@contactUs')->name('contact.index.username');
Route::get('/contact', 'FrontendController@contactUs')->name('contact.index');

Route::get('/trade/btc', 'FrontendController@tradeBTC')->name('trade.bitcoin.view');
Route::get('/methods/active/{id}', 'FrontendController@ActiveMethode');
Route::post('advertise/statusChange', 'AdvertiseController@statusChange')->name('advertise.statusChange');
Route::get('/country-change/{country}', 'FrontendController@countryChange')->name('country.change');



Route::get('/profile/{username}', 'FrontendController@profileView')->name('user.profile.view');
Route::get('/terms', 'FrontendController@termsView')->name('terms.index');
Route::get('/policy', 'FrontendController@policyView')->name('policy.index');
Route::get('/how-to-buy-btc', 'FrontendController@howToBuyBTCview')->name('how-to-buy-btc.index');

Route::get('/ad/{id}/{payment}', 'FrontendController@viewSlug')->name('view');
Route::get('/notification-read/{id}', 'HomeController@notificationRead');
Route::get('/notification-read/{id}/{username}', 'HomeController@notificationRead');

Route::post('/contact-us', ['uses' => 'FrontendController@contactSubmit', 'as' => 'contact-submit']);
Route::get('/comment/close/{ticket}', 'TicketController@ticketClose')->name('ticket.close');

Route::get('authorization', 'FrontendController@authCheck')->name('user.authorization');
Route::post('/sendemailver', 'FrontendController@sendemailver')->name('sendemailver');
Route::post('/emailverify', 'FrontendController@emailverify')->name('emailverify');
Route::post('/sendsmsver', 'FrontendController@sendsmsver')->name('sendsmsver');
Route::post('/smsverify', 'FrontendController@smsverify')->name('smsverify');
Route::post('/g2fa-verify', 'FrontendController@verify2fa')->name('go2fa.verify');
Route::post('/withdraw2fa-verify', 'FrontendController@withdraw2faVerify')->name('withdraw2fa.verify');
Route::post('/search', 'FrontendController@searchRe')->name('quick.search');
Route::group(['prefix' => 'user','middleware' => ['auth', 'prevent-back-history']], function () {
    Route::get('/notification', 'HomeController@notification')->name('notification.get');
    Route::get('/allNotification', 'HomeController@allNotification')->name('allNotification.get');
    Route::get('/allNotification/{username}', 'HomeController@allNotification')->name('allNotificationUsername.get');
    Route::get('/readallNotification/{id}', 'HomeController@readallNotification')->name('readallNotification.get');
    
    Route::get('/deal_messages/{id}', 'HomeController@deal_messages')->name('deal_messages.get');

    Route::post('/rating', 'HomeController@rating')->name('rating.post');
    Route::post('/rated/{id}', 'HomeController@ratingUpdated')->name('rating.updated');
    Route::post('/note/submit', 'HomeController@noteSubmit')->name('note.submit');
  
    // Canned Messages
    Route::get('/{username}/canned-messages', 'HomeController@cannedMessages')->name('canned.messages.get');
    Route::post('/{username}/canned-messages/add', 'HomeController@cannedMessagesAdd')->name('canned.messages.add');
    Route::post('/{username}/canned-messages/edit', 'HomeController@cannedMessagesEdit')->name('canned.messages.edit');
    Route::get('/{username}/canned-messages/delete/{id}', 'HomeController@cannedMessagesDelete')->name('canned.messages.delete');

    Route::get('{username}/withdraws', 'WithdrawController@index')->name('user.withdraws');
    Route::get('{username}/sends', 'WithdrawController@sends')->name('user.sends');
    Route::get('withdraws2faUi', 'WithdrawController@withdraw2faUI')->name('user.withdraws.withdraw2faUI');
    Route::post('withdraws', 'WithdrawController@store')->name('user.withdraws.store');
    Route::get('withdraw', 'WithdrawController@afterStore')->name('user.withdraws.afterStore');
    Route::delete('withdraws/{withdraw}', 'WithdrawController@destroy')->name('user.withdraws.destroy');
    Route::post('withdraws/{withdraw}', 'WithdrawController@destroy')->name('user.withdraws.destroy');

    Route::get('{username}/advertise/coin', 'AdvertiseController@sellCoin')->name('advertise.sell.coin');
    Route::get('{username}/advertise/history', 'AdvertiseController@showAdvertiseHistory')->name('advertise.history');

    Route::post('verification', 'HomeController@sendVcode')->name('user.send-vcode');
    Route::post('smsVerify', 'HomeController@smsVerify')->name('user.sms-verify');

    Route::post('verify-email', 'HomeController@sendEmailVcode')->name('user.send-emailVcode');
    Route::post('postEmailVerify', 'HomeController@postEmailVerify')->name('user.email-verify');
    
    Route::get('fee-structure', 'HomeController@feeStructure')->name('user.fee-structure');

    Route::middleware(['CheckStatus', 'auth', 'prevent-back-history'])->group(function () {
        Route::get('{username}/home', 'HomeController@index')->name('home');

        Route::get('{username}/deposit', 'HomeController@deposit')->name('deposit');
        Route::post('/deposit-confirm', 'PaymentController@depositConfirm')->name('deposit.confirm');

        Route::group(['prefix' => '{username}/advertise'], function () {
            Route::post('coin', 'AdvertiseController@showCurrency')->name('currency.check');
            Route::post('create', 'AdvertiseController@store')->name('sell.buy');
            Route::get('{advertise}/edit', 'AdvertiseController@edit')->name('sell_buy.edit');
            Route::put('{advertise}/update', 'AdvertiseController@update')->name('sell.buy.update');
        });
        Route::post('/contact/deal/{advertise}', 'HomeController@storeDealBuy')->name('store.deal');
        Route::post('/send/message', 'HomeController@dealSendMessage')->name('send.message.deal');
        Route::post('/send/message/reply', 'HomeController@dealSendMessageReply')->name('send.message.deal.reply');
        Route::get('deal/{id}', 'HomeController@dealView')->name('buy.message');
        Route::get('deal-reply/{id}', 'HomeController@notiReply')->name('noti.message');

        Route::post('confirm/paid', 'HomeController@confirmPaid')->name('confirm.paid');
        Route::post('confirm/cancel', 'HomeController@confirmCencel')->name('confirm.cancel');

        
        Route::get('open/trade', 'HomeController@openTrade')->name('open.trade');
        Route::get('close/trade', 'HomeController@closeTrade')->name('close.trade');
        Route::get('complete/trade', 'HomeController@completeTrade')->name('complete.trade');
        Route::get('cancel/trade', 'HomeController@cancelTrade')->name('cancel.trade');
        Route::get('expire/trade', 'HomeController@expireTrade')->name('expire.trade');

        Route::post('cancel/trade/reverse', 'HomeController@cancelTradeReverce')->name('confirm.cancel.reverse');
        Route::post('paid/trade/reverse', 'HomeController@paidTradeReverce')->name('confirm.paid.reverse');

        Route::get('{username}/deposits', 'HomeController@depHistory')->name('deposit.history');
        Route::get('{username}/deposit', 'HomeController@depGuide')->name('deposit.guide');
        Route::get('{username}/receives', 'HomeController@receivesHistory')->name('receives.history');
        Route::get('{username}/transactions', 'HomeController@transHistory')->name('trans.history');
        Route::get('statistics', 'HomeController@stats')->name('stats');

        Route::get('{username}/change-password', 'HomeController@changePassword')->name('user.change-password');
        Route::put('{username}/change-password', 'HomeController@submitPassword')->name('user.update-password');

        Route::get('{username}/edit-profile', 'HomeController@editProfile')->name('edit-profile');
        Route::put('{username}/edit-profile', 'HomeController@submitProfile')->name('edit-profile');
        Route::put('{username}/edit-profile/step-1', 'HomeController@submitProfile1')->name('edit-profile-step-1');
        Route::put('{username}/edit-profile/step-2', 'HomeController@submitProfile2')->name('edit-profile-step-2');
        Route::get('{username}/edit-profile/step-2', 'HomeController@editProfile2')->name('edit-profile-step-2');

        Route::post('/store/ticket', 'TicketController@ticketStore')->name('ticket.store');
        Route::get('/support/reply/{ticket}', 'TicketController@ticketReply')->name('ticket.customer.reply');
        Route::post('/support/store/{ticket}', 'TicketController@ticketReplyStore')->name('store.customer.reply');

        Route::get('{username}/support', 'TicketController@ticketIndex')->name('support.index.customer');
        Route::get('/support/new', 'TicketController@ticketCreate')->name('add.new.ticket');

        Route::get('{username}/security/two/step', 'HomeController@twoFactorIndex')->name('two.factor.index');
        Route::post('/g2fa-create', 'HomeController@create2fa')->name('go2fa.create');
        Route::post('/g2fa-disable', 'HomeController@disable2fa')->name('disable.2fa');
    });
});

Route::get('/adminio', 'AdminLoginController@index')->name('admin.loginForm');
Route::post('/adminio', 'AdminLoginController@authenticate')->name('admin.login');
Route::group(['prefix' => 'adminio','middleware' => [ 'admin', 'prevent-back-history']], function () {
    
    Route::post('/send', 'AdminController@AdminSendMessage')->name('admin.send.message');
    Route::get('/deal_messages/{id}', 'AdminController@deal_messages')->name('admin.deal_messages.get');
    Route::get('/deal_hold/{id}/{status}', 'AdminController@deal_hold')->name('admin.deal_hold');
    Route::get('/adminconfirm/paid/{id}', 'AdminController@confirmPaid')->name('adminconfirm.paid');
    Route::get('/adminconfirm/cancel/{id}', 'AdminController@confirmCencel')->name('adminconfirm.cancel');
    Route::get('/adminconfirm/dispute/{id}', 'AdminController@confirmDispute')->name('adminconfirm.dispute');


    Route::group(['middleware' => 'auth:admin'], function () {
        Route::get('/getDashboardStats', 'IpnController@getDashboardStats')->name('ipn.getDashboardStats');
        // counries controller
        Route::get('/countries', 'CountryController@countries')->name('country.all');

        // overall balance 
        Route::get('/overallbalance', 'AdminController@systemOverAllBalanceDetails')->name('admin.overallbalance');
        Route::get('/dashboard', 'AdminController@dashboard')->name('admin.dashboard');
        // Route::get('/trigerEvent', 'AdminController@trigerEvent')->name('admin.dashboard');
        Route::get('/dashboard-table', 'AdminController@dashboard_table')->name('admin.dashboard-table');
        Route::get('/dashboard-chart', 'AdminController@dashboard_chart')->name('admin.dashboard-chart');
        Route::get('/feeSetup', 'GeneralSettingController@feeSetup')->name('admin.feesetup');
        Route::post('/feeSetup', 'GeneralSettingController@updateFeeSetup')->name('admin.updatefeesetup');
        Route::post('/dashboard/charts', 'AdminController@getChartsData')->name('admin.dashboard.charts');

        Route::get('/transactions', 'DepositController@transLog')->name('trans.log');
        Route::get('/actions', 'DepositController@actionsLog')->name('actions.log');
        Route::get('/trade24hoursHistory', 'DepositController@trade24hoursHistory')->name('trade24hoursHistory.log');
        Route::get('/deals', 'DepositController@dealLog')->name('deal.log');
        Route::get('/complete-deals', 'DepositController@dealCompleteLog')->name('dealComplete.log');
        Route::get('/expired-deals', 'DepositController@dealExpiredLog')->name('dealExpired.log');
        Route::get('/24hours-deals', 'DepositController@deal24hoursLog')->name('deal24hours.log');
        Route::get('/cancelled-deals', 'DepositController@dealCancelledLog')->name('dealCancelled.log');
        Route::get('/openDeals', 'DepositController@openDealLog')->name('openDeal.log');
        Route::get('/dispute/deals', 'DepositController@disputedealLog')->name('deal.dispute');
        Route::get('/hold/deals', 'DepositController@holddealLog')->name('deal.hold');
        Route::get('/deals/search', 'DepositController@dealSearch')->name('trans.search');
        Route::get('/deals/{trans_id}', 'DepositController@dealView')->name('deal.view.admin');

        Route::get('/terms/policy', 'Admin\UsersController@viewTerms')->name('terms.policy');
        Route::post('/terms/policy', 'Admin\UsersController@updateTerms')->name('terms.policy.update');

        Route::get('/supports', 'TicketController@indexSupport')->name('support.admin.index');
        Route::get('/support/reply/{ticket}', 'TicketController@adminSupport')->name('ticket.admin.reply');
        Route::post('/reply/{ticket}', 'TicketController@adminReply')->name('store.admin.reply');
        Route::get('/pending/ticket', 'TicketController@pendingTicketAdmin')->name('pending.support.ticket');

        //Gateway
        Route::get('/gateway', 'GatewayController@show')->name('gateway');
        Route::post('/gateway', 'GatewayController@update')->name('update.gateway');

        //Deposit
        Route::get('/external-transactions', 'DepositController@index')->name('deposits');
        Route::get('/withdraw-requests/{withdraw}', 'WithdrawController@show')->name('admin.withdraw.requests.show');
        Route::get('/send-requests/{withdraw}', 'WithdrawController@sendShow')->name('admin.send.requests.show');
        Route::post('/update-withdraw-address/{withdraw}', 'WithdrawController@updateWithDrawWalletAddress')->name('admin-withdraw-address-update');
        Route::post('/update-send-address/{withdraw}', 'WithdrawController@updateSendWalletAddress')->name('admin-send-address-update');
        Route::get('/withdraw-requests', 'WithdrawController@withdrawRequests')->name('admin.withdraw.requests');
        Route::get('/send-requests', 'WithdrawController@sendRequests')->name('admin.send.requests');
        Route::get('/withdraw-pending-requests', 'WithdrawController@withdrawPendingRequests')->name('admin.withdraw.requests.pending');
        Route::get('/send-pending-requests', 'WithdrawController@sendPendingRequests')->name('admin.send.requests.pending');
        Route::get('/withdraw-complete-requests', 'WithdrawController@withdrawCompleteRequests')->name('admin.withdraw.requests.complete');
        Route::get('/send-complete-requests', 'WithdrawController@sendCompleteRequests')->name('admin.send.requests.complete');
        Route::post('/withdraw-requests/{withdraw}/reject', 'WithdrawController@reject')->name('admin.withdraw.requests.reject');
        Route::post('/send-requests/{withdraw}/reject', 'WithdrawController@sendReject')->name('admin.send.requests.reject');
        Route::post('/withdraw-requests/{withdraw}/confirm', 'WithdrawController@confirm')->name('admin.withdraw.requests.confirm');
        Route::post('/send-requests/{withdraw}/confirm', 'WithdrawController@sendConfirm')->name('admin.send.requests.confirm');

        //Email Template
        Route::get('/template', 'EtemplateController@index')->name('email.template');
        Route::post('/template-update', 'EtemplateController@update')->name('template.update');
        //Sms Api
        Route::get('/sms-api', 'EtemplateController@smsApi')->name('sms.api');
        Route::post('/sms-update', 'EtemplateController@smsUpdate')->name('sms.update');


        // General Settings
        Route::get('/general-settings', 'GeneralSettingController@GenSetting')->name('admin.GenSetting');
        Route::post('/general-settings', 'GeneralSettingController@UpdateGenSetting')->name('admin.UpdateGenSetting');
        Route::get('/change-password', 'GeneralSettingController@changePassword')->name('admin.changePass');
        Route::post('/change-password', 'GeneralSettingController@updatePassword')->name('admin.changePass');
        Route::get('/profile', 'GeneralSettingController@profile')->name('admin.profile');
        Route::post('/profile', 'GeneralSettingController@updateProfile')->name('admin.profile');


        //User Management
        Route::get('users', 'Admin\UsersController@index')->name('users');
        Route::get('users/filter', 'Admin\UsersController@filter')->name('users.filter');
        Route::get('delete-user/{id}', 'Admin\UsersController@deleteUser')->name('user.delete');
        Route::get('marketing-users', 'Admin\UsersController@marketing_users')->name('marketing.users');
        Route::get('marketing-users/{id}', 'Admin\UsersController@marketing_users_country')->name('marketing.users.country');
        Route::get('users-country/{id}', 'Admin\UsersController@users_country')->name('users.country');
        Route::post('marketing-users', 'Admin\UsersController@marketing_users_action')->name('marketing.users.action');
        Route::get('users24signups', 'Admin\UsersController@users24signups')->name('users24signups');
        Route::get('usersonline', 'Admin\UsersController@usersonline')->name('usersonline');
        Route::get('users-active', 'Admin\UsersController@activeUsers')->name('users-active');
        Route::get('users-inactive', 'Admin\UsersController@inactiveUsers')->name('users-inactive');
        Route::put('user/update-status/{user}', 'Admin\UsersController@updateStatus');

        Route::post('user-search', 'Admin\UsersController@userSearch')->name('search.users');
        Route::get('user-search', 'Admin\UsersController@userSearchGet')->name('search.users.get');
        Route::get('user/{user}', 'Admin\UsersController@singleUser')->name('user.single');
        Route::get('balance-nullify/{user}', 'Admin\UsersController@balanceNullify')->name('user.balance.nullify');
        Route::get('users/{user}/balance-history', 'Admin\UsersController@showBalanceHistory')->name('user.balance.history');
        Route::get('users/{user}/access-history', 'Admin\UsersController@showAccessHistory')->name('user.access.history');
        Route::put('user/pass-change/{user}', 'Admin\UsersController@userPasschange')->name('user.passchange');
        Route::put('user/status/{user}', 'Admin\UsersController@statusupdate')->name('user.status');
        Route::get('mail/{user}', 'Admin\UsersController@userEmail')->name('user.email');
        Route::post('/sendmail', 'Admin\UsersController@sendemail')->name('send.email');
        Route::get('/user-login-history/{id}', 'Admin\UsersController@loginLogsByUsers')->name('user.login.history');
        Route::get('/user-balance/{id}', 'Admin\UsersController@ManageBalanceByUsers')->name('user.balance');
        Route::post('/user-balance', 'Admin\UsersController@saveBalanceByUsers')->name('user.balance.update');
        Route::get('login-logs/{user?}', 'Admin\UsersController@loginLogs')->name('user.login-logs');

        Route::get('/user-transaction/{id}', 'Admin\UsersController@userTrans')->name('user.trans');
        Route::get('/user-deposit/{id}', 'Admin\UsersController@userDeposit')->name('user.deposit');

        //Contact Setting
        Route::get('contact-setting', 'WebSettingController@getContact')->name('contact-setting');
        Route::put('contact-setting/{id}', 'WebSettingController@putContactSetting')->name('contact-setting-update');

        Route::get('manage-logo', 'WebSettingController@manageLogo')->name('manage-logo ');
        Route::post('manage-logo', 'WebSettingController@updateLogo')->name('manage-logo');

        // Route::get('manage-slider', 'WebSettingController@manageSlider')->name('manage-slider ');
        // Route::post('add-slider', 'WebSettingController@addSliderView')->name('add-slider');
        // Route::get('add-slider', 'WebSettingController@addSlider')->name('add-slider');
        // Route::post('remove-slider', 'WebSettingController@removeSlider')->name('remove-slider');

        Route::get('manage-footer', 'WebSettingController@manageFooter')->name('manage-footer');
        Route::put('manage-footer', 'WebSettingController@updateFooter')->name('manage-footer-update');


        Route::get('manage-social', 'WebSettingController@manageSocial')->name('manage-social');
        Route::post('manage-social', 'WebSettingController@storeSocial')->name('manage-social');
        Route::get('manage-social/{product_id?}', 'WebSettingController@editSocial')->name('social-edit');
        Route::put('manage-social/{product_id?}', 'WebSettingController@updateSocial')->name('social-edit');
        Route::delete('manage-social/{product_id?}', 'WebSettingController@deleteSocial')->name('social-delete');

        Route::get('menu-create', 'WebSettingController@createMenu')->name('menu-create');
        Route::post('menu-create', 'WebSettingController@storeMenu')->name('menu-create');
        Route::get('menu-control', 'WebSettingController@manageMenu')->name('menu-control');
        Route::get('menu-edit/{id}', 'WebSettingController@editMenu')->name('menu-edit');
        Route::post('menu-update/{id}', 'WebSettingController@updateMenu')->name('menu-update');
        Route::delete('menu-delete/{menu}', 'WebSettingController@deleteMenu')->name('menu-delete');

        Route::get('slider-index', 'WebSettingController@manageSlider')->name('slider-index');
        Route::post('slider-delete', 'WebSettingController@deleteSlider')->name('slider-delete');
        Route::post('slider-store', 'WebSettingController@storeSlider')->name('slider-store');

        Route::resource('payment-methods', 'PaymentMethodController');
        Route::get('/methods/category', 'WebSettingController@categories')->name('methods.viewcategories');
        
        Route::get('/methods/advises', 'WebSettingController@advises')->name('methods.viewadvises');
        Route::get('/advises/{id}', 'WebSettingController@EditAdvises')->name('advises.edit');
        Route::post('/advises/{id}', 'WebSettingController@updateAdvises')->name('advises.update');
        Route::get('/advisesDel/{id}', 'WebSettingController@DeleteAdvises')->name('advises.delete');

        Route::get('/methods/createCategories', 'WebSettingController@createCategories')->name('methods.categories');
        Route::post('/methods/createCategories', 'WebSettingController@saveCategories')->name('save.categories');
        
        Route::get('/methods/{id}', 'WebSettingController@EditCategories')->name('categories.edit');
        Route::put('/methods/{id}', 'WebSettingController@updateCategories')->name('categories.update');
        Route::get('/methodDel/{id}', 'WebSettingController@DeleteCategories')->name('categories.delete');
        Route::get('/Deletereview/{id}', 'Admin\UsersController@DeleteReview')->name('admin.review.delete');
        
        Route::get('/review/{id}', 'Admin\UsersController@EditReview')->name('admin.review.edit');
        Route::post('/reviews', 'Admin\UsersController@SaveReview')->name('admin.review.save');
        
        
        
        Route::resource('currency', 'CurrencyController');
        Route::put('bitcoind', 'GeneralSettingController@updateBitcoindScheme');
        Route::get('/addresses', 'AdminController@addresses')->name('addresses.show');
        Route::post('/addresses', 'AdminController@addressesUpload')->name('addresses.upload');
        
        Route::get('/admins', 'AdminController@admins')->name('admins.list');
        Route::get('/adminsCreate', 'AdminController@adminsCreate')->name('admins.create');
        Route::post('/adminsCreateSave', 'AdminController@adminsCreateSave')->name('admins.save');
        Route::get('/adminsEdit/{id}', 'AdminController@adminsEdit')->name('admins.edit');
        Route::post('/adminsEditSave/{id}', 'AdminController@adminsEditSave')->name('admins.editsave');
        Route::get('/adminsDelete/{id}', 'AdminController@adminsDelete')->name('admins.delete');

        // cron jobs handling
        Route::get('/cron-jobs', 'AdminController@cronJobsIndex')->name('cron-jobs.index');
        Route::post('/cron-jobs-update', 'AdminController@cronJobsUpdate')->name('cron-jobs.update');
        
        //Reports
        Route::get('/reports/overview', 'AdminController@overview')->name('reports.overview');
        Route::get('/reports/overview-search', 'AdminController@overviewSearch')->name('search.overview');
        
        Route::get('/ads', 'AdminController@ads')->name('admin.ads');
        Route::get('/ads-active', 'AdminController@activeAds')->name('admin.ads.active');
        Route::get('/ads-inactive', 'AdminController@inactiveAds')->name('admin.ads.inactive');
        Route::get('/ads-24hours', 'AdminController@ads24hours')->name('admin.ads.24hours');
        Route::get('/ads-search', 'AdminController@adsSearch_bkp')->name('search.ads');
        Route::get('/ads-search/{pm_id}/{username}/{add_id}/{country_id}/{currency}/get', 'AdminController@adsSearch')->name('search.adss');



        Route::get('/logout', 'AdminController@logout')->name('admin.logout');
    });
});

/*============== User Password Reset Route list ===========================*/
Route::get('user-password/reset', 'User\ForgotPasswordController@showLinkRequestForm')->name('user.password.request');
Route::post('user-password/email', 'User\ForgotPasswordController@sendResetLinkEmail')->name('user.password.email');
Route::get('reset/{token}', 'User\ResetPasswordController@showResetForm')->name('user.password.reset');
Route::post('/reset', 'User\ResetPasswordController@reset')->name('reset.passw');

Auth::routes();

Route::get('{username}/home', 'HomeController@index')->name('home');
