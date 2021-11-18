<?php
function percentual($numero, $perc)
{
    return ($numero / 100) * $perc;
}

function getDataAgora()
{
    $timezone = new DateTimeZone('America/Sao_Paulo');
    $dataAgora = new DateTime('now', $timezone);

    return $dataAgora;
}
