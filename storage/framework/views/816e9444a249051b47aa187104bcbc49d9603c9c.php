<?php $__env->startSection('title'); ?>
Editar vendas
<?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
        <?php if(!auth()->user()->is_representante): ?>
        <li class="breadcrumb-item"><a href="<?php echo e(route('representantes.index')); ?>">Representantes</a></li>
        <?php endif; ?>
        <li class="breadcrumb-item"><a href="<?php echo e(route('venda.show', $venda->representante_id)); ?>">Vendas</a></li>
        <li class="breadcrumb-item active" aria-current="page">Editar</li>
    </ol>
</nav>
    <form method="POST" action="<?php echo e(route('venda.update', $venda->id)); ?>" id='formUpdateVenda'>
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="row">
            <div class="col-md-6">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.form-group','data' => ['name' => 'data_venda','type' => 'date','autofocus' => true,'value' => ''.e($venda->data_venda).'']]); ?>
<?php $component->withName('form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'data_venda','type' => 'date','autofocus' => true,'value' => ''.e($venda->data_venda).'']); ?>Data <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
            </div>
            <div class="col-md-6 form-group">
                <label for="cliente_id">Cliente</label>
                <select name="cliente_id" id="cliente_id" class="form-control">
                    <option></option>
                    <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cliente->id); ?>" <?php echo e(old('cliente_id', $venda->cliente_id) == $cliente->id ? 'selected': ''); ?> >
                        <?php echo e($cliente->pessoa->nome); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            
            <div class="col-md-6 form-group">
                <label for="representante_id">Representante</label>
                <select name="representante_id" id="representante_id" class="form-control" required>
                    <option></option>
                    <?php $__currentLoopData = $representantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $representante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($representante->id); ?>"
                            <?php echo e(old('representante_id', $venda->representante_id) == $representante->id ? 'selected': ''); ?> >
                            <?php echo e($representante->pessoa->nome); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <?php if (isset($component)) { $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Table::class, []); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['class' => 'table-striped table-bordered table-dark']); ?>
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>Quantidade</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Peso</td>
                        <td><?php if (isset($component)) { $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Input::class, []); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['type' => 'number','name' => 'peso','step' => '0.001','value' => ''.e(old('peso', $venda->peso)).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7)): ?>
<?php $component = $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7; ?>
<?php unset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7); ?>
<?php endif; ?></td>
                        <td><?php if (isset($component)) { $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Input::class, []); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['type' => 'number','name' => 'cotacao_peso','step' => '0.01','value' => ''.e(old('cotacao_peso', $venda->cotacao_peso)).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7)): ?>
<?php $component = $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7; ?>
<?php unset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7); ?>
<?php endif; ?></td>
                    </tr>
                    <tr>
                        <td>Fator</td>
                        <td><?php if (isset($component)) { $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Input::class, []); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['type' => 'number','name' => 'fator','step' => '0.01','value' => ''.e(old('fator', $venda->fator)).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7)): ?>
<?php $component = $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7; ?>
<?php unset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7); ?>
<?php endif; ?></td>
                        <td><?php if (isset($component)) { $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Input::class, []); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['type' => 'number','name' => 'cotacao_fator','step' => '0.01','value' => ''.e(old('cotacao_fator', $venda->cotacao_fator)).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7)): ?>
<?php $component = $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7; ?>
<?php unset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7); ?>
<?php endif; ?></td>
                    </tr>
                    <tr>
                        <td colspan='2'>Total</td>
                        <td><?php if (isset($component)) { $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Input::class, []); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'valor_total','type' => 'number','step' => '0.01','value' => ''.e(old('valor_total', $venda->valor_total)).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7)): ?>
<?php $component = $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7; ?>
<?php unset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7); ?>
<?php endif; ?></td>
                    </tr>
                </tbody>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6)): ?>
<?php $component = $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6; ?>
<?php unset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6); ?>
<?php endif; ?>

            <div class="col-sm-6 col-md-4 col-lg-3 form-group">
                <label for="metodo_pagamento">Método de Pagamento</label>
                <?php if (isset($component)) { $__componentOriginal9664ac210be45add4be058f3177c16028511e71a = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Select::class, []); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'metodo_pagamento','required' => true]); ?>
                    <option value=""></option>
                    <?php $__currentLoopData = $metodo_pagamento; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metodo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option <?php echo e($venda->metodo_pagamento == $metodo ? 'selected' : ''); ?> value="<?php echo e($metodo); ?>"><?php echo e($metodo); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a)): ?>
<?php $component = $__componentOriginal9664ac210be45add4be058f3177c16028511e71a; ?>
<?php unset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a); ?>
<?php endif; ?> 
            </div>
        </div> 
        
        <div id="infoCheques" class="row">
           
            <?php $__currentLoopData = $venda->parcela; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parcela): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-4">
                    <div class="card mb-4 card-hover">
                        <div class="card-body">
                            <input type="hidden" value='<?php echo e($parcela->id); ?>' name='parcela_id[<?php echo e($loop->index); ?>]'>
                            <h5 class="card-title mb-4"> 
                                <div class="d-flex justify-content-between">
                                    <div><?php echo e($loop->iteration); ?>ª Parcela</div>
                                </div>
                            </h5>
                            <div class='form-group'>
                                <label for="forma_pagamento[<?php echo e($loop->index); ?>]">Informe a forma de pagamento</label>
                                <?php if (isset($component)) { $__componentOriginal9664ac210be45add4be058f3177c16028511e71a = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Select::class, []); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['class' => 'form-control','name' => 'forma_pagamento['.e($loop->index).']','data-index' => ''.e($loop->index).'']); ?>
                                    <option value=""></option>
                                    <?php $__currentLoopData = $forma_pagamento; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $forma): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($forma); ?>" <?php echo e($forma == old('forma_pagamento.'.$loop->index, $parcela->forma_pagamento) ? 'selected' : ''); ?>>
                                            <?php echo e($forma); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a)): ?>
<?php $component = $__componentOriginal9664ac210be45add4be058f3177c16028511e71a; ?>
<?php unset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a); ?>
<?php endif; ?>
                                <?php $__errorArgs = ['forma_pagamento.'.$loop->index];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-inline">
                                        <?php echo e($message); ?>

                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group <?php echo e(old('forma_pagamento.'.$loop->index, $parcela->forma_pagamento) == 'Cheque' ? '' : 'd-none'); ?>" id="groupNome_<?php echo e($loop->index); ?>">
                                <label for="nome_cheque[<?php echo e($loop->index); ?>]">Nome</label>
                                <div class="d-flex">
                                    <?php if (isset($component)) { $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Input::class, []); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['type' => 'text','name' => 'nome_cheque['.e($loop->index).']','id' => 'nome_cheque['.e($loop->index).']','class' => 'form-control primeiroInputNome '.e($errors->has('nome_cheque.'.$loop->index) ? 'is-invalid' : '').'','value' => ''.e(old('nome_cheque.'.$loop->index, $parcela->nome_cheque)).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7)): ?>
<?php $component = $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7; ?>
<?php unset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7); ?>
<?php endif; ?>
                                    <?php $__errorArgs = ['nome_cheque.'.$loop->index];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-inline">
                                            <?php echo e($message); ?>

                                        </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="form-group <?php echo e(old('forma_pagamento.'.$loop->index, $parcela->forma_pagamento) == 'Cheque' ? '' : 'd-none'); ?>" id="groupBanco_<?php echo e($loop->index); ?>">
                                <label for="numero_banco[<?php echo e($loop->index); ?>]">Número do Banco</label>
                                <div class="d-flex">
                                    <?php if (isset($component)) { $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Input::class, []); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['type' => 'text','name' => 'numero_banco['.e($loop->index).']','id' => 'numero_banco['.e($loop->index).']','class' => 'form-control '.e($errors->has('numero_banco.'.$loop->index) ? 'is-invalid' : '').'','value' => ''.e(old('numero_banco.'.$loop->index, $parcela->numero_banco)).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7)): ?>
<?php $component = $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7; ?>
<?php unset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7); ?>
<?php endif; ?>
                                    <?php $__errorArgs = ['numero_banco.'.$loop->index];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-inline">
                                            <?php echo e($message); ?>

                                        </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="form-group <?php echo e(old('forma_pagamento.'.$loop->index, $parcela->forma_pagamento) == 'Cheque' ? '' : 'd-none'); ?>" id="groupNumero_<?php echo e($loop->index); ?>">
                                <label for="numero_cheque[<?php echo e($loop->index); ?>]">Número do Cheque</label>
                                <div class="d-flex">
                                    <?php if (isset($component)) { $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Input::class, []); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['type' => 'text','name' => 'numero_cheque['.e($loop->index).']','id' => 'numero_cheque['.e($loop->index).']','class' => 'form-control '.e($errors->has('numero_cheque.'.$loop->index) ? 'is-invalid' : '').'','value' => ''.e(old('numero_cheque.'.$loop->index, $parcela->numero_cheque)).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7)): ?>
<?php $component = $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7; ?>
<?php unset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7); ?>
<?php endif; ?>
                                    <?php $__errorArgs = ['numero_cheque.'.$loop->index];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-inline">
                                            <?php echo e($message); ?>

                                        </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="data_parcela[<?php echo e($loop->index); ?>]">Data da Parcela</label>
                                <?php if (isset($component)) { $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Input::class, []); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['type' => 'date','name' => 'data_parcela['.e($loop->index).']','id' => 'data_parcela['.e($loop->index).']','class' => 'form-control '.e($errors->has('data_parcela.'.$loop->index) ? 'is-invalid' : '').'','value' => ''.e(old('data_parcela.'.$loop->index, $parcela->data_parcela)).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7)): ?>
<?php $component = $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7; ?>
<?php unset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7); ?>
<?php endif; ?>
                                <?php $__errorArgs = ['data_parcela.'.$loop->index];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-inline">
                                        <?php echo e($message); ?>

                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group">
                                <label for="valor_parcela[<?php echo e($loop->index); ?>]">Valor</label>
                                <?php if (isset($component)) { $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Input::class, []); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['type' => 'number','name' => 'valor_parcela['.e($loop->index).']','id' => 'valor_parcela['.e($loop->index).']','class' => 'form-control primeiroInputValor '.e($errors->has('valor_parcela.'.$loop->index) ? 'is-invalid' : '').'','value' => ''.e(old('valor_parcela.'.$loop->index, $parcela->valor_parcela)).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7)): ?>
<?php $component = $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7; ?>
<?php unset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7); ?>
<?php endif; ?>
                                <?php $__errorArgs = ['valor_parcela.'.$loop->index];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-inline">
                                        <?php echo e($message); ?>

                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            
                            <div class="form-group">
                                <label for="status[<?php echo e($loop->index); ?>]">Status</label>
                                <?php if (isset($component)) { $__componentOriginal9664ac210be45add4be058f3177c16028511e71a = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Select::class, []); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'status['.e($loop->index).']','id' => 'status['.e($loop->index).']','class' => ''.e($errors->has('status.'.$loop->index) ? 'is-invalid' : '').'']); ?>
                                    <option value=""></option>
                                    <?php if(old('forma_pagamento.'.$loop->index, $parcela->forma_pagamento) == 'Cheque'): ?>
                                        <option value="Aguardando" <?php echo e(old('status.'.$loop->index, $parcela->status) == 'Aguardando' ? 'selected' : ''); ?>>Aguardando Depósito</option>
                                        <option value="Aguardando Envio" <?php echo e(old('status.'.$loop->index, $parcela->status) == 'Aguardando Envio' ? 'selected' : ''); ?>>Aguardando Envio</option>
                                    <?php elseif(old('forma_pagamento.'.$loop->index, $parcela->forma_pagamento) == 'Pix' || old('forma_pagamento.'.$loop->index, $parcela->forma_pagamento) == 'Dinheiro'): ?>
                                        <option value="Pago">Pago</option>
                                    <?php elseif(old('forma_pagamento.'.$loop->index, $parcela->forma_pagamento) == 'Transferência Bancária'): ?>  
                                        <option value="Pago" <?php echo e(old('status.'.$loop->index, $parcela->status) == 'Pago' ? 'selected' : ''); ?>>Pago</option>
                                        <option value="Aguardando Pagamento" <?php echo e(old('status.'.$loop->index, $parcela->status) == 'Aguardando Pagamento' ? 'selected' : ''); ?>>Aguardando Pagamento</option>
                                    <?php endif; ?>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a)): ?>
<?php $component = $__componentOriginal9664ac210be45add4be058f3177c16028511e71a; ?>
<?php unset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a); ?>
<?php endif; ?>

                                <?php $__errorArgs = ['status.'.$loop->index];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-inline">
                                        <?php echo e($message); ?>

                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                
                            </div>
                            <div class="form-group">
                                <label for="recebido_representante[<?php echo e($loop->index); ?>]">Recebido pelo representante</label>
                                <input type="checkbox" name="recebido_representante[<?php echo e($loop->index); ?>]" id="recebido_representante[<?php echo e($loop->index); ?>]" class="form-control" value=1
                                <?php echo e(old('recebido_representante', $parcela->recebido_representante) == 1 ? 'checked' : ''); ?>>
                            </div>

                            <div class="form-group">
                                <label for="observacao[<?php echo e($loop->index); ?>]">Observação</label>
                                <?php if (isset($component)) { $__componentOriginalada24a059c331be0784ec187913c2ecfacd51890 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Textarea::class, []); ?>
<?php $component->withName('textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'observacao['.e($loop->index).']','id' => 'observacao['.e($loop->index).']','class' => 'form-control']); ?>
                                    <?php echo e(old('observacao.'.$loop->index, $parcela->observacao)); ?>

                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalada24a059c331be0784ec187913c2ecfacd51890)): ?>
<?php $component = $__componentOriginalada24a059c331be0784ec187913c2ecfacd51890; ?>
<?php unset($__componentOriginalada24a059c331be0784ec187913c2ecfacd51890); ?>
<?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
        </div>
       
        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul class='mt-2'>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
        <input type="submit" class='btn btn-success'>
    </form>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
    // $("#metodo_pagamento").change( (e) => {
    //     let metodo = $(e.target).val()

    //     if (metodo !== 'Cheque') {
    //         $("#campoQtdParcelas").html("");
    //         $("#infoCheques").html("");
    //         return false;
    //     }
    //     if (!$("#valor_total").val()) {
    //         return false;
    //     }

    //     $("#campoQtdParcelas").html(`
    //         <div class='form-group col-md-6'>
    //             <label for="parcelas">Informe o período de prazo</label>
    //             <input class="form-control" id="prazo" type="number" value=30>
    //         </div>
    //         <div class='form-group col-md-6'>
    //             <label for="parcelas">Informe a quantidade de parcelas</label>
    //             <input class="form-control" id="parcelas" type="number">
    //         </div>
    //     `)

    //     $("#parcelas").change ( (e) => {
    //         let parcelas = $(e.target).val()
    //         let prazo = $("#prazo").val()
    //         let dataVenda = $("#data_venda").val()
    //         let valorTotal = $("#valor_total").val()
    //         let proximaData;
    //         let campoValor;
    //         let html = "";

    //         if (valorTotal) {
    //             campoValor = valorTotal / parcelas
    //             campoValorTratado = campoValor.toFixed(2)
    //         }

    //         if (parcelas < 0 && parcelas > 9) {
    //             $("#cheques").html(`
    //                 <div class='alert alert-danger'>Número de parcelas não aceito ${parcelas}!</div>
    //             `);
    //             return false;
    //         } else if (!dataVenda) {
    //             $("#cheques").html(`
    //                 <div class='alert alert-danger'>Informe a data da venda!</div>
    //             `);
    //             return false;
    //         }
            
    //         for (let index = 0; index < parcelas; index++) {
    //             if (dataVenda && prazo) {
    //                 if (!proximaData) {
    //                     proximaData = addDays(dataVenda, prazo);
    //                 } else {
    //                     proximaData = addDays(proximaData, prazo);
    //                 }
    //             }
                
    //             html += `
    //                 <div class="col-md-2 form-group">
    //                     Cheque ${index+1}
    //                 </div>
    //                 <div class="col-md-5 form-group">
    //                     <input type="date" name="data_parcela[]" class="form-control" value="${proximaData}">
    //                 </div>
    //                 <div class="col-md-5 form-group">
                        
    //                     <input type="number" name="valor_parcela[]" class="form-control" value="${campoValorTratado}">
    //                 </div>
    //             `;

    //         }
    //         $("#infoCheques").html(html);
    //     })
    // })

    const FORMA_PAGAMENTO = ['Dinheiro', 'Cheque', 'Transferência Bancária', 'Pix']

    $("#cotacao_fator, #fator, #cotacao_peso, #peso").change( (e) => {
        let cotacao_fator = $("#cotacao_fator").val()
        let cotacao_peso = $("#cotacao_peso").val()
        let fator = $("#fator").val()
        let peso = $("#peso").val()

        calcularTotalVenda(cotacao_fator, cotacao_peso, fator, peso)

    })

    function addDays(date, days) {
        let arrDate = date.split("-")
        let daysFiltered = parseInt(days)

        var result = new Date(arrDate[0], arrDate[1]-1, arrDate[2])
        result.setDate(result.getDate() + daysFiltered);
    
        return result.getFullYear() + '-' 
        + (adicionaZero(result.getMonth()+1).toString()) + "-"
        + adicionaZero(result.getDate().toString());
    }

    function adicionaZero(numero){
        if (numero <= 9) 
            return "0" + numero;
        else
            return numero; 
    }

    function calcularTotalVenda (cotacao_fator, cotacao_peso, fator, peso) {
        if (!cotacao_fator || !fator || !peso || !cotacao_peso) {
            return false;
        }

        let totalFator = cotacao_fator * fator;
        let totalPeso = cotacao_peso * peso;
        let totalCompra = totalFator + totalPeso;
        // parseFloat(totalCompra);

        $("#valor_total").val(totalCompra)
    }

    function listenerFormaPagamentoParcela () {
        $("select[name^='forma_pagamento']").each( (index, element) => {
            $(element).change( (e) => {
                let select = $(e.target)
                let valorSelect = select.val()
                let indexSelect = $(e.target).data('index')

                if (valorSelect == 'Cheque') {
                    $("#groupNumero_" + indexSelect).removeClass('d-none')
                    $("#groupNome_" + indexSelect).removeClass('d-none')
                    $("#groupBanco_" + indexSelect).removeClass('d-none')
                    
                    listenerNomes()
                    tratarCampoStatus(indexSelect, valorSelect)
                } else if (valorSelect == 'Dinheiro' || valorSelect == 'Pix') {
                    $("#groupNumero_" + indexSelect).addClass('d-none')
                    $("#groupNome_" + indexSelect).addClass('d-none')
                    $("#groupBanco_" + indexSelect).addClass('d-none')
                    
                    tratarCampoStatus(indexSelect, valorSelect)
                } else if (valorSelect == 'Transferência Bancária') {
                    $("#groupNumero_" + indexSelect).addClass('d-none')
                    $("#groupNome_" + indexSelect).addClass('d-none')
                    $("#groupBanco_" + indexSelect).addClass('d-none')

                    tratarCampoStatus(indexSelect, valorSelect)
                }

            });
            
        })
    }
    listenerFormaPagamentoParcela()

    function tratarCampoStatus(index, valorSelect) {
        let opcoesDisponiveis = []
        let element = $("select[name^='status']:eq(" + index + ")")

        if (valorSelect == 'Cheque') {
            opcoesDisponiveis = {
                '': '',
                'Aguardando Depósito': 'Aguardando',
                'Aguardando Envio': 'Aguardando Envio' 
            }
        } else if (valorSelect == 'Pix' || valorSelect == 'Dinheiro') {
            opcoesDisponiveis = {
                'Pago': 'Pago'
            }
        } else if (valorSelect == 'Transferência Bancária') {
            opcoesDisponiveis = {
                '': '',
                'Pago': 'Pago', 
                'Aguardando Pagamento': 'Aguardando Pagamento'
            }
        }
        
        element.empty()

        $.each(opcoesDisponiveis, function(key,value) {
            element.append($("<option></option>")
            .attr("value", value).text(key));
        });
    }

    $("#formUpdateVenda").submit( (element) => {
        element.preventDefault();
        
        let valorTotalPago = 0;
        $("input[name^='valor_parcela']").each( (index, element) => {
            valorTotalPago += parseFloat($(element).val())
            // console.log({index, element})
        })

        let valorTotalPedido = parseFloat($("#valor_total").val())
        let form = element.target;

        if (valorTotalPago < valorTotalPedido) {
           
            Swal.fire({
                title: 'Atenção!',
                html: 
                    "O valor pago é <b>menor</b> que o valor do pedido!" + 
                    "<br> Valor total pago: " + moeda.format(valorTotalPago) + 
                    "<br> Valor total do pedido: " + moeda.format(valorTotalPedido) ,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                    return
                }
            })
        } else {
            form.submit();
        }
    })

    let moeda = Intl.NumberFormat('en-GB', {
        style: 'currency',
        currency: 'BRL',
    });

    function listenerNomes () {
        $(".titularCheque").focus( (e) => {
            $(e.target).autocomplete({
                minLength: 0,
                source: titularDoUltimoCheque,
                autoFocus: true,
            });
        })
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/venda/edit.blade.php ENDPATH**/ ?>