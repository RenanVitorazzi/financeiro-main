<?php $__env->startSection('title'); ?>
Prorrogações
<?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
<div class='mb-2 d-flex justify-content-between'>
    <h3> Prorrogações </h3>
</div>
       
<?php if (isset($component)) { $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Table::class, []); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['id' => 'tabelaBalanco']); ?>
    <?php if (isset($component)) { $__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\TableHeader::class, []); ?>
<?php $component->withName('table-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
        <tr>
            <th>Titular</th>
            <th>Parceiro</th>
            <th>Representante</th>
            <th>Data</th>
            <th>Nova data</th>
            <th>Valor</th>
            <th>Juros</th>
            <th>Ações</th>
        </tr>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36)): ?>
<?php $component = $__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36; ?>
<?php unset($__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36); ?>
<?php endif; ?>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $prorrogacoes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prorrogacao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        
            <tr>
                <td><?php echo e($prorrogacao->parcelas->nome_cheque); ?></td>
                
                <td><?php echo e($parceiros->where('id', $prorrogacao->parcelas->parceiro_id)->first()->pessoa->nome ?? 'CARTEIRA'); ?></td>
                <td><?php echo e($representantes->where('id', $prorrogacao->parcelas->representante_id)->first()->pessoa->nome); ?></td>
                <td><?php echo date('d/m/Y', strtotime($prorrogacao->parcelas->data_parcela)); ?></td>
                <td><?php echo date('d/m/Y', strtotime($prorrogacao->nova_data)); ?></td>
                <td><?php echo 'R$ ' . number_format($prorrogacao->parcelas->valor_parcela, 2, ',', '.'); ?></td>
                <td><?php echo 'R$ ' . number_format($prorrogacao->juros_totais, 2, ',', '.'); ?></td>
                <td>
                    <?php if (isset($component)) { $__componentOriginalc7dfdfe339a23ddfcb22882c80952c28748ef247 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\BotaoExcluir::class, []); ?>
<?php $component->withName('botao-excluir'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['action' => ''.e(route('adiamentos.destroy', $prorrogacao->id)).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc7dfdfe339a23ddfcb22882c80952c28748ef247)): ?>
<?php $component = $__componentOriginalc7dfdfe339a23ddfcb22882c80952c28748ef247; ?>
<?php unset($__componentOriginalc7dfdfe339a23ddfcb22882c80952c28748ef247); ?>
<?php endif; ?>
                    
                </td>
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
    const TAXA = 3
    const MODAL = $("#modal")
    const MODAL_BODY = $("#modal-body")

    $(".btn-adiar").each( (index, element) => {
        $(element).click( () => {
            adiarCheque(element)
        })
    });

    function adiarCheque(element) {

        let data = $(element).data()
        console.log(data);
        let novaData = addDays(data.dia, 15)
        let jurosNovos = calcularNovosJuros(element, 15)
        
        // let jurosAntigos = 0;
        let jurosTotais = parseFloat(jurosNovos)/* + parseFloat(jurosAntigos)*/
        MODAL.modal("show")
        
        $("#modal-title").html("Prorrogação")
        
        MODAL_BODY.html(`
            <form id="formAdiamento" action="<?php echo e(route('adiamentos.store')); ?>"> 
                <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
                <p>Titular: <b>${data.nome}</b></p>
                <p>Valor do cheque: <b>${data.valor}</b></p>
                <p>Data: <b>${data.dia}</b></p>
                
                <p>Dias adiados: <b><span id="diasAdiados">15</span></b></p>
                
                <div class="form-group">
                    <label for="nova_data">Informe a nova data</label>
                    <?php if (isset($component)) { $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Input::class, []); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['type' => 'date','value' => '${novaData}','name' => 'nova_data']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7)): ?>
<?php $component = $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7; ?>
<?php unset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7); ?>
<?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="taxa_juros">Informe a taxa de juros (%)</label>
                    <?php if (isset($component)) { $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Input::class, []); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['type' => 'number','value' => '${TAXA.toFixed(2)}','name' => 'taxa_juros']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7)): ?>
<?php $component = $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7; ?>
<?php unset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7); ?>
<?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="juros_totais">Valor total de juros</label>
                    <?php if (isset($component)) { $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Input::class, []); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['readonly' => true,'type' => 'number','value' => '${(jurosTotais).toFixed(2)}','name' => 'juros_totais']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7)): ?>
<?php $component = $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7; ?>
<?php unset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7); ?>
<?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="observacao">Observação</label>
                    <textarea class="form-control" name="observacao" id="observacao"></textarea>
                </div>
            </form>
        `)

        $("#taxa_juros, #nova_data").change( () => {
            let dataNova = $("#nova_data").val()
            let diferencaDias = calcularDiferencaDias(data.dia, dataNova)

            let jurosNovos = calcularNovosJuros(element, diferencaDias)
            let jurosTotais = parseFloat(jurosNovos)

            $("#diasAdiados").html(diferencaDias)
            $("#juros_totais").val((jurosTotais).toFixed(2))
        })

        $(".modal-footer > .btn-primary").click( () => {
            let dataForm = $("#formAdiamento").serialize() 
                + "&parcela_data=" + data.dia 
                + "&parcela_id=" + data.id
                    
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: $('#formAdiamento').attr('action'),
                data: dataForm,
                dataType: 'json',
                beforeSend: () => {
                    swal.showLoading()
                },
                success: (response) => {
                    console.log(response);
                    Swal.fire({
                        title: response.title,
                        icon: response.icon,
                        text: response.text
                    })
                        
                    MODAL.modal("hide")
                },
                error: (jqXHR, textStatus, errorThrown) => {
        
                    var response = JSON.parse(jqXHR.responseText)
                    var errorString = ''
                    $.each( response.errors, function( key, value) {
                        errorString += '<div>' + value + '</div>'
                    });
            
                    Swal.fire({
                        title: 'Erro',
                        icon: 'error',
                        html: errorString
                    })
                }
            });
        })
    }

    function addDays (date, days) {
        var result = new Date(date)
        result.setDate(result.getDate() + days)
        return result.toISOString().slice(0,10)
    }

    function calcularNovosJuros (element, dias) {
        let taxa = $("#taxa_juros").val();
        let valor_cheque = $(element).data("valor")
        let porcentagem = taxa / 100 || TAXA / 100 ;
        
        return ( ( (valor_cheque * porcentagem) / 30 ) * dias).toFixed(2)
    }

    function calcularDiferencaDias (dataAntiga, dataNova) {
        let date1 = new Date(dataAntiga)
        let date2 = new Date(dataNova)
        if (date1.getTime() > date2.getTime()) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'A data de adiamento deve ser maior que a data do cheque!'
            })
        }
        
        const diffTime = Math.abs(date2 - date1)
        return Math.ceil(diffTime / (1000 * 60 * 60 * 24))
    }

    $(".form-resgate").submit( (e) => {
        e.preventDefault()
        console.log($(e.target));
        Swal.fire({
            title: 'Tem certeza de que deseja resgatar esse cheque?',
            icon: 'warning',
            showConfirmButton: true,
            showCancelButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                $(e.target)[0].submit()
            }
        })
    })
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/adiamento/index.blade.php ENDPATH**/ ?>