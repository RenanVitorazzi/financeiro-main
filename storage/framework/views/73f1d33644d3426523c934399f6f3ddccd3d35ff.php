<?php $__env->startSection('title'); ?>
Editar representante
<?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('representantes.index')); ?>">Representantes</a></li>
        <li class="breadcrumb-item active" aria-current="page">Editar</li>
    </ol>
</nav>

<form method="POST" action="<?php echo e(route('representantes.update', $representante->id)); ?>">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>
    <div class="card mb-2">
        <div class="card-body">
            <h5 class="card-title">Dados Gerais</h5>
            <div class="row">
                <div class="col-4">
                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.form-group','data' => ['name' => 'nome','value' => ''.e($representante->pessoa->nome).'']]); ?>
<?php $component->withName('form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'nome','value' => ''.e($representante->pessoa->nome).'']); ?>Nome <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="tipoCadastro">Tipo de cadastro</label>
                        <?php if (isset($component)) { $__componentOriginal9664ac210be45add4be058f3177c16028511e71a = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Select::class, []); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'tipoCadastro','required' => true]); ?>
                            <option value='Pessoa Física' <?php echo e(($representante->pessoa->tipoCadastro == 'Pessoa Física') ? 'selected' : ''); ?> > Pessoa Física</option>
                            <option value='Pessoa Jurídica' <?php echo e(($representante->pessoa->tipoCadastro == 'Pessoa Jurídica') ? 'selected' : ''); ?> > Pessoa Jurídica</option>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a)): ?>
<?php $component = $__componentOriginal9664ac210be45add4be058f3177c16028511e71a; ?>
<?php unset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a); ?>
<?php endif; ?>
                    </div>
                </div>
                <div class="col-4">
                    <div <?php echo e(($representante->pessoa->tipoCadastro == 'Pessoa Jurídica') ? 'style=display:none' : ''); ?> id='cpfGroup'>
                        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.form-group','data' => ['name' => 'cpf','value' => ''.e($representante->pessoa->cpf).'']]); ?>
<?php $component->withName('form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'cpf','value' => ''.e($representante->pessoa->cpf).'']); ?>CPF <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                    </div>
                    <div <?php echo e(($representante->pessoa->tipoCadastro == 'Pessoa Jurídica') ? '' : 'style=display:none'); ?> id='cnpjGroup'>
                        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.form-group','data' => ['name' => 'cnpj','value' => ''.e($representante->pessoa->cnpj).'']]); ?>
<?php $component->withName('form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'cnpj','value' => ''.e($representante->pessoa->cnpj).'']); ?>CPNJ <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                    </div>
                </div>
            </div> 
        </div> 
    </div> 

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Endereço</h5>
            <div class='row'>
                <div class="col-4">
                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.form-group','data' => ['name' => 'cep','value' => ''.e($representante->pessoa->cep).'']]); ?>
<?php $component->withName('form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'cep','value' => ''.e($representante->pessoa->cep).'']); ?>CEP <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    
                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.form-group','data' => ['name' => 'bairro','value' => ''.e($representante->pessoa->bairro).'']]); ?>
<?php $component->withName('form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'bairro','value' => ''.e($representante->pessoa->bairro).'']); ?>Bairro <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                </div>
                <div class="col-4">
                    <div class='form-group'>
                        <label for='estado'>Estado</label>
                        <?php if (isset($component)) { $__componentOriginal9664ac210be45add4be058f3177c16028511e71a = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Select::class, []); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'estado']); ?>
                            <option></option>
                            <option <?php echo e(($representante->pessoa->estado == 'AC') ? 'selected' : ''); ?> value="AC">Acre</option>
                            <option <?php echo e(($representante->pessoa->estado == 'AL') ? 'selected' : ''); ?> value="AL">Alagoas</option>
                            <option <?php echo e(($representante->pessoa->estado == 'AP') ? 'selected' : ''); ?> value="AP">Amapá</option>
                            <option <?php echo e(($representante->pessoa->estado == 'AM') ? 'selected' : ''); ?> value="AM">Amazonas</option>
                            <option <?php echo e(($representante->pessoa->estado == 'BA') ? 'selected' : ''); ?> value="BA">Bahia</option>
                            <option <?php echo e(($representante->pessoa->estado == 'CE') ? 'selected' : ''); ?> value="CE">Ceará</option>
                            <option <?php echo e(($representante->pessoa->estado == 'DF') ? 'selected' : ''); ?> value="DF">Distrito Federal</option>
                            <option <?php echo e(($representante->pessoa->estado == 'ES') ? 'selected' : ''); ?> value="ES">Espírito Santo</option>
                            <option <?php echo e(($representante->pessoa->estado == 'GO') ? 'selected' : ''); ?> value="GO">Goiás</option>
                            <option <?php echo e(($representante->pessoa->estado == 'MA') ? 'selected' : ''); ?> value="MA">Maranhão</option>
                            <option <?php echo e(($representante->pessoa->estado == 'MT') ? 'selected' : ''); ?> value="MT">Mato Grosso</option>
                            <option <?php echo e(($representante->pessoa->estado == 'MS') ? 'selected' : ''); ?> value="MS">Mato Grosso do Sul</option>
                            <option <?php echo e(($representante->pessoa->estado == 'MG') ? 'selected' : ''); ?> value="MG">Minas Gerais</option>
                            <option <?php echo e(($representante->pessoa->estado == 'PA') ? 'selected' : ''); ?> value="PA">Pará</option>
                            <option <?php echo e(($representante->pessoa->estado == 'PB') ? 'selected' : ''); ?> value="PB">Paraíba</option>
                            <option <?php echo e(($representante->pessoa->estado == 'PR') ? 'selected' : ''); ?> value="PR">Paraná</option>
                            <option <?php echo e(($representante->pessoa->estado == 'PE') ? 'selected' : ''); ?> value="PE">Pernambuco</option>
                            <option <?php echo e(($representante->pessoa->estado == 'PI') ? 'selected' : ''); ?> value="PI">Piauí</option>
                            <option <?php echo e(($representante->pessoa->estado == 'RJ') ? 'selected' : ''); ?> value="RJ">Rio de Janeiro</option>
                            <option <?php echo e(($representante->pessoa->estado == 'RN') ? 'selected' : ''); ?> value="RN">Rio Grande do Norte</option>
                            <option <?php echo e(($representante->pessoa->estado == 'RS') ? 'selected' : ''); ?> value="RS">Rio Grande do Sul</option>
                            <option <?php echo e(($representante->pessoa->estado == 'RO') ? 'selected' : ''); ?> value="RO">Rondônia</option>
                            <option <?php echo e(($representante->pessoa->estado == 'RR') ? 'selected' : ''); ?> value="RR">Roraima</option>
                            <option <?php echo e(($representante->pessoa->estado == 'SC') ? 'selected' : ''); ?> value="SC">Santa Catarina</option>
                            <option <?php echo e(($representante->pessoa->estado == 'SP') ? 'selected' : ''); ?> value="SP">São Paulo</option>
                            <option <?php echo e(($representante->pessoa->estado == 'SE') ? 'selected' : ''); ?> value="SE">Sergipe</option>
                            <option <?php echo e(($representante->pessoa->estado == 'TO') ? 'selected' : ''); ?> value="TO">Tocantins</option>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a)): ?>
<?php $component = $__componentOriginal9664ac210be45add4be058f3177c16028511e71a; ?>
<?php unset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a); ?>
<?php endif; ?>
                    </div>
                    
                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.form-group','data' => ['name' => 'logradouro','value' => ''.e($representante->pessoa->logradouro).'']]); ?>
<?php $component->withName('form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'logradouro','value' => ''.e($representante->pessoa->logradouro).'']); ?>Logradouro <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                
                </div>
            
                <div class="col-4">
                    <div class='form-group'>
                        <label for='municipio'>Município</label>
                        <?php if (isset($component)) { $__componentOriginal9664ac210be45add4be058f3177c16028511e71a = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Select::class, []); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'municipio']); ?>
                            <?php if($representante->pessoa->municipio): ?>
                                <option value="<?php echo e($representante->pessoa->municipio); ?>"><?php echo e($representante->pessoa->municipio); ?></option>
                            <?php endif; ?>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a)): ?>
<?php $component = $__componentOriginal9664ac210be45add4be058f3177c16028511e71a; ?>
<?php unset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a); ?>
<?php endif; ?>
                    </div>
                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.form-group','data' => ['name' => 'numero','value' => ''.e($representante->pessoa->numero).'']]); ?>
<?php $component->withName('form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'numero','value' => ''.e($representante->pessoa->numero).'']); ?>Número <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                </div>
            
            </div>
            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.form-group','data' => ['name' => 'complemento','value' => ''.e($representante->pessoa->complemento).'']]); ?>
<?php $component->withName('form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'complemento','value' => ''.e($representante->pessoa->complemento).'']); ?>Complemento <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
        </div>  
    </div>  

    <div class="card mt-2 mb-2">
        <div class="card-body">
            <h5 class="card-title">Contato</h5>
            <div class="row">
                <div class="col-4">
                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.form-group','data' => ['name' => 'telefone','value' => ''.e($representante->pessoa->telefone).'','placeholder' => '(00)0000-0000']]); ?>
<?php $component->withName('form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'telefone','value' => ''.e($representante->pessoa->telefone).'','placeholder' => '(00)0000-0000']); ?>
                        Telefone
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                </div>
                <div class="col-4">
                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.form-group','data' => ['name' => 'celular','value' => ''.e($representante->pessoa->celular).'','placeholder' => '(00)00000-0000']]); ?>
<?php $component->withName('form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'celular','value' => ''.e($representante->pessoa->celular).'','placeholder' => '(00)00000-0000']); ?>
                        Celular
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                </div>
                <div class="col-4">
                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.form-group','data' => ['name' => 'email','type' => 'email','value' => ''.e($representante->pessoa->email).'']]); ?>
<?php $component->withName('form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'email','type' => 'email','value' => ''.e($representante->pessoa->email).'']); ?>
                        E-mail
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.form-group','data' => ['name' => 'telefone2','value' => ''.e($representante->pessoa->telefone2).'','placeholder' => '(00)0000-0000']]); ?>
<?php $component->withName('form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'telefone2','value' => ''.e($representante->pessoa->telefone2).'','placeholder' => '(00)0000-0000']); ?>
                        Segundo Telefone
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                </div>
                <div class="col-4">
                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.form-group','data' => ['name' => 'celular2','value' => ''.e($representante->pessoa->celular2).'','placeholder' => '(00)00000-0000']]); ?>
<?php $component->withName('form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'celular2','value' => ''.e($representante->pessoa->celular2).'','placeholder' => '(00)00000-0000']); ?>
                        Segundo Celular
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <input type="submit" class='btn btn-success'>
</form>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script type="text/javascript" src="<?php echo e(asset('js1/cep.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/representante/edit.blade.php ENDPATH**/ ?>