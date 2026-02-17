<?php

require_once ('vendor/autoload.php');
require_once ("globals.php");
require_once ("db.php");
require_once ("models/message.php");
require_once ("DAO/usuarioDAO.php");
require_once ("DAO/testesDAO.php");

use Dompdf\Dompdf;
use Dompdf\Options;

//configurações e busca de dados
$option = new Options();
$option->set("isRemoteEnabled", true); //permite carregar css externo
$dompdf = new Dompdf($option);

//instanciando classe usuarioDAO
$testesDao = new TestesDAO($conn, $BASE_URL);
$usuarioDao = new UsuarioDAO($conn, $BASE_URL);
$usuarioData = $usuarioDao->verifyToken(true);
$allTests = $testesDao->findAllTests($usuarioData->email);

//instanciando classe message
$message = new Message($BASE_URL);
$flashMessage = $message -> getMessage();

if(!empty ($flashMessage["msg"])){
  //limpar a mensagem
  $message -> clearMessage();
}

//montando o html do teste
$html = '
  <table border="1" width="100%" style="border-collapse: collapse; text-align: center;">
    <thead>
      <tr style="background-color: #ddd;">
        <th>Data teste</th>
        <th>Madrug</th>
        <th>A/C</th>
        <th>D/C</th>
        <th>A/A</th>
        <th>D/A</th>
        <th>A/J</th>
        <th>D/J</th>
      </tr>
    </thead>

    <tbody>';
  foreach($allTests as $testesL1){
  $html .= '
  <tr style="text-align: center;">
      <td>'.(date("d/m/Y", strtotime($testesL1["data_teste"]))) . '</td>
      <td>'.($testesL1['madrugada'] > 0 ? $testesL1['madrugada'] : '-').'</td>
      <td>'.($testesL1['antesCafe'] > 0 ? $testesL1['antesCafe'] : '-').'</td>
      <td>'.($testesL1['depoisCafe'] > 0 ? $testesL1['depoisCafe'] : '-').'</td>
      <td>'.($testesL1['antesAlmoco'] > 0 ? $testesL1['antesAlmoco'] : '-').'</td>
      <td>'.($testesL1['depoisAlmoco'] > 0 ? $testesL1['depoisAlmoco'] : '-').'</td>
      <td>'.($testesL1['antesJantar'] > 0 ? $testesL1['antesJantar'] : '-').'</td>
      <td>'.($testesL1['depoisJantar'] > 0 ? $testesL1['depoisJantar'] : '-').'</td>
    </tr>';
  }
  $html .= "</tbody></table>";
try {
    // Código de geração do PDF aqui...
  //renderização
  $dompdf->loadHtml($html);
  $dompdf->setPaper('A4', "landscape");
  $dompdf->render();
  // 4. Saída para o navegador (Download automático)
  $dompdf->stream("relatorio_glicemia.pdf", ["Attachment" => true]);
  $message->setMessage("download efetuado com sucesso", "success", "allTests");
}catch (Exception $e) {
    // 3. Caso ocorra um erro inesperado no Dompdf
    $message->setMessage("Erro ao gerar PDF: " . $e->getMessage(), "error", "back");
}

  ?>