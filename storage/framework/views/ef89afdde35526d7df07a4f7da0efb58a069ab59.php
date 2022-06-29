<?php $__env->startSection('content'); ?>

<div>
    <form action="<?php echo e(route('management.createUser')); ?>" method="post">
        <?php echo csrf_field(); ?>
        <label for="new_user_name_id">新しいユーザーを追加：</label>
        <input type="text" placeholder="名前を入力してください" name="new_user_name" id="new_user_name_id">
        <button type="submit">OK</button>
    </form>
</div>

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
            <form action="<?php echo e(route('management.updateUserId')); ?>" method="post" id="idm-form">
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

<div>
    <form action="<?php echo e(route('management.createManualStartEndTime')); ?>" method="post">
        <?php echo csrf_field(); ?>
        <p>出勤退勤時間を追加する</p>

        <label for="name_manual">名前を選択</label>
        <select name="user_id" id="name_manual">
            <option value="" selected>---</option>
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <label for="datetime">日時を選択</label>
        <input type="datetime-local" name="datetime" id="datetime">

        <label for="start_end_manual">出退勤を選択</label>
        <select name="start_or_end" id="start_end_manual">
            <option value="" selected>---</option>

            <?php $__currentLoopData = $start_end; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($key); ?>"><?php echo e($value); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <button type="submit">OK</button>
    </form> 
</div>


<div>
    <form action="<?php echo e(route('export')); ?>" method="get">
        <?php echo csrf_field(); ?>
        <select name="selected_y_m">
            <option value="" selected>---</option>
            <?php $__currentLoopData = $exist_y_m; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y_m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($y_m->year_month); ?>"><?php echo e($y_m->year_month); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <button type="submit">選択した年月の勤怠データをダウンロード(.csv)</button>
    </form>
</div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/management.blade.php ENDPATH**/ ?>