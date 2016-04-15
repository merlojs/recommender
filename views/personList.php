<?php
$i=0;
if (count($personList)){
    foreach ($personList as $person){
        $claseTr = "odd";
        if ($i%2) $claseTr = "even";
?>
    <tr class="fila">
        <td><?php echo $person->getPersonLastname();?></td>
        <td><?php echo $person->getPersonFirstname();?></td>
        <td><?php echo $person->getBirthDate();?></td>
	<td width="10%" class="colAcciones">
            <div class="accionfila btnNavEdit pointer" title="Editar"   onclick='editar(<?php echo $person->getPersonId();?>)'><i class="icon-pencil"></div>
<?php
    if ($_SESSION['profile'] == 'admin') {
?>            
            <div class="accionfila btnNavDel  pointer" title="Eliminar" onclick='eliminar(<?php echo $person->getPersonId();?>)'></div>
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
