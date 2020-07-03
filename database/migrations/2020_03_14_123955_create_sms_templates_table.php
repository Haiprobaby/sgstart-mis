<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('sms_templates');
        Schema::create('sms_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('admission_pro')->nullable();
            $table->longText('student_admit')->nullable();
            $table->longText('login_disable')->nullable();
            $table->longText('exam_schedule')->nullable();
            $table->longText('exam_publish')->nullable();
            $table->longText('due_fees')->nullable();
            $table->longText('collect_fees')->nullable();
            $table->longText('stu_promote')->nullable();
            $table->longText('attendance_sms')->nullable();
            $table->longText('absent')->nullable();
            $table->longText('late_sms')->nullable();
            $table->longText('er_checkout')->nullable();
            $table->longText('st_checkout')->nullable();
            $table->longText('st_credentials')->nullable();
            $table->longText('staff_credentials')->nullable();
            $table->longText('holiday')->nullable();
            $table->longText('leave_app')->nullable();
            $table->longText('approve_sms')->nullable();
            $table->longText('birth_st')->nullable();
            $table->longText('birth_staff')->nullable();
            $table->longText('cheque_bounce')->nullable();
            $table->longText('l_issue_b')->nullable();
            $table->longText('re_issue_book')->nullable();
            $table->longText('sms_text')->nullable();
            $table->boolean('active_status')->default(1);
            $table->timestamps();
        });
        
        DB::table('sms_templates')->insert([
            [
                'admission_pro' => 'Dear parent |ParentName|, your child |StudentName| admission is in process.',
                'student_admit' => 'Dear parent |ParentName|, your child |StudentName| admission is completed You can login to your account using username:|Username| Password:|Password|',
                'login_disable' => 'hello world',
                'exam_schedule' => 'hello world',
                'exam_publish' => 'hello world',
                'due_fees' => 'Fee Due Reminder for your child |StudentName|. 
                                Dear Parent |ParentName|, please find the below fee summary.
                                Fee: Rs.|Fee|, Back dues 
                                Adjustment: Rs.|Adjustment|, 
                                Total: Rs.|Total|, 
                                Paid: Rs.|Paid|, 
                                Balance: Rs.|Balance|. 
                                Please ignore in case already paid.',
                'collect_fees' => 'Fee Due Reminder for your child |StudentName|. 
                                Dear Parent |ParentName|, please find the below fee summary.
                                Fee: Rs.|Fee|, Back dues 
                                Adjustment: Rs.|Adjustment|, 
                                Total: Rs.|Total|, 
                                Paid: Rs.|Paid|, 
                                Balance: Rs.|Balance|. 
                                Please ignore in case already paid.',
                'stu_promote' => 'hello world',
                'attendance_sms' => 'Dear Parent |ParentName|, your child |StudentName| came to the school at |time|',
                'absent' => 'Dear parent |ParentName|, your child |StudentName| is absent to the school on |AttendanceDate|',
                'late_sms' => 'hello world',
                'er_checkout' => 'hello world',
                'st_checkout' => 'Dear Parent |ParentName|, your child |StudentName| left the school at |time|',
                'st_credentials' => 'Dear parent |ParentName|, your child |StudentName| login details: username:|Username| Password:|Password|',
                'staff_credentials' => 'Dear staff |StaffName| your login details: username:|Username| Password:|Password|',
                'holiday' => 'This is to update you that |HolidayDate| is holiday due to |HolidayName|',
                'leave_app' => 'hello world',
                'approve_sms' => 'hello world',
                'birth_st' => 'Dear parent |ParentName|, Warm wishes to your child  |StudentName| on behalf of his/her birthday',
                'birth_staff' => 'hello world',
                'cheque_bounce' => 'Dear parent |ParentName|, the Cheque with no :|ChequeNo| for Rs.|FeePaid| received towards fee payment for your child :|StudentName| with receipt number:|ReceiptNo| has been Bounced',
                'l_issue_b' => 'Dear parent |ParentName|, Library book  is issued to your child |StudentName| studying in class: |ClassName| , section: |SectionName| with roll no:|RollNo| On |IssueDate| .Please find the details , Book Title: |BookTitle|, Book No: |BookNo|, Due Date: |DueDate|',
                're_issue_book' => 'Dear parent |ParentName|, Library book  is returned by your child |StudentName| studying in class: |ClassName| , section: |SectionName| with roll no:|RollNo| On |ReturnDate| .Please find the details , Book Title: |BookTitle|, Book No: |BookNo|, Issue Date: |IssueDate|, Due Date: |DueDate|',
                'sms_text' => 'hello world',
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_templates');
    }
}
