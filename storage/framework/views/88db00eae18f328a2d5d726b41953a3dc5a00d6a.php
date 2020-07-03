<?php $__env->startSection('mainContent'); ?>
<?php  $setting = App\SmGeneralSettings::find(1); if(!empty($setting->currency_symbol)){ $currency = $setting->currency_symbol; }else{ $currency = '$'; } ?>

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1><?php echo app('translator')->getFromJson('lang.search_fees_payment'); ?></h1>
            <div class="bc-pages">
                <a href="<?php echo e(url('dashboard')); ?>"><?php echo app('translator')->getFromJson('lang.dashboard'); ?></a>
                <a href="#"><?php echo app('translator')->getFromJson('lang.fees_collection'); ?></a>
                <a href="#"><?php echo app('translator')->getFromJson('lang.search_fees_payment'); ?></a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <?php if(session()->has('message-success') != ""): ?>
                    <?php if(session()->has('message-success')): ?>
                    <div class="alert alert-success">
                        <?php echo e(session()->get('message-success')); ?>

                    </div>
                    <?php endif; ?>
                <?php endif; ?>
                <div class="white-box">
                    <?php echo e(Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'fees_payment_search', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student'])); ?>

                        <div class="row">
                            <input type="hidden" name="url" id="url" value="<?php echo e(URL::to('/')); ?>">
                            <div class="col-lg-3 mt-30-md">
                                <select class="w-100 bb niceSelect form-control <?php echo e($errors->has('class') ? ' is-invalid' : ''); ?>" id="select_class" name="class">
                                    <option data-display="<?php echo app('translator')->getFromJson('lang.select_class'); ?> *" value=""><?php echo app('translator')->getFromJson('lang.select_class'); ?> *</option>
                                    <?php $__currentLoopData = @$classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($class->id); ?>"  <?php echo e(( old("class") == $class->id ? "selected":"")); ?>><?php echo e($class->class_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php if($errors->has('class')): ?>
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong><?php echo e($errors->first('class')); ?></strong>
                                </span>
                                <?php endif; ?>
                            </div>
                            <div class="col-lg-3 mt-30-md" id="select_section_div">
                                <select class="w-100 bb niceSelect form-control<?php echo e($errors->has('current_section') ? ' is-invalid' : ''); ?>" id="select_section" name="section">
                                    <option data-display="<?php echo app('translator')->getFromJson('lang.select_section'); ?> *" value=""><?php echo app('translator')->getFromJson('lang.select_section'); ?></option>
                                </select>
                                <?php if($errors->has('section')): ?>
                                <span class="invalid-feedback invalid-select d-block" role="alert">
                                    <strong><?php echo e($errors->first('section')); ?></strong>
                                </span>
                                <?php endif; ?>
                            </div>

                            <div class="col-lg-6 mt-30-md">
                                <div class="input-effect">
                                    <input class="primary-input form-control" type="text" name="keyword">
                                    <label><?php echo app('translator')->getFromJson('lang.search_by_name'); ?>, <?php echo app('translator')->getFromJson('lang.admission'); ?> <?php echo app('translator')->getFromJson('lang.no'); ?>, <?php echo app('translator')->getFromJson('lang.roll'); ?> <?php echo app('translator')->getFromJson('lang.no'); ?></label>
                                    <span class="focus-border"></span>
                                </div>
                            </div>
                                                            

                            <div class="col-lg-12 mt-20 text-right">
                                <button type="submit" class="primary-btn small fix-gr-bg">
                                    <span class="ti-search pr-2"></span>
                                    <?php echo app('translator')->getFromJson('lang.search'); ?>
                                </button>
                            </div>
                        </div>
                    <?php echo e(Form::close()); ?>

                </div>
            </div>
        </div>
        <?php if(@$fees_payments): ?>            
        <div class="row mt-40">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0"> <?php echo app('translator')->getFromJson('lang.payment_ID_Details'); ?></h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">

                        <table id="table_id" class="display school-table" cellspacing="0" width="100%">

                            <thead>
                                <tr>
                                    <th><?php echo app('translator')->getFromJson('lang.payment'); ?> <?php echo app('translator')->getFromJson('lang.id'); ?></th>
                                    <th><?php echo app('translator')->getFromJson('lang.date'); ?></th>
                                    <th><?php echo app('translator')->getFromJson('lang.name'); ?></th>
                                    <th><?php echo app('translator')->getFromJson('lang.class'); ?></th>
                                    <th><?php echo app('translator')->getFromJson('lang.fees_group'); ?></th>
                                    <th><?php echo app('translator')->getFromJson('lang.fees_type'); ?></th>
                                    <th><?php echo app('translator')->getFromJson('lang.mode'); ?></th>
                                    <th><?php echo app('translator')->getFromJson('lang.amount'); ?> (<?php echo e($currency); ?>) </th>
                                    <th><?php echo app('translator')->getFromJson('lang.discount'); ?> (<?php echo e($currency); ?>) </th>
                                    <th><?php echo app('translator')->getFromJson('lang.fine'); ?> (<?php echo e($currency); ?>) </th>
                                    <th><?php echo app('translator')->getFromJson('lang.action'); ?></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $__currentLoopData = $fees_payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fees_payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($fees_payment->id.'/'.$fees_payment->fees_type_id); ?></td>
                                    <td>
                                        <?php echo e(App\SmGeneralSettings::DateConvater($fees_payment->payment_date)); ?>

                                      
                                    <!-- <?php echo e($fees_payment->payment_date != ""? App\SmGeneralSettings::DateConvater($fees_payment->payment_date):''); ?> -->

                                    </td>
                                    <td><?php echo e($fees_payment->full_name!=""?$fees_payment->full_name:""); ?></td>
                                    <td>
                                        <?php echo e($fees_payment->class_name); ?>

                                    </td>
                                    <td><?php echo e($fees_payment->name !=""?$fees_payment->name: ""); ?></td>
                                    <td><?php echo e($fees_payment->fees_type_name!=""?$fees_payment->fees_type_name:""); ?></td>
                                    <td>
                                        <?php if($fees_payment->payment_mode == "C"): ?>
                                            <?php echo e('Cash'); ?>

                                        <?php elseif($fees_payment->payment_mode == "Cq"): ?>
                                            <?php echo e('Cheque'); ?>

                                        <?php else: ?>
                                            <?php echo e('DD'); ?>

                                        <?php endif; ?>
                                        
                                    </td>
                                    <td><?php echo e($fees_payment->amount); ?></td>
                                    <td><?php echo e($fees_payment->discount_amount); ?></td>
                                    <td><?php echo e($fees_payment->fine); ?></td>
                                    <td><div class="dropdown">
                                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                <?php echo app('translator')->getFromJson('lang.select'); ?>
                                            </button>

                                            <?php if(in_array(115, App\GlobalVariable::GlobarModuleLinks()) || Auth::user()->role_id == 1 ): ?>

                                           

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="<?php echo e(route('fees_collect_student_wise', [$fees_payment->student_id])); ?>"><?php echo app('translator')->getFromJson('lang.view'); ?></a>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backEnd.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\Mis-Saigonstar\resources\views/backEnd/feesCollection/search_fees_payment.blade.php ENDPATH**/ ?>