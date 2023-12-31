<?php $__env->startSection('title'); ?>
Entrega de cheques
<?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('entrega_parcela.index')); ?>">Entrega de cheques</a></li>
        <li class="breadcrumb-item active" aria-current="page">Receber</li>
    </ol>
</nav>
<div class="d-flex justify-content-between">
    <h3>
        Cheques -
        <?php echo e($tipo == 'entregue_parceiro' ? $parceiro->pessoa->nome : $representante->pessoa->nome); ?>

    </h3>
    <?php if($tipo == 'entregue_representante'): ?>
        <div>
            <?php if (isset($component)) { $__componentOriginale7af6f5f93c3f23c2bd6667675861a3352692bb5 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\BotaoImprimir::class, []); ?>
<?php $component->withName('botao-imprimir'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['href' => ''.e(route('pdf_cheques_entregues', ['representante_id' => $representante->id, 'data_entrega' => $hoje])).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale7af6f5f93c3f23c2bd6667675861a3352692bb5)): ?>
<?php $component = $__componentOriginale7af6f5f93c3f23c2bd6667675861a3352692bb5; ?>
<?php unset($__componentOriginale7af6f5f93c3f23c2bd6667675861a3352692bb5); ?>
<?php endif; ?>
        </div>
    <?php endif; ?>
</div>
<form action="<?php echo e(route('entrega_parcela.store')); ?>" method="POST" id="formCheques">
    <?php echo csrf_field(); ?>
    <input type="hidden" name='tipo' value=<?php echo e($tipo); ?>>
    <?php if (isset($component)) { $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Table::class, []); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['id' => 'tabelaCheques']); ?>
        <?php if (isset($component)) { $__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\TableHeader::class, []); ?>
<?php $component->withName('table-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
            <tr>
                <th><input type="checkbox" id="selecionaTodos"></th>
                <th>Data</th>
                <th>Titular do Cheque</th>
                <th>Valor</th>
                <th>Número</th>
                <th>Status</th>
            </tr>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36)): ?>
<?php $component = $__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36; ?>
<?php unset($__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36); ?>
<?php endif; ?>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $cheques; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cheque): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td>
                    <input type="checkbox" name="cheque_id[]" value="<?php echo e($cheque->id); ?>">
                </td>
                <td><?php echo date('d/m/Y', strtotime($cheque->data_parcela)); ?></td>
                <td><?php echo e($cheque->nome_cheque); ?></td>
                <td><?php echo 'R$ ' . number_format($cheque->valor_parcela, 2, ',', '.'); ?></td>
                <td><?php echo e($cheque->numero_cheque); ?></td>
                <td><?php echo e($cheque->status); ?> <?php echo e($cheque->motivo); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan=7>Nenhum registro</td>
            </tr>
            <?php endif; ?>
        </tbody>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6)): ?>
<?php $component = $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6; ?>
<?php unset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6); ?>
<?php endif; ?>
    <?php if($tipo == 'entregue_representante'): ?>
        <div class="form-group">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="tipo_entrega" value="entregue_representante" id="radio1" checked>
                <label class="form-check-label" for="radio1">
                    Entregue em mãos
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="tipo_entrega" value="enviado_correio" id="radio2" >
                <label class="form-check-label" for="radio2">
                    Enviado por correio
                </label>
            </div>
        </div>  
        <div class="form-group" id='group_rastreio' style='display:none'>
            <label for="codigo_rastreio">Código de rastreio</label>
            <?php if (isset($component)) { $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Input::class, []); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'codigo_rastreio']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7)): ?>
<?php $component = $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7; ?>
<?php unset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7); ?>
<?php endif; ?>
        </div>
      
    <?php endif; ?>  
    
    
    <input class="btn btn-success" type="submit">

</form>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
    $("#tabelaCheques").DataTable({
        "lengthMenu": [ [-1], ["Todos"] ]
    });

    $("#selecionaTodos").click( (e) => {

        let status = $(e.target).prop("checked")

        $("input[name='cheque_id[]']").each( (index, element) => {
           $(element).prop("checked", status)
        });
    })

    $("#formCheques").submit( (event) => {
        event.preventDefault()

        let qtdCheques = $("input[name='cheque_id[]']:checked").length

        if (qtdCheques === 0) {
            Swal.fire({
                title: 'Erro!',
                text: 'Informe no mínimo um cheque',
                icon: 'error'
            })

            return
        }
        console.log($("input[name='cheque_id[]']:checked"));
        $("#formCheques")[0].submit()
    });

    $("#radio2").click( (e) => {
        $("#group_rastreio").toggle()
    })
    <?php if(Session::has('message')): ?>
        toastr["success"]("<?php echo e(Session::get('message')); ?>")
    <?php endif; ?>
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/entrega_parcela/receber_parceiro.blade.php ENDPATH**/ ?>