<?php
$i=0;
if (count($userMessageList)){
    foreach ($userMessageList as $userMessage){
        $claseTr = "odd";
        if ($i%2) $claseTr = "even";
?>
    <tr class="fila">
        <td width="5%"><input class="sch" type="checkbox"></td>
        		<td><?php echo $userMessage->getMessageDate();?></td>
		<td><?php echo $userMessage->getMessageText();?></td>

        <td width="10%" class="colAcciones">
            <div class="accionfila btnNavEdit pointer" title="Editar"   onclick='editar(<?php echo $userMessage->getMessageId();?>)'><i class="icon-pencil"></div>
            <div class="accionfila btnNavDel  pointer" title="Eliminar" onclick='eliminar(<?php echo $userMessage->getMessageId();?>)'></div>
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
