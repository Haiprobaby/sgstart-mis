<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

  
		
	Route::post('auth/login', 'api\SmAdminController@login');

	Route::group(['middleware' => ['auth:api']],function(){ 
		Route::post('auth/logout', 'api\SmAdminController@logout');
	});
	

	Route::any('attendance-sync', 'SmApiController@AttendanceSync');

	Route::group(['middleware' => ['XSS']], function () {

		//api url http://localhost/project/101.infixedu.v4/api/send-sms?phonenumber=+88017648456&sender_id=1&api_password=978&api_id=2&sms_type=2&encoding=ty&textmessage=yu
		Route::get('send-sms','SmApiController@SendSMS');
		
		Route::get('sync', 'SmApiController@sync');

		Route::get('privacy-permission/{id}', 'SmApiController@privacyPermission');
		Route::get('privacy-permission-status', 'SmApiController@privacyPermissionStatus');



	
		// -------------------Start admin Module------------------ 
		Route::any('is-enabled', 'SmApiController@checkColumnAvailable');

		// admin section visitor  
		Route::any('login', 'SmApiController@mobileLogin');


		Route::get('class-id/{id}', 'SmApiController@get_class_name');
		Route::get('section-id/{id}', 'SmApiController@get_section_name');
		Route::get('teacher-id/{id}', 'SmApiController@get_teacher_name');
		Route::get('subject-id/{id}', 'SmApiController@get_subject_name');
		Route::get('room-id/{id}', 'SmApiController@get_room_name');
		Route::get('class-period-id/{id}', 'SmApiController@get_class_period_name');


		Route::get('visitor', ['as' => 'visitor', 'uses' => 'SmApiController@visitor_index']);
		Route::post('visitor-store', ['as' => 'visitor_store', 'uses' => 'SmApiController@visitor_store']);
		Route::get('visitor-edit/{id}', ['as' => 'visitor_edit', 'uses' => 'SmApiController@visitor_edit']);

		Route::post('visitor-update', ['as' => 'visitor_update', 'uses' => 'SmApiController@visitor_update']);
		Route::get('visitor-delete/{id}', ['as' => 'visitor_delete', 'uses' => 'SmApiController@visitor_delete']);




		// admin section complaint
		Route::get('complaint', 'SmApiController@complaint');
		Route::post('complaint-store', 'SmApiController@complaintStore');


		Route::get('complaint', 'SmApiController@complaint_index');
		Route::post('complaint-store', 'SmApiController@complaint_store');
		Route::get('complaint-edit/{id}', 'SmApiController@complaint_edit');
		Route::post('complaint-update', 'SmApiController@complaint_update');
		Route::get('complaint-delete/{id}', 'SmApiController@complaint_update');


		// Admin section postal-receive

		Route::get('postal-receive', 'SmApiController@postal_receive_index');
		Route::post('postal-receive-store', 'SmApiController@postal_receive_store');
		Route::post('postal-receive-edit/{id}', 'SmApiController@postal_receive_show');
		Route::post('postal-receive-update', 'SmApiController@postal_receive_update');
		Route::get('postal-receive-delete/{id}', 'SmApiController@postal_receive_destroy');


		// Admin section postal-dispatch
		Route::get('postal-dispatch', 'SmApiController@postal_dispatch_index');
		Route::post('postal-dispatch-store', 'SmApiController@postal_dispatch_store');
		Route::get('postal-dispatch-edit/{id}', 'SmApiController@postal_dispatch_show');
		Route::post('postal-dispatch-update', 'SmApiController@postal_dispatch_update');
		Route::get('postal-dispatch-delete/{id}', 'SmApiController@postal_dispatch_destroy');
		// Phone Call Log
		Route::resource('phone-call', 'api\ApiSmPhoneCallLogController');

		// Admin Setup
		Route::resource('setup-admin', 'api\ApiSmSetupAdminController');
		Route::get('setup-admin-delete/{id}', 'SmApiController@setup_admin_destroy');

		// -------------------End admin Module------------------


		// -----------Start Student Information---------------
		// student list
		Route::get('student-list', ['as' => 'student_list', 'uses' => 'SmApiController@studentDetails']);

		// student search

		Route::post('student-list-search', 'SmApiController@studentDetailsSearch');
		Route::get('student-list-search', 'SmApiController@student_search_Details');

		// student list
		Route::get('student-view/{id}', ['as' => 'student_view', 'uses' => 'SmApiController@studentView']);
		// student delete
		Route::any('student-delete', ['as' => 'student_delete', 'uses' => 'SmApiController@studentDelete']);
		// student edit
		Route::get('student-edit/{id}', ['as' => 'student_edit', 'uses' => 'SmApiController@studentEdit']);


		// Student Attendance
		Route::get('student-attendance', ['as' => 'student_attendance', 'uses' => 'SmApiController@student_attendance_index']);
		Route::post('student-search', 'SmApiController@studentSearch');
		Route::get('student-search', 'SmApiController@student_search_index');

		Route::post('student-attendance-store', 'SmApiController@studentAttendanceStore');

		// Student Attendance Report
		Route::get('student-attendance-report', ['as' => 'student_attendance_report', 'uses' => 'SmApiController@studentAttendanceReport']);

		Route::post('student-attendance-report-search', ['as' => 'student_attendance_report_search', 'uses' => 'SmApiController@studentAttendanceReportSearch']);
		Route::get('student-attendance-report-search', 'SmApiController@studentAttendanceReport_search');

		// Student Category
		Route::get('student-category', ['as' => 'student_category', 'uses' => 'SmApiController@student_type_index']);
		Route::post('student-category-store', ['as' => 'student_category_store', 'uses' => 'SmApiController@student_type_store']);
		Route::get('student-category-edit/{id}', ['as' => 'student_category_edit', 'uses' => 'SmApiController@student_type_edit']);
		Route::post('student-category-update', ['as' => 'student_category_update', 'uses' => 'SmApiController@student_type_update']);
		Route::get('student-category-delete/{id}', ['as' => 'student_category_delete', 'uses' => 'SmApiController@student_type_delete']);


		// Student Group Routes
		Route::get('student-group', ['as' => 'student_group', 'uses' => 'SmApiController@student_group_index']);
		Route::post('student-group-store', ['as' => 'student_group_store', 'uses' => 'SmApiController@student_group_store']);
		Route::get('student-group-edit/{id}', ['as' => 'student_group_edit', 'uses' => 'SmApiController@student_group_edit']);
		Route::post('student-group-update', ['as' => 'student_group_update', 'uses' => 'SmApiController@student_group_update']);
		Route::get('student-group-delete/{id}', ['as' => 'student_group_delete', 'uses' => 'SmApiController@student_group_delete']);


		// Student Promote search
		Route::get('student-promote', ['as' => 'student_promote', 'uses' => 'SmApiController@studentPromote_index']);

		Route::get('student-current-search', 'SmApiController@studentPromote');
		Route::post('student-current-search', 'SmApiController@studentCurrentSearch');
		Route::get('view-academic-performance/{id}', 'SmApiController@view_academic_performance');


		// // Student Promote Store
		Route::get('student-promote-store', 'SmApiController@studentPromote_store');
		Route::post('student-promote-store', 'SmApiController@studentPromoteStore');

		// Disabled Student
		Route::get('disabled-student', ['as' => 'disabled_student', 'uses' => 'SmApiController@disabledStudent']);
		Route::post('disabled-student', ['as' => 'disabled_student', 'uses' => 'SmApiController@disabledStudentSearch']);
		// -----------End Student Information---------------

		// -------------------Teacher Module------------------
		// Start Upload Content
		Route::get('upload-content', 'SmApiController@uploadContentList');
		Route::post('save-upload-content', 'SmApiController@saveUploadContent'); // incomplete for API
		Route::get('delete-upload-content/{id}', 'SmApiController@deleteUploadContent');


		// Start rest of the routes
		Route::get('assignment-list', 'SmApiController@assignmentList');
		Route::get('study-metarial-list', 'SmApiController@studyMetarialList');
		Route::get('syllabus-list', 'SmApiController@syllabusList');
		Route::get('other-download-list', 'SmApiController@otherDownloadList');
		// End rest of the routes

		// ------------------- End Teacher Module------------------


		//--------------- Start Fees Collection --------------

		// Collect Fees
		Route::get('collect-fees', ['as' => 'collect_fees', 'uses' => 'SmApiController@collectFees']);
		Route::get('fees-collect-student-wise/{id}', ['as' => 'fees_collect_student_wise', 'uses' => 'SmApiController@collectFeesStudentApi']);
		Route::post('collect-fees', ['as' => 'collect_fees', 'uses' => 'SmApiController@collectFeesSearch']);

		//Search Fees Payment
		Route::get('search-fees-payment', ['as' => 'search_fees_payment', 'uses' => 'SmApiController@searchFeesPayment']);
		Route::post('fees-payment-search', ['as' => 'fees_payment_search', 'uses' => 'SmApiController@feesPaymentSearch']);
		Route::get('fees-payment-search', ['as' => 'fees_payment_search', 'uses' => 'SmApiController@search_Fees_Payment']);

		//Fees Search due
		Route::get('search-fees-due', ['as' => 'search_fees_due', 'uses' => 'SmApiController@searchFeesDue']);
		Route::post('fees-due-search', ['as' => 'fees_due_search', 'uses' => 'SmApiController@feesDueSearch']);
		Route::get('fees-due-search', ['as' => 'fees_due_search', 'uses' => 'SmApiController@search_FeesDue']);


		// Route::resource('fees-master', 'SmFeesMasterController');
		Route::post('fees-master-single-delete', 'SmApiController@deleteSingle');
		Route::post('fees-master-group-delete', 'SmApiController@deleteGroup');
		Route::get('fees-assign/{id}', ['as' => 'fees_assign', 'uses' => 'SmApiController@feesAssign']);
		Route::get('fees-assign/{id}', ['as' => 'fees_assign', 'uses' => 'SmApiController@fees_Assign']);
		Route::post('fees-assign-search', 'SmApiController@feesAssignSearch');

		// Fees Master
		Route::get('fees-master-store', ['as' => 'fees_master_add', 'uses' => 'SmApiController@feesMasterStore']);
		Route::get('fees-master-update', ['as' => 'fees_master_update', 'uses' => 'SmApiController@feesMasterUpdate']);

		// Fees Group routes
		Route::get('fees-group', ['as' => 'fees_group', 'uses' => 'SmApiController@fees_group_index']);
		Route::get('fees-group-store', ['as' => 'fees_group_store', 'uses' => 'SmApiController@fees_group_store']);
		Route::get('fees-group-edit/{id}', ['as' => 'fees_group_edit', 'uses' => 'SmApiController@fees_group_edit']);
		Route::get('fees-group-update', ['as' => 'fees_group_update', 'uses' => 'SmApiController@fees_group_update']);
		Route::post('fees-group-delete', ['as' => 'fees_group_delete', 'uses' => 'SmApiController@fees_group_delete']);

		// Fees type routes
		Route::get('fees-type', ['as' => 'fees_type', 'uses' => 'SmApiController@fees_type_index']);
		Route::get('fees-type-store', ['as' => 'fees_type_store', 'uses' => 'SmApiController@fees_type_store']);
		Route::get('fees-type-edit/{id}', ['as' => 'fees_type_edit', 'uses' => 'SmApiController@fees_type_edit']);
		Route::get('fees-type-update', ['as' => 'fees_type_update', 'uses' => 'SmApiController@fees_type_update']);
		Route::get('fees-type-delete/{id}', ['as' => 'fees_type_delete', 'uses' => 'SmApiController@fees_type_delete']);

		// Fees Discount routes
		Route::get('fees-discount', ['as' => 'fees_discount', 'uses' => 'SmApiController@fees_discount_index']);
		Route::post('fees-discount-store', ['as' => 'fees_discount_store', 'uses' => 'SmApiController@fees_discount_store']);
		Route::get('fees-discount-edit/{id}', ['as' => 'fees_discount_edit', 'uses' => 'SmApiController@fees_discount_edit']);
		Route::post('fees-discount-update', ['as' => 'fees_discount_update', 'uses' => 'SmApiController@fees_discount_update']);
		Route::get('fees-discount-delete/{id}', ['as' => 'fees_discount_delete', 'uses' => 'SmApiController@fees_discount_delete']);
		Route::get('fees-discount-assign/{id}', ['as' => 'fees_discount_assign', 'uses' => 'SmApiController@feesDiscountAssign']);
		Route::post('fees-discount-assign-search', 'SmApiController@feesDiscountAssignSearch');
		Route::get('fees-discount-assign-store', 'SmApiController@feesDiscountAssignStore');

		Route::get('fees-generate-modal/{amount}/{student_id}/{type}', 'SmApiController@feesGenerateModal');
		Route::get('fees-discount-amount-search', 'SmApiController@feesDiscountAmountSearch');
		// delete fees payment
		Route::post('fees-payment-delete', 'SmApiController@feesPaymentDelete');

		// Fees carry forward
		Route::get('fees-forward', ['as' => 'fees_forward', 'uses' => 'SmApiController@feesForward']);
		Route::post('fees-forward-search', 'SmApiController@feesForwardSearch');
		Route::get('fees-forward-search', 'SmApiController@fees_Forward');

		Route::post('fees-forward-store', 'SmApiController@feesForwardStore');
		Route::get('fees-forward-store', 'SmApiController@Fees_fward');

		//--------------- End Fees Collection --------------


		//--------------- Start Accounts Modules --------------

		// Profit of account
		Route::get('profit', ['as' => 'profit', 'uses' => 'SmApiController@profit']);
		Route::post('search-profit-by-date', ['as' => 'search_profit_by_date', 'uses' => 'SmApiController@searchProfitByDate']);
		Route::get('search-profit-by-date', ['as' => 'search_profit_by_date', 'uses' => 'SmApiController@Accounts_Profit']);

		// add income routes
		Route::get('add-income', ['as' => 'add_income', 'uses' => 'SmApiController@income_index']);
		Route::post('add-income-store', ['as' => 'add_income_store', 'uses' => 'SmApiController@income_store']);
		Route::get('add-income-edit/{id}', ['as' => 'add_income_edit', 'uses' => 'SmApiController@income_edit']);
		Route::post('add-income-update', ['as' => 'add_income_update', 'uses' => 'SmApiController@income_update']);
		Route::post('add-income-delete', ['as' => 'add_income_delete', 'uses' => 'SmApiController@income_delete']);

		// Add Expense
		Route::resource('add-expense', 'api\ApiSmAddExpenseController');

		//payment method
		Route::get('payment-method', ['as' => 'payment_method', 'uses' => 'SmApiController@payment_index']);
		Route::post('payment-method-store', ['as' => 'payment_method_store', 'uses' => 'SmApiController@payment_store']);
		Route::get('payment-method-edit/{id}', ['as' => 'payment_method_edit', 'uses' => 'SmApiController@payment_edit']);
		Route::post('payment-method-update', ['as' => 'payment_method_update', 'uses' => 'SmApiController@payment_update']);
		Route::get('payment-method-delete/{id}', ['as' => 'payment_method_delete', 'uses' => 'SmApiController@payment_delete']);

		//--------------- End Accounts Modules --------------


		//--------------- Start Human Resource  --------------

		// staff directory
		Route::get('staff-directory', ['as' => 'staff_directory', 'uses' => 'SmApiController@staffList']);
		Route::get('staff-roles', ['as' => 'staff_roles', 'uses' => 'SmApiController@staffRoles']);
		Route::get('staff-list/{role_id}', ['as' => 'staff_dlist', 'uses' => 'SmApiController@roleStaffList']);
		Route::get('staff-view/{id}', ['as' => 'staff_view', 'uses' => 'SmApiController@staffView']);
		Route::get('search-staff', 'SmApiController@staff_List');
		Route::post('search-staff', ['as' => 'searchStaff', 'uses' => 'SmApiController@searchStaff']);
		Route::get('deleteStaff/{id}', 'SmApiController@deleteStaff');

		//Staff Attendance
		Route::get('staff-attendance', ['as' => 'staff_attendance', 'uses' => 'SmApiController@staffAttendance']);
		Route::post('staff-attendance-search', 'SmApiController@staffAttendanceSearch');
		Route::post('staff-attendance-store', 'SmApiController@staffAttendanceStore');

		Route::get('staff-attendance-report', ['as' => 'staff_attendance_report', 'uses' => 'SmApiController@staffAttendanceReport']);
		Route::post('staff-attendance-report-search', ['as' => 'staff_attendance_report_search', 'uses' => 'SmApiController@staffAttendanceReportSearch']);

		// Staff designation
		Route::resource('designation', 'api\ApiSmDesignationController');

		//Department
		Route::resource('department', 'api\ApiSmHumanDepartmentController');
		//--------------- End Human Resource  --------------


		//--------------- Start Leave module --------------

		//Start Approve Leave Request
		Route::get('approve-leave', 'SmApiController@Approve_Leave_index');
		Route::post('approve-leave-store', 'SmApiController@Approve_Leave_store');
		Route::get('approve-leave-edit/{id}', 'SmApiController@Approve_Leave_edit');
		Route::get('staffNameByRole', 'SmApiController@staffNameByRole');
		Route::post('update-approve-leave', 'SmApiController@updateApproveLeave');
		Route::get('view-leave-details-approve/{id}', 'SmApiController@viewLeaveDetails');
		//End Approve Leave Request

		//Start Apply Leave
		Route::get('apply-leave', 'SmApiController@apply_leave_index');
		Route::post('apply-leave-store', 'SmApiController@apply_leave_store');
		Route::get('apply-leave-edit/{id}', 'SmApiController@apply_leave_show');
		Route::post('apply-leave-update', 'SmApiController@apply_leave_update');
		Route::get('view-leave-details-apply/{id}', 'SmApiController@view_Leave_Details');
		Route::get('delete-apply-leave/{id}', 'SmApiController@apply_leave_destroy');
		
		//End Apply Leave
		
		//Student leave
		Route::get('student-apply-leave', 'Parent\SmParentPanelController@leaveApply');
		Route::get('student-view-leave-details-apply/{id}', 'Parent\SmParentPanelController@viewLeaveDetails');
		Route::get('student-apply-leave-edit/{id}', 'Parent\SmParentPanelController@parentLeaveEdit');
		Route::post('student-apply-leave-update', 'Parent\SmParentPanelController@update');
		Route::post('student-apply-leave-store', 'Parent\SmParentPanelController@leaveStore');
		Route::get('student-delete-apply-leave/{id}', 'Parent\SmParentPanelController@DeleteLeave');

		//End student leave

		// Staff leave define
		Route::resource('leave-define', 'api\ApiSmLeaveDefineController');

		// Staff leave type
		Route::resource('leave-type', 'api\ApiSmLeaveTypeController');

		//--------------- End Leave module --------------


		//--------------- Start Examination Module--------------

		// Marks Grade
		Route::resource('marks-grade', 'api\ApiSmMarksGradeController');

		//--------------- End Examination Module--------------


		//--------------- Start Academic Module--------------

		// class routine new
		Route::get('class-routine-new', ['as' => 'class_routine_new', 'uses' => 'SmApiController@classRoutine']);
		Route::post('class-routine-new', 'SmApiController@classRoutineSearch');

		//assign subject
		Route::get('assign-subject', ['as' => 'assign_subject', 'uses' => 'SmApiController@assignSubject']);
		Route::get('assign-subject-create', ['as' => 'assign_subject_create', 'uses' => 'SmApiController@assigSubjectCreate']);
		Route::post('assign-subject-search', ['as' => 'assign_subject_search', 'uses' => 'SmApiController@assignSubjectSearch']);
		Route::get('assign-subject-search', 'SmApiController@assign_Subject_Create');
		Route::post('assign-subject-store', 'SmApiController@assignSubjectStore');
		Route::get('assign-subject-store', 'SmApiController@assignSubject_Create');
		Route::post('assign-subject', 'SmApiController@assignSubjectFind');
		Route::get('assign-subject-get-by-ajax', 'SmApiController@assignSubjectAjax');

		//Assign Class Teacher
		Route::resource('assign-class-teacher', 'api\ApiSmAssignClassTeacherControler');

		// Subject routes
		Route::get('subject', ['as' => 'subject', 'uses' => 'SmApiController@subject_index']);
		Route::post('subject-store', ['as' => 'subject_store', 'uses' => 'SmApiController@subject_store']);
		Route::get('subject-edit/{id}', ['as' => 'subject_edit', 'uses' => 'SmApiController@subject_edit']);
		Route::post('subject-update', ['as' => 'subject_update', 'uses' => 'SmApiController@subject_update']);
		Route::get('subject-delete/{id}', ['as' => 'subject_delete', 'uses' => 'SmApiController@subject_delete']);

		// Class route
		Route::get('class', ['as' => 'class', 'uses' => 'SmApiController@class_index']);
		Route::post('class-store', ['as' => 'class_store', 'uses' => 'SmApiController@class_store']);
		Route::get('class-edit/{id}', ['as' => 'class_edit', 'uses' => 'SmApiController@class_edit']);
		Route::post('class-update', ['as' => 'class_update', 'uses' => 'SmApiController@class_update']);
		Route::get('class-delete/{id}', ['as' => 'class_delete', 'uses' => 'SmApiController@class_delete']);

		//Class Section routes
		Route::get('section', ['as' => 'section', 'uses' => 'SmApiController@Section_index']);
		Route::post('section-store', ['as' => 'section_store', 'uses' => 'SmApiController@Section_store']);
		Route::get('section-edit/{id}', ['as' => 'section_edit', 'uses' => 'SmApiController@Section_edit']);
		Route::post('section-update', ['as' => 'section_update', 'uses' => 'SmApiController@Section_update']);
		Route::get('section-delete/{id}', ['as' => 'section_delete', 'uses' => 'SmApiController@Section_delete']);


		// Class room
		Route::resource('class-room', 'api\ApiSmClassRoomController');

		//class time
		Route::resource('class-time', 'api\ApiSmClassTimeController');


		//class routine
		Route::get('student-class-routine/{id}', 'SmApiController@class_Routine');
		//--------------- End Academic Module--------------


		//--------------- Start Homework Module--------------
		//homework list
		Route::get('homework-list', ['as' => 'homework-list', 'uses' => 'SmApiController@homeworkList']);
		Route::post('homework-list', ['as' => 'homework-list', 'uses' => 'SmApiController@searchHomework']);

		//--------------- End Homework Module--------------


		//--------------- Start Communicate Module --------------
		// Communicate
		Route::get('notice-list', 'SmApiController@noticeList');
		Route::get('send-message', 'SmApiController@sendMessage');
		Route::post('save-notice-data', 'SmApiController@saveNoticeData');
		Route::get('edit-notice/{id}', 'SmApiController@editNotice');
		Route::post('update-notice-data', 'SmApiController@updateNoticeData');
		Route::get('delete-notice-view/{id}', 'SmApiController@deleteNoticeView');
		Route::get('send-email-sms-view', 'SmApiController@sendEmailSmsView');
		Route::get('delete-notice/{id}', 'SmApiController@deleteNotice');

		//Event
		Route::resource('event', 'api\ApiSmEventController');
		Route::get('delete-event-view/{id}', 'SmApiController@deleteEventView');
		Route::get('delete-event/{id}', 'SmApiController@deleteEvent');

		//--------------- Start Communicate Module --------------


		//--------------- Start Library Module --------------

		// Book
		Route::get('book-list', 'SmApiController@Library_index');
		// Route::get('add-book', 'SmBookController@addBook');
		Route::get('save-book-data', 'SmApiController@saveBookData');
		Route::get('edit-book/{id}', 'SmApiController@editBook');
		Route::get('update-book-data/{id}', 'SmApiController@updateBookData');
		Route::get('delete-book-view/{id}', 'SmApiController@deleteBookView');
		Route::get('delete-book/{id}', 'SmApiController@deleteBook');
		Route::get('member-list', 'SmApiController@memberList');
		Route::get('issue-books/{member_type}/{id}', 'SmApiController@issueBooks');
		Route::get('save-issue-book-data', 'SmApiController@saveIssueBookData');
		Route::get('return-book-view/{id}', 'SmApiController@returnBookView');
		Route::get('return-book/{id}', 'SmApiController@returnBook');
		Route::get('all-issed-book', 'SmApiController@allIssuedBook');
		Route::get('search-issued-book', 'SmApiController@searchIssuedBook');
		Route::get('search-issued-book', 'SmApiController@all_IssuedBook');

		//library member
		Route::resource('library-member', 'api\ApiSmLibraryMemberController');
		Route::get('add-library-member', 'SmApiController@library_member_store');
		Route::get('library-member-role', 'SmApiController@member_role');
		Route::get('cancel-membership/{id}', 'SmApiController@cancelMembership');

		//--------------- End Library Module --------------


		//-----------------Start Inventory Module------------------------

		//Item Category
		Route::resource('item-category', 'api\ApiSmItemCategoryController');
		Route::get('delete-item-category-view/{id}', 'SmApiController@deleteItemCategoryView');
		Route::get('delete-item-category/{id}', 'SmApiController@deleteItemCategory');

		//Item List
		Route::resource('item-list', 'api\ApiSmItemController');
		Route::get('delete-item-view/{id}', 'SmApiController@deleteItemView');
		Route::get('delete-item/{id}', 'SmApiController@deleteItem');

		//Item Store
		Route::resource('item-store', 'api\ApiSmItemStoreController');
		Route::get('delete-store-view/{id}', 'SmApiController@deleteStoreView');
		Route::get('delete-store/{id}', 'SmApiController@deleteStore');

		//Supplier
		Route::resource('suppliers', 'api\ApiSmSupplierController');
		Route::get('delete-supplier-view/{id}', 'SmApiController@deleteSupplierView');
		Route::get('delete-supplier/{id}', 'SmApiController@deleteSupplier');

		//Issue Item
		Route::get('item-issue', 'SmApiController@itemIssueList');
		Route::post('save-item-issue-data', 'SmApiController@saveItemIssueData');
		Route::get('getItemByCategory', 'SmApiController@getItemByCategory');
		Route::get('return-item-view/{id}', 'SmApiController@returnItemView');
		Route::get('return-item/{id}', 'SmApiController@returnItem');
		//-----------------End Inventory Module------------------------


		//------------------Start Transport Module--------------

		//routes
		Route::resource('transport-route', 'api\ApiSmRouteController');

		//Vehicle
		Route::resource('vehicle', 'api\ApiSmSmVehicleController');

		//Assign Vehicle
		Route::resource('assign-vehicle', 'api\ApiSmAssignVehicleController');
		Route::post('assign-vehicle-delete', 'SmApiController@Assign_Vehicle_delete');

		// student transport report
		Route::get('student-transport-report', ['as' => 'student_transport_report', 'uses' => 'SmApiController@studentTransportReportApi']);

		//Route::get('student-transport-reportApi', ['as' => 'student_transport_report', 'uses' => 'SmTransportController@studentTransportReportApi']);


		Route::post('student-transport-report', ['as' => 'student_transport_report', 'uses' => 'SmApiController@studentTransportReportSearch']);
		//------------------End Transport Module--------------


		// ---------------Start Dormitory Module-----------------

		//Room list
		Route::resource('room-list', 'api\ApiSmRoomListController');

		//Room Type
		Route::resource('room-type', 'api\ApiSmRoomTypeController');

		//Dormitory List
		Route::resource('dormitory-list', 'api\ApiSmDormitoryListController');

		// Student Dormitory Report
		Route::get('student-dormitory-report', ['as' => 'student_dormitory_report', 'uses' => 'SmApiController@studentDormitoryReport']);
		Route::post('student-dormitory-report', ['as' => 'student_dormitory_report', 'uses' => 'SmApiController@studentDormitoryReportSearch']);

		// ---------------End Dormitory Module-----------------


		//------------- Start Report Module---------------------

		//Student Report
		Route::get('student-report', ['as' => 'student_report', 'uses' => 'SmApiController@studentReport']);
		Route::post('student-report', ['as' => 'student_report', 'uses' => 'SmApiController@studentReportSearch']);

		//guardian report
		Route::get('guardian-report', ['as' => 'guardian_report', 'uses' => 'SmApiController@guardianReport']);
		Route::post('guardian-report-search', ['as' => 'guardian_report_search', 'uses' => 'SmApiController@guardianReportSearch']);
		Route::get('guardian-report-search', ['as' => 'guardian_report_search', 'uses' => 'SmApiController@guardian_Report']);

		//Student history
		Route::get('student-history', ['as' => 'student_history', 'uses' => 'SmApiController@studentHistory']);
		Route::post('student-history-search', ['as' => 'student_history_search', 'uses' => 'SmApiController@studentHistorySearch']);
		Route::get('student-history-search', ['as' => 'student_history_search', 'uses' => 'SmApiController@student_History']);

		// student login report
		Route::get('student-login-report', ['as' => 'student_login_report', 'uses' => 'SmApiController@studentLoginReport']);
		Route::post('student-login-search', ['as' => 'student_login_search', 'uses' => 'SmApiController@studentLoginSearch']);
		Route::get('student-login-search', ['as' => 'student_login_search', 'uses' => 'SmApiController@student_Login_Report']);

		// student & parent reset password
		Route::post('reset-student-password', 'SmApiController@resetStudentPassword');

		//Fees Statement
		Route::get('fees-statement', ['as' => 'fees_statement', 'uses' => 'SmApiController@feesStatemnt']);
		Route::post('fees-statement-search', ['as' => 'fees_statement_search', 'uses' => 'SmApiController@feesStatementSearch']);

		// Balance fees report
		Route::get('balance-fees-report', ['as' => 'balance_fees_report', 'uses' => 'SmApiController@balanceFeesReport']);
		Route::post('balance-fees-search', ['as' => 'balance_fees_search', 'uses' => 'SmApiController@balanceFeesSearch']);
		Route::get('balance-fees-search', ['as' => 'balance_fees_search', 'uses' => 'SmApiController@balance_Fees_Report']);

		// Transaction Report
		Route::get('transaction-report', ['as' => 'transaction_report', 'uses' => 'SmApiController@transactionReport']);
		Route::post('transaction-report-search', ['as' => 'transaction_report_search', 'uses' => 'SmApiController@transactionReportSearch']);
		Route::get('transaction-report-search', ['as' => 'transaction_report_search', 'uses' => 'SmApiController@transaction_Report']);

		// Class Report
		Route::get('class-report', ['as' => 'class_report', 'uses' => 'SmApiController@classReport']);
		Route::post('class-report', ['as' => 'class_report', 'uses' => 'SmApiController@classReportSearch']);

		// class routine report
		Route::get('class-routine-report', ['as' => 'class_routine_report', 'uses' => 'SmApiController@classRoutineReport']);
		Route::post('class-routine-report', 'SmApiController@classRoutineReportSearch');

		// exam routine report
		Route::get('exam-routine-report', ['as' => 'exam_routine_report', 'uses' => 'SmApiController@examRoutineReport']);
		Route::post('exam-routine-report', ['as' => 'exam_routine_report', 'uses' => 'SmApiController@examRoutineReportSearch']);

		//teacher class routine report
		Route::get('teacher-class-routine-report', ['as' => 'teacher_class_routine_report', 'uses' => 'SmApiController@teacherClassRoutineReport']);
		Route::post('teacher-class-routine-report', 'SmApiController@teacherClassRoutineReportSearch');

		// merit list Report
		Route::get('merit-list-report', ['as' => 'merit_list_report', 'uses' => 'SmApiController@meritListReport']);
		Route::post('merit-list-report', ['as' => 'merit_list_report', 'uses' => 'SmApiController@meritListReportSearch']);

		// online exam report
		Route::get('online-exam-report', ['as' => 'online_exam_report', 'uses' => 'SmApiController@onlineExamReport']);
		Route::post('online-exam-report', ['as' => 'online_exam_report', 'uses' => 'SmApiController@onlineExamReportSearch']);

		//mark sheet report student
		Route::get('mark-sheet-report-student', ['as' => 'mark_sheet_report_student', 'uses' => 'SmApiController@markSheetReportStudent']);
		Route::post('mark-sheet-report-student', ['as' => 'mark_sheet_report_student', 'uses' => 'SmApiController@markSheetReportStudentSearch']);

		//mark sheet report student
		Route::get('mark-sheet-report-student', ['as' => 'mark_sheet_report_student', 'uses' => 'SmApiController@markSheetReport_Student']);
		Route::post('mark-sheet-report-student', ['as' => 'mark_sheet_report_student', 'uses' => 'SmApiController@markSheetReportStudent_Search']);

		// Tabulation Sheet Report
		Route::get('tabulation-sheet-report', ['as' => 'tabulation_sheet_report', 'uses' => 'SmApiController@tabulationSheetReport']);
		Route::post('tabulation-sheet-report', ['as' => 'tabulation_sheet_report', 'uses' => 'SmApiController@tabulationSheetReportSearch']);

		// progress card report
		Route::get('progress-card-report', ['as' => 'progress_card_report', 'uses' => 'SmApiController@progressCardReport']);
		Route::post('progress-card-report', ['as' => 'progress_card_report', 'uses' => 'SmApiController@progressCardReportSearch']);

		//student fine report
		Route::get('student-fine-report', ['as' => 'student_fine_report', 'uses' => 'SmApiController@studentFineReport']);
		Route::post('student-fine-report', ['as' => 'student_fine_report', 'uses' => 'SmApiController@studentFineReportSearch']);

		//user log
		Route::get('user-log', ['as' => 'user_log', 'uses' => 'SmApiController@userLog']);
		//------------- End Report Module---------------------


		//------------Start System Settings Module--------------

		//General Settings
		Route::get('general-settings', 'SmApiController@generalSettingsView');
		Route::get('update-general-settings', 'SmApiController@updateGeneralSettings');
		Route::post('update-general-settings-data', 'SmApiController@updateGeneralSettingsData');
		Route::post('update-school-logo', 'SmApiController@updateSchoolLogo');

		//Role Setup
		Route::get('system-role', ['as' => 'system-role', 'uses' => 'SmApiController@systemRole']);

		Route::get('role', ['as' => 'role', 'uses' => 'SmApiController@role_index']);
		Route::post('role-store', ['as' => 'role_store', 'uses' => 'SmApiController@role_store']);
		Route::get('role-edit/{id}', ['as' => 'role_edit', 'uses' => 'SmApiController@role_edit']);
		Route::post('role-update', ['as' => 'role_update', 'uses' => 'SmApiController@role_update']);
		Route::post('role-delete', ['as' => 'role_delete', 'uses' => 'SmApiController@role_delete']);

		// Role Permission
		Route::get('assign-permission/{id}', ['as' => 'assign_permission', 'uses' => 'SmApiController@assignPermission']);
		Route::post('role-permission-store', ['as' => 'role_permission_store', 'uses' => 'SmApiController@rolePermissionStore']);

		// Base group
		Route::get('base-group', ['as' => 'base_group', 'uses' => 'SmApiController@base_group_index']);
		Route::post('base-group-store', ['as' => 'base_group_store', 'uses' => 'SmApiController@base_group_store']);
		Route::get('base-group-edit/{id}', ['as' => 'base_group_edit', 'uses' => 'SmApiController@base_group_edit']);
		Route::post('base-group-update', ['as' => 'base_group_update', 'uses' => 'SmApiController@base_group_update']);
		Route::get('base-group-delete/{id}', ['as' => 'base_group_delete', 'uses' => 'SmApiController@base_group_delete']);

		//academic year
		Route::resource('academic-year', 'api\ApiSmAcademicYearController');

		//Session
		Route::resource('session', 'api\ApiSmSessionController');

		//Holiday
		Route::resource('holiday', 'api\ApiSmHolidayController');
		Route::get('delete-holiday-view/{id}', 'SmApiController@deleteHolidayView');
		Route::get('delete-holiday/{id}', 'SmApiController@deleteHoliday');

		//weekend
		Route::resource('weekend', 'api\ApiSmWeekendController');

		//------------End System Settings Module--------------


		//******************Start Student Panel ********************


		//------------Start Student Dashboard --------------
		Route::get('student-homework/{id}', 'SmApiController@studentHomework');
		Route::get('student-dashboard/{id}', 'SmApiController@studentDashboard');
		Route::get('student-my-attendance/{id}', 'SmApiController@studentMyAttendanceSearchAPI');
		Route::get('student-noticeboard/{id}', 'SmApiController@studentNoticeboard');
		//------------End Student Dashboard --------------


		//******************Start Student Panel ********************


		Route::get('studentSubject/{id}', 'SmApiController@studentSubjectApi');
		Route::get('student-library/{id}', 'SmApiController@studentLibrary');
		Route::get('studentTeacher/{id}', 'SmApiController@studentTeacherApi');

		Route::get('studentAssignment/{id}', 'SmApiController@studentAssignmentApi');
		Route::get('studentDocuments/{id}', 'SmApiController@studentsDocumentApi');

		Route::get('student-dormitory', 'SmApiController@studentDormitoryApi');

		Route::get('student-exam_schedule/{id}', 'SmApiController@studentExamScheduleApi');

		Route::get('student-timeline/{id}', 'SmApiController@studentTimelineApi');


		Route::get('student-online-exam/{id}', 'SmApiController@studentOnlineExamApi');
		Route::get('choose-exam/{id}', 'SmApiController@chooseExamApi');
		Route::get('online-exam-result/{id}/{exam_id}', 'SmApiController@examResultApi');
		Route::get('getGrades/{marks}', 'SmApiController@getGrades');


		//******************SYSTEM********************
		Route::get('getSystemVersion', 'SmApiController@getSystemVersion');
		Route::get('getSystemUpdate/{id}', 'SmApiController@getSystemUpdate');


		Route::get('exam-list/{id}', 'SmApiController@examListApi');
		Route::get('exam-schedule/{id}/{exam_id}', 'SmApiController@examScheduleApi');
		Route::get('exam-result/{id}/{exam_id}', 'SmApiController@examResult_Api');

		//Add new exam setup
		Route::get('new-exam-setup', 'SmApiController@NewExamSetup');
		Route::get('new-exam-schedule', 'SmApiController@NewExamSchedule');

		Route::any('change-password', 'SmApiController@updatePassowrdStoreApi');


		Route::get('child-list/{id}', 'SmApiController@childListApi');
		Route::get('child-info/{id}', 'SmApiController@childProfileApi');
		Route::get('child-fees/{id}', 'SmApiController@collectFeesChildApi');
		Route::get('child-class-routine/{id}', 'SmApiController@classRoutineApi');
		Route::get('child-homework/{id}', 'SmApiController@childHomework');

		Route::get('child-attendance/{id}', 'SmApiController@childAttendanceAPI');

		Route::get('childInfo/{id}', 'SmApiController@childInfo');

		Route::get('parent-about', 'SmApiController@aboutApi');


		//Route::get('parent-about', 'Parent\SmParentPanelController@aboutApi');


		//Teacher Api

		Route::any('search-student', 'SmApiController@searchStudent');
		// https://infixedu.com/api/search-student?class=2
		// https://infixedu.com/api/search-student?section=1&class=2
		// https://infixedu.com/api/search-student?name=Conner Stamm
		// https://infixedu.com/api/search-student?roll_no=28229
		Route::get('my-routine/{id}', 'SmApiController@myRoutine');
		Route::get('section-routine/{id}/{class}/{section}', 'SmApiController@sectionRoutine');
		Route::get('class-section/{id}', 'SmApiController@classSection');
		Route::get('subject/{id}', 'SmApiController@subjectsName');


		Route::get('teacher-class-list', 'SmApiController@teacherClassList');
		Route::get('teacher-section-list', 'SmApiController@teacherSectionList');



		Route::any('add-homework', 'SmApiController@addHomework');
		Route::get('homework-list/{id}', 'SmApiController@homework_List');
		Route::get('my-attendance/{id}', 'SmApiController@teacherMyAttendanceSearchAPI');
		Route::get('staff-leave-type', 'SmApiController@leaveTypeList');
		Route::any('staff-apply-leave', 'SmApiController@applyLeave');
		Route::get('staff-apply-list/{id}', 'SmApiController@staffLeaveList');
		// Route::get('upload-content-type', 'teacher\SmAcademicsController@contentType');
		Route::any('teacher-upload-content', 'SmApiController@uploadContent');
		Route::get('content-list', 'SmApiController@contentList');
		Route::get('delete-content/{id}', 'SmApiController@deleteContent');



		//Super Admin Api
		Route::get('pending-leave', 'SmApiController@pendingLeave');
		Route::get('approved-leave', 'SmApiController@approvedLeave');
		Route::get('reject-leave', 'SmApiController@rejectLeave');
		Route::any('staff-leave-apply', 'SmApiController@apply_Leave');
		Route::get('update-leave', 'SmApiController@updateLeave');

		Route::post('update-staff',  'SmApiController@UpdateStaffApi');
		Route::post('update-student',  'SmApiController@UpdateStudentApi');
		//Super Admin Student
		Route::any('set-token', 'SmApiController@setToken');

		Route::get('group-token', 'SmApiController@groupToken');
		//infixedu.com/android/api/group-token?id=2&body=Notification body&title=Notification title
		Route::get('notification-api', 'SmSystemSettingController@notificationApi');

		Route::get('flutter-group-token', 'SmApiController@flutterGroupToken');
		Route::get('flutter-notification-api', 'SmSystemSettingController@flutterNotificationApi');
		Route::get('homework-notification-api', 'SmApiController@HomeWorkNotification');

		Route::get('room-list', 'SmApiController@roomList');

		Route::get('room-type-list', 'SmApiController@roomTypeList');
		Route::post('room-list', 'SmApiController@storeRoom');
		Route::get('room-update', 'SmApiController@updateRoom');
		Route::get('room-delete/{id}', 'SmApiController@deleteRoom');

		Route::get('dormitory-list', 'SmApiController@dormitoryList');
		Route::get('add-dormitory', 'SmApiController@addDormitory');
		Route::get('edit-dormitory', 'SmApiController@editDormitory');
		Route::get('delete-dormitory/{id}', 'SmApiController@deleteDormitory');

		Route::get('driver-list', 'SmApiController@getDriverList');
		Route::get('student-attendance-check', 'SmApiController@studentAttendanceCheck');
		Route::get('student-attendance-store-first', 'SmApiController@studentAttendanceStoreFirst');
		Route::get('student-attendance-store-second', 'SmApiController@studentAttendanceStoreSecond');

		Route::get('book-category', 'SmApiController@bookCategory');
		//download file
		Route::get('download-content-document/{file_name}', 'SmApiController@DownloadContent');
		Route::get('download-complaint-document/{file_name}', 'SmApiController@DownloadComplaint');
		Route::get('download-visitor-document/{file_name}', 'SmApiController@DownloadVisitor');
		Route::get('postal-receive-document/{file_name}', 'SmApiController@DownloadPostal');
		Route::get('postal-dispatch-document/{file_name}', 'SmApiController@DownloadDispatch');


		// End Upload Content
		Route::post('custom-merit-list','CustomResultSettingController@meritListReport');

		Route::post('custom-progress-card','CustomResultSettingController@progressCardReport');
		Route::post('student-final-result','CustomResultSettingController@studentFinalResult');
		//User Info for demo

		Route::get('user-demo', 'SmApiController@DemoUser');
		Route::get('currency-converter', 'SmApiController@convertCurrency'); //api/currency-converter?amount=2&from_currency=USD&to_currency=BDT
		Route::any('student-fees-payment', 'SmApiController@studentFeesPayment');
	});
