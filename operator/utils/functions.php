<?php

function formata_data($data)
{
    if ($data == '') {
        return '';
    }
    $d = explode('/', $data);

    return $d[2].'-'.$d[1].'-'.$d[0];
}
function data_volta($data)
{
    if ($data == '' || $data == '0000-00-00') {
        return '';
    }
    $d = explode('-', $data);

    return $d[2].'/'.$d[1].'/'.$d[0];
}
function hora($hora)
{ //Deixa a hora 20:00
  $h = explode(':', $hora);

    return $h[0].':'.$h[1];
}
function formatar($data)
{
    if ($data == '') {
        return '';
    }
    $d = explode('/', $data);

    return $d[2].'-'.$d[1].'-'.$d[0];
}
function diasemana($data)
{  // Traz o dia da semana para qualquer data informada
  $tmp = explode('-', $data);

    $diasemana = date('w', mktime(0, 0, 0, $tmp[1], $tmp[2], $tmp[0]));

    return $diasemana;
}
function m2h($mins)
{
    // Se os minutos estiverem negativos
  if ($mins < 0) {
      $min = abs($mins);
  } else {
      $min = $mins;
  }

  // Arredonda a hora
  $h = floor($min / 60);
    $m = ($min - ($h * 60)) / 100;
    $horas = $h + $m;

  // Matemática da quinta série
  // Detalhe: Aqui também pode se usar o abs()
  if ($mins < 0) {
      $horas *= -1;
  }

  // Separa a hora dos minutos
  $sep = explode('.', $horas);
    $h = $sep[0];
    if (empty($sep[1])) {
        $sep[1] = 00;
    }

    $m = $sep[1];

  // Aqui um pequeno artifício pra colocar um zero no final
  if (strlen($m) < 2) {
      $m = $m. 0;
  }

    return sprintf('%02d:%02d', $h, $m);
}
function h2m($hora)
{
    $tmp = explode(':', $hora);

    return $tmp[1] + ($tmp[0] * 60);
}

function getSemana($dia, $completo = 0)
{
    switch ($dia) {
    case 1:
      $r = 'SEG'; $comp = 'Segunda-feira'; break;
    case 2:
      $r = 'TER'; $comp = 'Terça-feira'; break;
    case 3:
      $r = 'QUA'; $comp = 'Quarta-feira'; break;
    case 4:
      $r = 'QUI'; $comp = 'Quinta-feira'; break;
    case 5:
      $r = 'SEX'; $comp = 'Sexta-feira'; break;
    case 6:
      $r = 'SAB'; $comp = 'Sábado'; break;
    case 7:
      $r = 'DOM'; $comp = 'Domingo'; break;
  }
    if ($completo == 1) {
        return $comp;
    } else {
        return $r;
    }
}
function getSemana2($dia, $completo = 0)
{
    switch ($dia) {
    case 1:
      $r = 'Seg'; $comp = 'Segunda-feira'; break;
    case 2:
      $r = 'Ter'; $comp = 'Terça-feira'; break;
    case 3:
      $r = 'Qua'; $comp = 'Quarta-feira'; break;
    case 4:
      $r = 'Qui'; $comp = 'Quinta-feira'; break;
    case 5:
      $r = 'Sex'; $comp = 'Sexta-feira'; break;
    case 6:
      $r = 'Sab'; $comp = 'Sábado'; break;
    case 7:
      $r = 'Dom'; $comp = 'Domingo'; break;
  }
    if ($completo == 1) {
        return $comp;
    } else {
        return $r;
    }
}

function getDiaSemana($dia, $completo = 0)
{
    switch ($dia) {
    case 1:
      $r = 'Dom'; $comp = 'Domingo'; break;
    case 2:
      $r = 'Seg'; $comp = 'Segunda-feira'; break;
    case 3:
      $r = 'Ter'; $comp = 'Terça-feira'; break;
    case 4:
      $r = 'Qua'; $comp = 'Quarta-feira'; break;
    case 5:
      $r = 'Qui'; $comp = 'Quinta-feira'; break;
    case 6:
      $r = 'Sex'; $comp = 'Sexta-feira'; break;
    case 7:
      $r = 'Sab'; $comp = 'Sábado'; break;
  }
    if ($completo == 1) {
        return $comp;
    } else {
        return $r;
    }
}

function hoje($data)
{
    $dt = explode('/', $data);

    return getSemana(date('N', mktime(0, 0, 0, $dt[1], $dt[0], intval($dt[2]))), 1);
}
function timeDiff($firstTime, $lastTime)
{
    $firstTime = strtotime($firstTime);
    $lastTime = strtotime($lastTime);
    $timeDiff = $lastTime - $firstTime;

    return $timeDiff;
}
function separa_hora($hora, $op)
{ //$op = minutos = 1; hora = 0
  $hr = explode(':', $hora);

    return $hr[$op];
}
function dataExtenso($dt)
{
    $da = explode('/', $dt);

    return $da[0].' de '.getMes($da[1]).' de '.$da[2];
}
function dataExtensoTimeline($dt)
{
    $da = explode('/', $dt);
    $diasemana = date('w', mktime(0, 0, 0, $da[1], $da[0], $da[2]));

    return getSemana2($diasemana, 0).'  '.getMes2($da[1]).'  '.$da[0].' '.$da[2];
}
function getMes($m)
{
    switch ($m) {
    case 1: $mes = 'Janeiro'; break;
    case 2: $mes = 'Fevereiro'; break;
    case 3: $mes = 'Março'; break;
    case 4: $mes = 'Abril'; break;
    case 5: $mes = 'Maio'; break;
    case 6: $mes = 'Junho'; break;
    case 7: $mes = 'Julho'; break;
    case 8: $mes = 'Agosto'; break;
    case 9: $mes = 'Setembro'; break;
    case 10: $mes = 'Outubro'; break;
    case 11: $mes = 'Novembro'; break;
    case 12: $mes = 'Dezembro'; break;
  }

    return $mes;
}
function getMes2($m)
{
    switch ($m) {
    case 1: $mes = 'Jan'; break;
    case 2: $mes = 'Fev'; break;
    case 3: $mes = 'Mar'; break;
    case 4: $mes = 'Abr'; break;
    case 5: $mes = 'Mai'; break;
    case 6: $mes = 'Jun'; break;
    case 7: $mes = 'Jul'; break;
    case 8: $mes = 'Ago'; break;
    case 9: $mes = 'Set'; break;
    case 10: $mes = 'Out'; break;
    case 11: $mes = 'Nov'; break;
    case 12: $mes = 'Dez'; break;
  }

    return $mes;
}

/**
 * retorno o dia atual da semana por extenso.
 *
 * @param $day
 * @param $complete
 *
 * @author Jaisson Santos <jaissonssantos@gmail.com>
 *
 * @return string
 */
function getDayWeek($day, $complete = 0)
{
    switch (intval($day)) {
    case 0:
      $r = 'Dom'; $comp = 'Domingo'; break;
    case 1:
      $r = 'Seg'; $comp = 'Segunda-feira'; break;
    case 2:
      $r = 'Ter'; $comp = 'Terça-feira'; break;
    case 3:
      $r = 'Qua'; $comp = 'Quarta-feira'; break;
    case 4:
      $r = 'Qui'; $comp = 'Quinta-feira'; break;
    case 5:
      $r = 'Sex'; $comp = 'Sexta-feira'; break;
    case 6:
      $r = 'Sab'; $comp = 'Sábado'; break;
  }
    if ($complete == 1) {
        return $comp;
    } else {
        return $r;
    }
}

/**
 * retorno o dia atual da semana por extenso.
 *
 * @param $data (date): data no padrão de banco de dados(Ex.: 2016-01-01 10:00:00)
 *
 * @author Jaisson Santos <jaissonssantos@gmail.com>
 *
 * @return string
 */
function today($data)
{
    return getDayWeek(date('w', strtotime($data)), 1);
}

/**
 * retorno de data por extenso.
 *
 * @param $date (date): data no padrão de banco de dados(Ex.: 2016-01-01 10:00:00)
 *
 * @author Jaisson Santos <jaissonssantos@gmail.com>
 *
 * @return string
 */
function todayextensive($date)
{
    $dta = explode('-', $date);
    $year = $dta[0];
    $month = $dta[1];
    $day = $dta[2];
    if (strlen($day) > 4) {
        $datetime = explode(':', $day);
        $hour = substr($datetime[0], 3, 5);
        $minute = $datetime[1];
        $day = substr($day, 0, 2);

        $result = $day.' de '.getMes($month).' de '.$year.' às '.$hour.'h'.$minute.'min';
    } else {
        $result = $day.' de '.getMes($month).' de '.$year;
    }

    return $result;
}

function colocaAcentoMaiusculo($texto)
{
    $array1 = array('á', 'à', 'â', 'ã', 'ä', 'é', 'è', 'ê', 'ë', 'í', 'ì', 'î', 'ï', 'ó', 'ò', 'ô', 'õ', 'ö', 'ú', 'ù', 'û', 'ü', 'ç');

    $array2 = array('Á', 'À', 'Â', 'Ã', 'Ä', 'É', 'È', 'Ê', 'Ë', 'Í', 'Ì', 'Î', 'Ï', 'Ó', 'Ò', 'Ô', 'Õ', 'Ö', 'Ú', 'Ù', 'Û', 'Ü', 'Ç');

    return str_replace($array1, $array2, $texto);
}

function retira_acentos($texto)
{
    $array1 = array('á', 'à', 'â', 'ã', 'ä', 'é', 'è', 'ê', 'ë', 'í', 'ì', 'î', 'ï', 'ó', 'ò', 'ô', 'õ', 'ö', 'ú', 'ù', 'û', 'ü', 'ç', 'Á', 'À', 'Â', 'Ã', 'Ä', 'É', 'È', 'Ê', 'Ë', 'Í', 'Ì', 'Î', 'Ï', 'Ó', 'Ò', 'Ô', 'Õ', 'Ö', 'Ú', 'Ù', 'Û', 'Ü', 'Ç');
    $array2 = array('a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'c', 'A', 'A', 'A', 'A', 'A', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'C');

    return str_replace($array1, $array2, $texto);
}
// Cria uma função que retorna o timestamp de uma data no formato DD/MM/AAAA
function geraTimestamp($data)
{
    $partes = explode('/', $data);

    return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
}

function calculaDiferencaDatas($data_inicial, $data_final)
{
    // Usa a função criada e pega o timestamp das duas datas:
$time_inicial = geraTimestamp($data_inicial);
    $time_final = geraTimestamp($data_final);

// Calcula a diferença de segundos entre as duas datas:
$diferenca = $time_final - $time_inicial; // 19522800 segundos

// Calcula a diferença de dias
$dias = (int) floor($diferenca / (60 * 60 * 24)); // 225 dias

// Exibe uma mensagem de resultado:
//echo "A diferença entre as datas ".$data_inicial." e ".$data_final." é de <strong>".$dias."</strong> dias";
  return $dias;
}
function apelidometadatos($variavel)
{
    /*$a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ ,;:./';
  $b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr______';
  //$string = utf8_decode($string);
  $string = strtr($string, utf8_decode($a), $b); //substitui letras acentuadas por "normais"
  $string = str_replace(" ","",$string); // retira espaco
  $string = strtolower($string); // passa tudo para minusculo*/
  $string = strtolower(ereg_replace('[^a-zA-Z0-9-]', '-', strtr(utf8_decode(trim($variavel)), utf8_decode('áàãâéêíóôõúüñçÁÀÃÂÉÊÍÓÔÕÚÜÑÇ'), 'aaaaeeiooouuncAAAAEEIOOOUUNC-')));

    return utf8_encode($string); //finaliza, gerando uma saída para a funcao
}

function getExtensaoArquivo($extensao)
{
    switch ($extensao) {
    case 'image/jpeg':  $ext = '.jpeg'; break;
    case 'image/jpg':   $ext = '.jpg'; break;
    case 'image/pjpeg': $ext = '.pjpg'; break;
    case 'image/JPEG':  $ext = '.JPEG'; break;
    case 'image/gif':   $ext = '.gif'; break;
    case 'image/png':   $ext = '.png'; break;
    case 'video/webm':  $ext = '.webm'; break;
    case 'video/mp4':   $ext = '.mp4'; break;
    case 'video/flv':   $ext = '.flv'; break;
    case 'video/webm':   $ext = '.webm'; break;
    case 'audio/mp4':   $ext = '.acc'; break;
    case 'audio/mpeg':   $ext = '.mp3'; break;
    case 'audio/ogg':   $ext = '.ogg'; break;
  }

    return $ext;
}

function uploadArquivoPermitido($arquivo)
{
    $tiposPermitidos = array('image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png', 'video/webm', 'video/mp4', 'video/ogv', 'audio/mp3', 'audio/mp4', 'audio/mpeg', 'audio/ogg');
    if (array_search($arquivo, $tiposPermitidos) === false) {
        return false;
    } else {
        return true;
    }//end if
}

function converteValorMonetario($valor)
{
    $valor = str_replace('.', '', $valor);
    $valor = str_replace('.', '', $valor);
    $valor = str_replace('.', '', $valor);
    $valor = str_replace(',', '.', $valor);

    return $valor;
}

function valorMonetario($valor)
{
    $valor = number_format($valor, 2, ',', '.');

    return $valor;
}

//função para retornar o ID do STATUS do BD referente as transações e documentações de pagamento do Mercado Pago
function getIdStatusPagamentoPlano($status)
{
    switch ($status) {
    case 'pending':     $s = 4; break; //Aguardando Pagamento(O usuário ainda não completou o processo de pagamento.)
    case 'in_process':  $s = 5; break; //Em análise(O pagamento estão em revisão.)
    case 'approved':    $s = 6; break; //Paga, Pago ou Aprovado(O pagamento foi aprovado e acreditado.)
    case 'refunded':    $s = 7; break; //Devolvido(O pagamento foi devolvido ao usuário.)
    case 'cancelled':   $s = 8; break; //Cancelada(O pagamento foi cancelado por uma das parte ou porque o tempo expirou.)
    case 'charged_back':$s = 25; break; //Charged Back(Foi feito um chargeback no cartão do comprador.)
  }

    return $s;
}

function sendPasswordReset($login, $email, $url)
{
    return '<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="width:100%;max-width:480px" valign="top"><tbody><tr><td valign="top" align="left" style="word-break:normal;border-collapse:collapse;font-family:Circular,Helvetica Neue,Helvetica,Arial,sans-serif;font-size:12px;line-height:18px;color:#555555"><center><div><table width="100%" cellspacing="0" cellpadding="0" height="50" style="border:none;margin:0px;padding:0px;border-collapse:collapse;width:100%;height:50px"><tbody valign="middle" style="border:none;margin:0px;padding:0px"><tr valign="middle" height="20" style="border:none;margin:0px;padding:0px;height:20px"><td valign="middle" height="20" style="border:none;margin:0px;padding:0px;height:20px" colspan="3"></td></tr><tr valign="middle" style="border:none;margin:0px;padding:0px"><td width="6.25%" valign="middle" style="border:none;margin:0px;padding:0px;width:6.25%"></td><td valign="middle" style="border:none;margin:0px;padding:0px"><a target="_blank" style="border:none;margin:0px;padding:0px;text-decoration:none" href="http://www.like.com.br"><img width="122" height="37" style="border:none;margin:0px;padding:0px;display:block;max-width:100%;width:122px;min-height:37px" alt="" src="http://www.likecell.com.br/img/logo@2x.png" class="CToWUd"></a></td><td width="6.25%" valign="middle" style="border:none;margin:0px;padding:0px;width:6.25%"></td></tr><tr valign="middle" height="20" style="border:none;margin:0px;padding:0px;height:20px"><td valign="middle" height="20" style="border:none;margin:0px;padding:0px;height:20px" colspan="3"></td></tr></tbody></table><table width="100%" cellspacing="0" cellpadding="0" style="border:none;margin:0px;padding:0px;border-collapse:collapse;width:100%"><tbody valign="middle" style="border:none;margin:0px;padding:0px"><tr valign="middle" height="28" style="border:none;margin:0px;padding:0px;height:28px"><td valign="middle" height="28" style="border:none;margin:0px;padding:0px;height:28px" colspan="3"></td></tr><tr valign="middle" style="border:none;margin:0px;padding:0px"><td width="6.25%" valign="middle" style="border:none;margin:0px;padding:0px;width:6.25%"></td><td valign="middle" style="border:none;margin:0px;padding:0px"><h1 align="center" style="border:none;margin:0px;padding:0px;font-family:Circular,Helvetica Neue,Helvetica,Arial,sans-serif;font-weight:bold;text-align:center;text-decoration:none;font-size:40px;line-height:45px;color:rgb(85,85,85);letter-spacing:-0.04em">Olá.</h1><h2 align="center" style="border:none;margin:0px;padding:7px 0px 0px;font-family:Circular,Helvetica Neue,Helvetica,Arial,sans-serif;font-weight:200;text-align:center;text-decoration:none;font-size:17px;line-height:23px;color:rgb(97,100,103)"></h2></td><td width="6.25%" valign="middle" style="border:none;margin:0px;padding:0px;width:6.25%"></td></tr><tr valign="middle" height="16" style="border:none;margin:0px;padding:0px;height:16px"><td valign="middle" height="16" style="border:none;margin:0px;padding:0px;height:16px" colspan="3"></td></tr></tbody></table><table width="100%" cellspacing="0" cellpadding="0" style="border:none;margin:0px;padding:0px;border-collapse:collapse;width:100%"><tbody valign="middle" style="border:none;margin:0px;padding:0px"><tr valign="middle" style="border:none;margin:0px;padding:0px"><td width="6.25%" valign="middle" style="width:6.25%;border:none;margin:0px;padding:0px"></td><td valign="middle" style="border:none;margin:0px;padding:0px"><table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin:0px;padding:0px"><tbody><tr><td align="left" style="border:none;margin:0px;padding:0px 0px 5px;font-family:Circular,Helvetica Neue,Helvetica,Arial,sans-serif;font-weight:200;text-align:left;text-decoration:none;font-size:14px;line-height:20px;color:rgb(97,100,103)"> Não se preocupe, você pode redefinir sua senha do LikeCell clicando no link abaixo:</td></tr></tbody></table><table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin:0px;padding:0px"><tbody><tr><td align="left" style="border:none;margin:0px;padding:0px 0px 5px;font-family:Circular,Helvetica Neue,Helvetica,Arial,sans-serif;font-weight:200;text-align:left;text-decoration:none;font-size:14px;line-height:20px;color:rgb(97,100,103)"> <a target="_blank" style="border:none;margin:0px;padding:0px;text-decoration:none;font-family:Circular,Helvetica Neue,Helvetica,Arial,sans-serif;font-weight:200;text-align:left;color:rgb(29,185,84)" align="left" href="'.$url.'">'.$url.'</a></td></tr></tbody></table><table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin:0px;padding:0px"><tbody><tr><td align="left" style="border:none;margin:0px;padding:0px 0px 5px;font-family:Circular,Helvetica Neue,Helvetica,Arial,sans-serif;font-weight:200;text-align:left;text-decoration:none;font-size:14px;line-height:20px;color:rgb(97,100,103)"> Seu nome de usuário é: '.$login.'</td></tr></tbody></table><table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin:0px;padding:0px"><tbody><tr><td align="left" style="border:none;margin:0px;padding:0px 0px 5px;font-family:Circular,Helvetica Neue,Helvetica,Arial,sans-serif;font-weight:200;text-align:left;text-decoration:none;font-size:14px;line-height:20px;color:rgb(97,100,103)"> Se você não solicitou uma redefinição de senha, fique à vontade para apagar este email e continuar utilizando sua conta!</td></tr></tbody></table><table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin:0px;padding:0px"><tbody><tr><td align="left" style="border:none;margin:0px;padding:0px 0px 5px;font-family:Circular,Helvetica Neue,Helvetica,Arial,sans-serif;font-weight:200;text-align:left;text-decoration:none;font-size:14px;line-height:20px;color:rgb(97,100,103)"> Tudo de bom, <br style="border:none;margin:0px;padding:0px"> A Equipe do LikeCell.</td></tr></tbody></table></td><td width="6.25%" valign="middle" style="width:6.25%;border:none;margin:0px;padding:0px"></td></tr><tr valign="middle" style="border:none;margin:0px;padding:0px"><td valign="middle" height="20" style="border:none;margin:0px;padding:0px;height:20px" colspan="3"></td></tr></tbody></table><table width="100%" cellspacing="0" cellpadding="0" style="border:none;margin:0px;padding:0px;border-collapse:collapse;width:100%"><tbody valign="middle" style="border:none;margin:0px;padding:0px"><tr valign="middle" height="22" style="border:none;margin:0px;padding:0px;height:22px"><td valign="middle" height="22" style="border:none;margin:0px;padding:0px;height:22px" colspan="3"></td></tr></tbody></table><table width="100%" cellspacing="0" cellpadding="0" bgcolor="#F7F7F7" style="border:none;margin:0px;padding:0px;border-collapse:collapse;width:100%;background-color:rgb(247,247,247)"><tbody valign="middle" style="border:none;margin:0px;padding:0px"><tr valign="middle" height="25" style="border:none;margin:0px;padding:0px;height:25px"><td valign="middle" height="25" style="border:none;margin:0px;padding:0px;height:25px" colspan="3"></td></tr><tr valign="middle" style="border:none;margin:0px;padding:0px"><td width="6%" valign="middle" style="border:none;margin:0px;padding:0px;width:6.25%"></td><td valign="middle" style="border:none;margin:0px;padding:0px"><hr style="border:none;margin:0px;padding:0px;min-height:1px;background-color:rgb(209,213,217)" bgcolor="#D1D5D9"></td><td width="6%" valign="middle" style="border:none;margin:0px;padding:0px;width:6.25%"></td></tr><tr valign="middle" style="border:none;margin:0px;padding:0px"><td width="6%" valign="middle" style="border:none;margin:0px;padding:0px;width:6.25%"></td><td valign="middle" align="left" style="border:none;margin:0px;padding:0px;font-family:Circular,Helvetica Neue,Helvetica,Arial,sans-serif;font-weight:200;text-align:left;text-decoration:none;font-size:11px;line-height:1.65em;color:rgb(136,137,140)"> Esta mensagem foi mandada para <a target="_blank" href="mailto:'.$email.'">'.$email.'</a>. Se você tem dúvidas ou reclamações, <a target="_blank" align="left" style="border:none;margin:0px;padding:0px;text-decoration:none;color:rgb(109,109,109);font-weight:bold;font-family:Circular,Helvetica Neue,Helvetica,Arial,sans-serif;text-align:left" href="https://www.likecell.com.br/#contact">fale conosco</a>.</td><td width="6%" valign="middle" style="border:none;margin:0px;padding:0px;width:6.25%"></td></tr><tr valign="middle" height="33" style="border:none;margin:0px;padding:0px;height:33px"><td valign="middle" height="33" style="border:none;margin:0px;padding:0px;height:33px" colspan="3"></td></tr><tr valign="middle" style="border:none;margin:0px;padding:0px"><td width="6%" valign="middle" style="border:none;margin:0px;padding:0px;width:6.25%"></td><td valign="middle" align="left" style="border:none;margin:0px;padding:0px;font-family:Circular,Helvetica Neue,Helvetica,Arial,sans-serif;font-weight:200;text-align:left;text-decoration:none;font-size:11px;line-height:1.65em;color:rgb(136,137,140)"><a target="_blank" style="border-style:none solid none none;border-right-width:1px;border-left-width:1px;border-right-color:rgb(195,195,195);border-left-color:transparent;margin:0px;padding:0px 7px 0px 0px;text-decoration:none;display:inline-block;color:rgb(109,109,109);font-weight:bold" href="https://www.likecell.com.br/end-user-agreement/">Termos de uso</a><a target="_blank" style="border-style:none none none solid;border-right-width:1px;border-left-width:1px;border-right-color:rgb(195,195,195);border-left-color:transparent;margin:0px;padding:0px 0px 0px 7px;text-decoration:none;display:inline-block;color:rgb(109,109,109);font-weight:bold" href="https://www.likecell.com.br/#contact">Fale conosco</a></td><td width="6%" valign="middle" style="border:none;margin:0px;padding:0px;width:6.25%"></td></tr><tr valign="middle" style="border:none;margin:0px;padding:0px"><td valign="middle" height="12" style="border:none;margin:0px;padding:0px;height:12px" colspan="3"></td></tr><tr valign="middle" style="border:none;margin:0px;padding:0px"><td width="6%" valign="middle" style="border:none;margin:0px;padding:0px;width:6.25%"></td><td valign="middle" align="left" style="border:none;margin:0px;padding:0px;font-family:Circular,Helvetica Neue,Helvetica,Arial,sans-serif;font-weight:200;text-align:left;text-decoration:none;font-size:11px;line-height:1.65em;color:rgb(136,137,140)">G.O Torres(ME) 09.049.923/0001-02, com sede em Sena Madureira, AC, escritório administrativo na cidade de Rio Branco, AC e central de distribuição em São Paulo, SP, Brasil.</td><td width="6%" valign="middle" style="border:none;margin:0px;padding:0px;width:6.25%"></td></tr><tr valign="middle" height="20" style="border:none;margin:0px;padding:0px;height:20px"><td valign="middle" height="25" style="border:none;margin:0px;padding:0px;height:25px" colspan="3"></td></tr></tbody></table></div></center></td></tr></tbody></table>';
}

function envia_email($email, $assunto, $msg, $emaile, $nome)
{
    // Inclui o arquivo class.phpmailer.php localizado na pasta phpmailer
  // Inicia a classe PHPMailer
  $mail = new PHPMailer();
  // Define os dados do servidor e tipo de conexão
  // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
  $mail->IsSMTP(); // Define que a mensagem será SMTP
  $mail->Host = 'mail.kambotecnologia.com.br'; // Endereço do servidor SMTP
  $mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
  $mail->Mailer = 'smtp';
    $mail->SMTPSecure = 'tls';
    $mail->SMTP_PORT = '587'; //porta 465 ou 587
  $mail->Username = 'naoresponda@markday.com.br'; // Usuário do servidor SMTP
  $mail->Password = 'kbtech2016@#123'; // Senha do servidor SMTP

  // Define o remetente
  // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
  $mail->From = $emaile; // Seu e-mail
  $mail->FromName = 'Markday'; // Seu nome

  // Define os destinatário(s)
  // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
  $mail->AddAddress($email, $nome);
    $mail->addReplyTo('naoresponda@markday.com.br', 'Plataforma Markday');
    $mail->AddBCC($emaile); // Copia
  // $mail->AddBCC('fulano@dominio.com.br', 'Fulano da Silva'); // Cópia Oculta

  // Define os dados técnicos da Mensagem
  // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
  $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
  $mail->CharSet = 'utf-8'; // Charset da mensagem (opcional)

  // Define a mensagem (Texto e Assunto)
  // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
  $mail->Subject = $assunto; // Assunto da mensagem
  // $mail->Body = '<b>TESTE DE ENVIO</b>';
  $mail->Body = $msg;
    $mail->AltBody = 'e-mail enviado em '.date('d/m/Y h:i');

  // Define os anexos (opcional)
  // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
  //$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo

  // Envia o e-mail
  $enviado = $mail->Send();

  // Limpa os destinatários e os anexos
  $mail->ClearAddresses();
    $mail->ClearAllRecipients();
    $mail->ClearAttachments();

  // Exibe uma mensagem de resultado
  if ($enviado) {
      return true;
  } else {
      return false;
  }
}

/**
 * retorno de hash.
 *
 * @param $hash (string): valor para gerar o hash
 * @param $cost (integer)  custo de processamento
 *
 * @author Jaisson Santos <jaissonssantos@gmail.com>
 *
 * @return string
 */
function generatehash($cost = 1)
{
    for ($i = 0; $i <= $cost; ++$i) {
        $hash .= uniqid(null, true);
    }
    $hash = str_replace('.', '-', $hash);

    return $hash;
}
