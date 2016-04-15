<?php
$i=0;
/* @var $movieSeries MovieSeries */ 
if (count($movieSeriesList)){
    foreach ($movieSeriesList as $movieSeries){
        $claseTr = "odd";
        if ($i%2) $claseTr = "even";
?>
    <tr class="fila">
        <td><?php echo $movieSeries->getOriginalTitle();?></td>
        <td><?php echo $movieSeries->getMovieSeriesFlag();?></td>        
        <td><?php echo $movieSeries->getImdbLink();?></td>
        <td><?php echo $movieSeries->getYear();?></td>
<?php
        if ($movieSeries->getMovieSeriesFlag() == 'S'){
?>        
        <td><?php echo $movieSeries->getSeasons();?></td>
<?php  
        } else {
?>
        <td> - </td>
<?php
        }
?>
        <td width="10%" class="colAcciones">
            <div class="accionfila btnNavEdit pointer" title="Editar"   onclick='editar(<?php echo $movieSeries->getMovieSeriesId();?>)'></div>
            <div class="accionfila btnNavList pointer" title="Cast" onclick='editCast(<?php echo $movieSeries->getMovieSeriesId();?>)'></div>
<?php
    if ($_SESSION['profile'] == 'admin') {
?>            
            <div class="accionfila btnNavDel  pointer" title="Eliminar" onclick='eliminar(<?php echo $movieSeries->getMovieSeriesId();?>)'></div>
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
