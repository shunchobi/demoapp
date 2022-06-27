<?php $__env->startSection('content'); ?>

<?php if(count($non_registered_cards) > 0): ?>
<div>
    <table>
        <thead>
            <tr>
                <td>IDm Number</td>
                <td>Name</td>
            </tr>
        </thead>
        <tbody>
            <form action="<?php echo e(route('management.store')); ?>" method="post" id="idm-form">
            <?php echo csrf_field(); ?>
            <?php $__currentLoopData = $non_registered_cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td> 
                    <lavel name="selected_user_id[]" for="<?php echo e($card->idm); ?>"><?php echo e($card->idm); ?> <?php echo e($card->touched_at); ?></lavel> 
                </td>
                <td>
                    <input type="hidden" name="card_id[]" value="<?php echo e($card->id); ?>"/>
                    <select name="selected_user_id[]">
                        <option value="" selected>---</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        </form>
    </table>
    <button form="idm-form" type="submit">OK</button>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/management.blade.php ENDPATH**/ ?>