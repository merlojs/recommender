<?php
/* @var $performer Performer */
$i=0;
if (count($performerList)){
    foreach ($performerList as $performer){
        $claseTr = "odd";
        if ($i%2) $claseTr = "even";
?>
    <tr class="fila">
        <td><?php echo($performer->getPerson()->getPersonLastname().', '.$performer->getPerson()->getPersonFirstname()); ?></td>
        <td>
<?php
            switch ($performer->getPerformerType()) {
                case 'A':
                    echo('Actor');
                break;
                case 'D':
                    echo('Director');
                break;
            }
?>
        </td>
        <td class="colAcciones">
            <div class="accionfila btnNavEdit pointer" title="Editar"   onclick='editPerformer(<?php echo($performer->getPerformerId());?>)'><i class="icon-pencil"></div>
            <div class="accionfila btnNavDel  pointer" title="Eliminar" onclick='deletePerformer(<?php echo($performer->getPerformerId());?>)'></div>
        </td>
    </tr>
<?php
    $i++;
    }
}
?>
    
    
<input type="hidden" id="resultado_pagina" value="<?php echo $pagina;?>">
<input type="hidden" id="resultado_desde" value="<?php echo $desde;?>">
<input type="hidden" id="resultado_hasta" value="<?php echo $hasta;?>">
<input type="hidden" id="resultado_paginasTotales" value="<?php echo $paginasTotales;?>">
<input type="hidden" id="resultado_cantidadTotal" value="<?php echo $resultadosTotales;?>">
