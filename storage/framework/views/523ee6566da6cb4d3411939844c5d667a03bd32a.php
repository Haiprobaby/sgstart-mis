<?php $__env->startSection('mainContent'); ?>
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1><?php echo app('translator')->getFromJson('lang.currency'); ?></h1>
                <div class="bc-pages">
                    <a href="<?php echo e(url('dashboard')); ?>"><?php echo app('translator')->getFromJson('lang.dashboard'); ?></a>
                    <a href="#"><?php echo app('translator')->getFromJson('lang.currency'); ?></a>
                    <a href="#"><?php echo app('translator')->getFromJson('lang.manage'); ?> <?php echo app('translator')->getFromJson('lang.currency'); ?></a>

                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <?php if(isset($edit_languages)): ?>
                <div class="row">
                    <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                        <a href="<?php echo e(url('marks-grade')); ?>" class="primary-btn small fix-gr-bg">
                            <span class="ti-plus pr-2"></span>
                            <?php echo app('translator')->getFromJson('lang.add'); ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-lg-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h3 class="mb-30"><?php if(isset($edit_languages)): ?>
                                        <?php echo app('translator')->getFromJson('lang.edit'); ?>
                                    <?php else: ?>
                                        <?php echo app('translator')->getFromJson('lang.add'); ?>
                                    <?php endif; ?>
                                    <?php echo app('translator')->getFromJson('lang.currency'); ?>
                                </h3>
                            </div>
                            <?php if(isset($editData)): ?>
                                <?php echo e(Form::open(['class' => 'form-horizontal', 'files' => true, 'url' => 'currency-update', 'method' => 'POST', 'enctype' => 'multipart/form-data'])); ?>

                                <input type="hidden" name="id" value="<?php echo e(isset($editData)? @$editData->id: ''); ?>">
                            <?php else: ?>
                                <?php echo e(Form::open(['class' => 'form-horizontal', 'files' => true, 'url' => 'currency-store', 'method' => 'POST', 'enctype' => 'multipart/form-data'])); ?>

                            <?php endif; ?>
                            <div class="white-box">
                                <div class="add-visitor">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <?php if(session()->has('message-success')): ?>
                                                <div class="alert alert-success">
                                                    <?php echo e(session()->get('message-success')); ?>

                                                </div>
                                            <?php elseif(session()->has('message-danger')): ?>
                                                <div class="alert alert-danger">
                                                    <?php echo e(session()->get('message-danger')); ?>

                                                </div>
                                            <?php endif; ?>

                                        </div>
                                    </div>

                                    
                                    <div class="row mt-40"> 
                                        <div class="col-lg-12">
                                            <div class="input-effect">
                                                <input class="primary-input form-control<?php echo e($errors->has('name') ? ' is-invalid' : ''); ?>" type="text" name="name" autocomplete="off" value="<?php echo e(isset($editData)? @$editData->name: ''); ?>" maxlength="25" required>                                            
                                                <label><?php echo app('translator')->getFromJson('lang.name'); ?> <span>*</span></label>
                                                <span class="focus-border"></span>
                                                <?php if($errors->has('name')): ?>
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong><?php echo e($errors->first('name')); ?></strong>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-40"> 
                                        <div class="col-lg-12">
                                            <div class="input-effect">
                                                <input class="primary-input form-control<?php echo e($errors->has('code') ? ' is-invalid' : ''); ?>" type="text" name="code" autocomplete="off" value="<?php echo e(isset($editData)? @$editData->code: ''); ?>" maxlength="10" required>                                            
                                                <label><?php echo app('translator')->getFromJson('lang.code'); ?> <span>*</span></label>
                                                <span class="focus-border"></span>
                                                <?php if($errors->has('code')): ?>
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong><?php echo e($errors->first('code')); ?></strong>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-40"> 
                                        <div class="col-lg-12">
                                            <div class="input-effect">
                                                <input class="primary-input form-control<?php echo e($errors->has('symbol') ? ' is-invalid' : ''); ?>" type="text" name="symbol" autocomplete="off" value="<?php echo e(isset($editData)? @$editData->symbol: ''); ?>" maxlength="5" required>                                            
                                                <label><?php echo app('translator')->getFromJson('lang.symbol'); ?> <span>*</span></label>
                                                <span class="focus-border"></span>
                                                <?php if($errors->has('symbol')): ?>
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong><?php echo e($errors->first('symbol')); ?></strong>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
 

                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg">
                                                <span class="ti-check"></span>
                                                <?php if(isset($editData)): ?>
                                                    <?php echo app('translator')->getFromJson('lang.update'); ?>
                                                <?php else: ?>
                                                    <?php echo app('translator')->getFromJson('lang.save'); ?>
                                                <?php endif; ?>
                                                <?php echo app('translator')->getFromJson('lang.currency'); ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php echo e(Form::close()); ?>

                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-30"><?php echo app('translator')->getFromJson('lang.currency'); ?> <?php echo app('translator')->getFromJson('lang.list'); ?></h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                           


                            <table class="display school-table school-table-style" cellspacing="0" width="100%">


                                <thead>
                                <?php if(session()->has('message-success-delete') != "" ||session()->has('langChange')!= "" ||
                                session()->get('message-danger-delete') != ""): ?>
                                    <tr>
                                        <td colspan="6">
                                            <?php if(session()->has('message-success-delete')): ?>
                                                <div class="alert alert-success">
                                                    <?php echo e(session()->get('message-success-delete')); ?>

                                                </div>
                                            <?php elseif(session()->has('message-danger-delete')): ?>
                                                <div class="alert alert-danger">
                                                    <?php echo e(session()->get('message-danger-delete')); ?>

                                                </div>
                                            <?php endif; ?>
                                            <?php if(session()->has('langChange')): ?>
                                                <div class="alert alert-success">
                                                    <?php echo e(session()->get('langChange')); ?>

                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <th><?php echo app('translator')->getFromJson('lang.sl'); ?></th>
                                    <th><?php echo app('translator')->getFromJson('lang.name'); ?></th>
                                    <th><?php echo app('translator')->getFromJson('lang.code'); ?></th>
                                    <th><?php echo app('translator')->getFromJson('lang.symbol'); ?></th> 
                                    <th><?php echo app('translator')->getFromJson('lang.action'); ?></th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php $i=1;  ?>

                                <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($i++); ?>

                                        <td><?php echo e(@$value->name); ?></td>
                                        <td><?php echo e(@$value->code); ?></td>
                                        <td><?php echo e(@$value->symbol); ?></td> 
                                        <td>

                                        <div class="dropdown">
                                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                <?php echo app('translator')->getFromJson('lang.select'); ?>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                           
                                                <a class="dropdown-item" href="<?php echo e(route('currency_edit', [@$value->id])); ?>"><?php echo app('translator')->getFromJson('lang.edit'); ?></a>
                                          
                                                <a class="dropdown-item" data-toggle="modal" data-target="#deleteCurrency<?php echo e(@$value->id); ?>"  href="<?php echo e(url('manage-currency/delete', [@$value->id])); ?>"><?php echo app('translator')->getFromJson('lang.delete'); ?></a>
                                           
                                            </div>
                                        </div>
                                        </td>

                                            <div class="modal fade admin-query" id="deleteCurrency<?php echo e(@$value->id); ?>" >
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title"><?php echo app('translator')->getFromJson('lang.delete'); ?> <?php echo app('translator')->getFromJson('lang.currency'); ?></h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="text-center">
                                                                <h4><?php echo app('translator')->getFromJson('lang.are_you_sure_to_delete'); ?></h4>
                                                            </div>
                                                            <div class="mt-40 d-flex justify-content-between">
                                                                <button type="button" class="primary-btn tr-bg" data-dismiss="modal"><?php echo app('translator')->getFromJson('lang.cancel'); ?></button>
                                                                <a href="<?php echo e(url('manage-currency/delete', [@$value->id])); ?>" class="text-light">
                                                                <button class="primary-btn fix-gr-bg" type="submit"><?php echo app('translator')->getFromJson('lang.delete'); ?></button>
                                                                </a>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div> 
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('backEnd.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\Mis-Saigonstar\resources\views/backEnd/systemSettings/manageCurrency.blade.php ENDPATH**/ ?>