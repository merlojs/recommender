<?php
/* @var $userMessage UserMessage */
$i=0;
if (count($userMessageList)){
    foreach ($userMessageList as $userMessage){
        $claseTr = "odd";
        if ($i%2) $claseTr = "even";
?>
    <tr class="fila">
        <td><?php echo $userMessage->getMessageDate();?></td>
        <td><?php echo $userMessage->getMessageText();?></td>
        <td><?php echo $userMessage->getMovieSeries()->getOriginalTitle();?></td>
        <td>
<?php
            if($userMessage->getReadFlag() == 0){
?>
                <div class="accionfila btnEnvClosed pointer" title="Mark as read" onclick='read(<?php echo $userMessage->getMessageId();?>)'></div>
<?php
            } else {
?>
                <div class="accionfila btnEnvOpen pointer" title="Read"></div>
<?php
            }
?>
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
