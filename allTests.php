<?php
require_once ("templates/header.php");
require_once ("DAO/usuarioDAO.php");
require_once ("DAO/testesDAO.php");
require_once ("models/testes.php");

//verifica se o usuario esta logado
$usuarioData = $usuarioDao->verifyToken(true);



?>

<!--Tabela de resultados HGT-->
<div class="tabela">
  <div class="title1">
  <h3>Todos os testes!</h3>
  <p>Para realizar o download: <a href="<?= $BASE_URL ?>gerar_pdf">clique aqui</a></p>
  </div>
  <table>
    <thead>
      <tr class="titulTab">
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

    <tbody>
      <?php if(!empty($testesLan1)) :?>
      <?php foreach($testesDat as $testesL1): ?>
    <tr class="valoresTab">
      <td><?= date("d/m/Y", strtotime($testesL1["data_teste"]))?></td>
      <td><?= $testesL1['madrugada'] > 0 ? $testesL1['madrugada'] : '-' ?></td>
      <td><?= $testesL1['antesCafe'] > 0 ? $testesL1['antesCafe'] : '-' ?></td>
      <td><?= $testesL1['depoisCafe'] > 0 ? $testesL1['depoisCafe'] : '-' ?></td>
      <td><?= $testesL1['antesAlmoco'] > 0 ? $testesL1['antesAlmoco'] : '-' ?></td>
      <td><?= $testesL1['depoisAlmoco'] > 0 ? $testesL1['depoisAlmoco'] : '-' ?></td>
      <td><?= $testesL1['antesJantar'] > 0 ? $testesL1['antesJantar'] : '-' ?></td>
      <td><?= $testesL1['depoisJantar'] > 0 ? $testesL1['depoisJantar'] : '-' ?></td>
    </tr>
    <?php endforeach; ?>
    <?php else: ?>
        <tr>
          <td colspan="8">Nenhum registo encontrado no sistema.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php
require_once ("templates/footer.php");
?>